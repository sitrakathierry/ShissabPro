$(document).on('click','#btn-delete',function(event) {
	event.preventDefault();

	var url = Routing.generate('pdf_delete', { id : $('#id').val() });

	disabled_confirm(false); 

  swal({
        title: "Supprimer",
        text: "Voulez-vous vraiment supprimer ce modèle ? ",
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
    			show_success('Succès', 'Suppression éffectué', Routing.generate('pdf_consultation'));
    		}
    	});
      
  });

})

$('#texte_haut').summernote({
  	toolbar: [
	    ['style', ['style']],
	    ['fontsize', ['fontsize']],
	    ['font', ['bold', 'italic', 'underline', 'clear']],
	    ['fontname', ['fontname']],
	    ['color', ['color']],
	    ['para', ['ul', 'ol', 'paragraph']],
	    ['height', ['height']],
	    ['table', ['table']]
  	],
  	onpaste: function (e) {
		    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');

		    e.preventDefault();

		    setTimeout( function(){
		        document.execCommand( 'insertText', false, bufferText );
		    }, 10 );
		}
});

var type_modele = $('#type_modele').val();
change_modele( 'modele' + type_modele );

$('input:radio[name=modele]').change(function () {
	var id = $(this).attr('id');
	change_modele(id);

});

function change_modele(id) {
	console.log(id)
	if (id == 'modele1') {
		$('.logo-gauche').removeClass('hidden')
		$('.logo-centre').addClass('hidden')
		$('.logo-droite').removeClass('hidden')
		$('.logo').removeClass('hidden')
		$('#type_modele').val(1)
	}

	if (id == 'modele2') {
		$('.logo-gauche').removeClass('hidden')
		$('.logo-centre').addClass('hidden')
		$('.logo-droite').addClass('hidden')
		$('.logo').removeClass('hidden')
		$('#type_modele').val(2)


	}

	if (id == 'modele3') {
		$('.logo-gauche').addClass('hidden')
		$('.logo-centre').addClass('hidden')
		$('.logo-droite').removeClass('hidden')
		$('.logo').removeClass('hidden')
		$('#type_modele').val(3)

	}

	if (id == 'modele4') {
		$('.logo-gauche').addClass('hidden')
		$('.logo-centre').removeClass('hidden')
		$('.logo-droite').addClass('hidden')
		$('.logo').removeClass('hidden')
		$('#type_modele').val(4)

	}

	if (id == 'modele5') {
		$('.logo-gauche').addClass('hidden')
		$('.logo-centre').addClass('hidden')
		$('.logo-droite').addClass('hidden')
		$('.logo').addClass('hidden')
		$('#type_modele').val(5)

	}

	if (id == 'modele6') {
		$('.logo-gauche').removeClass('hidden')
		$('.logo-centre').addClass('hidden')
		$('.logo-droite').removeClass('hidden')
		$('.logo').removeClass('hidden')
		$('#type_modele').val(6)
	}

	if (id == 'modele7') {
		$('.logo-gauche').removeClass('hidden')
		$('.logo-centre').addClass('hidden')
		$('.logo-droite').addClass('hidden')
		$('.logo').removeClass('hidden')
		$('#type_modele').val(7)
	}
}

$(document).on('change','#logo-gauche',function(event) {
  var file = document.querySelector('#logo-gauche').files[0];
  getBase64(file).then((src)=>{
      $('#logo_gauche_img').attr('src',src);
  });
});

$(document).on('change','#logo-centre',function(event) {
  var file = document.querySelector('#logo-centre').files[0];
  getBase64(file).then((src)=>{
      $('#logo_centre_img').attr('src',src);
  });
});

$(document).on('change','#logo-droite',function(event) {
  var file = document.querySelector('#logo-droite').files[0];
  getBase64(file).then((src)=>{
      $('#logo_droite_img').attr('src',src);
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

$(document).on('click', '#btn-save-modele', function(event) {
	event.preventDefault();

	var data = {
		id : $('#id').val(),
		nom : $('#nom').val(),
		type_modele : $('#type_modele').val(),
		logo_gauche_img : $('#logo_gauche_img').attr('src'),
		logo_centre_img : $('#logo_centre_img').attr('src'),
		logo_droite_img : $('#logo_droite_img').attr('src'),
		texte_haut : $('#texte_haut').code(),
	}

	var url = Routing.generate('pdf_save');

	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		success: function(res) {
			show_info('Succès', 'Modèle enregistré');
			location.reload();
		}
	})
})