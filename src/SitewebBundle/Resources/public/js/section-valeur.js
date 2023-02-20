$(document).on('change','.section-img-input',function(event) {
  
  var file = $(this)[0].files[0];

  getBase64(file).then((src)=>{
      $(this).closest('div.type-img').find('img.section-img').attr('src',src);
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

$(document).on('click','#btn-save-section-valeur', function(event) {
	event.preventDefault();

	var sections = {};

	$('.section-item').each(function() {

		var name = $(this).data('id');

		if ($(this).hasClass('section-text')) {
			var value = $(this).val();
		}

		if ($(this).hasClass('section-editor')) {
			var value = $(this).code();
		}

		if ($(this).hasClass('section-img')) {
			var value = $(this).attr('src');
		}

		sections[name] = value;
	});

	var data = {
		id_siteweb : $('#id_siteweb').val(),
		sections : sections
	};

	var url = Routing.generate('siteweb_section_valeur_save');

	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		success: function(res) {
			show_info('Succès','Enregistrement éffectué');
			location.reload();
		}
	});

	
})