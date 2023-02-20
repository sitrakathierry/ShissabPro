$(document).ready(function(){

	$(document).on('click', '#btn_search', function(event) {
		event.preventDefault()
		load_list_boisson();
		
	});

	load_list_boisson();

	var pageRefresh = 60000;
    setInterval(function() {
		console.log('refresh')
    	load_list_boisson();
    }, pageRefresh)

	function load_list_boisson() {
		var data = {
			agence : $('#agence').val(),
			statut : 1,
			cuisine : 1,
			type : 2
		};

		var url = Routing.generate('restaurant_reservation_list');

		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(res) {
				$('#list_boisson').html( res )
			}
		})
	}

	$(document).on('click','.btn-annuler',function(event) {
		event.preventDefault();

		var id = $(this).data('id');
		var type = $(this).data('type');

		if (type == 'reservation') {
			var url = Routing.generate('restaurant_reservation_details_annuler', { id : id });
		} else {
			var url = Routing.generate('restaurant_emporter_details_annuler', { id : id });
		}


		disabled_confirm(false); 

  		swal({
		        title: "Annuler",
		        text: "Voulez-vous vraiment annuler ? ",
		        type: "error",
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
						show_success('Succès','Commande annulé');
					}
				})
		      
	  	});
	})

	$(document).on('click','.btn-terminer',function(event) {
		event.preventDefault();

		var id = $(this).data('id');
		var type = $(this).data('type');

		if (type == 'reservation') {
			var url = Routing.generate('restaurant_reservation_details_terminer', { id : id });
		} else {
			var url = Routing.generate('restaurant_emporter_details_terminer', { id : id });
		}


		disabled_confirm(false); 

  		swal({
		        title: "Terminer",
		        text: "Voulez-vous vraiment terminer ? ",
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
						show_success('Succès','Commande terminé');
					}
				})
		      
	  	});
	})
});