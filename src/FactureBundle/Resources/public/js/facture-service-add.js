$(document).ready(function(){

    $(document).on('change','.f_service',function(event) {
        event.preventDefault();

        $(this).closest('tr').find('.f_service_duree').trigger('change');
    })


    $(document).on('change','.f_service_duree',function(event) {
        event.preventDefault();

        var tr = $(this).closest('tr');
        var id = tr.find('.f_service').children('option:selected').val();
        var duree = $(this).children('option:selected').val();

        var data = {
            id : id,
            duree : duree
        };

        var url = Routing.generate('facture_service_prix');

        $.ajax({
            url : url,
            type : 'POST',
            data : data,
            success: function(res) {
                if (res.prix) {
                    tr.find('.f_service_prix').val(res.prix).trigger('input');
                } else {
                    tr.find('.f_service_prix').val('');
                }
            }
        })
    })


    $(document).on('change','.f_service_devise',function(event) {
        event.preventDefault();

        var montantprincipal = $(this).children('option:selected').data('montantprincipal');
        var montantconversion = $(this).children('option:selected').data('montantconversion');
        var total = $('#service_total').val();

        var montant_converti = (Number( total ) * Number( montantconversion )) / Number( montantprincipal );

        $(this).closest('tr').find('.f_service_montant_converti').val( montant_converti.toFixed(2) );
    })

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

    // $('#f_client').select2();
    
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

    $('.select2').select2();

    $(document).on('click', '.btn-add-row-service', function(event) {
        event.preventDefault();
        var id = $('#id-row-service').val();
        var new_id = parseInt(id) + 1;
        var services = $('.f_service').html();
        var durees = $('.f_service_duree').html();

        var a ='<td><div class="form-group"><div class="col-sm-12"><select class="form-control f_service_libre" name="f_service_libre[]"><option value="0">PRESTATION</option><option value="1">AUTRE</option></select></div></div></td>';
        var b = '<td><div class="form-group"><div class="col-sm-12"><select class="form-control select2 f_service" name="f_service[]">'+ services +'</select><div class="f_service_designation_container hidden"><textarea class="f_service_designation" name="f_service_designation[]"></textarea></div></div></div></td>';
        var c = '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control f_service_periode" name="f_service_periode[]"></div></div></td>';
        var d = '<td><div class="form-group"><div class="col-sm-12"><select class="form-control f_service_duree" name="f_service_duree[]">'+ durees +'</select></div></div></td>';
        var e = '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control f_service_prix" name="f_service_prix[]"></div></div></td>';
        var f = '<td><div class="form-group"><div class="col-sm-4"><select class="form-control f_service_remise_type_ligne" name="f_service_remise_type_ligne[]"><option value="0">%</option><option value="1">Montant</option></select></div><div class="col-sm-8"><input type="number" class="form-control f_service_remise_ligne" name="f_service_remise_ligne[]" ></div></div></td>';
        var g = '<td class="td-montant"><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control f_service_montant" name="f_service_montant[]"></div></div></td>';
        var h = '<td></td>';
        var markup = '<tr class="fact-row row-'+ new_id +'">' + a + b + c + d + e + f + g + h + '</tr>';
        $("#table-service-add tbody#principal-service").append(markup);
        $('#id-row-service').val(new_id);

        $('.fact-row row-'+new_id).find(".select2").select2("destroy");
        $("select.select2").select2();

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

    $(document).on('change','.f_service_remise_type_ligne',function (event) {

        var prix = Number( $(this).closest('tr').find('.f_service_prix').val() );
        var qte = Number( $(this).closest('tr').find('.f_service_periode').val() );

        var f_service_remise_type_ligne = $(this).children("option:selected").val();
        var f_service_remise_ligne = $(this).closest('tr').find('.f_service_remise_ligne').val();

        var total = prix;

        if (qte) {
            total = qte * prix
        }

        if (f_service_remise_type_ligne == '1') {
            remise_ligne_montant = Number( f_service_remise_ligne );
        } else {
            remise_ligne_montant = (total * Number( f_service_remise_ligne )) / 100;
        }

        total = total - remise_ligne_montant;

        var montant_selector = $(this).closest('tr').find('.f_service_montant');

        montant_selector.val(total);

        calculMontantService()

    });

    $(document).on('input','.f_service_remise_ligne',function (event) {

        var prix = Number( $(this).closest('tr').find('.f_service_prix').val() );
        var qte = Number( $(this).closest('tr').find('.f_service_periode').val() );

        var f_service_remise_type_ligne = $(this).closest('tr').find('.f_service_remise_type_ligne').val();
        var f_service_remise_ligne = event.target.value;

        var total = prix;

        if (qte) {
            total = qte * prix
        }

        if (f_service_remise_type_ligne == '1') {
            remise_ligne_montant = Number( f_service_remise_ligne );
        } else {
            remise_ligne_montant = (total * Number( f_service_remise_ligne )) / 100;
        }

        total = total - remise_ligne_montant;

        var montant_selector = $(this).closest('tr').find('.f_service_montant');

        montant_selector.val(total);

        calculMontantService()

    });

    

    $(document).on('input','.f_service_prix',function (event) {

        var prix = Number( event.target.value );
        var qte = Number( $(this).closest('tr').find('.f_service_periode').val() );

        var f_service_remise_type_ligne = $(this).closest('tr').find('.f_service_remise_type_ligne').val();
        var f_service_remise_ligne = $(this).closest('tr').find('.f_service_remise_ligne').val();

        var total = prix;

        if (qte) {
            total = qte * prix
        }

        if (f_service_remise_type_ligne == '1') {
            remise_ligne_montant = Number( f_service_remise_ligne );
        } else {
            remise_ligne_montant = (total * Number( f_service_remise_ligne )) / 100;
        }

        total = total - remise_ligne_montant;

        var montant_selector = $(this).closest('tr').find('.f_service_montant');

        montant_selector.val(total);

        calculMontantService()

    });

    $(document).on('input','.f_service_periode',function (event) {

        var qte = Number( event.target.value );
        var prix = Number( $(this).closest('tr').find('.f_service_prix').val() );

        var f_service_remise_type_ligne = $(this).closest('tr').find('.f_service_remise_type_ligne').val();
        var f_service_remise_ligne = $(this).closest('tr').find('.f_service_remise_ligne').val();


        var total = prix;

        if (qte) {
            total = qte * prix
        }

        var remise_ligne_montant = 0;

        if (f_service_remise_type_ligne == '1') {
            remise_ligne_montant = Number( f_service_remise_ligne );
        } else {
            remise_ligne_montant = (total * Number( f_service_remise_ligne )) / 100;
        }

        total = total - remise_ligne_montant;

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

        var f_service_remise_type = $('#f_service_remise_type').val();

        if (f_service_remise_type == 0) {
            remise = (montant * pourcentage) / 100;
        } else {
            remise = pourcentage;
        }

        $('#service_remise').val(remise);

        calculTotalService();
    }

    $(document).on('input','#f_service_remise',function (event) {
        var value = event.target.value;
        calculRemiseService(value)
    });

    $(document).on('change','#f_service_remise_type',function(event) {
        event.preventDefault();

        calculRemiseService( Number( $('#f_service_remise').val() ) );

    });

    function calculTotalService() {
        total = montant - remise;
        var letter = NumberToLetter(total) ;
        var devise_lettre = $('#devise_lettre').val();

        $('#service_total').val(total);
        $('#service_somme').html(letter + " " + devise_lettre);
        $('#id-somme-service').val(letter + " " + devise_lettre);
        $('.f_service_devise').trigger('change')
    }

})
