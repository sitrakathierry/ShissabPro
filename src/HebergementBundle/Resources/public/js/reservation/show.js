$('.clockpicker').clockpicker();

$('#data_1 .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true,
    format: 'dd/mm/yyyy',
    language: 'fr',

});

$(document).on('click','#btn-cancel',function(event) {
	event.preventDefault();

	var modal = $('#modal-annulation').data('template');

	show_modal(modal,'ANNULATION ET REMBOURSEMENT')
});

$(document).on('input','#pourcentage_remboursement',function(event) {
	var pourcentage = event.target.value;

	var total = $('#reservation_total').val();

	var montant = (Number(total) * Number(pourcentage)) / 100;

	$('#montant_remboursement').val(montant);
});

$(document).on('click','#id_save_annulationt', function(event) {
	event.preventDefault();

	var data = {
		id : $('#id').val(),
		pourcentage_remboursement : $('#pourcentage_remboursement').val(),
		montant_remboursement : $('#montant_remboursement').val(),
	};


	var url = Routing.generate('hebergement_reservation_annuler');

	disabled_confirm(false); 

	swal({
        title: "Annuler",
        text: "Voulez-vous vraiment annuler ? ",
        type: "info",
        showCancelButton: true,
        confirmButtonText: "Oui",
        cancelButtonText: "Non",
    },
    function () {
    	disabled_confirm(true);
			
		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			success: function(res) {
				show_success('Succès', 'Résevration annulé');
			}
		});
      
	});
})

$(document).on('click','#id_save_modele_pdf',function(event) {
  event.preventDefault();

  var data = {
    id : $('#id').val(),
    f_modele_pdf : $('#f_modele_pdf').val(),
  };

  var url = Routing.generate('hebergement_reservation_modele_pdf_save');

  $.ajax({
      data: data,
      type: 'POST',
      url: url,
      success: function(data) {
        var route = Routing.generate('hebergement_reservation_pdf', { id : data.id });
        window.open(route, '_blank');
      }
  });

});

$(document).on('click', '#btn-modal-print', function(event) {
  event.preventDefault();

  var data = {
    id : $('#id').val(),
    objet : 6,
  };

  var url = Routing.generate('hebergement_reservation_pdf_editor');

  $.ajax({
      data: data,
      type: 'POST',
      url: url,
      dataType: 'html',
      success: function(data) {
          show_modal(data,'Modèle Impression');
      }
  });

});

$(document).on('click','#btn-confirmer',function(event) {
	event.preventDefault();

	var id = $('#id').val();

	var url = Routing.generate('hebergement_reservation_confirmer', { id : id });

	disabled_confirm(false); 

  	swal({
	        title: "Confirmer",
	        text: "Voulez-vous vraiment confirmer ? ",
	        type: "success",
	        showCancelButton: true,
	        confirmButtonText: "Oui",
	        cancelButtonText: "Non",
	    },
	    function () {
	    	disabled_confirm(true);
				
			$.ajax({
				url: url,
				type: 'GET',
				success: function(res) {
					show_success('Succès', 'Résevration confirmé');
				}
			});
	      
  	});
})

$(document).on('click','#btn_search',function(event) {
	event.preventDefault();

	var data = {
		nb_pers : $('#nb_pers').val(),
		categorie : $('#categorie').val(),
		date_entree : $('#date_entree').val(),
		date_sortie : $('#date_sortie').val(),
	};

	if (data.date_entree == '' || data.date_sortie == '' || data.nb_pers == '') {
		show_info('Erreur','Champs obligatoire','error');
		return;
	}

	var url = Routing.generate('hebergement_chambre_search');

	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		success: function(res) {
			$('#chambres_disponibles').html(res.template)
			$('#reservation_nb_nuit').val(res.nb_nuit)
		}
	});

});

$(document).on('click','.btn-choisir',function(event) {
	event.preventDefault();

	var id = $(this).data('id');
	var nb_pers = $('#nb_pers').val();
	var nb_nuit = $('#reservation_nb_nuit').val()

	var data = {
		id : id,
		nb_pers : nb_pers
	};

	var url = Routing.generate('hebergement_chambre_tarif');

	$.ajax({
		url : url,
		type : 'POST',
		data: data,
		success: function(res) {
			var nom = res.chambre;
			var montant = Number(res.montant);
			var petit_dejeuner = res.petit_dejeuner;
			var montant_petit_dejeuner = Number(res.montant_petit_dejeuner);

			$('#reservation_chambre_nom').html(nom);
			$('#reservation_nb_pers').val(nb_pers);
			$('#chambre_id').val(id);
			$('#montant').val(montant);
			$('#reservation_avec_petit_dejeuner').val(petit_dejeuner).change();

			if (petit_dejeuner == 1) {
				$('#montant_petit_dejeuner').attr('readonly','readonly');
				$('#reservation_total').val(montant);
			} else {
				$('#montant_petit_dejeuner').removeAttr('readonly');
				$('#montant_petit_dejeuner').val(montant_petit_dejeuner);
				$('#reservation_total').val(montant + montant_petit_dejeuner);

			}

		}
	})
})

$(document).on('change','#reservation_avec_petit_dejeuner',function(event) {
	event.preventDefault();

	var avec_petit_dejeuner = $(this).children("option:selected").val();
	var nb_pers = $('#reservation_nb_pers').val();
	var montant_petit_dejeuner = $('#montant_petit_dejeuner').val();
	var reservation_total = $('#reservation_total').val();
	var nb_nuit = $('#reservation_nb_nuit').val();

	// var total = (Number( nb_pers ) * Number( tarif_pers ) ) * Number ( nb_nuit );;

	if (avec_petit_dejeuner == 1) {
		// total += (Number( nb_pers ) * Number( tarif_pers_petit_dejeuner )) * Number ( nb_nuit );;
		var total = Number( reservation_total ) - Number( montant_petit_dejeuner );
		$('#montant_petit_dejeuner').attr('readonly','readonly');
	} else {
		var total = Number( reservation_total ) + Number( montant_petit_dejeuner );
		$('#montant_petit_dejeuner').removeAttr('readonly');
	}

	$('#reservation_total').val(total);

});

$(document).on('click','#btn-save',function(event) {
	event.preventDefault();

	var data = {
		id : $('#id').val(),
		nb_pers : $('#nb_pers').val(),
		date_entree : $('#date_entree').val(),
		date_sortie : $('#date_sortie').val(),
		avec_petit_dejeuner : $('#reservation_avec_petit_dejeuner').val(),
		total : $('#reservation_total').val(),
		chambre_id : $('#chambre_id').val(),
		montant_petit_dejeuner : $('#montant_petit_dejeuner').val(),
		reservation_nb_nuit : $('#reservation_nb_nuit').val(),
		montant : $('#montant').val(),
		heure_entree : $('#heure_entree').val(),
		heure_sortie : $('#heure_sortie').val(),
		nom_client : $('#nom_client').val(),
		tel_client : $('#tel_client').val(),
		client : $('#client').val(),
		statut : $('#statut').val(),
	};

	var url = Routing.generate('hebergement_reservation_save');

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
				type: 'POST',
				data: data,
				success: function(res) {
					show_success('Succès', 'Résevration enregistré');
				}
			});
	      
  	});
});

$(document).on('click','#btn-start',function(event) {
	event.preventDefault();

	var	id = $('#id').val();

	var url = Routing.generate('hebergement_reservation_start', { id : id });

	disabled_confirm(false); 

  	swal({
	        title: "Commencer",
	        text: "Voulez-vous vraiment commencer cette réservation ? ",
	        type: "success",
	        showCancelButton: true,
	        confirmButtonText: "Oui",
	        cancelButtonText: "Non",
	    },
	    function () {
	    	disabled_confirm(true);
				
			$.ajax({
				url: url,
				type: 'GET',
				success: function(res) {
					show_success('Succès', 'Cette réservation commence maintenant');
				}
			});
	      
  	});

})

$(document).on('click','#btn-terminer',function(event) {
	event.preventDefault();

	var	id = $('#id').val();

	var url = Routing.generate('hebergement_reservation_terminer', { id : id });

	disabled_confirm(false); 

  	swal({
	        title: "Terminer",
	        text: "Voulez-vous vraiment terminer cette réservation et de passer eu paiement ? ",
	        type: "success",
	        showCancelButton: true,
	        confirmButtonText: "Oui",
	        cancelButtonText: "Non",
	    },
	    function () {
	    	disabled_confirm(true);
				
			$.ajax({
				url: url,
				type: 'GET',
				success: function(res) {
					show_success('Succès', 'Reservation terminé', Routing.generate('hebergement_caisse_cash', { id : id }));
				}
			});
	      
  	});

});

$(document).on('click','#btn-cancel-nights',function(event) {
	event.preventDefault();

	var id = $('#id').val();

	var url = Routing.generate('hebergement_reservation_cancel_night_editor', { id : id });

	$.ajax({
		url : url,
		type : 'GET',
		success: function(tpl) {
			show_modal(tpl,'ANNULER DES NUITS');

			$('#cancel_date_sortie,#cancel_date_calcul').datepicker({
			    todayBtn: "linked",
			    keyboardNavigation: false,
			    forceParse: false,
			    calendarWeeks: true,
			    autoclose: true,
			    format: 'dd/mm/yyyy',
			    language: 'fr',

			});

			$('#cancel_nb_nuit').trigger('input');
		}
	});
	
});

$(document).on('click','#btn_save_cancel_nights',function(event) {
	event.preventDefault();

	$(this).prop('disabled',true);

	var data = {
		booking : $('#id').val(),
		nb_nuit : $('#cancel_nb_nuit').val(),
		pourcentage : $('#cancel_pourcentage_remboursement').val(),
		montant : $('#cancel_montant_remboursement').val(),
		ancien_date_sortie : $('#date_sortie').val(),
		nouveau_date_sortie : $('#cancel_date_sortie').val(),
	};

	var url = Routing.generate('hebergement_reservation_cancel_night_save');

	$.ajax({
		url : url,
		type: 'POST',
		data: data,
		success: function(res) {
			show_success('Succès','Modification éffectué');
		}
	})

});

$(document).on('input','#cancel_nb_nuit', function(event) {
	min_max.call(this);
	var nb_nuit = event.target.value;
	var nb_nuit_total = $('#cancel_nb_nuit_total').val();
	var date_calcul = $("#cancel_date_calcul").datepicker('getDate');
	var new_nb_nuit = Number( nb_nuit_total ) - Number( nb_nuit )
  date_calcul.setDate(date_calcul.getDate() + new_nb_nuit  );

  var date_fin = new Date(date_calcul);

  $("#cancel_date_sortie").datepicker('setDate', date_fin);
});

$(document).on('input','#cancel_pourcentage_remboursement',function(event) {
	var pourcentage = event.target.value;

	var total = $('#reservation_total').val();

	var montant = (Number(total) * Number(pourcentage)) / 100;

	$('#cancel_montant_remboursement').val(montant);
});

function min_max(){
	var el = this;
  if(el.value != ""){
    if(parseInt(el.value) < parseInt(el.min)){
      el.value = el.min;
    }
    if(parseInt(el.value) > parseInt(el.max)){
      el.value = el.max;
    }
  }
}

var clientPrinters = null;
var _this = this;

function initJspm() {
  //WebSocket settings
  JSPM.JSPrintManager.auto_reconnect = true;
  JSPM.JSPrintManager.start();
  JSPM.JSPrintManager.WS.onStatusChanged = function () {
      if (jspmWSStatus()) {
          //get client installed printers
          JSPM.JSPrintManager.getPrinters().then(function (printersList) {
              clientPrinters = printersList;
              var options = '';
              for (var i = 0; i < clientPrinters.length; i++) {
                  options += '<option>' + clientPrinters[i] + '</option>';
              }
              $('#printer_name').html(options);
          });
      }
  };
}

//Check JSPM WebSocket status
function jspmWSStatus() {
    if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open)
        return true;
    else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Closed) {
        console.warn('JSPrintManager (JSPM) is not installed or not running! Download JSPM Client App from https://neodynamic.com/downloads/jspm');
        return false;
    }
    else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Blocked) {
        alert('JSPM has blocked this website!');
        return false;
    }
}

//Do printing...
function doPrinting(data) {
    if (jspmWSStatus()) {

        // Gen sample label featuring logo/image, barcode, QRCode, text, etc by using JSESCPOSBuilder.js

        var escpos = Neodynamic.JSESCPOSBuilder;
        
        var doc = new escpos.Document()

        
        .font(escpos.FontFamily.A)
        .align(escpos.TextAlignment.Center)
        .style([escpos.FontStyle.Bold])
        .size(1, 1)
        .setCharacterCodeTable(16)
      	.setEncoding(1252)
        .text(data['agence'])
        .font(escpos.FontFamily.B)
        .size(0, 0)
        .newLine()
        .text(data['adresse'])
        .newLine()
      	.text(data['tel'])
        .newLine()
        .text(data['recu'])
        .newLine()
        .text(data['type'])
        .newLine()
        .qrCode(data['qrcode'], new escpos.BarcodeQROptions(escpos.QRLevel.L, 6))
        .align(escpos.TextAlignment.LeftJustification)
        .newLine()
        .text(data['date'])
        .text('----------------------------------------')
        .newLine()
        .drawTable(data['thead'])
        .generateUInt8Array();

        for (var i = 0; i < data['tbody'].length; i++) {
        	var __doc = new escpos.Document()
            .newLine()
            .font(escpos.FontFamily.B)
        		.size(0, 0)
        		.setCharacterCodeTable(16)
        		.setEncoding(1252)
            .drawTable( data['tbody'][i] )
            .generateUInt8Array();

            doc = new Uint8Array([ ...doc, ...__doc ]);

        }

        var __doc = new escpos.Document()
        .newLine()
        .font(escpos.FontFamily.B)
        .setCharacterCodeTable(16)
      	.setEncoding(1252)
        .drawTable( data["tfoot"] )
        .newLine()
        .drawTable(data['caissier'])
        .feed(5)
        .cut()
        .generateUInt8Array();

        var escposCommands = new Uint8Array([ ...doc, ...__doc ]);
        var cpj = new JSPM.ClientPrintJob();
        var myPrinter = new JSPM.InstalledPrinter($('#printer_name').val());

        cpj.clientPrinter = myPrinter;
        cpj.binaryPrinterCommands = escposCommands;
        cpj.sendToClient();
        // JSPM.JSPrintManager.stop();
    }
}

$(document).on('click', '#print-ticket', function(event) {
	event.preventDefault();
	var template = $('#print-ticket-template').data('template');
  show_modal(template,'Impression reçu');
  initJspm();

});


$(document).on('click', '#print', function(event) {
	event.preventDefault();

	var data = {
		id : $('#id').val(),
		printer_name : $('#printer_name').val(),
	};

	var url = Routing.generate('hebergement_reservation_ticket');

	$.ajax({
		url : url,
		type : 'POST',
		data : data,
		success: function(res) {
			doPrinting(res.data);
		}
	});

});

$(document).on('click','#btn-delete',function(event) {
	event.preventDefault();

	var id = $('#id').val();
	var url = Routing.generate('hebergement_reservation_delete', { id : id });

	disabled_confirm(false); 

	swal({
        title: "Annuler",
        text: "Voulez-vous vraiment supprimer ? ",
        type: "info",
        showCancelButton: true,
        confirmButtonText: "Oui",
        cancelButtonText: "Non",
    },
    function () {
    	disabled_confirm(true);
			
			$.ajax({
				url: url,
				type: 'GET',
				success: function(res) {
					show_success('Succès', 'Résevration supprimé', Routing.generate('hebergement_homepage'));
				}
			});
      
	});

})