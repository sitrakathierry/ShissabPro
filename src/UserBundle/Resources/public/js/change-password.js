$(document).on('click', '#change-password', function(event) {
	event.preventDefault();

	$(this).addClass('hidden');
	$('#cancel-change-password, .password-container').removeClass('hidden');
    $('#u_pass, #u_pass_confirm').val('');
});

function confirmation(password, confirm) {

	var closed = $('.password-container').hasClass('hidden');

	if (password == confirm) {
		$('.password-confimed')
			.removeClass('fa-warning')
			.removeClass('danger')
			.addClass('fa-check')
			.addClass('success');

		if (!closed) {
			$('#btn-save').prop('disabled',false);
		}


	} else {
		$('.password-confimed')
			.removeClass('fa-check')
			.removeClass('success')
			.addClass('fa-warning')
			.addClass('danger');

		if (!closed) {
			$('#btn-save').prop('disabled', true);
		}

	}
}

$(document).on('input', '#u_pass', function(event) {
	var password = event.target.value;

	if (password != '') {
		var confirm = $('#u_pass_confirm').val();
	}

	confirmation(password, confirm);
});

$(document).on('input', '#u_pass_confirm', function(event) {
	var password = $('#u_pass').val();
	var confirm = event.target.value;

	confirmation(password, confirm);
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
	var password = event.target.value;
	
	if (password != '') {
    	password_checker();
	}
});


$(document).on('click', '#cancel-change-password', function(event) {
    event.preventDefault();

    $(this).addClass('hidden');
    
    $('#u_pass, #u_pass_confirm').val('');
	
	$('.password-container').addClass('hidden');
	
	$('#btn-save').prop('disabled',false);

	$('#change-password').removeClass('hidden')

})
