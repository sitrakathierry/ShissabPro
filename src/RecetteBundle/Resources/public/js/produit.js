var cl_row_edited = 'r-cl-edited';

$(document).ready(function(){

	load_list();

    function instance_list_grid() {
        var colNames = ['N° Facture','Produit','Date de création','Date facture','Client','Total'];
        var colModel = [{
                name    : 'num_fact',
                index   : 'num_fact',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-num_fact'
            },{
                name    : 'produit',
                index   : 'produit',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-produit'
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
                classes : 'js-total'
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
		var url = Routing.generate('recette_produit_list')
		var data = {
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
                            num_fact: "TOTAL",
                            total : $(this).jqGrid('getCol', 'total', false, 'sum')
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

    })




});