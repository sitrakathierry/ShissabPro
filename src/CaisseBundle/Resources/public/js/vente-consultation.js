$(document).ready(function(){

	$('#produit').select2();

	$(document).on('click','.btn-delete',function(event) {
		event.preventDefault();

		var id = $(this).data('id');
		var url = Routing.generate('caisse_vente_delete', { id : id });

		disabled_confirm(false); 

      	swal({	
            title: "SUPPRIMER",
            text: "Voulez-vous vraiment supprimer ? ",
            type: "warning",
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
        			show_success('Succès', 'Suppression éffectué');
        		}
        	});
          
      	});
	})

	$(document).on('click', '#btn_search', function(event) {
		event.preventDefault()
		load_list();
		
	})
	load_list();

	function load_list() {
		var data = {
			agence : $('#agence').val(),
			produit : $('#produit').val(),
			type_date : $('#type_date').val(),
			mois : $('#mois').val(),
			annee : $('#annee').val(),
			date_specifique : $('#date_specifique').val(),
			debut_date : $('#debut_date').val(),
			fin_date : $('#fin_date').val(),
		};

		var url = Routing.generate('caisse_commande_list');

		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(res) {
				$('#list_commande').html( res )
			}
		})
	}

	$(document).on('change','#type_date',function(event) {

		var val = $(this).val();

		switch (val){
			case "0":
				$('.selector_mois').addClass('hidden');
				$('.selector_annee').addClass('hidden');
				$('.selector_fourchette').addClass('hidden');
				$('.selector_specifique').addClass('hidden');
				break;
			case "1":
				$('.selector_mois').addClass('hidden');
				$('.selector_annee').addClass('hidden');
				$('.selector_fourchette').addClass('hidden');
				$('.selector_specifique').addClass('hidden');
				break;
			case "2":
				$('.selector_mois').removeClass('hidden');
				$('.selector_annee').removeClass('hidden');
				$('.selector_fourchette').addClass('hidden');
				$('.selector_specifique').addClass('hidden');

				break;
			case "3":
				$('.selector_mois').addClass('hidden');
				$('.selector_annee').removeClass('hidden');
				$('.selector_fourchette').addClass('hidden');
				$('.selector_specifique').addClass('hidden');
				break;
			case "4":
				$('.selector_mois').addClass('hidden');
				$('.selector_annee').addClass('hidden');
				$('.selector_fourchette').addClass('hidden');
				$('.selector_specifique').removeClass('hidden');

				$('.input-datepicker').datepicker({
				      todayBtn: "linked",
				      keyboardNavigation: false,
				      forceParse: false,
				      calendarWeeks: true,
				      autoclose: true,
				      format: 'dd/mm/yyyy',
				      language: 'fr',
				  });
				break;

			case "5":
				$('.selector_mois').addClass('hidden');
				$('.selector_annee').addClass('hidden');
				$('.selector_specifique').addClass('hidden');
				$('.selector_fourchette').removeClass('hidden');
				$('.input-datepicker').datepicker({
			      todayBtn: "linked",
			      keyboardNavigation: false,
			      forceParse: false,
			      calendarWeeks: true,
			      autoclose: true,
			      format: 'dd/mm/yyyy',
			      language: 'fr',
				});
				break;
		}	

	})
});