$(document).ready(function(){

    // load_morale_list();

    // load_physique_list();

    load_tous_list();

    function instance_tous_grid() {
        var colNames = ['N° client','Nom/Société','Statut','Adresse','Tél',''];
        
        var colModel = [{
                name    : 'num_police',
                index   : 'num_police',
                align   : 'left',
                editable: false,
                sortable: false,
                // width   : 125,
                classes : 'js-num_police'
            },{
                name    : 'nom',
                index   : 'nom',
                align   : 'left',
                editable: false,
                sortable: false,
                // width   : 125,
                classes : 'js-nom'
            },{
                name    : 'statut',
                index   : 'statut',
                align   : 'left',
                editable: false,
                sortable: false,
                // width   : 125,
                classes : 'js-statut'
            },{
                name    : 'adresse',
                index   : 'adresse',
                align   : 'left',
                editable: false,
                sortable: false,
                // width   : 125,
                classes : 'js-adresse'
            },{
                name    : 'tel',
                index   : 'tel',
                align   : 'left',
                editable: false,
                sortable: false,
                // width   : 125,
                classes : 'js-tel'
            },{
                name:'x',
                index:'x',
                align: 'center',
                formatter: function(v){ 
                    return '<i class="fa fa-pencil-square-o pointer cl_edit_client" data-type="0" aria-hidden="true"></i>' }
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
        };

        var tableau_grid = $('#list_client_morale');

        if (tableau_grid[0].grid == undefined) {
            tableau_grid.jqGrid(options);
        } else {
            delete tableau_grid;
            $('#list_client_morale').GridUnload('#list_client_morale');
            tableau_grid = $('#list_client_morale');
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

    // function instance_morale_grid() {
    //     var colNames = ['N° Police','Nom Société','Nom et Prénom Gérant','Adresse','Téléphone fixe','Fax','Email','Domaine d’intervention','N° de registre','Type de Société','Nom et Prénom','Téléphone ou Email',''];
        
    //     var colModel = [{
    //             name    : 'num_police',
    //             index   : 'num_police',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 125,
    //             classes : 'js-num_police'
    //         },{
    //             name    : 'nom_societe',
    //             index   : 'nom_societe',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 125,
    //             classes : 'js-nom_societe'
    //         }, {
    //             name    : 'nom_gerant',
    //             index   : 'nom_gerant',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-nom_gerant'
    //         }, {
    //             name    : 'adresse',
    //             index   : 'adresse',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-adresse',
    //         }, {
    //             name    : 'tel_fixe',
    //             index   : 'tel_fixe',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-tel_fixe',
    //         }, {
    //             name    : 'fax',
    //             index   : 'fax',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-fax',
    //         }, {
    //             name    : 'email',
    //             index   : 'email',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-email',
    //         }, {
    //             name    : 'domaine',
    //             index   : 'domaine',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-domaine',
    //         }, {
    //             name    : 'num_registre',
    //             index   : 'num_registre',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-num_registre',
    //         }, {
    //             name    : 'type_societe',
    //             index   : 'type_societe',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-type_societe',
    //         }, {
    //             name    : 'nom_pers_contact',
    //             index   : 'nom_pers_contact',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-nom_pers_contact',
    //         }, {
    //             name    : 'tel_pers_contact',
    //             index   : 'tel_pers_contact',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-tel_pers_contact',
    //         },{
    //             name:'x',
    //             index:'x',
    //             align: 'center',
    //             formatter: function(v){ 
    //                 return '<i class="fa fa-pencil-square-o pointer cl_edit_client" data-type="0" aria-hidden="true"></i>' }
    //         }

    //     ];

    //     var options = {
    //         datatype   : 'local',
    //         height     : 300,
    //         autowidth  : true,
    //         loadonce   : true,
    //         shrinkToFit: true,
    //         rownumbers : false,
    //         altRows    : false,
    //         colNames   : colNames,
    //         colModel   : colModel,
    //         viewrecords: true,
    //         hidegrid   : true,
    //         forceFit:true,
    //     };

    //     var tableau_grid = $('#list_client_morale');

    //     if (tableau_grid[0].grid == undefined) {
    //         tableau_grid.jqGrid(options);
    //     } else {
    //         delete tableau_grid;
    //         $('#list_client_morale').GridUnload('#list_client_morale');
    //         tableau_grid = $('#list_client_morale');
    //         tableau_grid.jqGrid(options);
    //     }

    //     var window_height = window.innerHeight - 600;

    //     if (window_height < 300) {
    //         tableau_grid.jqGrid('setGridHeight', 300);
    //     } else {
    //         tableau_grid.jqGrid('setGridHeight', window_height);
    //     }

    //     return tableau_grid;
    // }

    // function instance_physique_grid() {
    //     var colNames = ['N° Police','Nom','NIN','Adresse','Quartier','Téléphone','Email','Sexe','Situation','Type de social','Lieux de travail','Nom et Prénom','Lien de parenté','Observation',''];
        
    //     var colModel = [{
    //             name    : 'num_police',
    //             index   : 'num_police',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 125,
    //             classes : 'js-num_police'
    //         },{
    //             name    : 'nom',
    //             index   : 'nom',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 125,
    //             classes : 'js-nom'
    //         }, {
    //             name    : 'nin',
    //             index   : 'nin',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-nin'
    //         }, {
    //             name    : 'adresse',
    //             index   : 'adresse',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-adresse',
    //         }, {
    //             name    : 'quartier',
    //             index   : 'quartier',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-quartier',
    //         }, {
    //             name    : 'tel',
    //             index   : 'tel',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-tel',
    //         }, {
    //             name    : 'sexe',
    //             index   : 'sexe',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-sexe',
    //         }, {
    //             name    : 'email',
    //             index   : 'email',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-email',
    //         }, {
    //             name    : 'situation',
    //             index   : 'situation',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-situation',
    //         }, {
    //             name    : 'type_social',
    //             index   : 'type_social',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-type_social',
    //         }, {
    //             name    : 'lieu_travail',
    //             index   : 'lieu_travail',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-lieu_travail',
    //         }, {
    //             name    : 'nom_pers_contact',
    //             index   : 'nom_pers_contact',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-nom_pers_contact',
    //         }, {
    //             name    : 'lien_parente',
    //             index   : 'lien_parente',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-lien_parente',
    //         }, {
    //             name    : 'observation',
    //             index   : 'observation',
    //             align   : 'left',
    //             editable: false,
    //             sortable: false,
    //             // width   : 115,
    //             classes : 'js-observation',
    //         },{
    //             name:'x',
    //             index:'x',
    //             align: 'center',
    //             formatter: function(v){ 
    //                 return '<i class="fa fa-pencil-square-o pointer cl_edit_client" data-type="0" aria-hidden="true"></i>' }
    //         }

    //     ];

    //     var options = {
    //         datatype   : 'local',
    //         height     : 300,
    //         autowidth  : true,
    //         loadonce   : true,
    //         shrinkToFit: true,
    //         rownumbers : false,
    //         altRows    : false,
    //         colNames   : colNames,
    //         colModel   : colModel,
    //         viewrecords: true,
    //         hidegrid   : true,
    //         forceFit:true,
    //     };

    //     var tableau_grid = $('#list_client_physique');

    //     if (tableau_grid[0].grid == undefined) {
    //         tableau_grid.jqGrid(options);
    //     } else {
    //         delete tableau_grid;
    //         $('#list_client_physique').GridUnload('#list_client_physique');
    //         tableau_grid = $('#list_client_physique');
    //         tableau_grid.jqGrid(options);
    //     }

    //     var window_height = window.innerHeight - 600;

    //     if (window_height < 300) {
    //         tableau_grid.jqGrid('setGridHeight', 300);
    //     } else {
    //         tableau_grid.jqGrid('setGridHeight', window_height);
    //     }

    //     return tableau_grid;
    // }

    function load_tous_list(){
        var url = Routing.generate('client_tous_list');
        var data = {
            recherche_par: $('#recherche_par').val(),
            a_rechercher: $('#a_rechercher').val(),
            agence: $('#agence').val(),
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(data) {
                var grid = instance_tous_grid();
                if (typeof data == 'object') {
                    data = Array.from(Object.keys(data), k=>data[k]);
                }
                grid.jqGrid('setGridParam', {
                    data        : data,
                    loadComplete: function() {
                        // resize_tab_details();
                    }
                }).trigger('reloadGrid', [{
                    page: 1,
                    current: true
                }]);
            }
        })
    }


    function load_morale_list(){
        var url = Routing.generate('client_morale_list');
        var data = {
            recherche_par: $('#recherche_par').val(),
            a_rechercher: $('#a_rechercher').val(),
            agence: $('#agence').val(),
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(data) {
                var grid = instance_tous_grid();
                if (typeof data == 'object') {
                    data = Array.from(Object.keys(data), k=>data[k]);
                }
                grid.jqGrid('setGridParam', {
                    data        : data,
                    loadComplete: function() {
                        // resize_tab_details();
                    }
                }).trigger('reloadGrid', [{
                    page: 1,
                    current: true
                }]);
            }
        })
    }

    function load_physique_list(){
        var url = Routing.generate('client_physique_list');
        var data = {
            recherche_par: $('#recherche_par').val(),
            a_rechercher: $('#a_rechercher').val(),
            agence: $('#agence').val(),
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(data) {
                var grid = instance_tous_grid();
                if (typeof data == 'object') {
                    data = Array.from(Object.keys(data), k=>data[k]);
                }
                grid.jqGrid('setGridParam', {
                    data        : data,
                    loadComplete: function() {
                        // resize_tab_details();
                    }
                }).trigger('reloadGrid', [{
                    page: 1,
                    current: true
                }]);
            }
        })
    }

    $(window).resize(function() {
      resize_tab_details();
    });

    function resize_tab_details() {
        setTimeout(function() {
            var tableau_grid = $('#list_client_morale');
            var window_height = window.innerHeight - 700;

              tableau_grid.jqGrid('setGridHeight', window_height);

                var width = tableau_grid.closest(".t-content").width() + 200;

                tableau_grid.jqGrid("setGridWidth", width);

                if (window_height < 400) {
                    tableau_grid.jqGrid('setGridHeight', 400);
                } else {
                }

        }, 600);
    }

    $(document).on('click','#clp_btn_search',function(event) {
        event.preventDefault();
        load_morale_list();
    })

    $(document).on('click','#btn_search_clp',function(event) {
        event.preventDefault();
        load_physique_list();
    })

    $(document).on('click','#btn_search',function(event) {
        event.preventDefault();

        var statut = $('#statut').val();

        if (statut == 0) {
            load_tous_list();
        } else {
            if (statut == 1) {
                load_morale_list();
            } else {
                load_physique_list();
            }
        }
    })

    $(document).on('click', '.cl_edit_client', function(){
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id');
        var url = Routing.generate('client_show',{
            id: id
        });

        window.location.href = url;

    });

    $('#a_rechercher').on( "keydown", function( event ) {
      if (event.which === 13) {
        $('#btn_search').trigger('click');
      }
    });


});
