$('#descr').summernote();


$(document).on('change','.f_ps_designation',function(event) {
    event.preventDefault();

    var f_ps_type_designation = $(this).closest('tr').find('.f_ps_type_designation').val();

    if (f_ps_type_designation == 2) {
        $(this).closest('tr').find('.f_ps_duree').trigger('change');
    }

});

$(document).on('change','.f_ps_duree',function(event) {
    event.preventDefault();

    var tr = $(this).closest('tr');
    var id = tr.find('.f_ps_designation').children('option:selected').val();
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
                tr.find('.f_ps_prix').val(res.prix).trigger('input');
            } else {
                tr.find('.f_ps_prix').val('');
            }
        }
    })
})

$(document).on('change','.f_ps_devise',function(event) {
    event.preventDefault();

    var montantprincipal = $(this).children('option:selected').data('montantprincipal');
    var montantconversion = $(this).children('option:selected').data('montantconversion');
    var total = $('#ps_total').val();

    var montant_converti = (Number( total ) * Number( montantconversion )) / Number( montantprincipal );

    $(this).closest('tr').find('.f_ps_montant_converti').val( montant_converti.toFixed(2) );
})

$(document).on('change', '.f_ps_designation', function(event) {
    event.preventDefault();
    var f_ps_type_designation = $(this).closest('tr').find('.f_ps_type_designation').val();

    if (f_ps_type_designation == '1') {
        var prixvente = $(this).children("option:selected").data('prixvente');
    } else {
        var prixvente = '';
    }
    $(this).closest('tr').find('.f_ps_prix').val(prixvente);

});

$(document).on('change', '.f_ps_type_designation', function(event) {
	event.preventDefault();
	
	var soustable_selector = $(this).closest('tr').find('td.soustable');
	var designation_selector = $(this).closest('tr').find('.f_ps_designation');
    var type = $(this).children('option:selected').val();
    var url = Routing.generate('facture_produitservice_tpl', { type : type });

    $.ajax({
    	url : url,
    	type : 'GET',
    	success: function(res) {
    		soustable_selector.html(res.tpl);

    		designation_selector.html('');

            var data = res.designations;
            var options = "<option value=''></option>";

         	$.each(data, function(index, item) {

                if (type == 1) {
                    options += '<option data-prixvente="'+ item.prix_vente +'" value="' + item.id + '">' + item.code_produit + '/' + item.indice + ' | ' + item.nom + ' - ' + item.prix_vente + ' ' + res.devise.symbole + ' </option>';
                } else {
                    options += '<option value="' + item.id + '">' + item.nom + '</option>';
                }

            });
            designation_selector.append(options);

    	}
    })

});

$('.select2').select2();

$(document).on('click', '.btn-add-row-produitservice', function(event) {
    event.preventDefault();


    var id = $('#id-row-produitservice').val();
    var new_id = parseInt(id) + 1;
    var services = $('.f_service').html();
    var durees = $('.f_service_duree').html();

    var a = '<td><div class="form-group"><div class="col-sm-12"><select class="form-control f_ps_type_designation" name="f_ps_type_designation[]"><option></option><option value="1">PRODUIT</option><option value="2">PRESTATION</option></select></div></div></td>';
    var b = '<td><div class="form-group"><div class="col-sm-12"><select class="form-control select2 f_ps_designation" name="f_ps_designation[]"><option></option></select></div></div></td>';
    var c = '<td class="soustable"></td>';
    var d = '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control f_ps_prix" name="f_ps_prix[]" ></div></div></td>';
    var e = '<td><div class="form-group"><div class="col-sm-4"><select class="form-control f_ps_remise_type_ligne" name="f_ps_remise_type_ligne[]"><option value="0">%</option><option value="1">Montant</option></select></div><div class="col-sm-8"><input type="number" class="form-control f_ps_remise_ligne" name="f_ps_remise_ligne[]" ></div></div></td>';
    var f = '<td class="td-montant"><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control f_ps_montant" name="f_ps_montant[]" ></div></div></td>';

    var markup = '<tr class="fact-row row-'+ new_id +'">' + a + b + c + d + e + f + '</tr>';
    $("#table-produitservice-add tbody#principal-produitservice").append(markup);
    $('#id-row-produitservice').val(new_id);

    $('.fact-row row-'+new_id).find(".select2").select2("destroy");
    $("select.select2").select2();

    $('#table-produitservice-add tbody tr:last').find('.f_prix').val()
    
});

$(document).on('click', '.btn-remove-row-produitservice', function(event) {
    event.preventDefault();
    var id     = parseInt($('#id-row-produitservice').val());

    var new_id = id - 1;
    if (new_id >= 0) {
        $('#id-row-produitservice').val(new_id);

        $('#table-produitservice-add tbody tr:last').remove();
    } else {
        show_info("Attention", 'Le tableau devrait contenir au moins une ligne','error');
    }

    calculMontantPs();
});

$(document).on('input','.f_ps_prix',function (event) {

    var prix = Number( event.target.value );
    var type = Number( $(this).closest('tr').find('.f_ps_type_designation').val() );

    if (type == 1 || type == 3) {

        var f_ps_remise_type_ligne = $(this).closest('tr').find('.f_ps_remise_type_ligne').val();
        var f_ps_remise_ligne = $(this).closest('tr').find('.f_ps_remise_ligne').val();

        var qte = Number( $(this).closest('tr').find('.f_ps_qte').val() );
        var total = prix;

        if (qte) {
            total = qte * prix
        }

        var remise_ligne_montant = 0;

        if (f_ps_remise_type_ligne == '1') {
            remise_ligne_montant = Number( f_ps_remise_ligne );
        } else {
            remise_ligne_montant = (total * Number( f_ps_remise_ligne )) / 100;
        }

        total = total - remise_ligne_montant;

        var montant_selector = $(this).closest('tr').find('.f_ps_montant');
        montant_selector.val(total);
    } else {

        var f_ps_remise_type_ligne = $(this).closest('tr').find('.f_ps_remise_type_ligne').val();
        var f_ps_remise_ligne = $(this).closest('tr').find('.f_ps_remise_ligne').val();

        var qte = Number( $(this).closest('tr').find('.f_ps_periode').val() );
        var total = prix;

        if (qte) {
            total = qte * prix
        }

        var remise_ligne_montant = 0;

        if (f_ps_remise_type_ligne == '1') {
            remise_ligne_montant = Number( f_ps_remise_ligne );
        } else {
            remise_ligne_montant = (total * Number( f_ps_remise_ligne )) / 100;
        }

        total = total - remise_ligne_montant;

        var montant_selector = $(this).closest('tr').find('.f_ps_montant');
        montant_selector.val(total);
    }
    
    calculMontantPs();

});

$(document).on('change','.f_ps_remise_type_ligne',function(event) {


    var prix = Number( $(this).closest('tr').find('.f_ps_prix').val() );
    var type = Number( $(this).closest('tr').find('.f_ps_type_designation').val() );

    if (type == 1 || type == 3) {

        var f_ps_remise_type_ligne = $(this).children("option:selected").val();
        var f_ps_remise_ligne = $(this).closest('tr').find('.f_ps_remise_ligne').val();

        var qte = Number( $(this).closest('tr').find('.f_ps_qte').val() );
        var total = prix;

        if (qte) {
            total = qte * prix
        }

        var remise_ligne_montant = 0;

        if (f_ps_remise_type_ligne == '1') {
            remise_ligne_montant = Number( f_ps_remise_ligne );
        } else {
            remise_ligne_montant = (total * Number( f_ps_remise_ligne )) / 100;
        }

        total = total - remise_ligne_montant;

        var montant_selector = $(this).closest('tr').find('.f_ps_montant');
        montant_selector.val(total);
    } else {

        var f_ps_remise_type_ligne = $(this).children("option:selected").val();
        var f_ps_remise_ligne = $(this).closest('tr').find('.f_ps_remise_ligne').val();

        var qte = Number( $(this).closest('tr').find('.f_ps_periode').val() );
        var total = prix;

        if (qte) {
            total = qte * prix
        }

        var remise_ligne_montant = 0;

        if (f_ps_remise_type_ligne == '1') {
            remise_ligne_montant = Number( f_ps_remise_ligne );
        } else {
            remise_ligne_montant = (total * Number( f_ps_remise_ligne )) / 100;
        }

        total = total - remise_ligne_montant;

        var montant_selector = $(this).closest('tr').find('.f_ps_montant');
        montant_selector.val(total);
    }
    
    calculMontantPs();


});

$(document).on('input','.f_ps_remise_ligne',function(event) {

    var prix = Number( $(this).closest('tr').find('.f_ps_prix').val() );
    var type = Number( $(this).closest('tr').find('.f_ps_type_designation').val() );

    if (type == 1 || type == 3) {

        var f_ps_remise_type_ligne = $(this).closest('tr').find('.f_ps_remise_type_ligne').val();
        var f_ps_remise_ligne = event.target.value;

        var qte = Number( $(this).closest('tr').find('.f_ps_qte').val() );
        var total = prix;

        if (qte) {
            total = qte * prix
        }

        var remise_ligne_montant = 0;

        if (f_ps_remise_type_ligne == '1') {
            remise_ligne_montant = Number( f_ps_remise_ligne );
        } else {
            remise_ligne_montant = (total * Number( f_ps_remise_ligne )) / 100;
        }

        total = total - remise_ligne_montant;

        var montant_selector = $(this).closest('tr').find('.f_ps_montant');
        montant_selector.val(total);
    } else {

        var f_ps_remise_type_ligne = $(this).closest('tr').find('.f_ps_remise_type_ligne').val();
        var f_ps_remise_ligne = event.target.value;

        var qte = Number( $(this).closest('tr').find('.f_ps_periode').val() );
        var total = prix;

        if (qte) {
            total = qte * prix
        }

        var remise_ligne_montant = 0;

        if (f_ps_remise_type_ligne == '1') {
            remise_ligne_montant = Number( f_ps_remise_ligne );
        } else {
            remise_ligne_montant = (total * Number( f_ps_remise_ligne )) / 100;
        }

        total = total - remise_ligne_montant;

        var montant_selector = $(this).closest('tr').find('.f_ps_montant');
        montant_selector.val(total);
    }
    
    calculMontantPs();


})

$(document).on('input','.f_ps_qte',function (event) {

    var qte = Number( event.target.value );
    var prix = $(this).closest('tr').find('.f_ps_prix').val();

    var f_ps_remise_type_ligne = $(this).closest('tr').find('.f_ps_remise_type_ligne').val();
    var f_ps_remise_ligne = $(this).closest('tr').find('.f_ps_remise_ligne').val();

    prix = prix ? prix : 0;
    var total = prix;

    if (qte) {
        total = qte * prix
    }

    var remise_ligne_montant = 0;

    if (f_ps_remise_type_ligne == '1') {
        remise_ligne_montant = Number( f_ps_remise_ligne );
    } else {
        remise_ligne_montant = (total * Number( f_ps_remise_ligne )) / 100;
    }

    total = total - remise_ligne_montant;

    var montant_selector = $(this).closest('tr').find('.f_ps_montant');
    montant_selector.val(total);
    calculMontantPs();

});

$(document).on('input','.f_ps_periode',function (event) {

    var qte = Number( event.target.value );
    var prix = Number( $(this).closest('tr').find('.f_ps_prix').val() );

    var f_ps_remise_type_ligne = $(this).closest('tr').find('.f_ps_remise_type_ligne').val();
    var f_ps_remise_ligne = $(this).closest('tr').find('.f_ps_remise_ligne').val();

    var total = prix;

    if (qte) {
        total = qte * prix
    }

    var remise_ligne_montant = 0;

    if (f_ps_remise_type_ligne == '1') {
        remise_ligne_montant = Number( f_ps_remise_ligne );
    } else {
        remise_ligne_montant = (total * Number( f_ps_remise_ligne )) / 100;
    }

    total = total - remise_ligne_montant;

    var montant_selector = $(this).closest('tr').find('.f_ps_montant');
    montant_selector.val(total);
    calculMontantPs();

});

var montant = 0;
var remise = 0;
var total = 0;

function calculMontantPs() {

    montant = 0;

    $('table#table-produitservice-add > tbody  > tr').each(function(index, tr) { 
       var montant_selector = $(this).children('td.td-montant').find('.f_ps_montant');

       var f_montant = montant_selector.val();

       montant += Number(f_montant);

       $('#ps_montant').val(montant);

       calculRemisePs( Number( $('#f_ps_remise').val() ) )
      
    });
}

function calculRemisePs(pourcentage) {

    var f_ps_remise_type = $('#f_ps_remise_type').val();

    if (f_ps_remise_type == 0) {
        remise = (montant * pourcentage) / 100;
    } else {
        remise = pourcentage;
    }

    $('#ps_remise').val(remise);

    calculTotalPs();
}

$(document).on('input','#f_ps_remise',function (event) {
    var value = event.target.value;
    calculRemisePs(value)
});

$(document).on('change','#f_ps_remise_type',function(event) {
    event.preventDefault();

    calculRemisePs( Number( $('#f_ps_remise').val() ) );

});

function calculTotalPs() {
    total = montant - remise;
    var letter = NumberToLetter(total) ;
    var devise_lettre = $('#devise_lettre').val();

    $('#ps_total').val(total);
    $('#ps_somme').html(letter + " " + devise_lettre);
    $('#id-somme-produitservice').val(letter + " " + devise_lettre);

    $('.f_ps_devise').trigger('change')

}
