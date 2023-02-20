var cl_row_edited = 'r-cl-edited';

$(document).ready(function(){

	load_list();

    function instance_list_grid() {
        var colNames = ['N° Facture','Modele','Type','Date de création','Date facture','Client','Total',''];
        var colModel = [
            // {
            //     name    : 'agence',
            //     index   : 'agence',
            //     align   : 'left',
            //     editable: false,
            //     sortable: false,
            //     classes : 'js-agence'
            // },
            {
                name    : 'num_fact',
                index   : 'num_fact',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-num_fact'
            },{
                name    : 'modele',
                index   : 'modele',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-modele',
                formatter: function(v) {
                    if (v == 1) {
                        return 'PRODUIT';
                    }
                    if (v == 2) {
                        return 'PRESTATION';
                    }
                    if (v == 3) {
                        return 'PRODUIT & PRESTATION';
                    }
                    if (v == 4) {
                        return 'HÉBERGEMENT';
                    }
                    return '';
                }
            },{
                name    : 'type',
                index   : 'type',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-type'
            },{
                name    : 'date_creation',
                index   : 'date_creation',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-date_creation'
            },{
                name    : 'date',
                index   : 'date',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-date'
            },{
                name    : 'client',
                index   : 'client',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-client'
            },{
                name    : 'total',
                index   : 'total',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-total',
                formatter: function(v,i,r) {
                    if (r.modele == 4) {
                        return r.montant;
                    } else {
                        return v;
                    }
                }
            },{
                name:'x',
                index:'x',
                align: 'center',
                formatter: function(v,i,r){ 
                    if (r.modele == 1) {
                        return '<button class="btn btn-xs btn-outline btn-primary consulter_produit " data-type="0"><i class="fa fa-pencil-square-o "></i>&nbsp;Consulter</button>'; 
                    }

                    if (r.modele == 2) {
                        return '<button class="btn btn-xs btn-outline btn-primary consulter_service " data-type="0"><i class="fa fa-pencil-square-o "></i>&nbsp;Consulter</button>'; 
                    }

                    if (r.modele == 3) {
                        return '<button class="btn btn-xs btn-outline btn-primary consulter_produitservice " data-type="0"><i class="fa fa-pencil-square-o "></i>&nbsp;Consulter</button>'; 
                    }

                    if (r.modele == 4) {
                        return '<button class="btn btn-xs btn-outline btn-primary consulter_hebergement " data-type="0"><i class="fa fa-pencil-square-o "></i>&nbsp;Consulter</button>'; 
                    }

                    return '';
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
            rowNum: 1000000000000,
            viewrecords: true,
            hidegrid   : true,
            forceFit:true,
            footerrow : true,

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
		var url = Routing.generate('facture_corbeille_list')
		var data = {
            filtre_modele : $('#filtre_modele').val(),
            filtre_type : $('#filtre_type').val(),
            recherche_par : $('#recherche_par').val(),
            a_rechercher : $('#a_rechercher').val(),
            type_date : $('#type_date').val(),
            mois : $('#mois').val(),
            annee : $('#annee').val(),
            date_specifique : $('#date_specifique').val(),
            debut_date : $('#debut_date').val(),
            fin_date : $('#fin_date').val(),
            par_agence : $('#par_agence').val(),
		};

		$.ajax({
        	type: 'POST',
        	url: url,
        	data: data,
        	dataType: 'html',
        	success: function(res) {
        		var grid = instance_list_grid();
                grid.jqGrid('setGridParam', {
                    data        : $.parseJSON(res),
                    loadComplete: function() {
                        $(this).jqGrid("footerData", "set", {
                            agence: "Total",
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

    $(document).on('click','#btn_search',function(event) {
        event.preventDefault();
        load_list();
    })

	$(document).on('click','#btn-save',function(event) {
		event.preventDefault();

		var data = {
			libelle : $('#libelle').val(),
			code : $('#code').val(),
		};
		var url = Routing.generate('prestation_save');
		$.ajax({
			url: url,
			data: data,
			type: 'POST',
			success: function(res) {
				console.log('load');
				load_list();
			}
		})
	})

    $(document).on('click', '.consulter_produit', function(){
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);
        var url = Routing.generate('facture_produit_show',{
            id: id
        });

        window.location.href = url;
        
    });

    $(document).on('click', '.consulter_service', function(){
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);
        var url = Routing.generate('facture_service_show',{
            id: id
        });

        window.location.href = url;
        
    });

    $(document).on('click', '.consulter_produitservice', function(){
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);
        var url = Routing.generate('facture_produitservice_show',{
            id: id
        });

        window.location.href = url;
        
    });

    $(document).on('click', '.consulter_hebergement', function(){
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);
        var url = Routing.generate('facture_hebergement_show',{
            id: id
        });

        window.location.href = url;
        
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
        }    

    });

    $('#a_rechercher').on( "keydown", function( event ) {
      if (event.which === 13) {
        $('#btn_search').trigger('click');
      }
    });




});