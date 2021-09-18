load_list_prix_produit();

$(document).on('click', '.cl_statut_produit', function(event) {
	event.preventDefault();
	var html = 'Produit : ' + $(this).attr("data-produit") + '<span class="badge-warning">#'+$(this).attr('data-code')+'</span>';
	$('#status-produit-nom').html('');
	$('#date-expiration').val($(this).attr("data-expirer"));	
	$('#status-produit-nom').append(html);
	$('#btn-save-status').attr('data-id', $(this).attr("data-id"));
	var status = parseInt($(this).attr('data-status'));
	if(isNaN(status)) status = 0;
	if(status === 0){
		$('#status-actif').prop('checked', true);
		$('#status-desactiver').prop('checked', false);
	}
	if(status === 1){
		$('#status-desactiver').prop('checked', true);
		$('#status-actif').prop('checked', false);
	}
	$('#produit-status-modal').modal('show');
});

$('#date-expiration').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true,
    format: 'dd/mm/yyyy',
    language: 'fr'
});

$(document).on('click', '#btn-save-status', function(event) {
	event.preventDefault();
    var status = $(document).find('input:radio[name="status-value"]:checked').val(),    
        prixProduit = $(this).attr('data-id'),
        expirer = $('#date-expiration').val();

    var url = Routing.generate('produit_save_statut_prix_produit');

    var data = {
        prixProduit : prixProduit,
        status : status,
        expirer : expirer
    };

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function(res) {            
			show_info('Succès', 'Modification enregistré');
			location.reload();
        }
    });
});

function load_list_prix_produit() {
    var data = {
        produit_id : $('#id_produit').val()
    };

    var url = Routing.generate('produit_list_prix_produit');

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function(res) {
            $('#js_list_prix_produit').html(res);
        }
    });
}