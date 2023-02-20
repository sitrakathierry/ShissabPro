var cl_row_edited = 'r-cl-edited';

$(document).ready(function(){

	load_list();

    function instance_motif_grid() {
        var colNames = ['Libelle',''];
        var colModel = [{
                name    : 'libelle',
                index   : 'libelle',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-libelle'
            },{
                name:'x',
                index:'x',
                align: 'center',
                formatter: function(v){ 
                    return '<i class="fa fa-pencil-square-o pointer cl_edit_motif" data-type="0" aria-hidden="true"></i>&nbsp;&nbsp;<i class="fa fa-trash-o pointer cl_edit_motif" data-type="2" aria-hidden="true"></i>' }
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
            rowNum: 1000000000
        };

        var tableau_grid = $('#list_motif');

        if (tableau_grid[0].grid == undefined) {
            tableau_grid.jqGrid(options);
        } else {
            delete tableau_grid;
            $('#list_motif').GridUnload('#list_motif');
            tableau_grid = $('#list_motif');
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
		var url = Routing.generate('motif_decharge_motif_list')
		var data = {
		};

		$.ajax({
        	type: 'POST',
        	url: url,
        	data: data,
        	dataType: 'html',
        	success: function(res) {
        		var grid = instance_motif_grid();
                grid.jqGrid('setGridParam', {
                    data        : $.parseJSON(res),
                    loadComplete: function() {
                    	$('#libelle').val('')
                    }
                }).trigger('reloadGrid', [{
                    page: 1,
                    current: true
                }]);
        	}

		})
	}

	$(document).on('click','#btn-save',function(event) {
		event.preventDefault();

		var data = {
			libelle : $('#libelle').val()
		};
		var url = Routing.generate('motif_decharge_motif_save');
		$.ajax({
			url: url,
			data: data,
			type: 'POST',
			success: function(res) {
				load_list();
                show_info('Succés','motif enregistré');
			}
		})
	})

    $(document).on('click', '.cl_edit_motif', function(){
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
                var url = Routing.generate('motif_decharge_motif_delete',{
                    id : id
                });

                $.ajax({
                    url: url,
                    type: 'GET',
                    datatype: 'json',
                    success: function(res) {
                        swal("Supprimé!", "motif supprimé", "success");
                        // show_info("Succés", 'Ré-assureur supprimé!','success');
                        load_list();
                    }
                })
            });

        } else {
            if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);
            show_editeur_motif(id);
        }
    });

    function show_editeur_motif(id)
    {
        id = typeof id !== 'undefined' ? id : 0;

        $.ajax({
            data: {
                id: id
            },
            type: 'POST',
            url: Routing.generate('motif_decharge_motif_editor'),
            dataType: 'html',
            success: function(data) {
                show_modal(data,'Modification motif');
            }
        });
    }

    $(document).on('click','#id_save_motif',function(){
        var libelle = $('#id_libelle').val().trim(),
            id = $('#id_motif_edit').val();

        if (libelle === '')
        {
            show_info('Erreur','libelle vide','error');
            return;
        }

        var data = {
            id : id,
            libelle : libelle,
            ajax: true
        }

        edit_motif(0,data);
    });

    function edit_motif(act, data)
    {
        $.ajax({
            data: data,
            type: 'POST',
            url: Routing.generate('motif_decharge_motif_save'),
            dataType: 'html',
            success: function(data) {
                show_info('Succés','Modification enregistré');
                close_modal();
                load_list();
            }
        });
    }


});