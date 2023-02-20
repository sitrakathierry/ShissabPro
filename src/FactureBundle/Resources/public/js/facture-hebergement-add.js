$(document).ready(function(){

    $(document).on('change','.f_heb_devise',function(event) {
        event.preventDefault();

        var montantprincipal = $(this).children('option:selected').data('montantprincipal');
        var montantconversion = $(this).children('option:selected').data('montantconversion');
        var total = $('#total_heb_resto').val();

        var montant_converti = (Number( total ) * Number( montantconversion )) / Number( montantprincipal );

        $(this).closest('tr').find('.f_heb_montant_converti').val( montant_converti.toFixed(2) );
    })

	$(document).on('change', '#booking', function(event) {
        var booking = $(this).val();

        var url = Routing.generate('facture_hebergement_booking', { booking : booking });

        $.ajax({
            url : url,
            type : 'GET',
            success : function(res) {
                $('#table-hebergement-add tbody').html(res.tpl);
                $('#table-emporter-add tbody').html(res.tplRestaurant);
                $('#f_client').val(res.client_id).change();
                $('#f_hebergement_remise_type').val(res.type_remise).change();
                $('#f_hebergement_remise').val(res.remise).change();
                $('#hebergement_remise').val(res.remise_montant);
                $('#hebergement_a_payer').val(res.hebergement_a_payer);
                $('#montant_total').val(res.total_resto);
                calculMontantHebergement();
            }
        })
    });


	$(document).on('change','.f_hebergement_date_entree', function(event) {
    	var date_entree = $(this).datepicker("getDate");
    	var date_sortie = $(this).closest('tr').find('.f_hebergement_date_sortie').val();

    	if (date_sortie != '') {
	    	var diff = date_diff(date_entree,date_sortie);
	    	var nb = diff.day;
	    	if (nb == 0) {
	    		nb = 1;
	    	}
	    	$(this).closest('tr').find('.f_hebergement_nb_nuit').val(nb);
    	}
	});

	$(document).on('change','.f_hebergement_date_sortie', function(event) {
    	var date_entree = $(this).closest('tr').find('.f_hebergement_date_entree').val();
    	var date_sortie = $(this).datepicker("getDate");

    	if (date_entree != '') {
	    	var diff = date_diff(date_entree,date_sortie);
	    	var nb = diff.day;
	    	if (nb == 0) {
	    		nb = 1;
	    	}
	    	$(this).closest('tr').find('.f_hebergement_nb_nuit').val(nb);
    	}
	});

	$(document).on('change','.f_hebergement_chambre', function(event) {
		event.preventDefault();

		var data = {
			id : $(this).children('option:selected').val(),
			nb_pers : $(this).closest('tr').find('.f_hebergement_nb_pers').val()
		};

		var url = Routing.generate('hebergement_chambre_tarif');

		var self = this;
            
        var nb = $(this).closest('tr').find('.f_hebergement_nb_nuit').val();

		$.ajax({
			url : url,
			type : 'POST',
			data: data,
			success: function(res) {
				var montant = Number(res.montant);
				var petit_dejeuner = res.petit_dejeuner;
				var montant_petit_dejeuner = Number(res.montant_petit_dejeuner);

				$(self).closest('tr').find('.f_hebergement_avec_petit_dejeuner')
					.val(petit_dejeuner)
					.attr('disabled','disabled')
					.change();

				if (petit_dejeuner == 1) {
					$(self).closest('tr').find('.f_hebergement_montant').val(montant * nb);
				} else {
					$(self).closest('tr').find('.f_hebergement_montant').val((montant + montant_petit_dejeuner) * nb);
				}

        		calculMontantHebergement();

			}
		})

	});

	$(document).on('click', '.btn-add-row-hebergement', function(event) {
        event.preventDefault();
        var id = $('#id-row-hebergement').val();
        var new_id = parseInt(id) + 1;
        var chambres = $('.f_hebergement_chambre').html();

        var a = '<td><div class="form-group"><div class="col-sm-12"><input type="text" class="form-control f_hebergement_nb_pers" name="f_hebergement_nb_pers[]" ></div></div></td>';
        var b = '<td><div class="form-group"><div class="col-sm-12"><input type="text" class="form-control f_hebergement_date_entree input-datepicker" name="f_hebergement_date_entree[]" ></div></div></td>';
        var c = '<td><div class="form-group"><div class="col-sm-12"><input type="text" class="form-control f_hebergement_date_sortie input-datepicker" name="f_hebergement_date_sortie[]" ></div></div></td>';
        var d = '<td><div class="form-group"><div class="col-sm-12"><input type="text" class="form-control f_hebergement_nb_nuit" name="f_hebergement_nb_nuit[]" readonly=""></div></div></td>';
        var e = '<td><div class="form-group"><div class="col-sm-12"><select class="form-control f_hebergement_chambre select2" name="f_hebergement_chambre[]"><option></option>'+ chambres +'</select></div></div></td>';
        var f = '<td><div class="form-group"><div class="col-sm-12"><select class="form-control f_hebergement_avec_petit_dejeuner" name="f_hebergement_avec_petit_dejeuner[]"><option value="1">INCLUS</option><option value="2">SUPPLÉMENTAIRE</option></select></div></div></td>';
        var g = '<td class="td-montant"><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control f_hebergement_montant" name="f_hebergement_montant[]" ></div></div></td><td></td>';

        var markup = '<tr class="row-'+ new_id +'">' + a + b + c + d + e + f + g + '</tr>';
        $("#table-hebergement-add tbody#principal-hebergement").append(markup);
        $('#id-row-hebergement').val(new_id);
        
    });

    $(document).on('click', '.btn-remove-row-hebergement', function(event) {
        event.preventDefault();
        var id     = parseInt($('#id-row-hebergement').val());


        var new_id = id - 1;
        if (new_id >= 0) {
            $('#id-row-hebergement').val(new_id);
            // $('tr.row-' + id).remove();

            $('.f_hebergement_designation').destroy();

            $('#table-hebergement-add tbody tr:last').remove();
        } else {
            show_info("Attention", 'Le tableau devrait contenir au moins une ligne','error');
        }

        $('.f_service_designation').summernote();
        
        calculMontantHebergement();
    });

	var montant =  Number( $('#hebergement_montant').val() ) ;
    var remise = 0;
    var total = 0;

    function calculMontantHebergement() {

        montant = 0;

        $('table#table-hebergement-add > tbody  > tr').each(function(index, tr) { 
           var montant_selector = $(this).children('td.td-montant').find('.f_hebergement_montant');

           var f_montant = montant_selector.val();

           montant += Number(f_montant);

           $('#hebergement_montant').val(montant);

           calculRemiseHebergement($('#f_hebergement_remise').val())

          
        });
    }

    $(document).on('input','#f_hebergement_remise',function(event) {
        var pourcentage = event.target.value;
        calculRemiseHebergement( Number(pourcentage) );
    });

    $(document).on('input','#f_hebergement_remise_type',function(event) {
        var pourcentage = $('#f_hebergement_remise').val();
        calculRemiseHebergement( Number(pourcentage) );
    });

    function calculRemiseHebergement(pourcentage) {

        var f_hebergement_remise_type = $('#f_hebergement_remise_type').val();

        if (f_hebergement_remise_type == 1) {
            remise = (montant * pourcentage) / 100;
        } else {
            remise = pourcentage;
        }

        $('#hebergement_remise').val(remise);
        $('#hebergement_a_payer').val(montant - remise);

        calculTotalHebergement();
    }

    function calculTotalHebergement() {

    	var montant_restaurant = Number( $('#montant_total').val() );
    	total = montant + montant_restaurant;
        var devise_lettre = $('#devise_lettre').val();

        total = total - remise;

    	$('#total_heb_resto').val(total);
        // $('#hebergement_total').val(total);
     	var letter = NumberToLetter(total) ;

        $('#hebergement_somme').html(letter + " " + devise_lettre);
        $('#id-somme-hebergement').val(letter + " " + devise_lettre);
        $('.f_heb_devise').trigger('change');
    }


	function date_diff(date_str1, date_str2){
      var date1 = create_date_by_string(date_str1);
      var date2 = create_date_by_string(date_str2);

      var diff = {}                           // Initialisation du retour
      var tmp = date2 - date1;
   
      tmp = Math.floor(tmp/1000);             // Nombre de secondes entre les 2 dates
      diff.sec = tmp % 60;                    // Extraction du nombre de secondes
   
      tmp = Math.floor((tmp-diff.sec)/60);    // Nombre de minutes (partie entière)
      diff.min = tmp % 60;                    // Extraction du nombre de minutes
   
      tmp = Math.floor((tmp-diff.min)/60);    // Nombre d'heures (entières)
      diff.hour = tmp % 24;                   // Extraction du nombre d'heures
       
      tmp = Math.floor((tmp-diff.hour)/24);   // Nombre de jours restants
      diff.day = tmp;
       
      return diff;
  	}

  	function create_date_by_string(str) {
	    if (typeof str == 'string') {
	      var day = str.substr(0, 2);
	      var month = str.substr(3, 2);
	      var year = str.substr(6, 4);
	      var date = new Date(year,Number(month) - 1,day);
	      return date;

	    } else {
	      return str;
	    }
  	}
});
