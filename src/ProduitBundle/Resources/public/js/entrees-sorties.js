list_entrees_sorties();

$(document).on('click', '#btn_search', function(event) {
	event.preventDefault();
	list_entrees_sorties();
})

function list_entrees_sorties() {
	var data = {
		produit_id : $('#id_produit').val(),
		type : $('#type').val(),
	};

	var url = Routing.generate('produit_entrees_sorties');

	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		success: function(res) {
			$('#entres-sorties').html(res)
		}
	})
}