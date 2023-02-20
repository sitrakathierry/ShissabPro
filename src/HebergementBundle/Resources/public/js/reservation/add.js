$('.clockpicker').clockpicker();

$('#data_1 .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true,
    format: 'dd/mm/yyyy',
    language: 'fr',

});

$('#date_entree, #date_sortie').datepicker({
	startDate : new Date(),
  	autoclose: true,
  	format: 'dd/mm/yyyy',
  	language: 'fr',

});


$(document).on('change','#client',function(event) {
	event.preventDefault();

	var nom_client = $(this).children('option:selected').html().trim('');
	var tel_client = $(this).children('option:selected').data('tel');

	$('#nom_client').val(nom_client);
	$('#tel_client').val(tel_client);
})

$(document).on('click','#btn_search',function(event) {
	event.preventDefault();

	var data = {
		nb_pers : $('#nb_pers').val(),
		categorie : $('#categorie').val(),
		date_entree : $('#date_entree').val(),
		date_sortie : $('#date_sortie').val(),
		heure_entree : $('#heure_entree').val(),
		heure_sortie : $('#heure_sortie').val(),
	};

	if (data.date_entree == '' || data.date_sortie == '' || data.nb_pers == '' || data.heure_entree == '' || data.heure_sortie == '') {
		show_info('Erreur','Champs obligatoire','error');
		return;
	}

	var url = Routing.generate('hebergement_chambre_search');

	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		success: function(res) {
			$('#chambres_disponibles').html(res.template)
			$('#reservation_nb_nuit').val(res.nb_nuit)
			$('.slick_demo_2').slick({
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                centerMode: true,
                dots: true
                // responsive: [
                //     {
                //         breakpoint: 1024,
                //         settings: {
                //             slidesToShow: 3,
                //             slidesToScroll: 3,
                //             infinite: true,
                //             dots: true
                //         }
                //     },
                //     {
                //         breakpoint: 600,
                //         settings: {
                //             slidesToShow: 2,
                //             slidesToScroll: 2
                //         }
                //     },
                //     {
                //         breakpoint: 480,
                //         settings: {
                //             slidesToShow: 1,
                //             slidesToScroll: 1
                //         }
                //     }
                // ]
            });
		}
	});

});

$(document).on('click','.btn-choisir',function(event) {
	event.preventDefault();

	var id = $(this).data('id');
	var nb_pers = $('#nb_pers').val();
	var nb_nuit = $('#reservation_nb_nuit').val()

	var data = {
		id : id,
		nb_pers : nb_pers
	};

	var url = Routing.generate('hebergement_chambre_tarif');

	$.ajax({
		url : url,
		type : 'POST',
		data: data,
		success: function(res) {


			$([document.documentElement, document.body]).animate({
		        scrollTop: $("#details").offset().top
		    }, 1000);

			var nom = res.chambre;
			var montant = Number(res.montant);
			var petit_dejeuner = res.petit_dejeuner;
			var montant_petit_dejeuner = Number(res.montant_petit_dejeuner);
			var nb_nuit = $('#reservation_nb_nuit').val()

			$('#reservation_chambre_nom').html(nom);
			$('#reservation_nb_pers').val(nb_pers);
			$('#chambre_id').val(id);
			$('#montant').val(montant);
			$('#reservation_avec_petit_dejeuner').val(petit_dejeuner).change();

			if (petit_dejeuner == 1) {
				$('#montant_petit_dejeuner').attr('readonly','readonly');
				$('#reservation_total').val(montant * Number(nb_nuit));
			} else {
				$('#montant_petit_dejeuner').removeAttr('readonly');
				$('#montant_petit_dejeuner').val(montant_petit_dejeuner);
				$('#reservation_total').val((montant + montant_petit_dejeuner) * Number(nb_nuit));

			}

		}
	})
})

// $(document).on('click','.btn-choisir',function(event) {
// 	event.preventDefault();

// 	var id = $(this).data('id');
// 	var nb_pers = $('#nb_pers').val();
// 	var avec_petit_dejeuner = $('#reservation_avec_petit_dejeuner').val();
// 	var nb_nuit = $('#reservation_nb_nuit').val()

// 	var item = $(this).closest('div.product-box');
// 	var nom = item.find('.product-imitation').text();
// 	var tarif_pers = item.find('.tarif_pers').val();
// 	var tarif_pers_petit_dejeuner = item.find('.tarif_pers_petit_dejeuner').val();

// 	var total = (Number( nb_pers ) * Number( tarif_pers ) ) * Number ( nb_nuit );;

// 	if (avec_petit_dejeuner == 1) {
// 		total += (Number( nb_pers ) * Number( tarif_pers_petit_dejeuner )) * Number ( nb_nuit );;
// 	}

// 	$('#reservation_chambre_nom').html(nom);
// 	$('#reservation_nb_pers').val(nb_pers);
// 	$('#reservation_tarif_pers').val(tarif_pers);
// 	$('#reservation_tarif_pers_petit_dejeuner').val( tarif_pers_petit_dejeuner );
// 	$('#reservation_total').val(total);
// 	$('#chambre_id').val(id);
// });

$(document).on('change','#reservation_avec_petit_dejeuner',function(event) {
	event.preventDefault();

	var avec_petit_dejeuner = $(this).children("option:selected").val();
	var nb_pers = $('#reservation_nb_pers').val();
	var montant_petit_dejeuner = $('#montant_petit_dejeuner').val();
	var reservation_total = $('#reservation_total').val();
	var nb_nuit = $('#reservation_nb_nuit').val();

	// var total = (Number( nb_pers ) * Number( tarif_pers ) ) * Number ( nb_nuit );;

	if (avec_petit_dejeuner == 1) {
		// total += (Number( nb_pers ) * Number( tarif_pers_petit_dejeuner )) * Number ( nb_nuit );;
		var total = Number( reservation_total ) - (Number( montant_petit_dejeuner ) * Number( nb_nuit ) );
		$('#montant_petit_dejeuner').attr('readonly','readonly');
	} else {
		var total = Number( reservation_total ) + ( Number( montant_petit_dejeuner ) * Number( nb_nuit ));
		$('#montant_petit_dejeuner').removeAttr('readonly');
	}

	$('#reservation_total').val(total);

});

$(document).on('input','#montant_petit_dejeuner',function(event) {
	var montant_petit_dejeuner = event.target.value;
	var montant = Number($('#montant').val());
	var nb_nuit = $('#reservation_nb_nuit').val();
	var reservation_total = (montant + Number(montant_petit_dejeuner)) * Number(nb_nuit);

	$('#reservation_total').val(reservation_total);

})

$(document).on('click','#btn-save',function(event) {
	event.preventDefault();

	var data = {
		nb_pers : $('#nb_pers').val(),
		date_entree : $('#date_entree').val(),
		date_sortie : $('#date_sortie').val(),
		avec_petit_dejeuner : $('#reservation_avec_petit_dejeuner').val(),
		total : $('#reservation_total').val(),
		chambre_id : $('#chambre_id').val(),
		montant_petit_dejeuner : $('#montant_petit_dejeuner').val(),
		reservation_nb_nuit : $('#reservation_nb_nuit').val(),
		montant : $('#montant').val(),
		statut : $('#statut').val(),
		heure_entree : $('#heure_entree').val(),
		heure_sortie : $('#heure_sortie').val(),
		nom_client : $('#nom_client').val(),
		tel_client : $('#tel_client').val(),
		client : $('#client').val(),
		date : $('#date').val(),
		lieu : $('#lieu').val(),
	};

	var url = Routing.generate('hebergement_reservation_save');

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
					show_success('Succès', 'Résevration enregistré');
				}
			});
	      
  	});
})