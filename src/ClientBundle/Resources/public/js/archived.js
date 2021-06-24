$(document).on('click','#btn_search',function(event) {
    search_archived();
})

function search_archived() {
	var url = Routing.generate('client_find_archived');
    var data = {
        value : $('#search_value').val()
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function(data) {
            $('#tb-result').html('');
            $('#tb-result').append(data);
        }
    })
}

$(document).on('click','.btn-restore',function(event) {
    event.preventDefault();
    var id = $(this).data('id');
    swal({
        title: "Restaurer?",
        text: "Voulez-vous vraiment Restaurer le Fiche Client " + $(this).data('nom'),
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#21b9bb",
        confirmButtonText: "Oui, Restaurer-le!",
        cancelButtonText: "Non, annulez",
        closeOnConfirm: false,
        closeOnCancel: false 
    },
    function (isConfirm) {
        if (isConfirm) {
            swal.close();
            var url = Routing.generate('client_restore',{
                id: id,
                ajax: 1
            });
            $.ajax({
                url: url,
                type: 'GET',
                datatype: 'json',
                success: function(res) {
                    search_archived();
                    show_info("SUCCESS", 'LE FICHE A ETE RESTAURE','success');
                    return;
                }
            })
        } else {
            swal("Annulé", "La restauration à été annulé", "error");
        }
    });
    
})

$(document).on('click','.btn-delete-definitively',function(event) {
    event.preventDefault();
    var action = $(this).data('action');
    var id = $(this).data('id');
    swal({
        title: "Supprimer Définitivement?",
        text: "Voulez-vous vraiment Supprimer Définitivement le Fiche Client" + $(this).data('nom'),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Oui, supprimez-le!",
        cancelButtonText: "Non, annulez",
        closeOnConfirm: false,
        closeOnCancel: false 
    },
    function (isConfirm) {
        if (isConfirm) {
            swal.close();
            // window.location.href = action;
            var url = Routing.generate('client_delete_definitively',{
                id: id,
                ajax: 1
            });
            $.ajax({
                url: url,
                type: 'GET',
                datatype: 'json',
                success: function(res) {
                    search_archived();
                    show_info("SUCCESS", 'LE FICHE A ETE SUPPRIME','success');
                    return;
                }
            })
        } else {
            swal("Annulé", "La suppression à été annulé", "error");
        }
    });
});
