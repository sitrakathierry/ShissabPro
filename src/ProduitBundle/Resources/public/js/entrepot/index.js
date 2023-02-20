var cl_row_edited = 'r-cl-edited';

$(document).ready(function(){

	load_list();

    function instance_entrepot_grid() {
        var colNames = ['Nom','Adresse','Tél',''];
        var colModel = [{
                name    : 'nom',
                index   : 'nom',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-nom'
            },{
                name    : 'adresse',
                index   : 'adresse',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-adresse'
            },{
                name    : 'tel',
                index   : 'tel',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-tel'
            },{
                name:'x',
                index:'x',
                align: 'center',
                formatter: function(v){ 
                    return '<i class="fa fa-pencil-square-o pointer edit_entrepot" data-type="0" aria-hidden="true"></i>&nbsp;&nbsp;<i class="fa fa-trash-o pointer edit_entrepot" data-type="2" aria-hidden="true"></i>' }
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

        var tableau_grid = $('#list_entrepot');

        if (tableau_grid[0].grid == undefined) {
            tableau_grid.jqGrid(options);
        } else {
            delete tableau_grid;
            $('#list_entrepot').GridUnload('#list_entrepot');
            tableau_grid = $('#list_entrepot');
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
		var url = Routing.generate('entrepot_list')
		var data = {
		};

		$.ajax({
        	type: 'POST',
        	url: url,
        	data: data,
        	dataType: 'html',
        	success: function(res) {
        		var grid = instance_entrepot_grid();
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
            adresse : $('#adresse').val(),
            tel : $('#tel').val(),
		};
		var url = Routing.generate('entrepot_save');
		$.ajax({
			url: url,
			data: data,
			type: 'POST',
			success: function(res) {
                show_success('Succès','Enregistrement éffectué');
			}
		})
	})

    $(document).on('click', '.edit_entrepot', function(){
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
                var url = Routing.generate('entrepot_delete',{
                    id : id
                });

                $.ajax({
                    url: url,
                    type: 'GET',
                    datatype: 'json',
                    success: function(res) {
                        swal("Supprimé!", "Catégorie supprimé", "success");
                        // show_info("Succés", 'Ré-assureur supprimé!','success');
                        load_list();
                    }
                })
            });

        } else {
            if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);
            show_editeur_entrepot(id);
        }
    });

    function show_editeur_entrepot(id)
    {
        id = typeof id !== 'undefined' ? id : 0;

        $.ajax({
            data: {
                id: id
            },
            type: 'POST',
            url: Routing.generate('entrepot_editor'),
            dataType: 'html',
            success: function(data) {
                show_modal(data,'Entrepot');
            }
        });
    }

    $(document).on('click','#id_save_entrepot',function(){
        var nom = $('#id_nom').val().trim(),
            id = $('#id_entrepot_edit').val(),
            adresse = $('#id_adresse').val(),
            tel = $('#id_tel').val();

        if (nom === '')
        {
            show_info('Erreur','Nom vide','error');
            return;
        }

        var data = {
            id : id,
            nom : nom,
            adresse : adresse,
            tel : tel,
            ajax: true
        }

        edit_entrepot(0,data);
    });

    function edit_entrepot(act, data)
    {
        $.ajax({
            data: data,
            type: 'POST',
            url: Routing.generate('entrepot_save'),
            dataType: 'html',
            success: function(data) {
                show_info('Succés','Modification bien enregistrée avec succés');
                close_modal();
                load_list();
            }
        });
    }


});