$(document).on('click', '#btn-save', function(event) {
	event.preventDefault();

	var url = Routing.generate('pdf_attribution_save');
	
	var data = {
		id: $('#id').val(),
		facture: $('#facture').val(),
		produit: $('#produit').val(),
	};

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