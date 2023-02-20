var cl_row_edited = 'r-cl-edited';

$(document).ready(function(){

    $('.summernote').summernote();

	load_list();

    function instance_accompagnement_grid() {
        var colNames = ['Nom','Prix',''];
        var colModel = [{
                name    : 'nom',
                index   : 'nom',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-nom'
            },{
                name    : 'prix',
                index   : 'prix',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-prix'
            },{
                name:'x',
                index:'x',
                align: 'center',
                formatter: function(v){ 
                    return '<i class="fa fa-pencil-square-o pointer cl_edit_accompagnement" data-type="0" aria-hidden="true"></i>&nbsp;&nbsp;<i class="fa fa-trash-o pointer cl_edit_accompagnement" data-type="2" aria-hidden="true"></i>' }
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
        };

        var tableau_grid = $('#list_accompagnement');

        if (tableau_grid[0].grid == undefined) {
            tableau_grid.jqGrid(options);
        } else {
            delete tableau_grid;
            $('#list_accompagnement').GridUnload('#list_accompagnement');
            tableau_grid = $('#list_accompagnement');
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
		var url = Routing.generate('restaurant_accompagnement_list')
		var data = {
		};

		$.ajax({
        	type: 'POST',
        	url: url,
        	data: data,
        	dataType: 'html',
        	success: function(res) {
        		var grid = instance_accompagnement_grid();
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
		event.preventDefault();

		var data = {
			nom : $('#nom').val(),
            prix : $('#prix').val(),
            description : $('#description').code(),
		};
		var url = Routing.generate('restaurant_accompagnement_save');
		$.ajax({
			url: url,
			data: data,
			type: 'POST',
			success: function(res) {
                show_success('Succès','Enregistrement éffectué');
			}
		})
	})

    $(document).on('click', '.cl_edit_accompagnement', function(){
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
                var url = Routing.generate('restaurant_accompagnement_delete',{
                    id : id
                });

                $.ajax({
                    url: url,
                    type: 'GET',
                    datatype: 'json',
                    success: function(res) {
                        swal("Supprimé!", "Accompagnement supprimé", "success");
                        // show_info("Succés", 'Ré-assureur supprimé!','success');
                        load_list();
                    }
                })
            });

        } else {
            if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);
            show_editeur_accompagnement(id);
        }
    });

    function show_editeur_accompagnement(id)
    {
        id = typeof id !== 'undefined' ? id : 0;

        $.ajax({
            data: {
                id: id
            },
            type: 'POST',
            url: Routing.generate('restaurant_accompagnement_editor'),
            dataType: 'html',
            success: function(data) {
                show_modal(data,'Modification Accompagnement de plat');
                $('.summernote').summernote();
            }
        });
    }

    $(document).on('click','#id_save_accompagnement',function(){
        var nom = $('#id_nom').val().trim(),
            prix = $('#id_prix').val(),
            id = $('#id_accompagnement_edit').val(),
            description = $('#id_description').code()

        if (nom === '')
        {
            show_info('Erreur','Nom vide','error');
            return;
        }

        var data = {
            id : id,
            nom : nom,
            prix : prix,
            description : description,
            ajax: true
        }

        edit_accompagnement(0,data);
    });

    function edit_accompagnement(act, data)
    {
        $.ajax({
            data: data,
            type: 'POST',
            url: Routing.generate('restaurant_accompagnement_save'),
            dataType: 'html',
            success: function(data) {
                show_info('Succés','Modification bien enregistrée avec succés');
                close_modal();
                load_list();
            }
        });
    }


});