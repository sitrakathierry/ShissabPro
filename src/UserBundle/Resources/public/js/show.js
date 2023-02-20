$(document).on('change', '#u_role', function(event) {
	event.preventDefault()
	var role = $(this).children("option:selected").val();

	if (role == 'ROLE_ADMIN') {
		$('.agence_container').addClass('hidden');
	} else {
		$('.agence_container').removeClass('hidden');
	}
});

$('.u_nom').keyup(function() {

    var nom = $(this).val().trim();

    var data = {
        nom : nom
    };

    var url = Routing.generate('user_verify');

    $('.username-confirmed')
    .removeClass('fa-warning')
    .removeClass('fa-success')
    .removeClass('fa-spin')
    .removeClass('danger')
    .removeClass('success')
    .addClass('fa-refresh fa-spin');
    
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function(res) {
            if (res == 1) {
                show_info('Erreur', "Le nom d'utilisateur est déjà utilisé", 'error');
                $('.username-confirmed')
                .removeClass('fa-refresh fa-spin')
                .addClass('fa-warning danger');

                $('#btn-save').prop('disabled', true);

            } else {
                $('.username-confirmed')
                .removeClass('fa-refresh fa-spin')
                .addClass('fa-check success');

                $('#btn-save').prop('disabled', false);
            }
        }
    })
    


})

$(document).on('input', '.u_nom', function(event) {})

$('#user-form').on('submit', function (e) {
    e.preventDefault();
    var data = $(this).serializeArray();
    data.push({name: "image_pic", value: $('.profile-pic').attr('src')});

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
}); 

var readURL = function(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.profile-pic').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}


$(".file-upload").on('change', function(){
    readURL(this);
});

$(".upload-button").on('click', function() {
   $(".file-upload").click();
});