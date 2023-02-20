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
        produit_id : $('#id_produit').val(),
        id_entrepot : $('#id_entrepot').val(),
    };

    var url = Routing.generate('produit_list_variation');

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function(res) {
            $('#list_variation').html(res);
        }
    });
}

$(document).on('click','#btn-save-variation', function(event) {
    event.preventDefault();

    var data = {
        id_produit : $('#id_produit').val(),
        variation_entrepot : $('#variation_entrepot').val(),
        variation_indice : $('#variation_indice').val(),
        variation_prix_vente : $('#variation_prix_vente').val(),
        variation_stock : $('#variation_stock').val(),
        variation_operation : $('#variation_operation').val(),
        id_produit_entrepot : $('#id_produit_entrepot').val(),
    };

    var url = Routing.generate('produit_save_variation');

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
                    show_success('Succès', 'Variation Produit enregistré');
                }
            })
          
    });

});

$(document).on('click','.edit-variation',function(event) {
    event.preventDefault();

    var id = $(this).data('id');

    $.ajax({
        data: {
            id: id
        },
        type: 'POST',
        url: Routing.generate('produit_editor_variation'),
        dataType: 'html',
        success: function(data) {
            show_modal(data,'Modification Variation');
        }
    });
});

$(document).on('click','#id_save_variation', function(event) {
    event.preventDefault();

    var data = {
        prix_vente : $('#variation_prix_vente_edit').val(),
        id_variation : $('#id_variation_edit').val(),
    };

    var url = Routing.generate('produit_update_variation');

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function(res) {
            show_info('Succès', 'Variation Produit enregistré');
            close_modal();
            load_list_prix_produit();
        }
    });

});

$(document).on('click','.delete-variation',function(event) {

    event.preventDefault();

    var id = $(this).data('id');

    var url = Routing.generate('produit_delete_variation', { id : id });

    disabled_confirm(false); 

    swal({
            title: "SUPPRIMER",
            text: "Voulez-vous vraiment supprimer ? ",
            type: "warning",
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
                    show_info('Succès', 'Suppression éffectué');
                    close_modal();
                    load_list_prix_produit();

                }
            });
    });


});