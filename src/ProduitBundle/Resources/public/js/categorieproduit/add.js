$('#categorie_image').attr('src',get_picture_b64());

$(document).on('change','#image',function(event) {
  var file = document.querySelector('#image').files[0];
  getBase64(file).then((src)=>{
      $('#categorie_image').attr('src',src);
  });
});

$(document).on('click', '#btn-save', function(event) {
	event.preventDefault();

	var nom = $('#nom').val();

	if (nom) {
		var data = {
			nom : nom,
			categorie_image : $('#categorie_image').attr('src'),
		};

		var url = Routing.generate('produit_categorie_save');

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
							if (res.exist) {
								show_info('Erreur','Cette catégorie est déjà enregistré','error');
							} else {
								show_success('Succès', 'Catégorie enregistré');
							}
						}
					});
      });

	} else {
		show_info('Attention','Champs obligatoire','warning');
	}

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