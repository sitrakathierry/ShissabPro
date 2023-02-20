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

	$(document).on('click', '#btn-save-video', function(event) {
		event.preventDefault();

		var data = {
			id : $('#id').val(),
			id_siteweb : $('#id_siteweb').val(),
			titre : $('#titre').val(),
			url : $('#url').val(),
		};

		var url = Routing.generate('siteweb_video_save');

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


	$(document).on('click', '#btn-delete-video', function(event) {
		event.preventDefault();

		var url = Routing.generate('siteweb_video_delete', { id : $('#id').val() });

		swal({
	        title: "Supprimer ?",
	        text: "Voulez-vous vraiment supprimer ce video ",
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
					window.location.href = Routing.generate('siteweb_video_index', { id : $('#id_siteweb').val() })
				}
			})
	    });

	});


});
