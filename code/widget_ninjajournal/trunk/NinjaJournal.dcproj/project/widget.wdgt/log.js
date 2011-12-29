var Teamlog = new Class({

	Implements: Options,

	initialize: function(url, options){
		this.setOptions({
			msgDeletelog: 'Are you sure you want to delete the log?'
		}, options);

		this.url       = url;
		this.state     = $('state');
		this.projectId = $('project-id');
		this.logDelete = $$('#yoo-teamlog div.user-log ul.log span.delta a');
	},

	attachEvents: function() {
		new Tips($$('ul.log a.tooltip'));

		// logs
		new Observer(this.state, this.updateState.bind(this), { delay: 1000 });
		this.projectId.addEvent('change', this.getTasks.bind(this));
		this.logDelete.addEvent('click', this.confirmDelete.bind(this));
		if ($('team-log')) this.getTeamLog.bind(this).periodical(350000);

		// todos
		var wrp  = $('yoo-teamlog').getElement('div.todos');
		var trg  = $('todos-trigger');
		this.todo = new Fx.Slide(wrp, { duration: 200 });
		if(!Cookie.read('todos-toggle')) this.todo.hide();
		trg.addEvent('click', function() { this.todo.toggle(); Cookie.read('todos-toggle') ? Cookie.dispose('todos-toggle') : Cookie.write('todos-toggle', true); }.bind(this));
		this.attachTodos();
		this.getTodos.bind(this).periodical(300000);
	},

	updateState: function() {
		var obj = this;
		var fx  = this.state.effects({duration: 100, transition: Fx.Transitions.linear});

		new Ajax(this.state.form.getProperty('action'), {
			method: 'post',
			data: {
				_token: this.state.form.getElement('[name=_token]').get('value'),
				action: 'update',
				message: obj.state.get('value')
			},
			onRequest: function(){
				obj.state.addClass('loading');
			},
			onComplete: function(){
				obj.state.removeClass('loading');
				fx.start({
					'background-color': '#ffffaa'
				}).chain(function(){
					this.setOptions({duration: 700});
					this.start({
						'background-color': '#ffffff'
					});
				});
			}
		}).request();
	},

	getTasks: function() {
		var id = this.projectId.getProperty('value');
		
		new Request.HTML(this.url, {
			data: {
				project: id,
				layout: 'tasks'
			},
			update: $('task-id')
		}).get();

		new Request.HTML(this.url, {
			data: {
				project: id,
				layout: 'description'
			},
			update: $('project-description')
		}).get();
	},

	confirmDelete: function(e) {
		var event = new Event(e).stop();
		//if (confirm(this.options.msgDeletelog)){
			var request = new Ajax(window.location, {
				method: 'post',
				data: {
					_token: this.options.token,
					action: 'delete',
					id:		event.target.hash.replace('#', '')
				},
				onSuccess: function(){
					var remove = event.target.getParent().getParent(), parent = remove.getParent(), date = parent.getPrevious(), children = parent.getChildren(), props = {opacity: 0};

					if(children.length < 2){
						var fx = new Fx.Elements($$(parent, date), {onComplete: function(){
								parent.remove();
								date.remove();
							}}).start({
							    0: props,
							    1: props
						});
					} else {
						remove.effects({onComplete: function(){
							remove.remove();
						}}).start(props);
					}
				}
			}).request();
		//}
	},

	getTeamLog: function() {
		var obj = this;
		var bg  = $('yoo-teamlog').getElement('div.team-log-loading');

		new Request.HTML(this.url, {
			method: 'get',
			data: {
				layout: 'teamlog'
			},
			update: $('team-log'),
			onRequest: function(){
				bg.addClass('loading');
			},
			onComplete: function(){
				bg.removeClass('loading');
				new Tips($$('div.team-log ul.log a.tooltip'));
			}
		}).request();
	},

	attachTodos: function() {
		$$('#todos-form ul li').addEvent('click', function(){
			var event = new Event;
			event.target = this.getElement('.toggle-state');
			$('todos-form').fireEvent('click', event);
		}).addEvent('click', function(){
			this.updateTodos();
		}.bind(this));
		
		/*$$('#todos-form ul li').addEvent('click', function(){
				var input = this.getElement('[name=state]');
				input.setProperty('value', (input.getProperty('value') == 1 ? 0 : 1));
				this.toggleClass('checked');
				obj.updateTodos();
			});*/
	},

	getTodos: function() {
		var obj = this;
		var bg  = $('yoo-teamlog').getElement('div.todos-bg');

		new Ajax(this.url+'&layout=todos', {
			method: 'get',
			update: $('todos'),
			onRequest: function(){
				bg.addClass('loading');
			},
			onComplete: function(){
				bg.removeClass('loading');
				obj.attachTodos();
				if(obj.todo.open) obj.todo.hide().show();
				var length = $('todos-form').getElement('ul').getChildren().length, text = $('todos-trigger').getText();
				$('todos-trigger').setText(text.replace(/[0-9]/g, length));
			}
		}).request();
	},

	updateTodos: function() {
		var obj = this;
		var bg  = $('yoo-teamlog').getElement('div.todos-bg');
		var fx  = bg.effects({duration: 100, transition: Fx.Transitions.linear});
		
		$clear(this.timeout);
		this.timeout = function() {
			$('todos-form').send({
				onRequest: function(){
					bg.addClass('loading');
				},
				onComplete: function(){
					bg.removeClass('loading');
					fx.start({
						'background-color': '#FFE678'
					}).chain(function(){
						this.setOptions({duration: 700});
						this.start({
							'background-color': '#ffffaa'
						});
					});
				}
			});		
		}.delay(1000);
	}

});

Teamlog.implement(new Options);

var Observer = new Class({

	options: {
		'periodical': false,
		'delay': 1000
	},

	initialize: function(el, onFired, options){
		this.setOptions(options);
		this.addEvent('onFired', onFired);
		this.element = $(el);
		this.listener = this.fired.bind(this);
		this.value = this.element.get('value');
		if (this.options.periodical) this.timer = this.listener.periodical(this.options.periodical);
		else this.element.addEvent('keyup', this.listener);
	},

	fired: function() {
		var value = this.element.get('value');
		if (this.value == value) return;
		this.clear();
		this.value = value;
		this.timeout = this.fireEvent.delay(this.options.delay, this, ['onFired', [value]]);
	},

	clear: function() {
		$clear(this.timeout);
		return this;
	}
});

Observer.implement(new Options);
Observer.implement(new Events);