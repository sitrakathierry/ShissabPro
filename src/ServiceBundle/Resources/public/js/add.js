$('.summernote').summernote()

$(document).on('click', '#btn-save', function(event) {
	event.preventDefault();

	var data = {
		nom : $('#nom').val(),
		description : $('#description').code(),
		statut : 1,
	};

	var url = Routing.generate('service_save');

	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		success: function(res) {
			show_info('Succès', 'Service enregistré');
			location.reload();
		}
	})
});


