$(document).ready(function(){

	$(document).on('click', '#btn_search', function(event) {
		event.preventDefault()
		load_list();
		
	})
	load_list();

	function load_list() {
		var data = {
			agence : $('#agence').val()
		};

		var url = Routing.generate('caisse_commande_list');

		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(res) {
				$('#list_commande').html( res )
			}
		})
	}
});