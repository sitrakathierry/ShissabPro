$(document).on('change', '#u_role', function(event) {
	event.preventDefault()
	var role = $(this).children("option:selected").val();

	if (role == 'ROLE_ADMIN') {
		$('.agence_container').addClass('hidden');
	} else {
		$('.agence_container').removeClass('hidden');
	}
});

$('#user-form').on('submit', function (e) {
    e.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
    	url : Routing.generate('user_save'),
    	type: 'POST',
    	data: data,
    	success: function(res) {
            if(res == -1)
    		    show_info('Erreur','Capacité compte atteint','error');
            else{
                show_info('Succès', 'Utilisateur enregistré');
                location.reload();
            }    		
    	}
    })
})