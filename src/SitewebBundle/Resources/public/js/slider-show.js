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

	$(document).on('change','#slider',function(event) {
	  var file = document.querySelector('#slider').files[0];
	  getBase64(file).then((src)=>{
	      $('#slider_img').attr('src',src);
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

	$(document).on('click', '#btn-save-slider', function(event) {
		event.preventDefault();

		var data = {
			id : $('#id').val(),
			id_siteweb : $('#id_siteweb').val(),
			titre : $('#titre').val(),
			sous_titre : $('#sous_titre').val(),
			slider_img : $('#slider_img').attr('src'),
		};

		var url = Routing.generate('siteweb_slider_save');

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


	});

	$(document).on('click', '#btn-delete-slider', function(event) {
		event.preventDefault();

		var url = Routing.generate('siteweb_slider_delete', { id : $('#id').val() });

		swal({
	        title: "Supprimer ?",
	        text: "Voulez-vous vraiment supprimer ce slider ",
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
					window.location.href = Routing.generate('siteweb_slider_index', { id : $('#id_siteweb').val() })
				}
			})
	    });


	});

	$(document).on('click', '#btn-disable-slider', function(event) {
		event.preventDefault();

		var url = Routing.generate('siteweb_slider_disable', { id : $('#id').val() });

		swal({
	        title: "Désactiver ?",
	        text: "Voulez-vous vraiment désactiver ce slider ",
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
					show_info('Succès', 'Slider désactivé');
					location.reload();
					// window.location.href = Routing.generate('siteweb_slider_index', { id : $('#id_siteweb').val() })
				}
			})
	    });


	});

	$(document).on('click', '#btn-enable-slider', function(event) {
		event.preventDefault();

		var url = Routing.generate('siteweb_slider_enable', { id : $('#id').val() });

		swal({
	        title: "Activer ?",
	        text: "Voulez-vous vraiment activer ce slider ",
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
					show_info('Succès', 'Slider activé');
					location.reload();
					// window.location.href = Routing.generate('siteweb_slider_index', { id : $('#id_siteweb').val() })
				}
			})
	    });


	});

});
