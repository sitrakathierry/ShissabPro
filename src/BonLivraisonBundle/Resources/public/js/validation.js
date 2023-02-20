$(document).on('click', '#btn-valider', function(event) {
	event.preventDefault();

	var id = $('#id').val();

	var url = Routing.generate('bon_livraison_validation', { id : id });

	swal({
        title: "Validation ?",
        text: "Voulez-vous vraiment valider le bon de livraison ",
        type: "info",
        showCancelButton: true,
        confirmButtonText: "Oui",
        cancelButtonText: "Non",
    },
    function () {
		$.ajax({
			url : url,
			type : 'GET',
			success : function (res) {
				show_info('Succès', 'Bon de livraison validé');
					location.reload()
				}
		})
    });
});

$(document).on('click', '#btn-modal-print', function(event) {
  event.preventDefault();

  var data = {
    id : $('#id').val(),
    objet : 4,
  };

  var url = Routing.generate('bon_livraison_pdf_editor');

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
    id : $('#id').val(),
    f_modele_pdf : $('#f_modele_pdf').val(),
  };

  var url = Routing.generate('bon_livraison_modele_pdf_save');

  $.ajax({
      data: data,
      type: 'POST',
      url: url,
      success: function(data) {
        var route = Routing.generate('bon_livraison_pdf', { id : data.id });
        window.open(route, '_blank');
      }
  });

})