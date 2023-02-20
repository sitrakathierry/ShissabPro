$(document).on('click','#btn-bon-commande',function(event) {
	event.preventDefault();

	var url = Routing.generate('facture_produit_boncommande', { id : $('#f_id').val() });
    disabled_confirm(false); 
    swal({
      	title: "Bon de commande",
      	text: "Voulez-vous vraiment créer un bon de commande ?",
      	type: "info",
      	showCancelButton: true,
      	confirmButtonText: "OUI",
      	closeOnConfirm: false
  	}, function () {
    	disabled_confirm(true); 
      	$.ajax({
          	url: url,
          	type: 'GET',
          	success: function(res) {
              	show_success('Succès','Le bon de commande à été créer avec succès');
          	}
        })
  	});
})