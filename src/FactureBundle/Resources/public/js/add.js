$(document).ready(function(){

    $('#descr').summernote();

    $('#f_client').select2();
    
    $('#data_1 .input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: 'dd/mm/yyyy',
        language: 'fr',

    });

    $("#data_1 .input-group.date").datepicker('setDate', new Date());

    $(document).on('click', '.btn-add-row', function(event) {
        event.preventDefault();
        var id = $('#id-row').val();
        var new_id = parseInt(id) + 1;
        var a = '<td><div class="form-group"><div class="col-sm-10"><input type="text" class="form-control" name="f_designation[]" required=""></div></div></td>';
        var b = '<td><div class="form-group"><div class="col-sm-10"><input type="number" class="form-control f_qte" name="f_qte[]"></div></div></td>';
        var c = '<td><div class="form-group"><div class="col-sm-10"><input type="number" class="form-control f_prix" name="f_prix[]"></div></div></td>';
        var d = '<td class="td-montant"><div class="form-group"><div class="col-sm-10"><input type="number" class="form-control f_montant" name="f_montant[]"></div></div></td>';
        var e = '<td></td>';
        var markup = '<tr class="row-'+ new_id +'">' + a + b + c + d + e + '</tr>';
        $("#table-fact-add tbody").append(markup);
        $('#id-row').val(new_id);

        $('#table-fact-add tbody tr:last').find('.f_prix').val()
        
    });

    $(document).on('click', '.btn-remove-row', function(event) {
        event.preventDefault();
        var id     = parseInt($('#id-row').val());


        var new_id = id - 1;
        if (new_id >= 0) {
            $('#id-row').val(new_id);
            // $('tr.row-' + id).remove();
            $('#table-fact-add tbody tr:last').remove();
        } else {
            show_info("Attention", 'Le tableau devrait contenir au moins une ligne','error');
        }
        calculMontant();
    });

    

    $(document).on('input','.f_prix',function (event) {

        var prix = Number( event.target.value );
        var qte = Number( $(this).closest('tr').find('.f_qte').val() );

        var total = prix;

        if (qte) {
            total = qte * prix
        }

        var montant_selector = $(this).closest('tr').find('.f_montant');

        montant_selector.val(total);

        calculMontant()


    })

    $(document).on('input','.f_qte',function (event) {

        var qte = Number( event.target.value );
        var prix = Number( $(this).closest('tr').find('.f_prix').val() );

        var total = prix;

        if (qte) {
            total = qte * prix
        }

        var montant_selector = $(this).closest('tr').find('.f_montant');

        montant_selector.val(total);

        calculMontant()


    })

    var montant = 0;
    var remise = 0;
    var total = 0;

    function calculMontant() {

        montant = 0;

        $('table#table-fact-add > tbody  > tr').each(function(index, tr) { 
           var montant_selector = $(this).find(".f_montant");

           var f_montant = montant_selector.val();

           montant += Number(f_montant);

           $('#montant').val(montant);

           calculRemise($('#f_remise').val())

          
        });
    }

    function calculRemise(pourcentage) {
        remise = (montant * pourcentage) / 100;

        $('#remise').val(remise);

        calculTotal();
    }

    $(document).on('input','#f_remise',function (event) {
        var value = event.target.value;
        calculRemise(value)
    })

    function calculTotal() {
        total = montant - remise;
        $('#total').val(total);

         var letter = NumberToLetter(total) ;
        $('#somme').html(letter + " francs comorien");
        $('#id-somme').val(letter + " francs comorien");
    }

})
