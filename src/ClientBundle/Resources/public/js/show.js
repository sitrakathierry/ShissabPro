$(document).ready(function(){

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
			if (clm_nom_societe != '' && clm_tel_fixe != '' && clm_tel_contact != '') {
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
			if (clp_nom != '' && clp_adresse != '' && clp_tel != '' && clp_sexe != '') {
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
		}
	});

	function save_client(data,url) {
		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(res) {
				// show_info('Succés','Client enregistré');
				// location.reload();
				show_success('Succès','Enregistrement éffectué');
			}
		});
	}

	$(document).on('click','#btn-delete',function(event) {

		event.preventDefault();

		var id = $(this).data('id');

		var url = Routing.generate('client_delete', { id : id});

		disabled_confirm(false); 

		swal({
	        title: "Supprimer",
	        text: "Voulez-vous vraiment supprimer ? ",
	        type: "error",
	        showCancelButton: true,
	        confirmButtonText: "Oui",
	        cancelButtonText: "Non",
	    },
	    function () {
			disabled_confirm(true); 

			var redirect = Routing.generate('client_dashboard');

	    	$.ajax({
				url: url,
				type: 'GET',
				success: function(res) {
					show_success('Succès','Suppression éffectué', redirect);
				}
			});
	    });
	})
});