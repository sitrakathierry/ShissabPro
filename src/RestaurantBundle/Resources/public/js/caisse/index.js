$(document).ready(function(){

	$(document).on('click', '#btn_search', function(event) {
		event.preventDefault()
		load_list();
		
	})
	load_list();

	function load_list() {
		var data = {
			agence : $('#agence').val(),
			statut : 2,
			caisse : 1,
		};

		var url = Routing.generate('restaurant_reservation_list');

		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(res) {
				$('#list_reservation').html( res )
			}
		})
	}

	$(document).on('input','#montant_recu',function(event) {
		var montant_recu = event.target.value;
		var montant_a_payer = $('#montant_a_payer').val();
		var montant_remise = $('#montant_remise').val();
		var montant_a_rendre = Number(montant_recu) - Number(montant_a_payer) + Number(montant_remise);


		$('#btn-paiement').prop('disabled',true);

		if (montant_a_rendre < 0) {
			$('#montant_a_rendre').val('');
			return;
		}

		$('#btn-paiement').prop('disabled',false);

		$('#montant_a_rendre').val(montant_a_rendre);
		$('#montant_total').val( Number(montant_a_payer) - Number(montant_remise) );

	});

	$(document).on('input','#montant_remise',function(event) {
		var montant_recu = $('#montant_recu').val();
		var montant_a_payer = $('#montant_a_payer').val();
		var montant_remise = event.target.value;
		var montant_a_rendre = Number(montant_recu) - Number(montant_a_payer) + Number(montant_remise);


		$('#btn-paiement').prop('disabled',true);

		if (montant_a_rendre < 0) {
			$('#montant_a_rendre').val('');
			return;
		}

		$('#btn-paiement').prop('disabled',false);

		$('#montant_a_rendre').val(montant_a_rendre);
		$('#montant_total').val( Number(montant_a_payer) - Number(montant_remise) );

	})

	$(document).on('click','#btn-paiement',function(event) {
		event.preventDefault();

		var id = $(this).data('id');
		var type = $(this).data('type');

		if (type == 'reservation') {
			var url = Routing.generate('restaurant_reservation_payer');
			var redirect = Routing.generate('restaurant_reservation_show', { id : id });
		} else {
			var url = Routing.generate('restaurant_emporter_payer');
			var redirect = Routing.generate('restaurant_emporter_show', { id : id });
		}

		var data = {
			id : id,
			montant_remise : $('#montant_remise').val(),
			montant_a_payer : $('#montant_a_payer').val(),
			montant_total : $('#montant_total').val(),
			montant_recu : $('#montant_recu').val(),
			montant_a_rendre : $('#montant_a_rendre').val(),
		};

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
					data : data,
					success: function(res) {
						show_success('Succès','Paiement éffectué',redirect);
					}
				})
		      
		});
	})

	$(document).on('click','.btn-payer',function(event) {
		event.preventDefault();

		var total = $(this).data('total');
		var modal = $('#modal-calc').data('tpl');
		var id = $(this).data('id');
		var type = $(this).data('type');

		show_modal(modal,'PAIEMENT');

		$('#montant_a_payer').val(total);
		$('#montant_total').val(total);
		$('#btn-paiement').attr('data-id',id);
		$('#btn-paiement').attr('data-type',type);
		$('#btn-paiement').prop('disabled',true);
	})


	$(document).on('click','.btn-credit-heb',function(event) {
		event.preventDefault();
		var id = $(this).data('id');
		var type = $(this).data('type');

		var url = Routing.generate('restaurant_credit_valider', { id : id, type : type });

		if (type == 'reservation') {
			var redirect = Routing.generate('restaurant_reservation_show', { id : id });
		} else {
			var redirect = Routing.generate('restaurant_emporter_show', { id : id });
		}


		disabled_confirm(false); 

		swal({
		        title: "CREDIT",
		        text: "Voulez-vous vraiment valider ? ",
		        type: "success",
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
						show_success('Succès','Crédit validé',redirect);
					}
				})
		      
		});
	})
});