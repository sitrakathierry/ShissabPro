var cl_row_edited = 'r-cl-edited';

var datas = [];
$(document).ready(function(){

    load_list();

    function instance_list_grid() {
        var colNames = ['Bénéficiaire','N° chèque/virement','Montant', 'Mode de paiement','Date déclaration','Mois','Motif', ''];
        var colModel = [{
                name    : 'beneficiaire',
                index   : 'beneficiaire',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-beneficiaire',
                formatter: function(v, i, r) {
                    if (r.depense_type == 2) {
                        return r.prestataire;
                    }

                    return v;
                }
            },{
                name    : 'num',
                index   : 'num',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-num',
                formatter: function(v,i,r) {
                    if (r.mode_paiement == 1) {
                        return r.cheque
                    }
                    if (r.mode_paiement == 3) {
                        return r.virement
                    }

                    return '';
                }
            },{
                name    : 'montant',
                index   : 'montant',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-montant',
                formatter: jq_number_format,
                unformat: jq_number_unformat
            },{
                name    : 'mode_paiement',
                index   : 'mode_paiement',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-mode_paiement',
                formatter: function(v) {
                    if (v == 1) {
                        return 'Chèque';
                    }

                    if (v == 2) {
                        return 'Espèce';
                    }

                    if (v == 3) {
                        return 'Virement';
                    }
                }
            },{
                name    : 'date',
                index   : 'date',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-date'
            },{
                name    : 'mois',
                index   : 'mois',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-mois'
            },{
                name    : 'motif',
                index   : 'motif',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-motif',
                formatter: function(v, i, r) {
                    if (r.depense_type == 2) {
                        return 'SANTE';
                    }

                    return v;
                }
            },{
                name:'x',
                index:'x',
                align: 'center',
                formatter: function(v, i, r){

                    if (r.depense_type == 1) {
                        var btn = '<button class="btn btn-xs btn-outline btn-info consulter " data-type="0"><i class="fa fa-pencil-square-o "></i>&nbsp;Consulter</button> &nbsp;'; 
                        btn += '<button class="btn btn-xs btn-outline btn-primary valider " data-type="0"><i class="fa fa-pencil-square-o "></i>&nbsp;Valider</button> &nbsp;'; 
                        return btn;
                    }

                    if (r.depense_type == 2) {
                        var btn = '<button class="btn btn-xs btn-outline btn-info consulter_prestation " data-type="0"><i class="fa fa-pencil-square-o "></i>&nbsp;Consulter</button> &nbsp;'; 
                        btn += '<button class="btn btn-xs btn-outline btn-primary valider_prestation " data-type="0"><i class="fa fa-pencil-square-o "></i>&nbsp;Valider</button> &nbsp;'; 
                        return btn;
                    }

                }
            }
        ];

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
            footerrow : true,
            rowNum: 1000000000
        };

        var tableau_grid = $('#table_list');

        if (tableau_grid[0].grid == undefined) {
            tableau_grid.jqGrid(options);
        } else {
            delete tableau_grid;
            $('#table_list').GridUnload('#table_list');
            tableau_grid = $('#table_list');
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

    function load_list(){
        var url = Routing.generate('comptabilite_decharge_list')
        var data = {
            statut : 1,
            recherche_par : $('#recherche_par').val(),
            a_rechercher : $('#a_rechercher').val(),
            type_date : $('#type_date').val(),
            mois : $('#mois').val(),
            annee : $('#annee').val(),
            date_specifique : $('#date_specifique').val(),
            debut_date : $('#debut_date').val(),
            fin_date : $('#fin_date').val(),
            filtre_motif : $('#filtre_motif').val(),
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'html',
            success: function(res) {
                datas = res;
                var grid = instance_list_grid();
                grid.jqGrid('setGridParam', {
                    data        : $.parseJSON(res),
                    loadComplete: function() {

                        $(this).jqGrid("footerData", "set", {
                            societe: "Total",
                            montant : $(this).jqGrid('getCol', 'montant', false, 'sum'),
                        });
                    
                        
                    }
                }).trigger('reloadGrid', [{
                    page: 1,
                    current: true
                }]);
            }

        })
    }

    $(document).on('click','#btn_search',function(event) {
        event.preventDefault();
        load_list();
    })

    $(document).on('click', '.valider', function(){
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        swal({
            title: "Valider",
            text: "Voulez-vous vraiment valider?",
            type: "info",
            showCancelButton: true,
            // confirmButtonColor: "#DD6B55",
            confirmButtonText: "OUI",
            closeOnConfirm: false
        }, function () {
            var url = Routing.generate('comptabilite_decharge_validation',{
                id : id
            });

            $.ajax({
                url: url,
                type: 'GET',
                datatype: 'json',
                success: function(res) {
                    swal("Validé", "Décharge validé", "success");
                    load_list();
                }
            })
        });
    });

    $(document).on('click', '.valider_prestation', function(){

        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);


        swal({
            title: "Valider",
            text: "Voulez-vous vraiment valider ?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#0092df",
            confirmButtonText: "OUI",
            closeOnConfirm: false
        }, function () {

            var data = {
                id: id,
            }

            var url = Routing.generate('caisse_prestation_maladie_traiter');

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                datatype: 'json',
                success: function(res) {
                    swal.close()
                    show_info('Succés','Paiement effectué');
                    load_list();
                }
            })
        });

    
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
                $('.selector_mois').removeClass('hidden');
                $('.selector_annee').removeClass('hidden');
                $('.selector_fourchette').addClass('hidden');
                $('.selector_specifique').addClass('hidden');

                break;
        }    

    })

    $(document).on('click','.cl_export',function() {
        // var extension = $(this).attr('data-format');

        var url = Routing.generate('comptabilite_decharge_declare_export');

        var params = ''
                + '<input type="hidden" name="datas" value="'+encodeURI(datas)+'">'

        $('#form_export').attr('action',url).html(params);
        $('#form_export')[0].submit();

    })

    $(document).on('click','.consulter',function() {
        var id = $(this).closest('tr').attr('id');
        var url = Routing.generate('comptabilite_decharge_show',{ id : id });
        window.location.href = url;

    })

    $(document).on('click','.consulter_prestation',function() {
        var id = $(this).closest('tr').attr('id');
        var url = Routing.generate('caisse_prestation_maladie_paiement',{ id : id });
        window.location.href = url;

    })




});