var cl_row_edited = 'r-cl-edited';

$(document).ready(function(){

	load_list();

	function instance_grid() {
        var colNames = ['Désignation','Déscription',''];
        
        var colModel = [{ 
                name:'designation',
                index:'designation',
                align: 'center' 
            },
            { 
                name:'descr',
                index:'desc',
                align: 'center'
            },{
                name:'x',
                index:'x',
                align: 'center',
                formatter: function(v){ 
                    return '<i class="fa fa-pencil-square-o pointer cl_edit_type" data-type="0" aria-hidden="true"></i>&nbsp;&nbsp;<i class="fa fa-trash-o pointer cl_edit_type" data-type="2" aria-hidden="true"></i>' }
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

        var tableau_grid = $('#list_type_social');

        if (tableau_grid[0].grid == undefined) {
            tableau_grid.jqGrid(options);
        } else {
            delete tableau_grid;
            $('#list_type_social').GridUnload('#list_type_social');
            tableau_grid = $('#list_type_social');
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
    	
        var url = Routing.generate('typesocial_get_list')
        var data = {};

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

	$(document).on('click','#btn-save',function(event) {
		var data = {
			designation : $('#designation').val(),
			description : $('#description').val(),
		}

		var url = Routing.generate('typesocial_save');

		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(res) {
				show_info('SUCCESS','Situation social enregistré enregistré');
				load_list();
				// body...
			}
		})
	})


	$(document).on('click', '.cl_edit_type', function(){
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        if (action === 2) {

            swal({
                title: "SUPPRIMER",
                text: "Voulez-vous vraiment supprimer?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "OUI",
                closeOnConfirm: false
            }, function () {
                // swal("Deleted!", "Your imaginary file has been deleted.", "success");
                var url = Routing.generate('typesocial_delete',{
                    id : id
                });

                $.ajax({
                    url: url,
                    type: 'GET',
                    datatype: 'json',
                    success: function(res) {
                        swal("Supprimé!", "Situation social supprimé", "success");
                        // show_info("Succés", 'Ré-assureur supprimé!','success');
                        load_list();
                    }
                })
            });

        } else {
            if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);
            show_editeur_marque(id);
        }
    });

    function show_editeur_marque(id)
    {
        id = typeof id !== 'undefined' ? id : 0;

        $.ajax({
            data: {
                id: id
            },
            type: 'POST',
            url: Routing.generate('typesocial_editor'),
            dataType: 'html',
            success: function(data) {
                show_modal(data,'Modification Type société');
            }
        });
    }

    $(document).on('click','#id_save_type',function(){
        var designation = $('#id_designation').val().trim(),
            id = $('#id_type_edit').val();

        if (designation === '')
        {
            show_info('Erreur','Désignation vide','error');
            return;
        }

        var data = {
            id : id,
            designation : designation,
            ajax: true
        }

        edit_type(0,data);
    });

    function edit_type(act, data)
    {
        $.ajax({
            data: data,
            type: 'POST',
            url: Routing.generate('typesocial_save'),
            dataType: 'html',
            success: function(data) {
                show_info('Succés','Modification enregistrée');
                close_modal();
                load_list();
            }
        });
    }


});