var cl_row_edited = 'r-cl-edited';

$(document).ready(function(){

	$('.summernote').summernote();

	$(document).on('change','#slider',function(event) {
	  var file = document.querySelector('#slider').files[0];
	  getBase64(file).then((src)=>{
	      $('#slider_img').attr('src',src);
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

	$(document).on('click', '#btn-save-slider', function(event) {
		event.preventDefault();

		var data = {
			titre : $('#titre').val(),
			sous_titre : $('#sous_titre').val(),
			slider_img : $('#slider_img').attr('src'),
		};

		var url = Routing.generate('restaurant_slider_save');

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
        var colNames = ['Image','Titre', 'Sous titre', ''];
        
        var colModel = [{ 
            name:'img',
            index:'img',
            align: 'center',
            formatter: function(v) {
                return '<img style="width: 50px;padding: 5px;" src="'+ v +'">';
            }
        },
        { 
            name:'titre',
            index:'titre',
            align: 'center',
        },
        { 
            name:'sous_titre',
            index:'sous_titre',
            align: 'center',
        },
        {
            name:'action',
            index:'action',
            align: 'center',
            formatter: function(v){ 
                return '<button class="btn btn-xs btn-outline btn-primary edit_slider " data-type="0"><i class="fa fa-pencil-square-o "></i>&nbsp;Afficher</button> &nbsp;'; 
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

        var tableau_grid = $('#list_slider');

        if (tableau_grid[0].grid == undefined) {
            tableau_grid.jqGrid(options);
        } else {
            delete tableau_grid;
            $('#list_slider').GridUnload('#list_slider');
            tableau_grid = $('#list_slider');
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
    	
        var url = Routing.generate('restaurant_slider_list')
        var data = {
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

    $(document).on('click', '.edit_slider', function(){
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        location.href = Routing.generate('restaurant_slider_show', { id : id })
        
    });


});
