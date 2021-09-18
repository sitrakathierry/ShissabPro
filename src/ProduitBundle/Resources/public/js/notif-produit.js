var url = Routing.generate('produit_notification_list');
var data = {
	type_reponse : 'html'
};

$.ajax({
	url: url,
	type: 'POST',
	data: data,
	success: function(res) {
		$('#notif-produit').html(res)
	}
})