var cl_row_edited = 'r-cl-edited';

$(document).ready(function(){

	load_list();

	function instance_grid() {
        var colNames = ['Nom','Qté','Unite','Produit en stock','Libellé',''];
        
        var colModel = [{ 
            name:'nom',
            index:'nom',
            align: 'center' 
        },{ 
            name:'stock',
            index:'stock',
            align: 'center'
        },{ 
            name:'unite',
            index:'unite',
            align: 'center'
        },{ 
            name:'portion',
            index:'portion',
            align: 'center'
        },{ 
            name:'libelle',
            index:'libelle',
            align: 'center'
        },{
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
        };

        var tableau_grid = $('#liste_stock_interne');

        if (tableau_grid[0].grid == undefined) {
            tableau_grid.jqGrid(options);
        } else {
            delete tableau_grid;
            $('#liste_stock_interne').GridUnload('#liste_stock_interne');
            tableau_grid = $('#liste_stock_interne');
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
    	
        var url = Routing.generate('stock_interne_list');
        var data = {
        	agence : $('#agence').val(),
            recherche_par: $('#recherche_par').val(),
            a_rechercher: $('#a_rechercher').val()
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'html',
            success: function(res) {
                var grid = instance_grid();
                grid.jqGrid('setGridParam', {
                    data        : $.parseJSON(res),
                    loadComplete: function() {
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

        window.location.href = Routing.generate('stock_interne_show', { id : id })
    });

    $(document).on('click', '#btn_search', function(event) {
        event.preventDefault();

        load_list();
    });

});