$(document).ready(function(){
	
	$('.select2').select2();

	$(document).on('click', '#btn-save', function(event) {
		event.preventDefault();

		var url = Routing.generate('pdf_attribution_save');

		var attributions = [];

		$('.modele').each(function() {
			var modele = $(this).val();
			var objet = $(this).data('objet');

			attributions.push({
				modele : modele,
				objet : objet,
			});
		});

		var data = {
			attributions : attributions
		};
		
		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(res) {
				show_info('Succès', 'Attribution enregistré');
				location.reload();
			}
		});
	})
})