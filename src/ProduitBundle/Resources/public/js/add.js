$('#produit_image').attr('src',get_picture_b64());

$(document).on('change','#image',function(event) {
  var file = document.querySelector('#image').files[0];
  getBase64(file).then((src)=>{
      $('#produit_image').attr('src',src);
  });
});

$('#expirer').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true,
    format: 'dd/mm/yyyy',
    language: 'fr'
});

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
		unite : $('#unite').val(),
		stock_alerte : $('#stock_alerte').val(),
		produit_image : $('#produit_image').attr('src'),
		expirer: $('#expirer').val()
	};

	var url = Routing.generate('produit_save');

	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		success: function(res) {
			show_info('Succès', 'Produit enregistré');
			location.reload();
		}
	})
});

function getBase64(file) {
  return new Promise((resolve)=>{
     var reader = new FileReader();
     reader.readAsDataURL(file);
     reader.onload = function () {
       resolve(reader.result);
     };
     reader.onerror = function (error) {
       resolve(false)
     };
  })
}