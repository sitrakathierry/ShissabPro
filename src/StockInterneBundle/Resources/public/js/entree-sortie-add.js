$(document).ready(function(){

    $(document).on('change','.stock_interne',function(event) {
        event.preventDefault();

        var libelle = $(this).children('option:selected').data('libelle');
        var unite = $(this).children('option:selected').data('unite');

        $(this).closest('tr').find('.libelle').val(libelle);
        $(this).closest('tr').find('.unite').val(unite);
    })

	$('.input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: 'dd/mm/yyyy',
        language: 'fr'
    });

    $('.expirer').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: 'dd/mm/yyyy',
        language: 'fr'
    });

	$('.select2').select2();

	$(document).on('click', '.btn-add-row', function(event) {
        event.preventDefault();
        var id = $('#id-row').val();
        var new_id = parseInt(id) + 1;
        
        var stock_interne_options = $('.stock_interne').html();

        var a = '<td><div class="form-group"><div class="col-sm-12"><select class="form-control select2 stock_interne" name="stock_interne[]"><option></option>'+ stock_interne_options +'</select></div></div></td>';
        var b = '<td><div class="form-group"><div class="col-sm-8"><input type="number" class="form-control qte" name="qte[]" required=""></div><div class="col-sm-4"><input type="text" class="form-control unite" readonly=""></div></div></td>';
        var c = '<td><div class="form-group"><div class="col-sm-8"><input type="number" class="form-control portion" name="portion[]" required=""></div><div class="col-sm-4"><input type="text" class="form-control libelle" readonly=""></div></div></td>';
        var d = '<td class="td-montant"><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control prix" name="prix[]" required=""></div></div></td>';
        var e = '<td class="td-montant"><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control total" name="total[]" readonly=""></div></div></td><td></td>';

        var markup = '<tr data-id="'+ new_id +'">' + a + b + c + d + e + '</tr>';
        $("#table-entree-sortie-add tbody").append(markup);
        $('#id-row').val(new_id);

        $('.select2').select2();

        $('.expirer').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            format: 'dd/mm/yyyy',
            language: 'fr'
        });
        calculTotal();


    });

    $(document).on('click', '.btn-remove-row', function(event) {
        event.preventDefault();
        var id     = parseInt($('#id-row').val());
        var new_id = id - 1;
        if (new_id >= 0) {
            $('#id-row').val(new_id);
            $('#table-entree-sortie-add tbody tr:last').remove();
        } else {
            show_info("Attention", 'Le tableau devrait contenir au moins une ligne','error');
        }
        calculTotal();
    });

    $(document).on('input','.qte',function (event) {

        var qte = event.target.value;
        var prix_selector = $(this).closest('tr').find('.prix');
        var total_selector = $(this).closest('tr').find('.total');

        if (qte) {
        	var total = Number( prix_selector.val() ) * Number( qte );
        } else {
        	var total = prix_selector.val();
        }

        total_selector.val( total );
        calculTotal()
    });

    $(document).on('input','.prix',function (event) {

        var qte_selector = $(this).closest('tr').find('.qte');
        var prix = event.target.value;
        var total_selector = $(this).closest('tr').find('.total');

        if ( qte_selector.val() ) {
        	var total = Number( qte_selector.val() ) * Number( prix );
        } else {
        	var total = prix;
        }

        total_selector.val( total );
        calculTotal()
    });

    var montant_total = 0;


    function calculTotal() {

        montant_total = 0;

        $('table#table-entree-sortie-add > tbody  > tr').each(function(index, tr) { 
           var montant_selector = $(this).find(".total");

           var montant = montant_selector.val();

           montant_total += Number(montant);

           $('#montant_total').val(montant_total);
          
        });
    }

    $(document).on('click', '#btn-save', function(event) {
    	event.preventDefault();

    	var data = $('#form-entree-sortie').serializeArray();
        var id = $('#id-entree-sortie').val();
        if(id)
            data.push({name: "entree_sortie_id", value: id});

    	var url = Routing.generate('stock_interne_entree_sortie_save');

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
            			show_success('Succès', 'Approvisionnement éffectué');
            		}
            	})
              
        });

    })

})