$(document).ready(function(){

	$(document).on('change','#slider',function(event) {
	  var file = document.querySelector('#slider').files[0];
	  getBase64(file).then((src)=>{
	      $('#slider_img').attr('src',src);
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

	$(document).on('click', '#btn-save-slider', function(event) {
		event.preventDefault();

		var data = {
			id : $('#id').val(),
			titre : $('#titre').val(),
			sous_titre : $('#sous_titre').val(),
			slider_img : $('#slider_img').attr('src'),
		};

		var url = Routing.generate('restaurant_slider_save');

		$.ajax({
			url : url,
			type : 'POST',
			data : data,
			success : function (res) {
				show_info('Succès', 'Création éffectué');
				location.reload()
			}
		})

	});

});
