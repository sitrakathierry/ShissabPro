$(document).ready(function(){
	$('.js-switch').each(function() {
	    new Switchery(this, { color:'#fd9597' })
	});

	$(document).on('click', '#btn-save', function(event) {
		event.preventDefault();
	  	var values = new Array();

		$('.module').each(function() {
	    	var checked = $(this).is(':checked');

	    	if (checked) {
	    		values.push($(this).data('id'));
	    	}

		});

		var url = Routing.generate('siteweb_admin_autorisation_save');

		var data = {
			autorisations : values,
			id_siteweb : $('#id_siteweb').val()
		};

		$.ajax({
			url : url,
			method : 'POST',
			data: data,
			success: function(data) {
				show_info('Succès', 'Modification éffectué');
				location.reload();
			}
		})

	})
});