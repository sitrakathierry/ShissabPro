$(document).ready(function(){

	$(document).on('click', '#btn_search', function(event) {
		event.preventDefault()
		load_list();
		
	})
	load_list();

	function load_list() {
		var data = {
			agence : $('#agence').val(),
			statut : 1,
			statut_paiement : 0
		};

		var url = Routing.generate('credit_list');

		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(res) {
				$('#list_commande').html( res )
			}
		})
	}

	$(document).on('click','.btn-validation',function(event) {
		event.preventDefault();

		var id = $(this).data('id');

		var url = Routing.generate('credit_validation', { id : id });

		disabled_confirm(false); 

      	swal({
            title: "Valider",
            text: "Voulez-vous vraiment valider ? ",
            type: "info",
            showCancelButton: true,
            confirmButtonText: "Oui",
            cancelButtonText: "Non",
        },
        function () {
            disabled_confirm(true);
                
        	$.ajax({
        		url: url,
        		type: 'GET',
        		success: function(res) {
        			show_success('Succès', 'Validation éffectué');
        		}
        	});
      	});
	})
});