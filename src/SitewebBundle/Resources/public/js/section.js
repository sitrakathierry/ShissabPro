$(document).on('input', '#nom', function(event) {
 	var nom = event.target.value;
 	var slug = 	nom
 				.normalize("NFD")
 				.replace(/[\u0300-\u036f]/g, "")
 				.toLowerCase()
 				.replace(/\s/g, '')
 				.replace(/[^a-zA-Z0-9]/g,'');
 	
 	$('#slug').val(slug);
});

$(document).on('click','#btn-save-section', function(event) {
	event.preventDefault();

	var data = {
		id_siteweb : $('#id_siteweb').val(),
		nom : $('#nom').val(),
		type : $('#type').val(),
		slug : $('#slug').val(),
	};

	var url = Routing.generate('siteweb_section_save');

	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		success: function(res) {
			show_info('Succès','Section enregistré');
			location.reload();
		}
	})
})