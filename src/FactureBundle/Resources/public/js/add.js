$(document).on('change', '#f_model', function(event) {
    event.preventDefault();
    var model = $(this).val();

    var form_produit = $('#form-produit');
    var form_service = $('#form-service');
    var form_facture = $('#form-facture');

    form_produit.addClass('hidden');
    form_service.addClass('hidden');

    if (model != '') {

    	if (model == 1) {
        	form_produit.removeClass('hidden');
        	form_service.addClass('hidden');
    	    var url = Routing.generate('facture_produit_save');

    	    $('#descr').summernote();
    	}
    	if (model == 2) {
    		form_produit.addClass('hidden');
        	form_service.removeClass('hidden');
    	    var url = Routing.generate('facture_service_save');
    	}
	    
	    form_facture.attr('action',url);

    }

});