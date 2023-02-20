$(document).ready(function(){
	$(document).on('click','#btn-payer',function(event) {
		event.preventDefault();

		var	id = $('#id').val();

		var data = {
			id : id,
			type_remise : $('#type_remise').val(),
			montant_remise : $('#montant_remise').val(),
			montant_a_payer : $('#montant_a_payer').val(),
			montant_total : $('#montant_total').val(),
		};

		var url = Routing.generate('hebergement_caisse_payer');

		disabled_confirm(false); 

	  	swal({
		        title: "Payer",
		        text: "Voulez-vous vraiment payer ? ",
		        type: "success",
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
  	
	$(document).on('input','#montant_remise',function(event) {

		var montant_heb = $('#montant_heb').val();
		var montant_remise = event.target.value;
		var type_remise = $('#type_remise').val();
		var montant_resto = $('#montant_resto').val();
		var montant_total = 0;
		var montant_a_payer = 0;

		if (type_remise == 1) {
			montant_remise = (Number(montant_remise) * Number(montant_heb)) / 100;
			montant_total = Number(montant_heb) - Number(montant_remise);
		} else {
			montant_total = Number(montant_heb) - Number(montant_remise);
		}

		$('#montant_total').val(montant_total);

		montant_a_payer = montant_total;

		if (montant_resto) {
			montant_a_payer += Number( montant_resto );
		}

		$('#montant_a_payer').val(montant_a_payer);
		
	});

	$(document).on('change','#type_remise',function(event) {

		var montant_heb = $('#montant_heb').val();
		var montant_remise = $('#montant_remise').val();
		var type_remise = $(this).children('option:selected').val();
		var montant_resto = $('#montant_resto').val();
		var montant_total = 0;
		var montant_a_payer = 0;

		if (type_remise == 1) {
			montant_remise = (Number(montant_remise) * Number(montant_heb)) / 100;
			montant_total = Number(montant_heb) - Number(montant_remise);
		} else {
			montant_total = Number(montant_heb) - Number(montant_remise);
		}

		$('#montant_total').val(montant_total);

		montant_a_payer = montant_total;

		if (montant_resto) {
			montant_a_payer += Number( montant_resto );
		}

		$('#montant_a_payer').val(montant_a_payer);
		
	});
});
