$(document).ready(function(){
	$(document).on('click','#btn-valider',function(event) {
		event.preventDefault();

		var data = {
			date_paiement : $('#date_paiement').val(),
			montant : $('#montant').val(),
			id : $('#id').val(),
		};

		var url = Routing.generate('credit_payer');

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
	        		type: 'POST',
	        		data: data,
	        		success: function(res) {
	        			show_success('Succès', 'Paiement effectué');
	        		}
	        	});
	          
      	});
	});
});
