$('.summernote').summernote({
  	toolbar: [
	    ['style', ['style']],
	    ['fontsize', ['fontsize']],
	    ['font', ['bold', 'italic', 'underline', 'clear']],
	    ['fontname', ['fontname']],
	    ['color', ['color']],
	    ['para', ['ul', 'ol', 'paragraph']],
	    ['height', ['height']],
	    ['table', ['table']]
  	],
  	onpaste: function (e) {
		    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');

		    e.preventDefault();

		    setTimeout( function(){
		        document.execCommand( 'insertText', false, bufferText );
		    }, 10 );
		}
});

$(document).on('click','#btn-save', function(event) {
	event.preventDefault();

	var data = {
		text : $('#text').code(),
		agence_id : $('#agence_id').val()
	};

	var url = Routing.generate('hebergement_condition_save');

	$.ajax({
		url : url,
		type : 'POST',
		data : data,
		success: function(res) {
			show_success('Succès','Enregistrement éffectué');
		}
	})
})