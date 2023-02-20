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
                return 'Qté';
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
            return '<i class="fa fa-pencil-square-o pointer cl_edit_prix" data-type="0" aria-hidden="true"></i>&nbsp;&nbsp;<i class="fa fa-trash-o pointer cl_edit_prix" data-type="2" aria-hidden="true"></i>';
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
});

$(document).on('click', '.cl_edit_prix', function(){
    var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
        action = parseInt($(this).attr('data-type'));

    $('.'+cl_row_edited).removeClass(cl_row_edited);

    if (action === 2) {

        swal({
            title: "SUPPRIMER",
            text: "Voulez-vous vraiment supprimer ce tarif?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OUI",
            closeOnConfirm: false
        }, function () {
            var url = Routing.generate('service_delete_prix',{
                id : id
            });

            $.ajax({
                url: url,
                type: 'GET',
                datatype: 'json',
                success: function(res) {
                    swal("Supprimé!", "Le tarif a été supprimé", "success");
                    load_list_prix();
                }
            })
        });

    } else {
        if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);
        show_editeur_prix(id);
    }
});

function show_editeur_prix(id)
{
    id = typeof id !== 'undefined' ? id : 0;

    $.ajax({
        data: {
            id: id
        },
        type: 'POST',
        url: Routing.generate('service_editor_prix'),
        dataType: 'html',
        success: function(data) {
            show_modal(data,'Modification Tarif');
        }
    });
}

$(document).on('click','#id_save_tarif',function(){
        var type_tarif = $('#type_tarif_edit').val(),
            duree = $('#duree_edit').val(),
            prestation = $('#prestation_edit').val(),
            prix = $('#prix_edit').val(),
            id_service = $('#id_service').val()
            id = $('#id_tarif_edit').val();

        if (prix === '')
        {
            show_info('Erreur','Prix vide','error');
            return;
        }

        var data = {
            id : id,
            type_tarif : type_tarif,
            duree : duree,
            prestation : prestation,
            prix : prix,
            id_service : id_service,
            ajax: true
        }

        edit_tarif(0,data);
    });

    function edit_tarif(act, data)
    {
        $.ajax({
            data: data,
            type: 'POST',
            url: Routing.generate('service_save_prix'),
            dataType: 'html',
            success: function(data) {
                show_info('Succés','Modification bien enregistrée avec succés');
                close_modal();
                load_list_prix();
            }
        });
    }