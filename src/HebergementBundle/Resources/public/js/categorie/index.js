var cl_row_edited = 'r-cl-edited';

$(document).ready(function(){

    $(document).on('change','.petit_dejeuner',function(event) {
        event.preventDefault();

        var petit_dejeuner = $(this).children('option:selected').val();

        if (petit_dejeuner == 2) {
            $(this).closest('tr').find('.supplementaire').removeClass('hidden');
        } else {
            $(this).closest('tr').find('.supplementaire').addClass('hidden');
        }
    })


    $(document).on('click', '.btn-add-row', function(event) {
        event.preventDefault();

        var id = $('#id-row').val();

        var new_id = Number(id) + 1;

        var a = '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control nb_pers" name="nb_pers[]" required=""></div></div></td>';
        var b = '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control montant" name="montant[]" required=""></div></div></td>';
        var c = '<td><div class="form-group"><div class="col-sm-12"><select class="form-control petit_dejeuner" name="petit_dejeuner[]"><option value="1" selected="">INCLUS</option><option value="2">SUPPLÉMENTAIRE</option></select></div></div><div class="form-group supplementaire hidden"><div class="col-sm-12"><input type="number" class="form-control montant_petit_dejeuner" name="montant_petit_dejeuner[]" required=""></div></div></td>';
        var d = '<td><button class="btn btn-danger btn-full-width btn-remove-tr"><i class="fa fa-trash"></i></button></td>';
        
        var markup = '<tr data-id="'+ new_id +'">' + a + b + c + d + '</tr>';
        $("#table-tarif-add tbody").append(markup);
        $('#id-row').val(new_id);

    });

    $(document).on('click','.btn-remove-tr',function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
    });

    $('.summernote').summernote();

	load_list();

    function instance_categorie_grid() {
        var colNames = ['Type','Nom','Caractéristiques',''];
        var colModel = [{
                name    : 'type',
                index   : 'type',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-type'
            },{
                name    : 'nom',
                index   : 'nom',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-nom'
            },{
                name    : 'caracteristiques',
                index   : 'caracteristiques',
                align   : 'left',
                editable: false,
                sortable: false,
                classes : 'js-caracteristiques'
            },{
                name:'x',
                index:'x',
                align: 'center',
                formatter: function(v){ 
                    return '<i class="fa fa-pencil-square-o pointer cl_edit_categorie" data-type="0" aria-hidden="true"></i>&nbsp;&nbsp;<i class="fa fa-trash-o pointer cl_edit_categorie" data-type="2" aria-hidden="true"></i>' }
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

        var tableau_grid = $('#list_categorie');

        if (tableau_grid[0].grid == undefined) {
            tableau_grid.jqGrid(options);
        } else {
            delete tableau_grid;
            $('#list_categorie').GridUnload('#list_categorie');
            tableau_grid = $('#list_categorie');
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
		var url = Routing.generate('hebergement_categorie_list')
		var data = {
		};

		$.ajax({
        	type: 'POST',
        	url: url,
        	data: data,
        	dataType: 'html',
        	success: function(res) {
        		var grid = instance_categorie_grid();
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
            type : $('#type').val(),
            caracteristiques : $('#caracteristiques').val(),
            description : $('#description').code(),
            nb_pers : toArray('nb_pers'),
            montant : toArray('montant'),
            petit_dejeuner : toArray('petit_dejeuner'),
            montant_petit_dejeuner : toArray('montant_petit_dejeuner')
		};
		var url = Routing.generate('hebergement_categorie_save');
		$.ajax({
			url: url,
			data: data,
			type: 'POST',
			success: function(res) {
                show_success('Succès','Enregistrement éffectué');
			}
		})
	})

    $(document).on('click', '.cl_edit_categorie', function(){
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        if (action === 2) {

            swal({
                title: "SUPPRIMER",
                text: "Voulez-vous vraiment supprimer la type?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "OUI",
                closeOnConfirm: false
            }, function () {
                // swal("Deleted!", "Your imaginary file has been deleted.", "success");
                var url = Routing.generate('hebergement_categorie_delete',{
                    id : id
                });

                $.ajax({
                    url: url,
                    type: 'GET',
                    datatype: 'json',
                    success: function(res) {
                        swal("Supprimé!", "La type a été supprimé", "success");
                        // show_info("Succés", 'Ré-assureur supprimé!','success');
                        load_list();
                    }
                })
            });

        } else {
            if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);
            show_editeur_categorie(id);
        }
    });

    function show_editeur_categorie(id)
    {
        id = typeof id !== 'undefined' ? id : 0;

        var url = Routing.generate('hebergement_categorie_show', { id : id });

        window.location.href = url;

        // $.ajax({
        //     data: {
        //         id: id
        //     },
        //     type: 'POST',
        //     url: Routing.generate('hebergement_categorie_editor'),
        //     dataType: 'html',
        //     success: function(data) {
        //         show_modal(data,'Modification type de chambre');
        //         $('.summernote').summernote();
        //     }
        // });
    }

    $(document).on('click','#id_save_categorie',function(){
        var nom = $('#id_nom').val().trim(),
            id = $('#id_categorie_edit').val(),
            type = $('#id_type').val(),
            description = $('#id_description').code(),
            caracteristiques = $('#id_caracteristiques').val()

        if (nom === '')
        {
            show_info('Erreur','Nom vide','error');
            return;
        }

        var data = {
            id : id,
            nom : nom,
            type : type,
            description : description,
            caracteristiques : caracteristiques,
            ajax: true
        }

        edit_categorie(0,data);
    });

    function edit_categorie(act, data)
    {
        $.ajax({
            data: data,
            type: 'POST',
            url: Routing.generate('hebergement_categorie_save'),
            dataType: 'html',
            success: function(data) {
                show_info('Succés','Modification bien enregistrée avec succés');
                close_modal();
                load_list();
            }
        });
    }

    function toArray(selector, type = 'default') {
        var taskArray = new Array();
        $("." + selector).each(function() {

            if (type == 'summernote') {
                taskArray.push($(this).code());
            } else {
                taskArray.push($(this).val());
            }

        });
        return taskArray;
    }


});