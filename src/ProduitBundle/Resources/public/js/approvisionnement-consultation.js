$(document).ready(function(){
	load_list();

	function load_list() {
		var data = {
			agence : $('#agence').val()
		};

		var url = Routing.generate('produit_approvisionnement_list');

		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(res) {
				$('#list_appro').html( res )
			}
		})
	}
});