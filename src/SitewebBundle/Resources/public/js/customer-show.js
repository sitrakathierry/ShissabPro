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

	$(document).on('change','#customer_logo',function(event) {
	  var file = document.querySelector('#customer_logo').files[0];
	  getBase64(file).then((src)=>{
	      $('#customer_img').attr('src',src);
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

	$(document).on('click', '#btn-save-customer', function(event) {
		event.preventDefault();

		var data = {
			id : $('#id').val(),
			id_siteweb : $('#id_siteweb').val(),
			customer_img : $('#customer_img').attr('src'),
			nom : $('#nom').val(),
			adresse : $('#adresse').val(),
			tel : $('#tel').val(),
			email : $('#email').val(),
		};

		var url = Routing.generate('siteweb_customer_save');

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


	$(document).on('click', '#btn-delete-customer', function(event) {
		event.preventDefault();

		var url = Routing.generate('siteweb_customer_delete', { id : $('#id').val() });

		swal({
	        title: "Supprimer ?",
	        text: "Voulez-vous vraiment supprimer ce customer ",
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
					window.location.href = Routing.generate('siteweb_customer_index', { id : $('#id_siteweb').val() })
				}
			})
	    });

	});

	$(document).on('click', '#btn-disable-customer', function(event) {
		event.preventDefault();

		var url = Routing.generate('siteweb_customer_disable', { id : $('#id').val() });

		swal({
	        title: "Désactiver ?",
	        text: "Voulez-vous vraiment désactiver ce customer ",
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
					show_info('Succès', 'Customer désactivé');
					location.reload();
					// window.location.href = Routing.generate('siteweb_customer_index', { id : $('#id_siteweb').val() })
				}
			})
	    });


	});

	$(document).on('click', '#btn-enable-customer', function(event) {
		event.preventDefault();

		var url = Routing.generate('siteweb_customer_enable', { id : $('#id').val() });

		swal({
	        title: "Activer ?",
	        text: "Voulez-vous vraiment activer ce customer ",
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
					show_info('Succès', 'Customer activé');
					location.reload();
					// window.location.href = Routing.generate('siteweb_customer_index', { id : $('#id_siteweb').val() })
				}
			})
	    });


	});


});
