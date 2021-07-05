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