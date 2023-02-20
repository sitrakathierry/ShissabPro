
$(document).ready(function() {

	$.MonthPicker = {
        VERSION: '3.0.4', // Added in version 2.4;
        i18n: {
            year: 'Année',
            prevYear: '<<',
            nextYear: '>>',
            next12Years: 'Jump Forward 12 Years',
            prev12Years: 'Jump Back 12 Years',
            nextLabel: 'Suiv.',
            prevLabel: 'Prec.',
            buttonText: 'Open Month Chooser',
            jumpYears: '>',
            backTo: '<',
            months: ['Jan.', 'Fev.', 'Mar.', 'Avr.', 'Mai', 'Juin', 'Juil', 'Aoû.', 'Sep.', 'Oct.', 'Nov.', 'Dec.']
        }
    };


	$('#mois_facture').MonthPicker({ Button: false });

	$(document).on('change','#mode-paiement',function(event) {
		event.preventDefault();
		var mode = $(this).children("option:selected").val();

		if (mode == 1) {
			$('#div-num-cheque').removeClass('hidden');
			$('#div-date-cheque').removeClass('hidden');
			$('#div-num-virement').addClass('hidden');
			$('#div-date-virement').addClass('hidden');
		}

		if (mode == 2) {
			$('#div-num-cheque').addClass('hidden');
			$('#div-date-cheque').addClass('hidden');
			$('#div-num-virement').addClass('hidden');
			$('#div-date-virement').addClass('hidden');
		}

		if (mode == 3) {
			$('#div-num-cheque').addClass('hidden');
			$('#div-date-cheque').addClass('hidden');
			$('#div-num-virement').removeClass('hidden');
			$('#div-date-virement').removeClass('hidden');
		}

	})

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

		var beneficiaire = $('#beneficiaire').val();
		var cheque = $('#cheque').val();
		var montant = $('#montant').val();
		var raison = $('#raison').code();
		var date = $('#date').val();
		var lettre = $('#lettre').val();
		var mode_paiement = $('#mode-paiement').val();
		var devise = $('#devise').val();
		var motif = $('#motif').val();
		var date_cheque = $('#date_cheque').val();
		var mois_facture = $('#mois_facture').val();
		var virement = $('#virement').val();
		var date_virement = $('#date_virement').val();

		if (beneficiaire == '' || montant == '') {
			show_info('Erreur','Champs obligatoire','error');
		} else {

			disabled_confirm(false); 

			swal({
		        title: "Enregistrer ?",
		        text: "Voulez-vous vraiment enregistrer ? ",
		        type: "info",
		        showCancelButton: true,
		        confirmButtonText: "Oui",
		        cancelButtonText: "Non",
		    },
		    function () {

      			disabled_confirm(true);

				var url = Routing.generate('comptabilite_decharge_save');

				var data = {
					beneficiaire: beneficiaire,
					cheque: cheque,
					montant: montant,
					raison: raison,
					date: date,
					lettre: lettre,
					mode_paiement: mode_paiement,
					devise: devise,
					motif: motif,
					date_cheque: date_cheque,
					mois_facture: mois_facture,
					virement: virement,
					date_virement: date_virement,
				}

				$.ajax({
					url: url,
					type: 'POST',
					data: data,
					success: function(res) {
						show_success('Succès','Décharge enregistré');
					},
					error: function() {
						show_info('Erreur',"Erreur d'enregistrement",'error');
					}
				})
		    	
		    });

			
		}
	})
})