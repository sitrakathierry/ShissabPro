$(document).ready(function(){

	$('#data_1 .input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: 'dd/mm/yyyy',
        language: 'fr',

    });

	$(document).on('change','.portion_accompagnement',function(event) {
		event.preventDefault();

		var portion = Number( $(this).children('option:selected').val() );

		$(this).closest('tr').find('.qte_accompagnement').val(portion).trigger('input');
	});

	$(document).on('input','.qte_accompagnement',function(event) {

		var qte_plat = $(this).closest('table .table-accompagnement').parent('td').closest('tr').find('.qte').val();

		var accompagnement_supp = $(this).closest('table .table-accompagnement').parent('td').find('.accompagnement_supp').val();

		var qte = Number(event.target.value);

		var tr = $(this).closest('table .table-accompagnement').find('tbody > tr');
		var total_qte = 0;
		tr.each(function (index,tr) {
           var qte_accompagnement = $(this).find(".qte_accompagnement").val();

           total_qte += Number( qte_accompagnement );
		});

		var prix = $(this).closest('tr').find('.accompagnement').children('option:selected').data('prix');
		var qte_a_payer = 0;

		if (total_qte <= qte_plat) {
			prix = 0;
			$(this).closest('table .table-accompagnement').parent('td').find('.accompagnement_supp').val('0');
		} else {

			if (accompagnement_supp == 0) {
				qte_a_payer = total_qte - qte_plat;
			} else {
				qte_a_payer = qte;
			}

			$(this).closest('table .table-accompagnement').parent('td').find('.accompagnement_supp').val('1');
		}


		$(this).closest('tr').find('.prix_accompagnement').val(prix * qte_a_payer);

		calculTotalAccompagnement.call( this );
		
	});

	function calculTotalAccompagnement() {

        var total_accompagnement = 0;

		var tr = $(this).closest('table .table-accompagnement').find('tbody > tr');

        tr.each(function(index, tr) { 
           var montant_selector = $(this).find(".prix_accompagnement");

           var montant = montant_selector.val();

           total_accompagnement += Number(montant);
          
        });

        console.log(total_accompagnement)

       	$(this).closest('table .table-accompagnement').find('tfoot > tr').find('.total_accompagnement').val(total_accompagnement);


		var qte_plat = $(this).closest('table .table-accompagnement').parent('td').closest('tr').find('.qte').val();
		var prix_plat = $(this).closest('table .table-accompagnement').parent('td').closest('tr').find('.prix').val();

		var total = ( Number(qte_plat) * Number(prix_plat) ) + total_accompagnement;

		$(this).closest('table .table-accompagnement').parent('td').closest('tr').find('.total').val(total);

		calculTotal();


    }

	$(document).on('click', '.btn-add-row-accompagnement', function(event) {
        event.preventDefault();

        var accompagnement_options = $('.accompagnement').html();

       	var a = '<td><div class="form-group"><div class="col-sm-12"><select class="form-control select2 accompagnement" name="accompagnement[]"><option></option>'+ accompagnement_options +'</select></div></div></td>';
       	var b = '<td><div class="form-group"><div class="col-sm-12"><select class="option form-control portion_accompagnement"><option value="0"></option><option value="1">1 portion</option><option value="0.5">1/2 portion</option></select><input type="number" class="hidden qte_accompagnement" name="qte_accompagnement[]" placeholder="Portion"></div></div></td>';
       	var c = '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control prix_accompagnement" name="prix_accompagnement[]" placeholder="Prix"></div></div></td>';
       	var d = '<td><button class="btn btn-white btn-full-width btn-remove-tr-accompagnement"><i class="fa fa-trash"></i></button></td>';


        var markup = '<tr">' + a + b + c + d + '</tr>';

        $(this).closest('table .table-accompagnement').find('tbody').append(markup);

        $('.select2').select2();

    });

    $(document).on('click','.btn-remove-tr-accompagnement',function(event) {
		event.preventDefault();

		var prev = $(this).closest('tr').prev();
		
		$(this).closest('tr').remove();
		
		calculTotalAccompagnement.call(prev);

		calculTotal();
	});

	function accompagnement_details() {
		var data = [];

		$('table#table-reservation-add > tbody  > tr').each(function(index, tr) { 
           
           var table = $(this).find('table.table-accompagnement  > tbody');
           var accompagnement = selToArray(table.find('.accompagnement'));
           var qte_accompagnement = selToArray(table.find('.qte_accompagnement'));
           var prix_accompagnement = selToArray(table.find('.prix_accompagnement'));
           var total_accompagnement = $(this).find('.total_accompagnement').val();

           var item = {
           		accompagnement : accompagnement,
				qte_accompagnement : qte_accompagnement,
				prix_accompagnement : prix_accompagnement,
				total_accompagnement : total_accompagnement,
           };

           data.push(item);

        });

        return data;
	}

	$(document).on('click','#btn-client-heb',function(event) {
		event.preventDefault();

		$('.heb').removeClass('hidden');
	})

	$(document).on('click','.btn-remove-tr',function(event) {
		event.preventDefault();
		$(this).closest('tr').remove();
		calculTotal();
	});

	$(document).on('click','#btn-save',function (event) {
		event.preventDefault();

		var data = {
			date : $('#date').val(),
			booking : $('#booking').val(),
			nb_place : $('#nb_place').val(),
			selected_tables : get_selected_tables(),
			montant_total : $('#montant_total').val(),
			statut: 1,
			tables : toArray('tables'),
			plat : toArray('plat'),
			qte : toArray('qte'),
			prix : toArray('prix'),
			total : toArray('total'),
			cuisson : toArray('cuisson'),
			statut_detail : toArray('statut_detail'),
			accompagnement_details : accompagnement_details()

		};

		var url = Routing.generate('restaurant_reservation_save');

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
						show_success('Succès','Commande enregistré');
					}
				})
		      
		  });

	});

	function get_selected_tables() {
		var selected = [];

		$('.table_selected').each(function() {
			var checked = $(this).is(":checked");

			if (checked == true) {
				var id = $(this).data('id');
				var assis = $(this).data('assis');
				selected.push({
					id : id,
					assis : assis
				});
			}

		});

		return selected;
	}

	$('.select2').select2();

	let timeout;

	verification_place_disponible();

	$(document).on('input', '#nb_place', function(event) {
	    verification_nb_place( Number( event.target.value ) ) 
	});

	$(document).on('click','#btn-list-table',function(event) {
		event.preventDefault();

		var nb_place = $('#nb_place').val();

		if (nb_place == '' || nb_place == 0) {
			show_info('Attention','Nb place devrai être supérieure à 0','warning');
			return;
		}

		var url = Routing.generate('restaurant_table_disponible');

		$.ajax({
			url: url,
			type: 'GET',
			success: function(res) {
				$('#list_table').html(res);
				$('#nb_place').prop('disabled',true);
				$('#table-reservation').removeClass('hidden');
				$('#btn-save').removeClass('hidden');
			}
		})
	});

	$(document).on('change','.table_selected',function(event) {
		event.preventDefault();
		var checked = $(this).is(":checked");
		var id = $(this).data('id');
		var libre = Number($(this).data('libre'));
		var nb_a_placer = Number($('#nb_a_placer').val());
		var nb_assis = Number($('#nb_assis').val());
		var nb_non_place = Number($('#nb_non_place').val());

		if (checked == true) {
			if (nb_non_place > libre) {
				nb_assis = nb_assis + libre;
				$(this).data('assis', libre);
				$(this).closest('div.item-table').find('.assis').html(libre);
				nb_non_place = nb_a_placer - nb_assis;
			} else {
				nb_assis = nb_assis + nb_non_place;
				$(this).data('assis', nb_non_place);
				$(this).closest('div.item-table').find('.assis').html(nb_non_place);
				nb_non_place = nb_a_placer - nb_assis;
			}
		} else {
			var assis = Number($(this).data('assis'));

			nb_non_place = nb_non_place + assis;
			nb_assis = nb_assis - assis;

			$(this).closest('div.item-table').find('.assis').html('');

		}

		add_selected_table();

		$('#nb_assis').val(nb_assis);
		$('#nb_non_place').val(nb_non_place);
	});

	$(document).on('change','.plat',function(event) {
		event.preventDefault();

		var _tr = $(this).closest('tr');
		var prixvente = $(this).children('option:selected').data('prixvente');
		var qte = $(_tr).find('.qte').val();
		
		$(_tr).find('.prix').val(prixvente);

        var total = Number( prixvente ) * Number( qte );

        $(_tr).find('.total').val( Number(total) );

        calculTotal();


	});

	$(document).on('input','.qte',function(event) {

		var _tr = $(this).closest('tr');
		var prixvente = $(_tr).find('.prix').val();
		var qte = event.target.value;

        var total = Number( prixvente ) * Number( qte );

        $(_tr).find('.total').val( Number(total) );

        calculTotal();


	});

	$(document).on('input','.prix',function(event) {

		var _tr = $(this).closest('tr');
		var prixvente = event.target.value;
		var qte = $(_tr).find('.qte').val();

        var total = Number( prixvente ) * Number( qte );

        $(_tr).find('.total').val( Number(total) );

        calculTotal();

	});

	$(document).on('click', '.btn-add-row', function(event) {
        event.preventDefault();

        var id = $('#id-row').val();

        var new_id = Number(id) + 1;
        
        var tables_options = $('.tables').html();
        var plat_options = $('.plat').html();
        var cuisson_options = $('.cuisson').html();
        var accompagnement_options = $('.accompagnement').html();

        var a = '<td><div class="form-group"><div class="col-sm-12"><select class="form-control select2 tables" name="tables[]" multiple="">'+ tables_options +'</select></div></div></td>';
        var b = '<td><div class="form-group"><div class="col-sm-12"><select class="form-control select2 plat" name="plat[]">'+ plat_options +'</select></div></div></td>';
        var c = '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control qte" name="qte[]" ></div></div></td>';
        var d = '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control prix" name="prix[]" ></div></div></td>';
        var e = '<td><div class="form-group"><div class="col-sm-12"><select class="form-control cuisson" name="cuisson[]"><option></option>' + cuisson_options + '</select></div></div></td>';
        var f = '<td><input type="hidden" class="accompagnement_supp" value="0"><table class="table table-bordered table-accompagnement"><tbody><tr><td><div class="form-group"><div class="col-sm-12"><select class="form-control select2 accompagnement" name="accompagnement[]"><option></option>'+ accompagnement_options +'</select></div></div></td><td><div class="form-group"><div class="col-sm-12"><select class="option form-control portion_accompagnement"><option value="0"></option><option value="1">1 portion</option><option value="0.5">1/2 portion</option></select><input type="number" class="hidden qte_accompagnement" name="qte_accompagnement[]" placeholder="Portion"></div></div></td><td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control prix_accompagnement" name="prix_accompagnement[]" placeholder="Prix"></div></div></td><td><button class="btn btn-white btn-full-width btn-remove-tr-accompagnement"><i class="fa fa-trash"></i></button></td></tr></tbody><tfoot><tr><td colspan="2"></td><td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control total_accompagnement" name="total_accompagnement[]" placeholder="Total" readonly=""></div></div></td><td><button class="btn btn-white btn-full-width btn-add-row-accompagnement" data-id="0"><i class="fa fa-plus"></i></button></td></tr></tfoot></table></td>';
        var g = '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control total" name="total[]" ></div></div></td>';
        var h = '<td><button class="btn btn-danger btn-full-width btn-remove-tr"><i class="fa fa-trash"></i></button><input type="hidden" class="statut_detail" value="1"></td>';


        var markup = '<tr data-id="'+ new_id +'">' + a + b + c + d + e + f + g + h + '</tr>';
        $("#table-reservation-add tbody#principal").append(markup);
        $('#id-row').val(new_id);

        $('.select2').select2();

        calculTotal();


    });

    $(document).on('click', '.btn-remove-row', function(event) {
        event.preventDefault();
        var produits = [];
        var isFind = false;

        var id     = parseInt($('#id-row').val());
        var new_id = id - 1;
        if (new_id >= 0) {
            $('#id-row').val(new_id);
            $('#table-reservation-add tbody tr:last').remove();
        } else {
            show_info("Attention", 'Le tableau devrait contenir au moins une ligne','error');
        }
        calculTotal();

    });

    var montant_total = 0;


    function calculTotal() {

        montant_total = 0;

        $('table#table-reservation-add > tbody  > tr').each(function(index, tr) { 
           var montant_selector = $(this).find(".total");

           var montant = montant_selector.val();

           montant_total += Number(montant);

           $('#montant_total').val(montant_total);
          
        });
    }

	function add_selected_table() {
		var options = '<option></option>';
		$('.table_selected').each(function() {
			var checked = $(this).is(":checked");

			if (checked == true) {
				var nom = $(this).data('nom');
				var id = $(this).data('id');

				options += '<option value="'+ id +'">'+ nom +'</option>';
			}

		});

		$('.tables').html(options);
	}

	function verification_place_disponible() {
		var url = Routing.generate('restaurant_table_placedisponible');

		$.ajax({
			url: url,
			type: 'GET',
			success: function(places) {

				$('#place_disponible').val(places);
				$('#place_disponible_txt').html(places + ' places disponibles');

				if (places > 0) {
					$('#form-reservation').removeClass('hidden');
					$('#reservation-plein').addClass('hidden');
				} else {
					$('#form-reservation').addClass('hidden');
					$('#reservation-plein').removeClass('hidden');
				}
			}
		})
	}

	function verification_nb_place(place) {

		var place_disponible = Number( $('#place_disponible').val() );

		if (place > place_disponible) {
			$('#nb_place').val('');
			show_info('Erreur',"Il n'y a plus assez de place pour " + place , "error");
			return;
		} else {
			$('#nb_a_placer').val(place);
			$('#nb_non_place').val(place);
			$('#nb_assis').val('');
		}

	}

	function toArray(selector, type = 'default') {
        var taskArray = new Array();
        $("." + selector).each(function() {

            if (type == 'summernote') {
                taskArray.push($(this).code());
            } else {
                taskArray.push($(this).val());
            }

        });
        return taskArray;
    }

    function selToArray(selector, type = 'default') {
        var taskArray = new Array();
        $(selector).each(function() {

            if (type == 'summernote') {
                taskArray.push($(this).code());
            } else {
                taskArray.push($(this).val());
            }

        });
        return taskArray;
    }
});