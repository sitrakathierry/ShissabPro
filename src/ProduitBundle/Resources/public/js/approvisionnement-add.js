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

	$(document).on('click', '.btn-add-row', function(event) {
        event.preventDefault();
        var id = $('#id-row').val();
        var new_id = parseInt(id) + 1;
        
        var produit_options = $('.produit').html();

        var a = '<td><div class="form-group"><div class="col-sm-10"><select class="form-control select2 produit" name="produit[]">'+ produit_options +'</select></div></div></td>';
        b = '<td><div class="form-group"><div class="col-sm-10"><input type="number" class="form-control qte" name="qte[]" required=""></div></div></td>';
        c = '<td class="td-montant"><div class="form-group"><div class="col-sm-10"><input type="number" class="form-control prix" name="prix[]" required=""></div></div></td>';
        d = '<td class="td-montant"><div class="form-group"><div class="col-sm-10"><input type="number" class="form-control total" name="total[]" readonly=""></div></div></td><td></td>'

        var markup = '<tr data-id="'+ new_id +'">' + a + b + c + d + '</tr>';
        $("#table-appro-add tbody").append(markup);
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
            $('#table-appro-add tbody tr:last').remove();
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

        $('table#table-appro-add > tbody  > tr').each(function(index, tr) { 
           var montant_selector = $(this).find(".total");

           var montant = montant_selector.val();

           montant_total += Number(montant);

           $('#montant_total').val(montant_total);
          
        });
    }

    $(document).on('click', '#btn-save', function(event) {
    	event.preventDefault();

    	var data = $('#form-appro').serializeArray();
        var id = $('#id-appro').val();
        if(id)
            data.push({name: "appro_id", value: id});

    	var url = Routing.generate('produit_approvisionnement_save');

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

})