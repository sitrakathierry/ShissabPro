$(document).ready(function(){

    $('.f_service_designation').summernote();

    $(document).on('change','.f_service_libre',function(event) {
        var libre = $(this).children("option:selected").val();

        if (libre == 1) {
            $(this).closest('tr').find('.f_service').addClass('hidden');
            $(this).closest('tr').find('.f_service_designation_container').removeClass('hidden');
            
            $('.f_service_designation').summernote();
        } else {
            $(this).closest('tr').find('.f_service').removeClass('hidden');
            $(this).closest('tr').find('.f_service_designation_container').addClass('hidden');
        }
    })


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

    $(document).on('click', '.btn-add-row-service', function(event) {
        event.preventDefault();
        var id = $('#id-row-service').val();
        var new_id = parseInt(id) + 1;
        var services = $('.f_service').html();
        var durees = $('.f_service_duree').html();

        var a ='<td><div class="form-group"><div class="col-sm-10"><select class="form-control f_service_libre" name="f_service_libre[]"><option value="0">SERVICE</option><option value="1">AUTRE</option></select></div></div></td>';
        var b = '<td><div class="form-group"><div class="col-sm-10"><select class="form-control f_service" name="f_service[]">'+ services +'</select><div class="f_service_designation_container hidden"><textarea class="f_service_designation" name="f_service_designation[]"></textarea></div></div></div></td>';
        var c = '<td><div class="form-group"><div class="col-sm-10"><input type="number" class="form-control f_service_periode" name="f_service_periode[]"></div></div></td>';
        var d = '<td><div class="form-group"><div class="col-sm-10"><select class="form-control f_service_duree" name="f_service_duree[]">'+ durees +'</select></div></div></td>';
        var e = '<td><div class="form-group"><div class="col-sm-10"><input type="number" class="form-control f_service_prix" name="f_service_prix[]"></div></div></td>';
        var f = '<td class="td-montant"><div class="form-group"><div class="col-sm-10"><input type="number" class="form-control f_service_montant" name="f_service_montant[]"></div></div></td>';
        var g = '<td></td>';
        var markup = '<tr class="row-'+ new_id +'">' + a + b + c + d + e + f + g + '</tr>';
        $("#table-service-add tbody#principal-service").append(markup);
        $('#id-row-service').val(new_id);

        $('#table-service-add tbody tr:last').find('.f_prix').val()
        
    });

    $(document).on('click', '.btn-remove-row-service', function(event) {
        event.preventDefault();
        var id     = parseInt($('#id-row-service').val());


        var new_id = id - 1;
        if (new_id >= 0) {
            $('#id-row-service').val(new_id);
            // $('tr.row-' + id).remove();

            $('.f_service_designation').destroy();

            $('#table-service-add tbody tr:last').remove();
        } else {
            show_info("Attention", 'Le tableau devrait contenir au moins une ligne','error');
        }

        $('.f_service_designation').summernote();
        
        calculMontantService();
    });

    

    $(document).on('input','.f_service_prix',function (event) {

        var prix = Number( event.target.value );
        var qte = Number( $(this).closest('tr').find('.f_service_periode').val() );

        var total = prix;

        if (qte) {
            total = qte * prix
        }

        var montant_selector = $(this).closest('tr').find('.f_service_montant');

        montant_selector.val(total);

        calculMontantService()


    })

    $(document).on('input','.f_service_periode',function (event) {

        var qte = Number( event.target.value );
        var prix = Number( $(this).closest('tr').find('.f_service_prix').val() );

        var total = prix;

        if (qte) {
            total = qte * prix
        }

        var montant_selector = $(this).closest('tr').find('.f_service_montant');

        montant_selector.val(total);

        calculMontantService()


    })

    var montant = 0;
    var remise = 0;
    var total = 0;

    function calculMontantService() {

        montant = 0;

        $('table#table-service-add > tbody  > tr').each(function(index, tr) { 
           // var montant_selector = $(this).find(".f_service_montant");
           var montant_selector = $(this).children('td.td-montant').find('.f_service_montant');

           var f_montant = montant_selector.val();

           montant += Number(f_montant);

           $('#service_montant').val(montant);

           calculRemiseService($('#f_service_remise').val())

          
        });
    }

    function calculRemiseService(pourcentage) {
        remise = (montant * pourcentage) / 100;

        $('#service_remise').val(remise);

        calculTotalService();
    }

    $(document).on('input','#f_service_remise',function (event) {
        var value = event.target.value;
        calculRemiseService(value)
    })

    function calculTotalService() {
        total = montant - remise;
        $('#service_total').val(total);

         var letter = NumberToLetter(total) ;
        $('#service_somme').html(letter + " francs comorien");
        $('#id-somme-service').val(letter + " francs comorien");
    }

})
