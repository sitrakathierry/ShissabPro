$('#categorie_image').attr('src',get_picture_b64());

$(document).on('change','#image',function(event) {
  var file = document.querySelector('#image').files[0];
  getBase64(file).then((src)=>{
      $('#categorie_image').attr('src',src);
  });
});

$(document).on('click', '#btn-save', function(event) {
	event.preventDefault();

	var data = {
		nom : $('#nom').val(),
		stock : $('#stock').val(),
		unite : $('#unite').val(),
		categorie_image : $('#categorie_image').attr('src'),
	};

	var url = Routing.generate('produit_categorie_save');

	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		success: function(res) {
			show_info('Succès', 'Catégorie enregistré');
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