$(document).ready(function(){

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
        var b = '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control portion" name="portion[]" required=""></div></div></td>';
        var c = '<td></td>';

        var markup = '<tr data-id="'+ new_id +'">' + a + b + c + '</tr>';
        $("#table-entree-sortie-add tbody").append(markup);
        $('#id-row').val(new_id);

        $('.select2').select2();

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
    });


    $(document).on('click', '#btn-save', function(event) {
    	event.preventDefault();

    	var data = $('#form-entree-sortie').serializeArray();
        var id = $('#id-entree-sortie').val();
        if(id)
            data.push({name: "entree_sortie_id", value: id});

    	var url = Routing.generate('stock_interne_entree_sortie_save_sortie');

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