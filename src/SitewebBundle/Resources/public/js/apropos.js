
$(document).ready(function(){

	$('.summernote').summernote({
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

	$(document).on('change','#logo',function(event) {
	  var file = document.querySelector('#logo').files[0];
	  getBase64(file).then((src)=>{
	      $('#logo_img').attr('src',src);
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

	$(document).on('click', '#btn-save-apropos', function(event) {
		event.preventDefault();

		var data = {
			id_apropos : $('#id_apropos').val(),
			id_siteweb : $('#id_siteweb').val(),
			logo_img : $('#logo_img').attr('src'),
			slogon : $('#slogon').val(),
			apropos : $('#apropos').code(),
			titre : $('#titre').val(),
			adresse : $('#adresse').val(),
			tel_fixe : $('#tel_fixe').val(),
			tel_mobile : $('#tel_mobile').val(),
			email : $('#email').val(),
			facebook : $('#facebook').val(),
		};

		var url = Routing.generate('siteweb_apropos_save');

		swal({
	        title: "Enregistrer ?",
	        text: "Voulez-vous vraiment enregistrer les modification ",
	        type: "info",
	        showCancelButton: true,
	        confirmButtonText: "Oui",
	        cancelButtonText: "Non",
	    },
	    function () {

			$.ajax({
				url : url,
				type : 'POST',
				data : data,
				success : function (res) {
					show_info('Succès', 'Mise à jour éffectué');
					location.reload()
				}
			})
	    });


	})
});
