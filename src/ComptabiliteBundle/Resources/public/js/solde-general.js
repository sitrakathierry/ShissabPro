var cl_row_edited = 'r-cl-edited';

var datas = [];

$(document).ready(function(){

	load_list();

    function instance_list_grid() {
        var colNames = ['Date','Opération','N° opération','Type d\'opération','Banque','Compte bancaire','Personne concerné','Montant',''];
        var colModel = [{
                name    : 'date',
                index   : 'date',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-date'
            },{
                name    : 'operation',
                index   : 'operation',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-operation'
            },{
                name    : 'num_operation',
                index   : 'num_operation',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-num_operation'
            },{
                name    : 'type_operation',
                index   : 'type_operation',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-type_operation'
            },{
                name    : 'banque',
                index   : 'banque',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-banque'
            },{
                name    : 'compte_bancaire',
                index   : 'compte_bancaire',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-compte_bancaire'
            },{
                name    : 'op_nom',
                index   : 'op_nom',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-op_nom'
            },{
                name    : 'montant',
                index   : 'montant',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-montant',
                formatter: jq_number_format,
                unformat: jq_number_unformat
            },
            {
                name:'x',
                index:'x',
                align: 'center',
                formatter: function(v){ 
                    return '<i class="fa fa-pencil-square-o pointer cl_edit" data-type="0" aria-hidden="true"></i>&nbsp;&nbsp;<i class="fa fa-trash-o pointer cl_edit" data-type="2" aria-hidden="true"></i>' }
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

	function load_list(){
		var url = Routing.generate('comptabilite_mouvement_list')
		var data = {
            banque : $('#banque').val(),
			compte_bancaire : $('#compte_bancaire').val(),
			operation : $('#operation').val(),
			type_date : $('#type_date').val(),
			mois : $('#mois').val(),
			annee : $('#annee').val(),
			date_specifique : $('#date_specifique').val(),
			debut_date : $('#debut_date').val(),
			fin_date : $('#fin_date').val(),
		};

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
                        $(this).jqGrid("footerData", "set", {
                            date: "Montant total",
                            montant : $(this).jqGrid('getCol', 'montant', false, 'sum')
                        });

                        var rows = grid.getDataIDs();

                        rows.forEach(function(row,index) {
                            var item = grid.getRowData(rows[index]);
                            var operation = item['operation'];

                            if (operation == 'Retrait') {
                                grid.jqGrid('setRowData', rows[index], false, {
                                    'color'      : '#494a4a',
                                    'background' : '#ed55651a',
                                    'font-weight': 'bold',
                                })
                            } else {
                                grid.jqGrid('setRowData', rows[index], false, {
                                    'color'      : '#494a4a',
                                    'background' : '#2b99021a',
                                    'font-weight': 'bold',
                                })
                            }

                        })
                    }
                }).trigger('reloadGrid', [{
                    page: 1,
                    current: true
                }]);
        	}

		})
	}

    $(document).on('click','#btn_search',function(event) {
        event.preventDefault();
        load_list();
    })

	$(document).on('click','#btn-save',function(event) {
		event.preventDefault();

		var data = {
			libelle : $('#libelle').val(),
			code : $('#code').val(),
		};
		var url = Routing.generate('prestation_save');
		$.ajax({
			url: url,
			data: data,
			type: 'POST',
			success: function(res) {
				console.log('load');
				load_list();
			}
		})
	})

	$(document).on('click','.act_cash',function() {
		var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);
        // show_create_attestation(id);
        var url = Routing.generate('caisse_cash',{
            id: id
        });

        window.location.href = url;
	})

	$(document).on('click','.act_echeance',function() {
		var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);
        var url = Routing.generate('caisse_echeance',{
            id: id
        });

        window.location.href = url;
	})

    $(document).on('click', '.consulter', function(){
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
                var url = Routing.generate('prestation_delete',{
                    id : id
                });

                $.ajax({
                    url: url,
                    type: 'GET',
                    datatype: 'json',
                    success: function(res) {
                        swal("Supprimé!", "Préstation a été supprimé", "success");
                        load_list();
                    }
                })
            });

        } else {
            if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);
            // show_create_attestation(id);
            var url = Routing.generate('assurance_auto_show',{
	            id: id
	        });

	        window.location.href = url;
	        }
    });

    $('#banque').select2();


    $(document).on('change','#banque',function(event) {
		event.preventDefault();

		var id =  $(this).children("option:selected").attr('value');

		var url = Routing.generate('comptabilite_comptebancaire_list_by_banque',{
		    id_banque : id
		});

		var compte_selector = $('#compte_bancaire');
		compte_selector.html('');

		$.ajax({
	        url: url,
	        type: 'GET',
	        success : function(data) {
	          var options = "<option value='0'>Tous</option>";

	          if (data instanceof Array) {
	              $.each(data, function (index, item) {
	                  options += '<option data-id="'+ item.id +'" value="' + item.id + '">' + item.num_compte + '</option>';
	              });
	              compte_selector.append(options);
	              compte_selector.select2();
	          } else {
	              return 0;
	          }
	        }
	    })

	})


	// $('.input-datepicker').datepicker({
	//       todayBtn: "linked",
	//       keyboardNavigation: false,
	//       forceParse: false,
	//       calendarWeeks: true,
	//       autoclose: true,
	//       format: 'dd/mm/yyyy',
	//       language: 'fr',
	//   });


	$(document).on('change','#type_date',function(event) {

		var val = $(this).val();

		switch (val){
			case "0":
				$('.selector_mois').addClass('hidden');
				$('.selector_annee').addClass('hidden');
				$('.selector_fourchette').addClass('hidden');
				$('.selector_specifique').addClass('hidden');
				break;
			case "1":
				$('.selector_mois').addClass('hidden');
				$('.selector_annee').addClass('hidden');
				$('.selector_fourchette').addClass('hidden');
				$('.selector_specifique').addClass('hidden');
				break;
			case "2":
				$('.selector_mois').removeClass('hidden');
				$('.selector_annee').removeClass('hidden');
				$('.selector_fourchette').addClass('hidden');
				$('.selector_specifique').addClass('hidden');

				break;
			case "3":
				$('.selector_mois').addClass('hidden');
				$('.selector_annee').removeClass('hidden');
				$('.selector_fourchette').addClass('hidden');
				$('.selector_specifique').addClass('hidden');
				break;
			case "4":
				$('.selector_mois').addClass('hidden');
				$('.selector_annee').addClass('hidden');
				$('.selector_fourchette').addClass('hidden');
				$('.selector_specifique').removeClass('hidden');

				$('.input-datepicker').datepicker({
				      todayBtn: "linked",
				      keyboardNavigation: false,
				      forceParse: false,
				      calendarWeeks: true,
				      autoclose: true,
				      format: 'dd/mm/yyyy',
				      language: 'fr',
				  });
				break;

			case "5":
				$('.selector_mois').addClass('hidden');
				$('.selector_annee').addClass('hidden');
				$('.selector_specifique').addClass('hidden');
				$('.selector_fourchette').removeClass('hidden');
				$('.input-datepicker').datepicker({
			      todayBtn: "linked",
			      keyboardNavigation: false,
			      forceParse: false,
			      calendarWeeks: true,
			      autoclose: true,
			      format: 'dd/mm/yyyy',
			      language: 'fr',
				});
				break;
		}	

	})

    $(document).on('click','.cl_export',function() {
        // var extension = $(this).attr('data-format');

        var url = Routing.generate('comptabilite_solde_general_export');

        var params = ''
                + '<input type="hidden" name="datas" value="'+encodeURI(datas)+'">'

        $('#form_export').attr('action',url).html(params);
        $('#form_export')[0].submit();

    });

    $(document).on('click', '.cl_edit', function(){
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        if (action === 2) {

            swal({
                title: "SUPPRIMER",
                text: "Voulez-vous vraiment supprimer cette opération?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "OUI",
                closeOnConfirm: false
            }, function () {
                // swal("Deleted!", "Your imaginary file has been deleted.", "success");
                var url = Routing.generate('comptabilite_mouvement_delete',{
                    id : id
                });

                $.ajax({
                    url: url,
                    type: 'GET',
                    datatype: 'json',
                    success: function(res) {
                        swal("Supprimé!", "Opération supprimé", "success");
                        // show_info("Succés", 'Ré-assureur supprimé!','success');
                        load_list();
                    }
                })
            });

        } else {
            if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);
            show_editeur_mouvement(id);
        }
    });

    function show_editeur_mouvement(id)
    {
        id = typeof id !== 'undefined' ? id : 0;

        $.ajax({
            data: {
                id: id
            },
            type: 'POST',
            url: Routing.generate('comptabilite_mouvement_editor'),
            dataType: 'html',
            success: function(data) {
                show_modal(data,'Modification opération');
            }
        });
    }

    $(document).on('click','#id_save_mouvement',function(){
        var montant = $('#id_montant').val().trim(),
            id = $('#id_mouvement_edit').val(),
            op_nom = $('#id_op_nom').val(),
            num_operation = $('#id_num_operation').val();

        if (montant === '')
        {
            show_info('Erreur','Montant vide','error');
            return;
        }

        var data = {
            id : id,
            montant : montant,
            op_nom : op_nom,
            num_operation : num_operation,
            ajax: true
        }

        edit_mouvement(0,data);
    });

    function edit_mouvement(act, data)
    {
        $.ajax({
            data: data,
            type: 'POST',
            url: Routing.generate('comptabilite_mouvement_update'),
            dataType: 'html',
            success: function(data) {
                show_info('Succés','Modification bien enregistrée avec succés');
                close_modal();
                load_list();
            }
        });
    }


});