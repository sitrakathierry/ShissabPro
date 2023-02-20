$(document).ready(function(){

	$(document).on('click', '#btn_search', function(event) {
		event.preventDefault()
		load_list();
		
	})
	load_list();

	function load_list() {
		var data = {
			agence : $('#agence').val(),
			statut : 200,
			ficheconsommation : 1
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
});