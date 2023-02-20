var cl_row_edited = 'r-cl-edited';
var datas = [];

$(document).ready(function(){

    $('.select2').select2();

	load_list();

	function instance_grid() {
        var colNames = ['N° Réservation','Chambre', 'Date entrée', 'Date sortie','Nb nuits','Nb pers','Petit dejeuner','Statut',''];
        
        var colModel = [
        { 
            name:'num',
            index:'num',
            align: 'center' 
        },{ 
            name:'chambre',
            index:'chambre',
            align: 'center' 
        },{ 
            name:'date_entree',
            index:'date_entree',
            align: 'center' 
        },{ 
            name:'date_sortie',
            index:'date_sortie',
            align: 'center' 
        },{ 
            name:'nb_nuit',
            index:'nb_nuit',
            align: 'center' 
        },{ 
            name:'nb_pers',
            index:'nb_pers',
            align: 'center' 
        },{ 
            name:'avec_petit_dejeuner',
            index:'avec_petit_dejeuner',
            align: 'center',
            formatter: function(v) {
                if (v == 1) { return 'INCLUS'}

                if (v == 2) { return 'SUPPLÉMENTAIRE'}
                
            }
        },{ 
            name:'statut',
            index:'statut',
            align: 'center',
            width: 300,
            formatter: function(v,i,r) {
                if (v == 0) { return 'NON CONFIRMÉ'}

                if (v == 1) { return 'CONFIRMÉ'}

                if (v == 2) { return 'EN COURS'}

                if (v == 3) { 
                    if (r.periode == 3) {
                        return 'TERINÉ - À PAYER'
                    }
                    return 'ENCOURS - À PAYER'
                }

                if (v == 4) { 
                    if (r.periode == 3) {
                        return 'TERMINÉ - PAYÉ'
                    }
                    return 'ENCOURS - PAYÉ'
                }

                if (v == 5) { 

                    if (r.montant_remboursement) {
                        if (Number(r.montant_remboursement) != 0) {
                            return 'ANNULÉ AVEC REMBOURSEMENT';
                        } else {
                            return 'ANNULÉ SANS REMBOURSEMENT';
                        }
                    }

                    return 'ANNULÉ AUTOMATIQUE'
                }

                return v;

            },
            cellattr: function(i,v,r) {

                if (v == 'NON CONFIRMÉ') {
                    if (r.periode == 3) {
                        return 'style="background:#ed5565;color:#fff"';
                    }
                    return 'style="background:#ff9800;color:#fff"';
                }

                if (v == 'CONFIRMÉ') {
                    if (r.periode == 3) {
                        return 'style="background:#ed5565;color:#fff"';
                    }
                    return 'style="background:#ffeb3b"';
                }

                if (v == 'EN COURS' || v == 'ENCOURS - PAYÉ' || v == 'ENCOURS - À PAYER') {
                    if (r.periode == 3) {
                        return 'style="background:#ed5565;color:#fff"';
                    }
                    return 'style="background:#03a9f4;color:#fff"';
                }

                if (v == 'TERINÉ - À PAYER') {
                    if (r.periode == 3) {
                        return 'style="background:#ed5565;color:#fff"';
                    }
                    return 'style="background:#009688;color:#fff"';
                }

                if (v == 'TERMINÉ - PAYÉ') {
                    return 'style="background:#8bc34a;color:#fff"';
                }

                if (v == 'ANNULÉ AVEC REMBOURSEMENT') {
                    return 'style="background:#607d8b;color:#fff"';
                }

                if (v == 'ANNULÉ SANS REMBOURSEMENT') {
                    return 'style="background:#9e9e9e;color:#fff"';
                }

                if (v == 'ANNULÉ AUTOMATIQUE') {
                    return 'style="background:#795548;color:#fff"';
                }

                return 'style="background:#ed5565;color:#fff"';

            }
        }
        // ,{ 
        //     name:'periode',
        //     index:'periode',
        //     align: 'center' ,
        //     formatter: function(v,i,r) {
        //         if (v == 1) { return 'ACTUEL'}

        //         if (v == 2) { return 'À VENIR'}
                
        //         if (v == 3) { return 'PASSÉ'}



        //     },
        //     cellattr: function(i,v,r) {

        //         if (v == 'PASSÉ') {
        //             return 'style="background:#607d8b;color:#fff"';
        //         }

        //         if (v == 'ACTUEL') {
        //             return 'style="background:#03a9f4;color:#fff"';
        //         }

        //         if (v == 'À VENIR') {
        //             return 'style="background:#9c27b0;color:#fff"';
        //         }

        //     }
        // }
        // ,{ 
        //     name:'total',
        //     index:'total',
        //     align: 'center',
            
        // }
        ,{
            name:'action',
            index:'action',
            align: 'center',
            formatter: function(v){ 
                return '<button class="btn btn-xs btn-outline btn-primary afficher " data-type="0"><i class="fa fa-pencil-square-o "></i>&nbsp;Afficher'; 
            }
        }];

        var options = {
            datatype   : 'local',
            height     : 300,
            autowidth  : true,
            loadonce   : true,
            shrinkToFit: true,
            rownumbers : false,
            altRows    : false,
            colNames   : colNames,
            colModel   : colModel,
            viewrecords: true,
            hidegrid   : true,
            forceFit:true,
            rowNum: 1000000000,
            footerrow : true,

        };

        var tableau_grid = $('#liste_reservation');

        if (tableau_grid[0].grid == undefined) {
            tableau_grid.jqGrid(options);
        } else {
            delete tableau_grid;
            $('#liste_reservation').GridUnload('#liste_reservation');
            tableau_grid = $('#liste_reservation');
            tableau_grid.jqGrid(options);
        }

        var window_height = window.innerHeight - 600;

        if (window_height < 300) {
            tableau_grid.jqGrid('setGridHeight', 300);
        } else {
            tableau_grid.jqGrid('setGridHeight', window_height);
        }

        return tableau_grid;
    }

    function load_list() {
    	
        var url = Routing.generate('hebergement_reservation_list');
        var data = {
        	agence : $('#agence').val(),
            recherche_par: $('#recherche_par').val(),
            a_rechercher: $('#a_rechercher').val(),
            categorie: $('#categorie').val(),
            chambre: $('#chambre').val(),
            type_date: $('#type_date').val(),
            mois: $('#mois').val(),
            annee: $('#annee').val(),
            date_specifique: $('#date_specifique').val(),
            debut_date: $('#debut_date').val(),
            fin_date: $('#fin_date').val(),
            statut: $('#filtre_statut').val(),
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'html',
            success: function(res) {
                datas = res;
                var grid = instance_grid();
                grid.jqGrid('setGridParam', {
                    data        : $.parseJSON(res),
                    loadComplete: function() {
                        $(this).jqGrid("footerData", "set", {
                            num: "Total",
                            total : $(this).jqGrid('getCol', 'total', false, 'sum'),
                        });
                    }
                }).trigger('reloadGrid', [{
                    page: 1,
                    current: true
                }]);
            }

        })
    
    }


	$(document).on('click', '.afficher', function(){
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);

        window.location.href = Routing.generate('hebergement_reservation_show', { id : id })
    });

    $(document).on('click', '#btn_search', function(event) {
        event.preventDefault();

        load_list();
    });

    $(document).on('change','#categorie',function(event) {
        event.preventDefault();

        var categorie = $(this).children("option:selected").val();
        var chambre_selector = $('#chambre');


        chambre_selector.html('');

        if (categorie != 0) {

            var url = Routing.generate('hebergement_chambre_list');

            var data = {
                agence : $('#agence').val(),
                categorie : categorie
            };

            $.ajax({
                url : url,
                type : 'POST',
                data : data,
                success: function(data) {
                    var options = '<option value="0">Tous</option>';

                    $.each(data, function(index, item) {
                        options += '<option value="' + item.id + '">' + item.nom + '</option>';
                    });

                    chambre_selector.append(options);
                }
            })
        }

    });

    $(document).on('change','#type_date',function(event) {

        var val = $(this).val();

        switch (val){
            case "0":
                $('.selector_mois').addClass('hidden');
                $('.selector_annee').addClass('hidden');
                $('.selector_fourchette').addClass('hidden');
                $('.selector_specifique').addClass('hidden');
                break;
            case "1":
                $('.selector_mois').addClass('hidden');
                $('.selector_annee').addClass('hidden');
                $('.selector_fourchette').addClass('hidden');
                $('.selector_specifique').addClass('hidden');
                break;
            case "2":
                $('.selector_mois').removeClass('hidden');
                $('.selector_annee').removeClass('hidden');
                $('.selector_fourchette').addClass('hidden');
                $('.selector_specifique').addClass('hidden');

                break;
            case "3":
                $('.selector_mois').addClass('hidden');
                $('.selector_annee').removeClass('hidden');
                $('.selector_fourchette').addClass('hidden');
                $('.selector_specifique').addClass('hidden');
                break;
            case "4":
                $('.selector_mois').addClass('hidden');
                $('.selector_annee').addClass('hidden');
                $('.selector_fourchette').addClass('hidden');
                $('.selector_specifique').removeClass('hidden');

                $('.input-datepicker').datepicker({
                      todayBtn: "linked",
                      keyboardNavigation: false,
                      forceParse: false,
                      calendarWeeks: true,
                      autoclose: true,
                      format: 'dd/mm/yyyy',
                      language: 'fr',
                  });
                break;

            case "5":
                $('.selector_mois').addClass('hidden');
                $('.selector_annee').addClass('hidden');
                $('.selector_specifique').addClass('hidden');
                $('.selector_fourchette').removeClass('hidden');
                $('.input-datepicker').datepicker({
                  todayBtn: "linked",
                  keyboardNavigation: false,
                  forceParse: false,
                  calendarWeeks: true,
                  autoclose: true,
                  format: 'dd/mm/yyyy',
                  language: 'fr',
                });
                break;
            case "6":
                $('.selector_mois').addClass('hidden');
                $('.selector_annee').addClass('hidden');
                $('.selector_fourchette').addClass('hidden');
                $('.selector_specifique').addClass('hidden');
                break;
        }    

    });

    $(document).on('click','.cl_export',function() {
        var url = Routing.generate('hebergement_reservation_export');
        var params = ''
                + '<input type="hidden" name="datas" value="'+encodeURI(datas)+'">'
        $('#form_export').attr('action',url).html(params);
        $('#form_export')[0].submit();
    })

});