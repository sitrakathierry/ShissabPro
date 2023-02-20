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

    $(document).on('click', '.btn-add-field', function(event) {
    	event.preventDefault();

    	var tpl = '<div class="row">'
		tpl += '<div class="col-lg-12">';
		tpl += '<div class="form-group">';
		tpl += '<label class="col-sm-2 control-label"></label>';
		tpl += '<div class="col-sm-8">';
		tpl += '<input type="text" class="form-control field" placeholder="Nom du champs">';
		tpl += '</div>';
		tpl += '<div class="col-sm-2">';
		tpl += '<button class="btn btn-danger btn-remove-field" style="width:100%">';
		tpl += '<i class="fa fa-trash"></i>';
		tpl += '</button>';
		tpl += '</div>';
		tpl += '</div>';
		tpl += '</div>';
		tpl += '</div>';

		$('.fields-container').append(tpl);
    });

    var remove = new Array();

    $(document).on('click', '.btn-remove-field', function(event) {
    	event.preventDefault();

    	var id = $(this).data('id');

		if (id) {
    		remove.push({
    			id : id
    		});
		}

    	$(this).closest('div.row').remove();
    });

    $(document).on('input', '.field,#nom', function(event) {
	 	var nom = event.target.value;
	 	var slug = 	nom
	 				.normalize("NFD")
	 				.replace(/[\u0300-\u036f]/g, "")
	 				.toLowerCase()
	 				.replace(/\s/g, '')
	 				.replace(/[^a-zA-Z0-9]/g,'');
	 	$(this).attr('data-slug',slug);
    });


    $(document).on('click', '#btn-save', function(event) {
    	event.preventDefault();
    	var fields = new Array();
    	var update = new Array();
    	$('.field').each(function() {

    		var id = $(this).data('id');

    		if (id) {
	    		update.push({
	    			id : id,
	    			name : $(this).val(),
	    			slug : $(this).data('slug'),
	    		});
    		} else {
    			fields.push({
	    			name : $(this).val(),
	    			slug : $(this).data('slug'),
	    		});
    		}

    	});

    	var data = {
    		id : $('#id').val(),
    		id_siteweb : $('#id_siteweb').val(),
    		nom : $('#nom').val(),
    		slug : $('#nom').data('slug'),
    		description : $('#description').code(),
    		fields : fields,
    		remove : remove,
    		update : update,
    	};

    	var url = Routing.generate('siteweb_formulaire_save');

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
	    		data: data,
	    		success: function(res) {
	    			show_info('Succès', 'Enregistrement éffectué');
	    			location.reload();
	    		}
	    	})
	    	
	    });


    })
});
