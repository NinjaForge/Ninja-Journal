var Journal = new Class({

	initialize: function(url, options){
		this.setOptions({
			msgDeletelog: 'Are you sure you want to delete the log?'
		}, options);

		this.url       = url;
		this.state     = $('state');
		this.projectId = $('project-id');
		this.logDelete = $$('#ninjajournal div.user-log ul.log span.delta a');
	},
	
	refresh: function() {
		this.logDelete = $$('#ninjajournal div.user-log ul.log span.delta a');
		this.logDelete.addEvent('click', this.confirmDelete.bind(this));
	},

	attachEvents: function() {
		new Tips($$('ul.log a.tooltip'), {fixed:true,className:'ninjatool', offsets: {'x': 0, 'y': 28}});

		// logs
		new Observer(this.state, this.updateState.bind(this), { delay: 1000 });
		this.projectId.addEvent('change', this.getTasks.bind(this));
		this.logDelete.addEvent('click', this.confirmDelete.bind(this));
		if ($('team-log')) this.getTeamLog.bind(this).periodical(60000);

		// todos
		var wrp  = $('ninjajournal').getElement('div.todos');
		var trg  = $('todos-trigger');
		var dur  = Math.max($('todos-form').getElements('li').length * 30, 300), slowmo = dur * 6;
		this.todo = new Fx.Slide(wrp, { duration: dur });
		this.fade = new Fx.Style(wrp, 'opacity', { duration: dur });
		this.slowmo = false; i = 0;
		window.addEvent('keydown', function(event){
			event = new Event(event);
			if(event.shift){
				this.slowmo = true;
			}
		}.bind(this)).addEvent('keyup', function(event){
			event = new Event(event);
			if(this.slowmo){
				this.slowmo = false;
			}
		}.bind(this));
		
		if(!Cookie.get('todos-toggle')){
			this.todo.hide();
			wrp.setOpacity(0);
		}
		trg.addEvent('click', function(e) {
			this.todo.options.duration = this.fade.options.duration = (this.slowmo ? slowmo : dur);
			this.todo.options.transition = Fx.Transitions.Cubic[this.todo.open ? 'easeInOut' : 'easeOut'];
			this.fade.options.transition = Fx.Transitions.Quad[this.todo.open ? 'easeOut' : 'easeIn'];
			this.todo.toggle();
			this.fade.start(this.todo.open ? 0 : 1);
			Cookie.get('todos-toggle') ? Cookie.remove('todos-toggle') : Cookie.set('todos-toggle', true);
			new Event(e).stop();
		}.bind(this));
		this.attachTodos();
		this.getTodos.bind(this).periodical(300000);
	},

	updateState: function() {
		var obj = this;
		var fx  = this.state.effects({duration: 100, transition: Fx.Transitions.linear});

		new Ajax(this.state.form.getProperty('action'), {
			method: 'post',
			data: {
				_token: this.state.form.getElement('[name=_token]').getValue(),
				action: 'update',
				message: obj.state.getValue()
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
		
		new Ajax(this.url, {
			method: 'get',
			data: {
				project: id,
				layout: 'tasks'
			},
			update: $('task-id')
		}).request();

		new Ajax(this.url, {
			method: 'get',
			data: {
				project: id,
				layout: 'description'
			},
			update: $('project-description')
		}).request();
	},

	confirmDelete: function(e) {
		var event = new Event(e).stop();
		if (confirm(this.options.msgDeletelog)){
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
		}
	},

	getTeamLog: function() {
		var obj = this;
		var bg  = $('ninjajournal').getElement('div.team-log-loading');

		new Ajax(this.url, {
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
				new Tips($$('div.team-log ul.log a.tooltip'), {fixed:true,className:'ninjatool', offsets: {'x': 0, 'y': 28}});
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
		var bg  = $('ninjajournal').getElement('div.todos-bg');

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
				$('todos-trigger').setText(text.replace(/[0-9]+/g, length));
			}
		}).request();
	},

	updateTodos: function() {
		var obj = this;
		var bg  = $('ninjajournal').getElement('div.todos-bg');
		var fx  = bg.effects({duration: 100, transition: Fx.Transitions.linear});
		
		$clear(this.timeout);
		this.timeout = function() {
		new Ajax($('todos-form').getProperty('action'), {
				method: 'get',
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

Journal.implement(new Options);

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
		this.value = this.element.getValue();
		if (this.options.periodical) this.timer = this.listener.periodical(this.options.periodical);
		else this.element.addEvent('keyup', this.listener);
	},

	fired: function() {
		var value = this.element.getValue();
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