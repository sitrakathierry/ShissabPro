list_entrees_sorties();

$(document).on('click', '.cl_statut_produit', function(event) {
	$('#produit-status-modal').modal('show');
})

function list_entrees_sorties() {
	var data = {
		produit_id : $('#id_produit').val(),
		type : $('#type').val(),
		id_entrepot : $('#id_entrepot').val(),
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