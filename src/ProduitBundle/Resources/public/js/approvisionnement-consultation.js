$(document).ready(function(){

	$(document).on('click', '#btn_search', function(event) {
		event.preventDefault()
		load_list();
		
	})
	load_list();
	var cl_row_edited = 'r-cl-edited';

	/*$(document).on('click', '.cl_show_appro', function() {
        var id = $(this).hasClass('cl_add') ? 0 : $(this).closest('tr').attr('id'),
            action = parseInt($(this).attr('data-type'));

        $('.'+cl_row_edited).removeClass(cl_row_edited);

        if (id !== 0) $(this).closest('tr').addClass(cl_row_edited);

        window.location.href = Routing.generate('produit_show', { id : id })
	})*/

    $(document).on('click', '.cl_show_appro', function (event) {
        event.preventDefault();
        window.location.href = Routing.generate('produit_approvisionnement_detail', { approId : $(this).closest('tr').attr('data-appro-id') });
        /*var url = Routing.generate('produit_approvisionnement_detail');
        var data = {
            appro : $(this).closest('tr').attr('data-appro-id'),
            ravitaillement : $(this).closest('tr').attr('data-ravitaillement-id')
        };
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'html',
            success: function(res) {
            }

        });*/
    })

	function load_list() {
		var data = {
			agence : $('#agence').val()
		};

		var url = Routing.generate('produit_approvisionnement_list');

		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(res) {
				$('#list_appro').html( res )
			}
		})
	}
});