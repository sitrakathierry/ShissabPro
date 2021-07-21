$(document).on('click', '#btn-save', function(event) {
	event.preventDefault();

	var datas = [];
	var url = Routing.generate('pdf_attribution_save');
	$('.cl_model_attribuer').each(function() {
		var id = $(this).find('option:selected').attr('data-id');
		if(!id) id = null; 
		datas.push({
			value: $(this).val(),
			type: ($(this).attr('id') == 'facture') ? 0 : 1,
			id : id
		});
	});

	var data = {
		datas : datas
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