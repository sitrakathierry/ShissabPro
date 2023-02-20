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

	$(document).on('change','#membre_logo',function(event) {
	  var file = document.querySelector('#membre_logo').files[0];
	  getBase64(file).then((src)=>{
	      $('#membre_img').attr('src',src);
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

	$(document).on('click', '#btn-save-membre', function(event) {
		event.preventDefault();

		var data = {
			id : $('#id').val(),
			id_siteweb : $('#id_siteweb').val(),
			membre_img : $('#membre_img').attr('src'),
			nom : $('#nom').val(),
			poste : $('#poste').val(),
		};

		var url = Routing.generate('siteweb_membre_save');

		$.ajax({
			url : url,
			type : 'POST',
			data : data,
			success : function (res) {
				show_info('Succès', 'Création éffectué');
				location.reload()
			}
		})

	});

	$(document).on('click', '#btn-delete-membre', function(event) {
		event.preventDefault();

		var url = Routing.generate('siteweb_membre_delete', { id : $('#id').val() });

		swal({
	        title: "Supprimer ?",
	        text: "Voulez-vous vraiment supprimer ce membre ",
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
					window.location.href = Routing.generate('siteweb_membre_index', { id : $('#id_siteweb').val() })
				}
			})
	    });

	});

	$(document).on('click', '#btn-disable-membre', function(event) {
		event.preventDefault();

		var url = Routing.generate('siteweb_membre_disable', { id : $('#id').val() });

		swal({
	        title: "Désactiver ?",
	        text: "Voulez-vous vraiment désactiver ce membre ",
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
					show_info('Succès', 'Membre désactivé');
					location.reload();
					// window.location.href = Routing.generate('siteweb_membre_index', { id : $('#id_siteweb').val() })
				}
			})
	    });


	});

	$(document).on('click', '#btn-enable-membre', function(event) {
		event.preventDefault();

		var url = Routing.generate('siteweb_membre_enable', { id : $('#id').val() });

		swal({
	        title: "Activer ?",
	        text: "Voulez-vous vraiment activer ce membre ",
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
					show_info('Succès', 'Membre activé');
					location.reload();
					// window.location.href = Routing.generate('siteweb_membre_index', { id : $('#id_siteweb').val() })
				}
			})
	    });


	});


});
