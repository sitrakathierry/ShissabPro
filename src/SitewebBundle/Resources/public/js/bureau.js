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

	$(document).on('change','#bureau_logo',function(event) {
	  var file = document.querySelector('#bureau_logo').files[0];
	  getBase64(file).then((src)=>{
	      $('#bureau_img').attr('src',src);
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

	$(document).on('click', '#btn-save-bureau', function(event) {
		event.preventDefault();

		var data = {
			id_siteweb : $('#id_siteweb').val(),
			bureau_img : $('#bureau_img').attr('src'),
			nom : $('#nom').val(),
            adresse : $('#adresse').val(),
            tel : $('#tel').val(),
            email : $('#email').val(),
		};

		var url = Routing.generate('siteweb_bureau_save');

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
        var colNames = ['Image','Nom', 'Adresse', 'Tel', 'Email', ''];
        
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
            name:'adresse',
            index:'adresse',
            align: 'center',
        },
        { 
            name:'tel',
            index:'tel',
            align: 'center',
        },
        { 
            name:'email',
            index:'email',
            align: 'center',
        },
        {
            name:'action',
            index:'action',
            align: 'center',
            formatter: function(v){ 
                return '<button class="btn btn-xs btn-outline btn-primary edit_bureau " data-type="0"><i class="fa fa-pencil-square-o "></i>&nbsp;Afficher</button> &nbsp;'; 
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

        var tableau_grid = $('#list_bureau');

        if (tableau_grid[0].grid == undefined) {
            tableau_grid.jqGrid(options);
        } else {
            delete tableau_grid;
            $('#list_bureau').GridUnload('#list_bureau');
            tableau_grid = $('#list_bureau');
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
    	
        var url = Routing.generate('siteweb_bureau_list')
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

    $(document).on('click', '.edit_bureau', function(){
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        location.href = Routing.generate('siteweb_bureau_show', { id : id })
        
    });


});
