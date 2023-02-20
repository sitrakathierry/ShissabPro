$('.select2').select2();

$('.summernote').summernote();

// $('#plat_image').attr('src',get_picture_b64());

$(document).on('change','#image',function(event) {
  var file = document.querySelector('#image').files[0];
  getBase64(file).then((src)=>{
      $('#plat_image').attr('src',src);
  });
});

$(document).on('click', '#btn-save', function(event) {
	event.preventDefault();

	var data = {
		id : $('#id').val(),
		type : $('#type').val(),
		categorie : $('#categorie').val(),
		nom : $('#nom').val(),
		description : $('#description').code(),
		prix : $('#prix').val(),
		prix_vente : $('#prix_vente').val(),
		plat_image : $('#plat_image').attr('src'),
	};

	if (data.nom == '' || data.categorie == '' || data.type == '' || data.prix_vente == '') {
		show_info('Attention','Champs obligatoire','warning');
		return;
	}

	var url = Routing.generate('restaurant_plat_save');

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
					show_success('Succès', 'Plat enregistré');
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

var elem = document.querySelector('#statut');
var switchery = new Switchery(elem, { color: '#1AB394' });

$(document).on('change','#statut',function(event) {
	event.preventDefault();

	var statut = $(this).is(":checked");
	var statut = statut == true ? 1 : 0;
	var id = $('#id').val();

	var url = Routing.generate('restaurant_plat_statut', {
		id : id,
		statut : statut
	});

	$.ajax({
			url: url,
			type: 'GET',
			success: function(res) {
				show_success('Succès', 'Mise à jour éffectué');
			}
		})

});

$(document).on('click','#btn-delete',function(event) {
	event.preventDefault();

	var url = Routing.generate('restaurant_plat_delete', { id : $('#id').val() });

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
					show_success('Succès', 'Suppression éffectué', Routing.generate('restaurant_plat_consultation'));
				}
			});
      
  });
})