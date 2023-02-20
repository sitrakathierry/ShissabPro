$('.summernote').summernote()
$('.select2').select2()

$(document).on('click', '#btn-save', function(event) {
	event.preventDefault();

	var data = {
		id : $('#id').val(),
		nom : $('#nom').val(),
		prix : $('#prix').val(),
		qte : $('#qte').val(),
		unite : $('#unite').val(),
		portion : $('#portion').val(),
		libelle : $('#libelle').val(),
		fournisseur : $('#fournisseur').val(),
		description : $('#description').code(),
	};

	var url = Routing.generate('stock_interne_general_save');

	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		success: function(res) {
			show_info('Succès', 'Enregistrement éffectué');
			location.reload();
		}
	})
});

$(document).on('input','#prix', function(event) {
	event.preventDefault();

	var prix = event.target.value;

	var qte = $('#qte').val();

	var total = Number(prix) * Number(qte);

	$('#total').val(total);
});

$(document).on('input','#qte', function(event) {
	event.preventDefault();

	var qte = event.target.value;

	var prix = $('#prix').val();

	var total = Number(prix) * Number(qte);

	$('#total').val(total);
})

