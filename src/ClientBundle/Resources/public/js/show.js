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
				form.submit();
			} else {
				show_info('Erreur','Champs obligatoires','error');
			}

		} else {
			let clp_nom = $('input[name=clp_nom]').val();
			let clp_adresse = $('input[name=clp_adresse]').val();
			let clp_tel = $('input[name=clp_tel]').val();
			let clp_sexe = $('select[name=clp_sexe]').val();
			if (clp_nom != '' && clp_adresse != '' && clp_tel != '' && clp_sexe != '') {
				form.submit();
			} else {
				show_info('Erreur','Champs obligatoires','error');
			}
		}



	})
});