var cl_row_edited = 'r-cl-edited';

$(document).ready(function(){

	load_list();

    function instance_fournisseur_grid() {
        var colNames = ['Nom','Nom contact','Tel bureau','Tel mobile','Adresse','Email',''];
        var colModel = [{
                name    : 'nom',
                index   : 'nom',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-nom'
            },{
                name    : 'nom_contact',
                index   : 'nom_contact',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-nom_contact'
            },{
                name    : 'tel_bureau',
                index   : 'tel_bureau',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-tel_bureau'
            },{
                name    : 'tel_mobile',
                index   : 'tel_mobile',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-tel_mobile'
            },{
                name    : 'adresse',
                index   : 'adresse',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-adresse'
            },{
                name    : 'email',
                index   : 'email',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-email'
            },{
                name:'x',
                index:'x',
                align: 'center',
                formatter: function(v){ 
                    return '<i class="fa fa-pencil-square-o pointer edit_fournisseur" data-type="0" aria-hidden="true"></i>&nbsp;&nbsp;<i class="fa fa-trash-o pointer edit_fournisseur" data-type="2" aria-hidden="true"></i>' }
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

        var tableau_grid = $('#list_fournisseur');

        if (tableau_grid[0].grid == undefined) {
            tableau_grid.jqGrid(options);
        } else {
            delete tableau_grid;
            $('#list_fournisseur').GridUnload('#list_fournisseur');
            tableau_grid = $('#list_fournisseur');
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
		var url = Routing.generate('fournisseur_list')
		var data = {
		};

		$.ajax({
        	type: 'POST',
        	url: url,
        	data: data,
        	dataType: 'html',
        	success: function(res) {
        		var grid = instance_fournisseur_grid();
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
            nom_contact : $('#nom_contact').val(),
            tel_bureau : $('#tel_bureau').val(),
            tel_mobile : $('#tel_mobile').val(),
            adresse : $('#adresse').val(),
            email : $('#email').val(),
		};
		var url = Routing.generate('fournisseur_save');
		$.ajax({
			url: url,
			data: data,
			type: 'POST',
			success: function(res) {
                show_success('Succès','Enregistrement éffectué');
			}
		})
	})

    $(document).on('click', '.edit_fournisseur', function(){
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
                var url = Routing.generate('fournisseur_delete',{
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
            show_editeur_fournisseur(id);
        }
    });

    function show_editeur_fournisseur(id)
    {
        id = typeof id !== 'undefined' ? id : 0;

        $.ajax({
            data: {
                id: id
            },
            type: 'POST',
            url: Routing.generate('fournisseur_editor'),
            dataType: 'html',
            success: function(data) {
                show_modal(data,'Fournisseur');
            }
        });
    }

    $(document).on('click','#id_save_fournisseur',function(){
        var nom = $('#id_nom').val().trim(),
            id = $('#id_fournisseur_edit').val(),
            nom_contact = $('#id_nom_contact').val(),
            tel_bureau = $('#id_tel_bureau').val(),
            tel_mobile = $('#id_tel_mobile').val(),
            adresse = $('#id_adresse').val(),
            email = $('#id_email').val();

        if (nom === '')
        {
            show_info('Erreur','Nom vide','error');
            return;
        }

        var data = {
            id : id,
            nom : nom,
            nom_contact : nom_contact,
            tel_bureau : tel_bureau,
            tel_mobile : tel_mobile,
            adresse : adresse,
            email : email,
            ajax: true
        }

        edit_fournisseur(0,data);
    });

    function edit_fournisseur(act, data)
    {
        $.ajax({
            data: data,
            type: 'POST',
            url: Routing.generate('fournisseur_save'),
            dataType: 'html',
            success: function(data) {
                show_info('Succés','Modification bien enregistrée avec succés');
                close_modal();
                load_list();
            }
        });
    }


});