var user_id;



$(document).on('click', '#user-list .list-group-item', function (event) {
    event.preventDefault();

    $(this)
        .closest('.list-group')
        .find('.list-group-item')
        .removeClass('active');
    $(this).addClass('active');

    user_id = $(this).attr('data-id');

    $.ajax({
        url: Routing.generate('permission_user_list', {user_id: user_id}),
        type: 'GET',
        data: {},
        success: function (data) {
        	$('#permission-list-user').html(data);

        	$('.js-switch').each(function() {
			    new Switchery(this, { color:'#489eed' })
			});
        	
   //      	var elem = document.querySelector('.js-switch');
			// var switchery = new Switchery(elem, { color: '#1AB394' });
            
        }
    });
});

$(document).on('click','#btn-save-permission-user',function(event) {

	var permissions = {
		client : {
			create : $('#client_create').is(':checked'),
			edit : $('#client_edit').is(':checked'),
			delete : $('#client_delete').is(':checked'),
		}, facture : {
			create : $('#facture_create').is(':checked'),
			edit : $('#facture_edit').is(':checked'),
			delete : $('#facture_delete').is(':checked'),
		}, cotation : {
			create : $('#cotation_create').is(':checked'),
			edit : $('#cotation_edit').is(':checked'),
			delete : $('#cotation_delete').is(':checked'),
		}, assurance_auto : {
			create : $('#assurance_auto_create').is(':checked'),
			edit : $('#assurance_auto_edit').is(':checked'),
			delete : $('#assurance_auto_delete').is(':checked'),
		}, assurance_maladie : {
			create : $('#assurance_maladie_create').is(':checked'),
			edit : $('#assurance_maladie_edit').is(':checked'),
			delete : $('#assurance_maladie_delete').is(':checked'),
		}, sinistre : {
			create : $('#sinistre_create').is(':checked'),
			edit : $('#sinistre_edit').is(':checked'),
			delete : $('#sinistre_delete').is(':checked'),
		}, caisse : {
			create : $('#caisse_create').is(':checked'),
			edit : $('#caisse_edit').is(':checked'),
			delete : $('#caisse_delete').is(':checked'),
		}, comptabilite : {
			create : $('#comptabilite_create').is(':checked'),
			edit : $('#comptabilite_edit').is(':checked'),
			delete : $('#comptabilite_delete').is(':checked'),
		}, iard : {
			create : $('#iard_create').is(':checked'),
			edit : $('#iard_edit').is(':checked'),
			delete : $('#iard_delete').is(':checked'),
		}
	}

	permissions = JSON.stringify(permissions);

	var url = Routing.generate('permission_user_save');

	var data = {
		permissions : permissions,
		user_id : user_id
	}

	$.ajax({
		url : url,
		type : 'POST',
		data: data,
		success: function(res) {
			
		}
	})
})