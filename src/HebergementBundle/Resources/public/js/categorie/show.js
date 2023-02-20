var cl_row_edited = 'r-cl-edited';

$(document).ready(function(){

    $(document).on('change','.petit_dejeuner',function(event) {
        event.preventDefault();

        var petit_dejeuner = $(this).children('option:selected').val();

        if (petit_dejeuner == 2) {
            $(this).closest('tr').find('.supplementaire').removeClass('hidden');
        } else {
            $(this).closest('tr').find('.supplementaire').addClass('hidden');
        }
    });

    $(document).on('click', '.btn-add-row', function(event) {
        event.preventDefault();

        var id = $('#id-row').val();

        var new_id = Number(id) + 1;

        var a = '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control nb_pers" name="nb_pers[]" required=""></div></div></td>';
        var b = '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control montant" name="montant[]" required=""></div></div></td>';
        var c = '<td><div class="form-group"><div class="col-sm-12"><select class="form-control petit_dejeuner" name="petit_dejeuner[]"><option value="1" selected="">INCLUS</option><option value="2">SUPPLÉMENTAIRE</option></select></div></div><div class="form-group supplementaire hidden"><div class="col-sm-12"><input type="number" class="form-control montant_petit_dejeuner" name="montant_petit_dejeuner[]" required=""></div></div></td>';
        var d = '<td><button class="btn btn-danger btn-full-width btn-remove-tr"><i class="fa fa-trash"></i></button></td>';
        
        var markup = '<tr data-id="'+ new_id +'">' + a + b + c + d + '</tr>';
        $("#table-tarif-add tbody").append(markup);
        $('#id-row').val(new_id);

    });

    $(document).on('click','.btn-remove-tr',function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
    });

    $('.summernote').summernote();

	$(document).on('click','#btn-save',function(event) {
		event.preventDefault();

		var data = {
			id : $('#id').val(),
			nom : $('#nom').val(),
            type : $('#type').val(),
            caracteristiques : $('#caracteristiques').val(),
            description : $('#description').code(),
            nb_pers : toArray('nb_pers'),
            montant : toArray('montant'),
            petit_dejeuner : toArray('petit_dejeuner'),
            montant_petit_dejeuner : toArray('montant_petit_dejeuner')
		};
		var url = Routing.generate('hebergement_categorie_save');
		$.ajax({
			url: url,
			data: data,
			type: 'POST',
			success: function(res) {
                show_success('Succès','Enregistrement éffectué');
			}
		})
	})

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


});