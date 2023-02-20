var url = Routing.generate('hebergement_reservation_notification');
var data = {
	type_reponse : 'html'
};

$.ajax({
	url: url,
	type: 'POST',
	data: data,
	success: function(res) {
		$('#notif-booking').html(res)
	}
})