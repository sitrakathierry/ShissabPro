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

let timeout;
let password = document.getElementById('u_pass');
let strengthBadge = document.getElementById('password-strength');
let strongPassword = new RegExp('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})')
let mediumPassword = new RegExp('((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{6,}))|((?=.*[a-z])(?=.*[A-Z])(?=.*[^A-Za-z0-9])(?=.{8,}))')

$(document).on('input', '#nom', function(event) {
    var nom = event.target.value;
    $('#nom_responsable').val(nom.toLowerCase().replace(/\s/g, ''));
});

generate_password.call( $('#u_pass') );

function generate_password() {
    var length = 8
    var numberChars = "0123456789";
    var upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var lowerChars = "abcdefghijklmnopqrstuvwxyz";
    var specialChars = "!@-#$";

    var allChars = numberChars + upperChars + lowerChars + specialChars;
    var randPasswordArray = Array(length);
    randPasswordArray[0] = numberChars;
    randPasswordArray[1] = upperChars;
    randPasswordArray[2] = lowerChars;
    randPasswordArray[3] = specialChars;
    randPasswordArray = randPasswordArray.fill(allChars, 4);
    var password =  shuffle_array(randPasswordArray.map(function(x) { return x[Math.floor(Math.random() * x.length)] })).join('');
    
    $(this).val(password);
    password_checker();
}

function shuffle_array(array) {
  for (var i = array.length - 1; i > 0; i--) {
    var j = Math.floor(Math.random() * (i + 1));
    var temp = array[i];
    array[i] = array[j];
    array[j] = temp;
  }
  return array;
}



function strength_checker(password) {
    if(strongPassword.test(password)) {
        strengthBadge.style.backgroundColor = "green";
        strengthBadge.textContent = 'Forte';
    } else if(mediumPassword.test(password)) {
        strengthBadge.style.backgroundColor = 'orange';
        strengthBadge.textContent = 'Moyen';
    } else {
        strengthBadge.style.backgroundColor = 'red';
        strengthBadge.textContent = 'Faible';
    }
}

function password_checker() {
    strengthBadge.style.display = 'block';
    clearTimeout(timeout);
    timeout = setTimeout(() => strength_checker(password.value), 500);
    if(password.value.length !== 0) {
        strengthBadge.style.display != 'block';
    } else {
        strengthBadge.style.display = 'none';
    }
}

$(document).on('input', '#u_pass', function(event) {
    password_checker() 
})