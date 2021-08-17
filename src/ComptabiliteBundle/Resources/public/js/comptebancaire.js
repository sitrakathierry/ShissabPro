var cl_row_edited = 'r-cl-edited';
var datas = [];
$(document).ready(function(){

	load_list();

    function instance_comptebancaire_grid() {
        var colNames = ['Banque','Numéro de compte','Solde',''];
        var colModel = [{
                name    : 'banque',
                index   : 'banque',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-banque'
            },{
                name    : 'num_compte',
                index   : 'num_compte',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-num_compte'
            },{
                name    : 'solde',
                index   : 'solde',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-solde',
                formatter: jq_number_format,
                unformat: jq_number_unformat
            },{
                name:'x',
                index:'x',
                align: 'center',
                formatter: function(v){ 
                    return '<i class="fa fa-pencil-square-o pointer cl_edit_comptebancaire" data-type="0" aria-hidden="true"></i>&nbsp;&nbsp;<i class="fa fa-trash-o pointer cl_edit_comptebancaire" data-type="2" aria-hidden="true"></i>' }
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
            footerrow : true,
        };

        var tableau_grid = $('#list_comptebancaire');

        if (tableau_grid[0].grid == undefined) {
            tableau_grid.jqGrid(options);
        } else {
            delete tableau_grid;
            $('#list_comptebancaire').GridUnload('#list_comptebancaire');
            tableau_grid = $('#list_comptebancaire');
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

    $( "#a_rechercher" ).on( "keydown", function( event ) {
      if (event.which === 13) {
        load_list();
      }
    });

	function load_list(){
		var url = Routing.generate('comptabilite_comptebancaire_get_list')
		var data = {
            recherche_par : $('#recherche_par').val(),
            a_rechercher : $('#a_rechercher').val()
		};

		$.ajax({
        	type: 'POST',
        	url: url,
        	data: data,
        	dataType: 'html',
        	success: function(res) {
                datas = res;
        		var grid = instance_comptebancaire_grid();
                grid.jqGrid('setGridParam', {
                    data        : $.parseJSON(res),
                    loadComplete: function() {
                        $(this).jqGrid("footerData", "set", {
                            banque: "Total des soldes",
                            solde : $(this).jqGrid('getCol', 'solde', false, 'sum')
                        });
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
			banque : $('#banque').val(),
            num_compte : $('#num_compte').val(),
            solde : $('#solde').val(),
		};
		var url = Routing.generate('comptabilite_comptebancaire_save');
		$.ajax({
			url: url,
			data: data,
			type: 'POST',
			success: function(res) {
                clear_form();
				load_list();
			}
		})
	})

    function clear_form() {
         $('#banque').val('');
        $('#num_compte').val('');
        $('#solde').val('');
    }
    
    $(document).on('click', '.cl_edit_comptebancaire', function(){
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        if (action === 2) {

            swal({
                title: "SUPPRIMER",
                text: "Voulez-vous vraiment supprimer la comptebancaire?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "OUI",
                closeOnConfirm: false
            }, function () {
                var url = Routing.generate('comptabilite_comptebancaire_delete',{
                    id : id
                });

                $.ajax({
                    url: url,
                    type: 'GET',
                    datatype: 'json',
                    success: function(res) {
                        swal("Supprimé!", "La comptebancaire a été supprimé", "success");
                        load_list();
                    }
                })
            });

        } else {
            if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);
            show_editeur_comptebancaire(id);
        }
    });

    function show_editeur_comptebancaire(id)
    {
        id = typeof id !== 'undefined' ? id : 0;

        $.ajax({
            data: {
                id: id
            },
            type: 'POST',
            url: Routing.generate('comptabilite_comptebancaire_editor'),
            dataType: 'html',
            success: function(data) {
                show_modal(data,'Modification comptebancaire');
            }
        });
    }

    $(document).on('click','#id_save_comptebancaire',function(){
        var nom = $('#id_nom').val().trim(),
            id = $('#id_comptebancaire_edit').val();

        if (nom === '')
        {
            show_info('Erreur','Nom vide','error');
            return;
        }

        var data = {
            id : id,
            nom : nom,
            ajax: true
        }

        edit_comptebancaire(0,data);
    });

    function edit_comptebancaire(act, data)
    {
        $.ajax({
            data: data,
            type: 'POST',
            url: Routing.generate('comptabilite_comptebancaire_save'),
            dataType: 'html',
            success: function(data) {
                show_info('Succés','Modification bien enregistrée avec succés');
                close_modal();
                load_list();
            }
        });
    }

    $(document).on('click','#btn_search',function(event) {
        
        load_list();
    })

    $(document).on('click','.cl_export',function() {

        var url = Routing.generate('comptabilite_comptebancaire_export');

        var params = ''
                + '<input type="hidden" name="datas" value="'+encodeURI(datas)+'">'

        $('#form_export').attr('action',url).html(params);
        $('#form_export')[0].submit();

    })


});