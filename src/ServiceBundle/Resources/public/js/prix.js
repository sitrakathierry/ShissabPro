var cl_row_edited = 'r-cl-edited';

load_list_prix();

$(document).on('click','#save-prix', function(event) {
	var data = {
		type_tarif : $('#type_tarif').val(),
        duree : $('#duree').val(),
        prestation : $('#prestation').val(),
		prix : $('#prix').val(),
		id_service : $('#id_service').val()
	};

	var url = Routing.generate('service_save_prix');

	$.ajax({
		url : url,
		type : 'POST',
		data : data,
		success: function(res) {
			$('#prix').val('');
			show_info('Succès', 'Prix enregistré');
			load_list_prix();
		}
	})
});

function instance_grid_prix() {
    var colNames = ['Tarrif','Prix',''];
    
    var colModel = [{ 
        name:'tarif',
        index:'tarif',
        align: 'center' ,
        formatter : function(v, i, r) {

            if (r.type == 1) {
            	if (r.duree == 1) { return 'Heure'; }
            	if (r.duree == 2) { return 'Jour'; }
            	if (r.duree == 3) { return 'Mois'; }
            	if (r.duree == 4) { return 'Année'; }
            } else {
                return r.prestation
            }

        }
    },{ 
        name:'prix',
        index:'prix',
        align: 'center' ,
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

    var tableau_grid = $('#liste_prix');

    if (tableau_grid[0].grid == undefined) {
        tableau_grid.jqGrid(options);
    } else {
        delete tableau_grid;
        $('#liste_prix').GridUnload('#liste_prix');
        tableau_grid = $('#liste_prix');
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

function load_list_prix() {

    var url = Routing.generate('service_list_prix');
    var data = {
    	id_service : $('#id_service').val()
    };

    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        dataType: 'html',
        success: function(res) {
            var grid = instance_grid_prix();
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

$(document).on('change', '#type_tarif', function(event) {
    event.preventDefault();
    
    var type = $(this).children("option:selected").val();

    if (type == '1') {
        $('.row_duree').removeClass('hidden')
        $('.row_prestation').addClass('hidden')
    } else {
        $('.row_duree').addClass('hidden')
        $('.row_prestation').removeClass('hidden')
    }
})