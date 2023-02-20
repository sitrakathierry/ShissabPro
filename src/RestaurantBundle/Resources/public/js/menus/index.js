$(document).ready(function(){

	$('.select2').select2();

	$(document).on('click', '#btn_search', function(event) {
		event.preventDefault()
		load_list();
		
	});
	
	load_list();

	function load_list() {
		var data = {
			agence_id : $('#agence_id').val(),
			categorie : $('#categorie').val(),
			type_menu : $('#type_menu').val(),
		};

		var url = Routing.generate('restaurant_menus_list');

		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(res) {
				$('#liste_plat').html( res.template )
			}
		})
	}

	$(document).on('change','#type_menu',function(event) {
		event.preventDefault();

		var type_menu = $(this).children('option:selected').val();

		if (type_menu == 2 || type_menu == 3) {
			$('.plats').addClass('hidden');
		} else {
			$('.plats').removeClass('hidden');
		}
	})

});