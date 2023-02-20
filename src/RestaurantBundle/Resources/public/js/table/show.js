$('.select2').select2();

$('.summernote').summernote();

$(document).on('click', '#btn-save', function(event) {
	event.preventDefault();

	var data = {
		id : $('#id').val(),
		nom : $('#nom').val(),
		place : $('#place').val(),
		statut : $('#statut').val(),
	};

	var url = Routing.generate('restaurant_table_save');

	disabled_confirm(false); 

  swal({
        title: "Enregistrer",
        text: "Voulez-vous vraiment enregistrer ? ",
        type: "info",
        showCancelButton: true,
        confirmButtonText: "Oui",
        cancelButtonText: "Non",
    },
    function () {
    	disabled_confirm(true);
			
			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function(res) {
					show_success('Succès', 'Table enregistré');
				}
			})
      
  });

});

var elem = document.querySelector('#statut_checkbox');
var switchery = new Switchery(elem, { color: '#1AB394' });

$(document).on('change','#statut_checkbox',function(event) {
	event.preventDefault();

	var statut = $(this).is(":checked");
	var statut = statut == true ? 1 : 0;
	var id = $('#id').val();

	var url = Routing.generate('restaurant_table_statut', {
		id : id,
		statut : statut
	});

	$.ajax({
			url: url,
			type: 'GET',
			success: function(res) {
				show_success('Succès', 'Mise à jour éffectué');
			}
		})

});

$(document).on('click','#btn-delete',function(event) {
	event.preventDefault();

	var url = Routing.generate('restaurant_table_delete', { id : $('#id').val() });

	disabled_confirm(false); 

  swal({
        title: "SUPPRIMER",
        text: "Voulez-vous vraiment supprimer ? ",
        type: "error",
        showCancelButton: true,
        confirmButtonText: "Oui",
        cancelButtonText: "Non",
    },
    function () {
      disabled_confirm(true);
			$.ajax({
				url: url,
				type: 'GET',
				success: function(res) {
					show_success('Succès', 'Suppression éffectué', Routing.generate('restaurant_table_consultation'));
				}
			});
      
  });
})