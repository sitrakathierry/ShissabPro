$(document).on('click', '#creer-definitif', function(event) {
  	event.preventDefault();

  	var url = Routing.generate('facture_service_creer_definitif', { id : $('#f_id').val() });

    swal({
      	title: "DEFINITIF",
      	text: "Voulez-vous vraiment créer une facture définitif ?",
      	type: "info",
      	showCancelButton: true,
      	confirmButtonText: "OUI",
      	closeOnConfirm: false
  	}, function () {
      	$.ajax({
          	url: url,
          	type: 'GET',
          	success: function(res) {
              	show_info('Succès', 'Facture définitif créé');
              	location.reload();
          	}
        })
  	});

});