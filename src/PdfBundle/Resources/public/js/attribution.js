$(document).on('click', '#btn-save', function(event) {
	event.preventDefault();

	var data = {
		id : $('#id').val(),
		facture : $('#facture').val()
	};

	var url = Routing.generate('pdf_attribution_save');

	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		success: function(res) {
			show_info('Succès', 'Attribution enregistré');
			location.reload();
		}
	})
})