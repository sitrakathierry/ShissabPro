$(document).on('input','#annulation_automatique',function(event) {
	var h = event.target.value;

	if (Number(h) < 0 || h == "") {
		$(this).val('')
	}
})

$(document).on('click','#import-tarif-categorie',function(event) {
	event.preventDefault();

	var id = get_radio_value('.categorie');

	var url = Routing.generate('hebergement_categorie_tarifs', { id : id });

	$.ajax({
		url: url,
		type : 'GET',
		success: function(res) {
    		$("#table-tarif-add tbody").append(res);
		}
	})
})

$(document).on('change','.petit_dejeuner',function(event) {
    event.preventDefault();

    var petit_dejeuner = $(this).children('option:selected').val();

    if (petit_dejeuner == 2) {
        $(this).closest('tr').find('.supplementaire').removeClass('hidden');
    } else {
        $(this).closest('tr').find('.supplementaire').addClass('hidden');
    }
})


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

$(document).on('click', '#btn-save', function(event) {
	event.preventDefault();

	var data = {
		id : $('#id').val(),
		statut : $('#statut').val(),
		nom : $('#nom').val(),
		categorie : get_radio_value('.categorie'),
		nb_lit_simple : $('#nb_lit_simple').val(),
		nb_lit_double : $('#nb_lit_double').val(),
		nb_pers_chambre : $('#nb_pers').val(),
		nb_pers : toArray('nb_pers'),
        montant : toArray('montant'),
        petit_dejeuner : toArray('petit_dejeuner'),
        montant_petit_dejeuner : toArray('montant_petit_dejeuner'),
		periode_annulation : $('#periode_annulation').val(),
		pourcentage_remboursement : $('#pourcentage_remboursement').val(),
		annulation_automatique : $('#annulation_automatique').val(),
	};

	var url = Routing.generate('hebergement_chambre_save');

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
					show_success('Succès', 'Chambre enregistré');
				}
			})
	      
  	});

});

$(document).on('input','#nb_lit_simple',function(event) {
	var nb_lit_simple = event.target.value;
	var nb_lit_double = $('#nb_lit_double').val();

	var nb_pers = Number(nb_lit_simple) + ( Number(nb_lit_double) * 2 );

	$('#nb_pers').val( nb_pers );
})

$(document).on('input','#nb_lit_double',function(event) {
	var nb_lit_simple = $('#nb_lit_simple').val();
	var nb_lit_double = event.target.value;

	var nb_pers = Number(nb_lit_simple) + ( Number(nb_lit_double) * 2 );

	$('#nb_pers').val( nb_pers );
})

function get_radio_value(selector) {
	value = 0;
	$(selector).each(function() {
		if (this.checked == true) {
			value = this.value;
		}
	});
	return value;
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
