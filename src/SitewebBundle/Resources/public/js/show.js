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

	$(document).on('click', '#btn-save', function(event) {
		event.preventDefault();

		var data = {
			id : $('#id').val(),
			nom : $('#nom').val(),
			lien : $('#lien').val(),
			description : $('#description').code(),
		};

		var url = Routing.generate('siteweb_save');

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
					location.href = Routing.generate('siteweb_show', { id : res.id });
				}
			});
			
	    });



	})
});