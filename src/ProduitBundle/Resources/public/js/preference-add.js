$('.select2').select2();

$(document).on('click','#btn-save',function(event) {
	event.preventDefault();

	var data = {
		categories : $('#categories').val()
	};

	var url = Routing.generate('produit_preference_save');

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
			type : 'POST',
			data : data,
			success: function(res) {
				show_success('Succès', 'Préférence enregistré');
			}
		})
  	});


});