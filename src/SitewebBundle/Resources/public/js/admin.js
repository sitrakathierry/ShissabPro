var cl_row_edited = 'r-cl-edited';

$(document).ready(function(){

	load_list();

	function instance_grid() {
        var colNames = ['Agence','Nom','Lien', ''];
        
        var colModel = [{ 
            name:'agence',
            index:'agence',
            align: 'center',
        },{ 
            name:'nom',
            index:'nom',
            align: 'center',
        },
        { 
            name:'lien',
            index:'lien',
            align: 'center',
            formatter: function(v) {
            	return '<a href="'+ v +'" target="_blank">'+ v +'</a>';
            }
        },
        {
            name:'action',
            index:'action',
            align: 'center',
            formatter: function(v){ 
                return '<button class="btn btn-xs btn-outline btn-primary edit_siteweb " data-type="0"><i class="fa fa-lock "></i>&nbsp;Autorisations</button> &nbsp;'; 
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

        var tableau_grid = $('#list_siteweb');

        if (tableau_grid[0].grid == undefined) {
            tableau_grid.jqGrid(options);
        } else {
            delete tableau_grid;
            $('#list_siteweb').GridUnload('#list_siteweb');
            tableau_grid = $('#list_siteweb');
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
    	
        var url = Routing.generate('siteweb_list')
        var data = {
        	recherche_par : $('#recherche_par').val(),
			a_rechercher : $('#a_rechercher').val(),
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'html',
            success: function(res) {
                $('.cl_list_societe').removeClass('hidden');
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

	$(document).on('click', '.edit_siteweb', function(){
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        location.href = Routing.generate('siteweb_admin_autorisation_show', { id : id })

        
    });

    $(document).on('click', '#btn_search', function(event) {
    	event.preventDefault();
    	load_list();
    })

});