var cl_row_edited = 'r-cl-edited';

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

    $(document).on('change','.img-input',function(event) {
      	var file = $(this)[0].files[0];
  		getBase64(file).then((src)=>{
      		$(this).closest('div.type-img').find('.img-src').attr('src',src);
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

	$(document).on('click', '#btn-save-produit', function(event) {
		event.preventDefault();

		var data = {
			id : $('#id').val(),
			id_siteweb : $('#id_siteweb').val(),
			produit_img : $('#produit_img').attr('src'),
			produit_icon : $('#produit_icon').attr('src'),
			nom : $('#nom').val(),
			description : $('#description').code(),
		};

		var url = Routing.generate('siteweb_produit_save');

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
					show_info('Succès', 'Enregistrement éffectué');
					location.reload()
				}
			})

	    });

	});


	$(document).on('click', '#btn-delete-produit', function(event) {
		event.preventDefault();

		var url = Routing.generate('siteweb_produit_delete', { id : $('#id').val() });

		swal({
	        title: "Supprimer ?",
	        text: "Voulez-vous vraiment supprimer ce produit ",
	        type: "warning",
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
					show_info('Succès', 'Suppression éffectué');
					window.location.href = Routing.generate('siteweb_produit_index', { id : $('#id_siteweb').val() })
				}
			})
	    });

	});


});
