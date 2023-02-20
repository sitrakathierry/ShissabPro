$(document).on('click','#btn-save',function(event) {
	event.preventDefault();

	var data = {
		agence_id : $('#agence_id').val(),
		devise_symbole : $('#devise_symbole').val(),
		devise_lettre : $('#devise_lettre').val(),
	};

	var url = Routing.generate('agence_parametre_save');

	$.ajax({
		url : url,
		type : 'POST',
		data: data,
		success: function(res) {
			show_success('Succès','Paramètre enregistré');
		}
	});
});

$(document).on('click','.btn-delete-devise',function(event) {
	event.preventDefault();

	var id = $(this).closest('tr').find('.id').val();

	var url = Routing.generate('agence_devise_delete', { id : id });

	$.ajax({
		url : url,
		type : 'GET',
		success: function(res) {
			show_success('Succès','Suppression éffectué');
		}
	})
})

$(document).on('click','.btn-save-devise', function(event) {
	event.preventDefault();

	var btn = $(this);
	btn.prop('disabled', true);

	var tr = $(this).closest('tr');

	var data = {
		agence_id : $('#agence_id').val(),
		id : tr.find('.id').val(),
		symbole : tr.find('.symbole').val(),
		lettre : tr.find('.lettre').val(),
		montant_principal : tr.find('.montant_principal').val(),
		montant_conversion : tr.find('.montant_conversion').val(),
	};

	var url = Routing.generate('agence_devise_save');

	$.ajax({
		url : url,
		type: 'POST',
		data : data,
		success: function(res) {
			show_success('Succès','Paramètre enregistré');
		},
		error: function() {
			show_info('Erreur','Une erreur est survenu','error');
			btn.prop('disabled', false);
		}
	})
});

$(document).on('click','#btn-save-devise-entrepot',function(event) {
	event.preventDefault();

	var devises = [];

	$('.d_entrepot').each(function() {
		var tr = $(this).closest('tr');

		var item = {
			entrepot : $(this).val(),
			devise_symbole : tr.find('.d_devise').children('option:selected').val(),
			devise_lettre : tr.find('.d_devise').children('option:selected').data('lettre'),
		}

		devises.push(item);
	});

	var data = {
		devises : devises,
		agence_id : $('#agence_id').val()
	}

	var url = Routing.generate('agence_devise_entrepot_save');

	$.ajax({
		url : url,
		type : 'POST',
		data : data,
		success: function(res) {
			show_success('Succès','Enregistrement éffectué');
		}
	});

});


$(document).on('click','#btn-save-ticket',function(event) {
	event.preventDefault();

	var data = {
		agence_id : $('#agence_id').val(),
		ticket_titre : $('#ticket_titre').val(),
		ticket_soustitre : $('#ticket_soustitre').val(),
		ticket_adresse : $('#ticket_adresse').val(),
		ticket_tel : $('#ticket_tel').val(),
	};

	var url = Routing.generate('agence_parametre_ticket_save');

	$.ajax({
		url : url,
		type : 'POST',
		data: data,
		success: function(res) {
			show_success('Succès','Paramètre enregistré');
		}
	});
});