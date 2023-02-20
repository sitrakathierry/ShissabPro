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
          var options = "<option value=''></option>";

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

$('#date').datepicker({
      todayBtn: "linked",
      keyboardNavigation: false,
      forceParse: false,
      calendarWeeks: true,
      autoclose: true,
      format: 'dd/mm/yyyy',
      language: 'fr',
});
$("#date").datepicker('setDate', new Date());

$(document).on('click','#btn-save',function(event) {
	event.preventDefault();
	
	var data = {
		date : $('#date').val(),
		operation : $('#operation').val(),
		num_operation : $('#num_operation').val(),
		type_operation : $('#type_operation').val(),
		compte_bancaire : $('#compte_bancaire').val(),
		montant : $('#montant').val(),
		op_nom : $('#op_nom').val(),
	};

	if (data.type_operation == '' || data.montant == '' || data.operation == '' || data.operation == '' || data.compte_bancaire == '') {
		show_info('Attention','Champs obligatoire','warning');
		return;
	}

	var url = Routing.generate('comptabilite_mouvement_save');

	disabled_confirm(false); 

	swal({
        title: "Enregistrer",
        text: "Voulez-vous vraiment enregistrer ? ",
        type: "info",
        showCancelButton: true,
        confirmButtonText: "Oui",
        cancelButtonText: "Non",
    },
    function () {
      disabled_confirm(true);
			
			$.ajax({
				url: url,
				data: data,
				type: 'POST',
				success: function(res) {
					show_success('Succès','Opération enregistré!')
				}
			})
      
  });

})

$('#banque').select2();