var cl_row_edited = 'r-cl-edited';

$(document).ready(function(){

	$('.summernote').summernote({
	  	toolbar: [
		    ['style', ['style']],
		    ['fontsize', ['fontsize']],
		    ['font', ['bold', 'italic', 'underline', 'clear']],
		    ['fontname', ['fontname']],
		    ['color', ['color']],
		    ['para', ['ul', 'ol', 'paragraph']],
		    ['height', ['height']],
		    ['table', ['table']]
	  	],
	  	onpaste: function (e) {
			    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');

			    e.preventDefault();

			    setTimeout( function(){
			        document.execCommand( 'insertText', false, bufferText );
			    }, 10 );
			}
	});

	$(document).on('change','#membre_logo',function(event) {
	  var file = document.querySelector('#membre_logo').files[0];
	  getBase64(file).then((src)=>{
	      $('#membre_img').attr('src',src);
	  });
	});

	function getBase64(file) {
	  return new Promise((resolve)=>{
	     var reader = new FileReader();
	     reader.readAsDataURL(file);
	     reader.onload = function () {
	       resolve(reader.result);
	     };
	     reader.onerror = function (error) {
	       resolve(false)
	     };
	  })
	}

	$(document).on('click', '#btn-save-membre', function(event) {
		event.preventDefault();

		var data = {
			id_siteweb : $('#id_siteweb').val(),
			membre_img : $('#membre_img').attr('src'),
			nom : $('#nom').val(),
			poste : $('#poste').val(),
		};

		var url = Routing.generate('siteweb_membre_save');

		$.ajax({
			url : url,
			type : 'POST',
			data : data,
			success : function (res) {
				show_info('Succès', 'Création éffectué');
				location.reload()
			}
		})

	});

	load_list();

	function instance_grid() {
        var colNames = ['Image','Nom', 'Poste', 'Statut',''];
        
        var colModel = [{ 
            name:'img',
            index:'img',
            align: 'center',
            formatter: function(v) {
                return '<img style="width: 50px;padding: 5px;" src="'+ v +'">';
            }
        },
        { 
            name:'nom',
            index:'nom',
            align: 'center',
        },
        { 
            name:'poste',
            index:'poste',
            align: 'center',
        },
        { 
            name:'desactive',
            index:'desactive',
            align: 'center',
            formatter: function(v) {
                if (v == 1) {
                    return 'Désactivé';
                }

                return 'Actif';
            }
        },
        {
            name:'action',
            index:'action',
            align: 'center',
            formatter: function(v){ 
                return '<button class="btn btn-xs btn-outline btn-primary edit_membre " data-type="0"><i class="fa fa-pencil-square-o "></i>&nbsp;Afficher</button> &nbsp;'; 
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

        var tableau_grid = $('#list_membre');

        if (tableau_grid[0].grid == undefined) {
            tableau_grid.jqGrid(options);
        } else {
            delete tableau_grid;
            $('#list_membre').GridUnload('#list_membre');
            tableau_grid = $('#list_membre');
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
    	
        var url = Routing.generate('siteweb_membre_list')
        var data = {
        	id_siteweb : $('#id_siteweb').val(),
        };

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
                    }
                }).trigger('reloadGrid', [{
                    page: 1,
                    current: true
                }]);
            }

        })
    
    }

    $(document).on('click', '.edit_membre', function(){
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        location.href = Routing.generate('siteweb_membre_show', { id : id })
        
    });


});
