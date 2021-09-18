var datas = [];
$(document).ready(function(){

    load_list();

    function instance_list_grid() {
        var colNames = ['Utilisateur','Action','Date modification'];
        var colModel = [{
                name    : 'user',
                index   : 'user',
                align   : 'left',
                editable: false,
                sortable: false,
                formatter: cell_user
            },{
                name    : 'action',
                index   : 'action',
                align   : 'left',
                editable: false,
                sortable: false
            },{
                name    : 'date_modif',
                index   : 'date_modif',
                align   : 'left',
                editable: false,
                sortable: false
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

    function cell_user(cell_value) {
        var id = cell_value.split('-')[0];
        var nom = cell_value.split('-')[1];
        var url = Routing.generate('user_show', {
            id : id
        });
        return '<a class="pointer" href="'+url+'">'+nom+'</a>';
    }

    function load_list(){
        var url = Routing.generate('user_log')
        var data = null;
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
                        $('#nom').val('');
                    }
                }).trigger('reloadGrid', [{
                    page: 1,
                    current: true
                }]);
            }

        })
    }
});