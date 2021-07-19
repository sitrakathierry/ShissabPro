$('.summernote').summernote()

var qrcode = new QRCode(document.getElementById("qrcode"), {
	width : 100,
	height : 100
});

$(document).on('input', '#code', function(event) {
	var data = event.target.value;
	makeCode(data)
})

function makeCode (data) {		
	qrcode.makeCode(data);
}

$(document).on('click', '#btn-save', function(event) {
	event.preventDefault();

	var data = {
		code : $('#code').val(),
		qrcode : $('#qrcode img').attr('src'),
		nom : $('#nom').val(),
		description : $('#description').code(),
		prix_achat : $('#prix_achat').val(),
		prix_vente : $('#prix_vente').val(),
		stock : $('#stock').val(),
	};

	var url = Routing.generate('produit_save');

	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		success: function(res) {
			show_info('Succès', 'Produit enregistré');
		}
	})
})