$(document).on('click','#btn-delete',function(event) {
	event.preventDefault();

	var url = Routing.generate('produit_stock_entrepot_delete', { id : $('#id_produit_entrepot').val() });

	disabled_confirm(false); 

  swal({
        title: "SUPPRIMER",
        text: "Voulez-vous vraiment supprimer ? ",
        type: "error",
        showCancelButton: true,
        confirmButtonText: "Oui",
        cancelButtonText: "Non",
    },
    function () {
      	disabled_confirm(true);
		$.ajax({
			url: url,
			type: 'GET',
			success: function(res) {
				show_success('Succès', 'Suppression éffectué', Routing.generate('produit_stock_entrepot_index'));
			}
		});
      
  });
})

var image_src = $('#produit_image');

if (image_src.attr('src') == '') {
	$('#produit_image').attr('src',get_picture_b64());
}


$(document).on('change','#image',function(event) {
  var file = document.querySelector('#image').files[0];
  getBase64(file).then((src)=>{
      $('#produit_image').attr('src',src);
  });
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

makeCode( $('#code').val() );

$(document).on('click', '#btn-save', function(event) {
	event.preventDefault();

	var data = {
		id : $('#id_produit_entrepot').val(),
		indice : $('#indice').val(),
	};

	var url = Routing.generate('produit_stock_entrepot_edit');

	disabled_confirm(false); 

  swal({
        title: "Enregistrer",
        text: "Voulez-vous vraiment enregistrer ? ",
        type: "info",
        showCancelButton: true,
        confirmButtonText: "Oui",
        cancelButtonText: "Non",
    },
    function () {
      disabled_confirm(true);
			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function(res) {
					show_success('Succès', 'Produit enregistré');
				}
			})
      
  });
  
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