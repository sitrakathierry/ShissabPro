function selToArray(selector, type = 'default') {
    var taskArray = new Array();
    $(selector).each(function() {

        if (type == 'summernote') {
            taskArray.push($(this).code());
        } else {
            taskArray.push($(this).val());
        }

    });
    return taskArray;
}

function accompagnements() {
    var data = [];

    $('table#table-emporter-add > tbody  > tr').each(function(index, tr) { 
       
       var table = $(this).find('table.table-accompagnement  > tbody');
       var accompagnement = selToArray(table.find('.accompagnement'));
       var qte_accompagnement = selToArray(table.find('.qte_accompagnement'));
       var prix_accompagnement = selToArray(table.find('.prix_accompagnement'));
       var total_accompagnement = $(this).find('.total_accompagnement').val();

       var item = {
            accompagnement : accompagnement,
            qte_accompagnement : qte_accompagnement,
            prix_accompagnement : prix_accompagnement,
            total_accompagnement : total_accompagnement,
       };

       data.push(item);

    });

    return data;
}

$(document).on('click','#btn-save', function(event) {
    event.preventDefault();

    var model = $('#f_model').val();
    var client = $('#f_client').val();

    if (model&& client) {
        var accompagnement_details = accompagnements();
        $('#accompagnement_details').val( JSON.stringify(accompagnement_details) );

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

            $('#form-facture').submit();
        });
    } else {
        show_info('Attention','Champs obligatoire','warning');
    }

})

$(document).on('change', '#f_model', function(event) {
    event.preventDefault();
    var model = $(this).val();
    var type = $('#f_type').val();

    var form_produit = $('#form-produit');
    var form_service = $('#form-service');
    var form_produitservice = $('#form-produitservice');
    var form_hebergement = $('#form-hebergement');

    var form_facture = $('#form-facture');

    form_produit.addClass('hidden');
    form_service.addClass('hidden');

    $('.recu').addClass('hidden');
    $('.heb').addClass('hidden');

    if (model != '') {

	    $('#descr').summernote();
    	
        if (model == 1) {
        	form_produit.removeClass('hidden');
        	form_service.addClass('hidden');
            form_produitservice.addClass('hidden');
    	    var url = Routing.generate('facture_produit_save');

            if (type == 2) {
                $('.recu').removeClass('hidden');
            }

    	}
        
    	if (model == 2) {
        	form_service.removeClass('hidden');
    		form_produit.addClass('hidden');
            form_produitservice.addClass('hidden');
            form_hebergement.addClass('hidden');
    	    var url = Routing.generate('facture_service_save');
    	}

        if (model == 3) {
            form_produitservice.removeClass('hidden');
            form_produit.addClass('hidden');
            form_service.addClass('hidden');
            form_hebergement.addClass('hidden');
            var url = Routing.generate('facture_produitservice_save');
        }

        if (model == 4) {
            form_hebergement.removeClass('hidden');
            form_produitservice.addClass('hidden');
            form_produit.addClass('hidden');
            form_service.addClass('hidden');
            var url = Routing.generate('facture_hebergement_save');

            if (type == 2) {
                $('.heb').removeClass('hidden');
            }
        }
	    
	    form_facture.attr('action',url);

    }

});

$(document).on('change', '#f_type', function(event) {
    event.preventDefault();

    var model = $('#f_model').val();
    var type = $(this).val();

    $('.recu').addClass('hidden');
    $('.heb').addClass('hidden');

    if (type == 2 && model == 1) {
        $('.recu').removeClass('hidden');
    }

    if (type == 2 && model == 4) {
        $('.heb').removeClass('hidden');
    }

});


