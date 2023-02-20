
$(document).ready(function() {

	$(document).on('change','#devise',function(event) {
	  	var montant = Number($('#montant').val());
		var lettre = number_to_letter(montant);
		var devise = $(this).children("option:selected").val();
		$('#lettre').val(lettre + ' ' + devise);
	})

	$(document).on('input','#montant',function(event) {
		var montant = Number(event.target.value);
		var lettre = number_to_letter(montant);
		var devise = $('#devise').val();
		$('#lettre').val(lettre + ' ' + devise);
	})

	$('.summernote').summernote();

	$(document).on('click','#btn-save',function(event) {
		event.preventDefault();

		var cheque = $('#cheque').val();
		var montant = $('#montant').val();
		var raison = $('#raison').code();
		var date = $('#date').val();
		var lettre = $('#lettre').val();
		var devise = $('#devise').val();
		var date_cheque = $('#date_cheque').val();
		var banque = $('#banque').val();
		var type = $('#type').val();
		var statut = $('#statut').val();
		var id = $('#id').val();

		if (cheque == '' || montant == '') {
			show_info('Attention','Champs obligatoire','warning');
			return;
		}
		
		var url = Routing.generate('comptabilite_cheque_entree_save');

		var data = {
			cheque: cheque,
			montant: montant,
			raison: raison,
			date: date,
			lettre: lettre,
			devise: devise,
			date_cheque: date_cheque,
			banque: banque,
			type: type,
			statut: statut,
			id: id,
		}

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

			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function(res) {
					show_success('Succès','Chèque enregistré');
				},
				error: function() {
					show_info('Erreur',"Erreur d'enregistrement",'error');
				}
			});

	    });

		
	});

	$(document).on('click','#btn-delete',function(event) {
		event.preventDefault();

		disabled_confirm(false); 

		swal({
	        title: "SUPPRIMER",
	        text: "Voulez-vous vraiment supprimer ? ",
	        type: "error",
	        showCancelButton: true,
	        confirmButtonText: "Oui",
	        cancelButtonText: "Non",
	    },
	    function () {

	    	disabled_confirm(true); 
	    	
	    	var url = Routing.generate('comptabilite_cheque_entree_delete', { id : $('#id').val() });

	    	$.ajax({
	    		url : url,
	    		type : 'GET',
	    		success: function(res) {
	    			show_success('Succès','Suppression éffectué', Routing.generate('comptabilite_cheque_entree_encours'))
	    		}
	    	})
	    });
	})
})