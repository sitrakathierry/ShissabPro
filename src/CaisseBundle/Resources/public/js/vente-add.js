$(document).ready(function(){

	$('.input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: 'dd/mm/yyyy',
        language: 'fr',

    });

	$('.select2').select2();    

    $(document).on('change', '.cl_produit', function(event) {
        event.preventDefault();
        var stock = $(this).find('option:selected').attr('data-stock');
        var prix = $(this).find('option:selected').attr('data-prix');
        var tr = $(this).closest('tr');
        $(tr).find('.cl_qte').val(stock);
        $(tr).find('.cl_prix').val(Number(prix));

        if (Number(stock)) {
            $(tr).find('.cl_qte').attr('max', stock);
            var total = Number( stock ) * Number( prix );
            $(tr).find('.cl_total').val( Number(total) );
        } else {
            var total = $(tr).find('.cl_prix').val();
        }

        calculTotal();
    });

	$(document).on('click', '.btn-add-row', function(event) {
        event.preventDefault();
        var id = $('#id-row').val();
        var new_id = parseInt(id) + 1;
        
        var produit_options = $('.cl_produit').html();

        var a = '<td><div class="form-group"><div class="col-sm-10"><select class="form-control select2 cl_produit" name="produit[]">'+ produit_options +'</select></div></div></td>';
        b = '<td><div class="form-group"><div class="col-sm-10"><input type="number" class="form-control cl_qte" name="qte[]" required=""></div></div></td>';
        c = '<td class="td-montant"><div class="form-group"><div class="col-sm-10"><input type="number" class="form-control cl_prix" name="prix[]" required=""></div></div></td>';
        d = '<td class="td-montant"><div class="form-group"><div class="col-sm-10"><input type="number" class="form-control cl_total" name="total[]" readonly=""></div></div></td><td></td>'

        var markup = '<tr data-id="'+ new_id +'">' + a + b + c + d + '</tr>';
        $("#table-commande-add tbody").append(markup);
        $('#id-row').val(new_id);

        $('.select2').select2();

        calculTotal();


    });

    $(document).on('click', '.btn-remove-row', function(event) {
        event.preventDefault();
        var id     = parseInt($('#id-row').val());
        var new_id = id - 1;
        if (new_id >= 0) {
            $('#id-row').val(new_id);
            $('#table-commande-add tbody tr:last').remove();
        } else {
            show_info("Attention", 'Le tableau devrait contenir au moins une ligne','error');
        }
        calculTotal();
    });

    $(document).on('input','.cl_qte',function (event) {
        var qte = event.target.value;
        var stock = $(this).attr('max');
        stock = parseInt(stock);
        qte = parseInt(qte);
        if(stock && qte){
            if(stock < qte) {
                // $('#btn-save').addClass('disabled');
                $(this).val('')
                return show_info("Quantité non valide", 'Pas assez de stock','warning');
            }
        } 
        $('#btn-save').removeClass('disabled');
        var prix_selector = $(this).closest('tr').find('.cl_prix');
        var total_selector = $(this).closest('tr').find('.cl_total');

        if (qte) {
        	var total = Number( prix_selector.val() ) * Number( qte );
        } else {
        	var total = prix_selector.val();
        }

        total_selector.val( total );
        calculTotal()
    });

    $(document).on('input','.cl_prix',function (event) {

        var qte_selector = $(this).closest('tr').find('.cl_qte');
        var prix = event.target.value;
        var total_selector = $(this).closest('tr').find('.cl_total');

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

        $('table#table-commande-add > tbody  > tr').each(function(index, tr) { 
           var montant_selector = $(this).find(".cl_total");

           var montant = montant_selector.val();

           montant_total += Number(montant);

           $('#montant_total').val(montant_total);
          
        });
    }

    $(document).on('click', '#btn-save', function(event) {
    	event.preventDefault();

    	var data = $('#form-commande').serializeArray();

    	var url = Routing.generate('caisse_vente_save');

    	$.ajax({
    		url: url,
    		type: 'POST',
    		data: data,
    		success: function(res) {
    			show_info('Succès', 'Approvisionnement éffectué');
    			location.reload();
    		}
    	})
    })

});