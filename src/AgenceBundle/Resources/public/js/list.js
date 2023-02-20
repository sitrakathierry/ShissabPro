var cl_row_edited = 'r-cl-edited';

$(document).ready(function(){

    let timeout;
    let password = document.getElementById('mdp_responsable');
    let strengthBadge = document.getElementById('password-strength');
    let strongPassword = new RegExp('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})')
    let mediumPassword = new RegExp('((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{6,}))|((?=.*[a-z])(?=.*[A-Z])(?=.*[^A-Za-z0-9])(?=.{8,}))')

    $(document).on('input', '#nom', function(event) {
        var nom = event.target.value;
        $('#nom_responsable').val(nom.toLowerCase().replace(/\s/g, ''));
    });

    generate_password.call( $('#mdp_responsable') );

    function generate_password() {
        var length = 8
        var numberChars = "0123456789";
        var upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        var lowerChars = "abcdefghijklmnopqrstuvwxyz";
        var specialChars = "!@-#$";

        var allChars = numberChars + upperChars + lowerChars + specialChars;
        var randPasswordArray = Array(length);
        randPasswordArray[0] = numberChars;
        randPasswordArray[1] = upperChars;
        randPasswordArray[2] = lowerChars;
        randPasswordArray[3] = specialChars;
        randPasswordArray = randPasswordArray.fill(allChars, 4);
        var password =  shuffle_array(randPasswordArray.map(function(x) { return x[Math.floor(Math.random() * x.length)] })).join('');
        
        $(this).val(password);
        password_checker();
    }

    function shuffle_array(array) {
      for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
      }
      return array;
    }

    
    
    function strength_checker(password) {
        if(strongPassword.test(password)) {
            strengthBadge.style.backgroundColor = "green";
            strengthBadge.textContent = 'Forte';
        } else if(mediumPassword.test(password)) {
            strengthBadge.style.backgroundColor = 'orange';
            strengthBadge.textContent = 'Moyen';
        } else {
            strengthBadge.style.backgroundColor = 'red';
            strengthBadge.textContent = 'Faible';
        }
    }

    function password_checker() {
        strengthBadge.style.display = 'block';
        clearTimeout(timeout);
        timeout = setTimeout(() => strength_checker(password.value), 500);
        if(password.value.length !== 0) {
            strengthBadge.style.display != 'block';
        } else {
            strengthBadge.style.display = 'none';
        }
    }

    $(document).on('input', '#mdp_responsable', function(event) {
        password_checker() 
    })


    // password.addEventListener("input", () => {});



	load_list();

	function instance_grid() {
        var colNames = ['Nom','Région', 'Nb compte autorisé', ''];
        
        var colModel = [{ 
            name:'nom',
            index:'nom',
            align: 'center' 
        },
        { 
            name:'region',
            index:'region',
            align: 'center'
        },
        { 
            name:'capacite',
            index:'capacite',
            align: 'center'
        },
        {
            name:'action',
            index:'action',
            align: 'center',
            formatter: function(v){ 
                return '<button class="btn btn-xs btn-outline btn-primary cl_edit_agence " data-type="0"><i class="fa fa-pencil-square-o "></i>&nbsp;Afficher</button> &nbsp;&nbsp; <button class="btn btn-xs btn-outline btn-warning list_agents " data-type="0"><i class="fa fa-users "></i>&nbsp;Agents</button>'; 
                // return '<i class="fa fa-pencil-square-o pointer cl_edit_agence" data-type="0" aria-hidden="true"></i>&nbsp;&nbsp;<i class="fa fa-trash-o pointer cl_edit_agence" data-type="2" aria-hidden="true"></i>' 
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

        var tableau_grid = $('#list_agence');

        if (tableau_grid[0].grid == undefined) {
            tableau_grid.jqGrid(options);
        } else {
            delete tableau_grid;
            $('#list_agence').GridUnload('#list_agence');
            tableau_grid = $('#list_agence');
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
    	
        var url = Routing.generate('agence_get_list')
        var data = {};

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'html',
            success: function(res) {
                $('.cl_list_societe').removeClass('hidden');
                var grid = instance_grid();
                grid.jqGrid('setGridParam', {
                    data        : $.parseJSON(res),
                    loadComplete: function() {
                        $('#1').hide()
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
			nom : $('#nom').val(),
			region : $('#region').val(),
            adresse : $('#adresse').val(),
            tel : $('#tel').val(),
            capacite : $('#capacite').val(),
            nom_responsable : $('#nom_responsable').val(),
            email_responsable : $('#email_responsable').val(),
            mdp_responsable : $('#mdp_responsable').val(),
            responsabilite : $('#responsabilite').val(),
		}

		var url = Routing.generate('agence_save');

		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(res) {
				show_info('SUCCESS','Agence enregistré enregistré');
				// load_list();
                location.reload();
				// body...
			}
		})
	})

    $(document).on('click', '.list_agents', function(event) {
        event.preventDefault();
        var agence_id = $(this).closest('tr').attr('id');
        var href = Routing.generate('user_agence_list', { agence_id: agence_id });
        window.location.href = href;

    })


	$(document).on('click', '.cl_edit_agence', function(){
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
                var url = Routing.generate('agence_delete',{
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
            url: Routing.generate('agence_editor'),
            dataType: 'html',
            success: function(data) {
                show_modal(data,'Modification Société');
            }
        });
    }

    $(document).on('click','#id_save_agence',function(){
        var nom = $('#id_nom').val(),
            region = $('#id_region').val(),
            code = $('#id_code').val(),
            id = $('#id_agence_edit').val(),
            capacite = $('#id_capacite').val();

        // if (designation === '')
        // {
        //     show_info('Erreur','Désignation vide','error');
        //     return;
        // }

        var data = {
            id : id,
            nom : nom,
            region : region,
            code : code,
            capacite: capacite,
            ajax: true
        }

        edit_type(0,data);
    });

    function edit_type(act, data)
    {
        $.ajax({
            data: data,
            type: 'POST',
            url: Routing.generate('agence_save'),
            dataType: 'html',
            success: function(data) {
                show_info('Succés','Modification enregistrée');
                close_modal();
                load_list();
            }
        });
    }


});