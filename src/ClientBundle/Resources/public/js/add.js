$(document).ready(function(){
	$('.client_morale').removeClass('hidden');
	$('.client_physique').addClass('hidden');
	$(document).on('change','.statut',function() {
		var value = $(this).val();

		if (value == 1) {
			$('.client_morale').removeClass('hidden');
			$('.client_physique').addClass('hidden');

		} else {
			$('.client_morale').addClass('hidden');
			$('.client_physique').removeClass('hidden');

		}
	});

	$('#form').on('keyup keypress', function(e) {
	  var keyCode = e.keyCode || e.which;
	  if (keyCode === 13) { 
	    e.preventDefault();
	    return false;
	  }
	});

	$(document).on('click','#btn-save-cl',function(event) {
		event.preventDefault();

		let statut = $('select[name=statut]').val();
		let form = $('#form');

		if (statut == 1) {
			let clm_nom_societe = $('input[name=clm_nom_societe]').val();
			let clm_tel_fixe = $('input[name=clm_tel_fixe]').val();
			let clm_email = $('input[name=clm_email]').val();
			let clm_tel_contact = $('input[name=clm_tel_contact]').val();
			if (clm_nom_societe != '' && clm_tel_fixe != '') {
				// form.submit();
				var data = form.serializeArray();
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
					save_client(data, form.data('action'));
			    });

			} else {
				show_info('Erreur','Champs obligatoires','error');
			}

		} else {
			let clp_nom = $('input[name=clp_nom]').val();
			let clp_adresse = $('input[name=clp_adresse]').val();
			let clp_tel = $('input[name=clp_tel]').val();
			let clp_sexe = $('select[name=clp_sexe]').val();
			if (clp_nom != '' && clp_tel != '' && clp_sexe != '') {
				// form.submit();
				var data = form.serializeArray();

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
			    	// $(".confirm").attr('disabled', 'disabled'); 
		    		disabled_confirm(true); 
					save_client(data, form.data('action'));
			    });

			} else {
				show_info('Erreur','Champs obligatoires','error');
			}
		}
	});

	function save_client(data,url) {
		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(res) {
				if (res.success == true) {
					// show_info('Succés','Client enregistré');
					// location.reload();
					show_success('Succès','Enregistrement éffectué');
				} else {
	    		    disabled_confirm(false);
					swal({
		                title: "Le Client existant déjà",
		                text: "Voulez-vous consulter les informations du client",
		                type: "info",
		                showCancelButton: true,
		                // confirmButtonColor: "#DD6B55",
		                confirmButtonText: "OUI",
		                closeOnConfirm: false
		            }, function () {
		            	var url = Routing.generate('client_show', { id : res.id });
		            	window.location.href = url;
		            });
				}
			}
		})
	}
});