$(document).ready(function(){

	// $('.navbar-minimalize').trigger('click');
    // $("body").toggleClass("mini-navbar");

	$(document).on('click', '#btn_search', function(event) {
		event.preventDefault()
		load_list();
		
	})
	load_list();

	function load_list() {
		var data = {
			agence : $('#agence').val(),
			statut : 1
		};

		var url = Routing.generate('restaurant_reservation_list');

		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(res) {
				$('#list_reservation').html( res )
			}
		})
	}

	$(document).on('click','.btn-terminer',function(event) {
		event.preventDefault();

		var id = $(this).data('id');

		var url = Routing.generate('restaurant_reservation_terminer', { id : id });

		disabled_confirm(false); 

		swal({
		        title: "PASSER À LA CAISSE",
		        text: "Voulez-vous vraiment passer ce commande à la caisse ? ",
		        type: "success",
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
						show_success('Succès','Commande terminé', Routing.generate('restaurant_reservation_show', { id : res.id }));
					}
				})
		      
		});
	})

});