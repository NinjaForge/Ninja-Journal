(function($){
	window.addEvent('domready', function(){
		var forms = $$(document.forms).filter(function(form,i){
						return form.getElements('.toggle-state').length > 0;
					});
			
		forms.addEvent('click', function(event){
			var target = $(event.target);
			if(target.retrieve('busy')) return;
			if(target.hasClass('toggle-state'))
			{
				var options = target.retrieve('options') || Json.evaluate(target.get('rel')),
					toggle  = options.toggle,
					data	= {
						id: target.getParent().getParent().getElement('input.id').get('value'),
						action: options.state[options.toggle],
						_token: $(this).getElement('[name=_token]').get('value')
					};
				
				if(options.edit) data[options.edit] = target.get('value');

				target.store('busy', true);
				new Request.JSON({
					url: this.get('action')+'&format=json',
					data: data,
					onComplete: function(){
						options.toggle = toggle == 0 ? 1 : 0;
						target.swapClass('icon-toggle-' + options.state[options.toggle], 'icon-toggle-' + options.state[toggle]);
						target.getParent().getParent().swapClass('state-' + options.state[options.toggle], 'state-' + options.state[toggle]);
						target.store('options', options).eliminate('busy');
					},
					onSuccess: function(response){
						if(!response.msg) return;
					}
				}).post();
			}
		});
	});
})(document.id);