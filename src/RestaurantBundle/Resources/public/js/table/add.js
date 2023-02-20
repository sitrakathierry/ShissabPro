$(document).on('click', '#btn-save', function(event) {
	event.preventDefault();

	var data = {
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

function getBase64(file) {
  return new Promise((resolve)=>{
     var reader = new FileReader();
     reader.readAsDataURL(file);
     reader.onload = function () {
       resolve(reader.result);
     };
     reader.onerror = function (error) {
       resolve(false)
     };
  })
}