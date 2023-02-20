$(document).on('click','#btn-save', function(event) {
    event.preventDefault();

    var model = $('#f_model').val();
    var client = $('#f_client').val();

    if (model&& client) {
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
            $('#form-facture').submit();
        });
    } else {
        show_info('Attention','Champs obligatoire','warning');
    }
})


$(document).on('click', '#btn-delete', function(event) {
    event.preventDefault();

    var url = Routing.generate('facture_delete', { id : $('#f_id').val() });
    disabled_confirm(false); 
    swal({
        title: "SUPPRIMER",
        text: "Voulez-vous vraiment supprimer la facture ainsi que ses elements (Bon de commande, bon de livraison) ?",
        type: "error",
        showCancelButton: true,
        confirmButtonText: "OUI",
        closeOnConfirm: false
    }, function () {
        disabled_confirm(true);
        $.ajax({
            url: url,
            type: 'GET',
            success: function(res) {
              show_success('Supprimé','Suppression éffectué', Routing.generate('facture_consultation'))
            }
        })
    });
    
});

$(document).on('click', '#btn-archive', function(event) {
    event.preventDefault();

    var url = Routing.generate('facture_archive', { id : $('#f_id').val() });
    disabled_confirm(false); 
    swal({
        title: "MIS EN CORBEILLE",
        text: "Voulez-vous vraiment mettre la facture ainsi que ses elements (Bon de commande, bon de livraison) dans le corbeille ?",
        type: "error",
        showCancelButton: true,
        confirmButtonText: "OUI",
        closeOnConfirm: false
    }, function () {
        disabled_confirm(true);
        $.ajax({
            url: url,
            type: 'GET',
            success: function(res) {
              show_success('Mis dans le corbeille','Mis en corbeille éffectué', Routing.generate('facture_consultation'))
            }
        })
    });
    
});

$(document).on('click', '#btn-rearchive', function(event) {
    event.preventDefault();

    var url = Routing.generate('facture_rearchive', { id : $('#f_id').val() });
    disabled_confirm(false); 
    swal({
        title: "RESTAURER",
        text: "Voulez-vous vraiment restaurer la facture ainsi que ses elements (Bon de commande, bon de livraison) ?",
        type: "error",
        showCancelButton: true,
        confirmButtonText: "OUI",
        closeOnConfirm: false
    }, function () {
        disabled_confirm(true);
        $.ajax({
            url: url,
            type: 'GET',
            success: function(res) {
              show_success('Restaurer','Restauration éffectué', Routing.generate('facture_consultation'))
            }
        })
    });
    
});


$(document).on('click', '#btn-modal-print', function(event) {
  event.preventDefault();

  var data = {
    f_id : $('#f_id').val(),
    objet : 1,
  };

  var url = Routing.generate('facture_modele_pdf_editor');

  $.ajax({
      data: data,
      type: 'POST',
      url: url,
      dataType: 'html',
      success: function(data) {
          show_modal(data,'Modèle Impression');
      }
  });

});

$(document).on('click','#id_save_modele_pdf',function(event) {
  event.preventDefault();

  var data = {
    f_id : $('#f_id').val(),
    f_modele_pdf : $('#f_modele_pdf').val(),
  };

  var url = Routing.generate('facture_modele_pdf_save');

  $.ajax({
      data: data,
      type: 'POST',
      url: url,
      success: function(data) {

        var f_model = $('#f_model').val();

        if (f_model == '1') {
          var route = Routing.generate('facture_produit_pdf', { id : data.id });
        }

        if (f_model == '2') {
          var route = Routing.generate('facture_service_pdf', { id : data.id });
        }

        if (f_model == '3') {
          var route = Routing.generate('facture_produitservice_pdf', { id : data.id });
        }

        if (f_model == '4') {
          var route = Routing.generate('facture_hebergement_pdf', { id : data.id });
        }


        window.open(route, '_blank');

      }
  });

})