$(document).ready(function(){

	$(document).on('change','.portion_accompagnement',function(event) {
		event.preventDefault();

		var portion = Number( $(this).children('option:selected').val() );

		$(this).closest('tr').find('.qte_accompagnement').val(portion).trigger('input');
	})

	$(document).on('input','.qte_accompagnement',function(event) {

		var qte_plat = $(this).closest('table .table-accompagnement').parent('td').closest('tr').find('.qte').val();

		var accompagnement_supp = $(this).closest('table .table-accompagnement').parent('td').find('.accompagnement_supp').val();

		var qte = Number(event.target.value);

		var tr = $(this).closest('table .table-accompagnement').find('tbody > tr');
		var total_qte = 0;
		tr.each(function (index,tr) {
           var qte_accompagnement = $(this).find(".qte_accompagnement").val();

           total_qte += Number( qte_accompagnement );
		});

		var prix = $(this).closest('tr').find('.accompagnement').children('option:selected').data('prix');
		var qte_a_payer = 0;

		if (total_qte <= qte_plat) {
			prix = 0;
			$(this).closest('table .table-accompagnement').parent('td').find('.accompagnement_supp').val('0');
		} else {

			if (accompagnement_supp == 0) {
				qte_a_payer = total_qte - qte_plat;
			} else {
				qte_a_payer = qte;
			}

			$(this).closest('table .table-accompagnement').parent('td').find('.accompagnement_supp').val('1');
		}


		$(this).closest('tr').find('.prix_accompagnement').val(prix * qte_a_payer);

		calculTotalAccompagnement.call( this );
		
	});


	function calculTotalAccompagnement() {

        var total_accompagnement = 0;

		var tr = $(this).closest('table .table-accompagnement').find('tbody > tr');

        tr.each(function(index, tr) { 
           var montant_selector = $(this).find(".prix_accompagnement");

           var montant = montant_selector.val();

           total_accompagnement += Number(montant);
          
        });

        console.log(total_accompagnement)

       	$(this).closest('table .table-accompagnement').find('tfoot > tr').find('.total_accompagnement').val(total_accompagnement);


		var qte_plat = $(this).closest('table .table-accompagnement').parent('td').closest('tr').find('.qte').val();
		var prix_plat = $(this).closest('table .table-accompagnement').parent('td').closest('tr').find('.prix').val();

		var total = ( Number(qte_plat) * Number(prix_plat) ) + total_accompagnement;

		$(this).closest('table .table-accompagnement').parent('td').closest('tr').find('.total').val(total);

		calculTotal();


    }

	$(document).on('click', '.btn-add-row-accompagnement', function(event) {
        event.preventDefault();

        var accompagnement_options = $('.accompagnement').html();

       	var a = '<td><div class="form-group"><div class="col-sm-12"><select class="form-control select2 accompagnement" name="accompagnement[]"><option></option>'+ accompagnement_options +'</select></div></div></td>';
       	var b = '<td><div class="form-group"><div class="col-sm-12"><select class="option form-control portion_accompagnement"><option value="0"></option><option value="1">1 portion</option><option value="0.5">1/2 portion</option></select><input type="number" class="hidden qte_accompagnement" name="qte_accompagnement[]" placeholder="Portion"></div></div></td>';
       	var c = '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control prix_accompagnement" name="prix_accompagnement[]" placeholder="Prix"></div></div></td>';
       	var d = '<td><button class="btn btn-white btn-full-width btn-remove-tr-accompagnement"><i class="fa fa-trash"></i></button></td>';


        var markup = '<tr">' + a + b + c + d + '</tr>';

        $(this).closest('table .table-accompagnement').find('tbody').append(markup);

        $('.select2').select2();

    });

    $(document).on('click','.btn-remove-tr-accompagnement',function(event) {
		event.preventDefault();

		var prev = $(this).closest('tr').prev();
		
		$(this).closest('tr').remove();
		
		calculTotalAccompagnement.call(prev);

		calculTotal();
	});

	function accompagnement_details() {
		var data = [];

		$('table#table-emporter-add > tbody  > tr').each(function(index, tr) { 
           
           var table = $(this).find('table.table-accompagnement  > tbody');
           var accompagnement = selToArray(table.find('.accompagnement'));
           var qte_accompagnement = selToArray(table.find('.qte_accompagnement'));
           var prix_accompagnement = selToArray(table.find('.prix_accompagnement'));
           var total_accompagnement = $(this).find('.total_accompagnement').val();

           var item = {
           		accompagnement : accompagnement,
				qte_accompagnement : qte_accompagnement,
				prix_accompagnement : prix_accompagnement,
				total_accompagnement : total_accompagnement,
           };

           data.push(item);

        });

        return data;
	}

	$(document).on('click','.btn-credit-heb',function(event) {
		event.preventDefault();
		var id = $(this).data('id');
		var type = $(this).data('type');

		var url = Routing.generate('restaurant_credit_valider', { id : id, type : type });

		disabled_confirm(false); 

		swal({
		        title: "CREDIT",
		        text: "Voulez-vous vraiment valider ? ",
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
						show_success('Succès','Crédit validé');
					}
				})
		      
		});
	})

	$(document).on('click','#btn-payer',function(event) {
		event.preventDefault();
		var id = $('#id').val();

		var url = Routing.generate('restaurant_emporter_payer', { id : id });

		disabled_confirm(false); 

		swal({
		        title: "Payer",
		        text: "Voulez-vous vraiment payer ? ",
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
						show_success('Succès','Paiement éffectué');
					}
				})
		      
		});
	})

	$(document).on('click','.btn-remove-tr',function(event) {
		event.preventDefault();
		$(this).closest('tr').remove();
		calculTotal();
	});

	$(document).on('click','#btn-terminer',function(event) {
		event.preventDefault();

		var id = $('#id').val();

		var url = Routing.generate('restaurant_emporter_terminer', { id : id });

		disabled_confirm(false); 

		swal({
		        title: "PASSER À LA CAISSE",
		        text: "Voulez-vous vraiment passer ce commande à la caisse ? ",
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
						show_success('Succès','Commande terminé');
					}
				})
		      
		});
	})

	$(document).on('click','#btn-save',function (event) {
		event.preventDefault();

		var data = {
			id : $('#id').val(),
			booking : $('#booking').val(),
			montant_total : $('#montant_total').val(),
			statut: 1,
			plat : toArray('plat'),
			qte : toArray('qte'),
			prix : toArray('prix'),
			total : toArray('total'),
			cuisson : toArray('cuisson'),
			statut_detail : toArray('statut_detail'),
			accompagnement_details : accompagnement_details()
		};

		var url = Routing.generate('restaurant_emporter_save');

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
						show_success('Succès','Commande enregistré');
					}
				})
		      
		  });

	});

	$('.select2').select2();

	let timeout;


	$(document).on('change','.plat',function(event) {
		event.preventDefault();

		var _tr = $(this).closest('tr');
		var prixvente = $(this).children('option:selected').data('prixvente');
		var qte = $(_tr).find('.qte').val();
		
		$(_tr).find('.prix').val(prixvente);

        var total = Number( prixvente ) * Number( qte );

        $(_tr).find('.total').val( Number(total) );

        calculTotal();


	});

	$(document).on('input','.qte',function(event) {

		var _tr = $(this).closest('tr');
		var prixvente = $(_tr).find('.prix').val();
		var qte = event.target.value;

        var total = Number( prixvente ) * Number( qte );

        $(_tr).find('.total').val( Number(total) );

        calculTotal();


	});

	$(document).on('input','.prix',function(event) {

		var _tr = $(this).closest('tr');
		var prixvente = event.target.value;
		var qte = $(_tr).find('.qte').val();

        var total = Number( prixvente ) * Number( qte );

        $(_tr).find('.total').val( Number(total) );

        calculTotal();

	});

	$(document).on('click', '.btn-add-row', function(event) {
        event.preventDefault();

        
        var id = $('#id-row').val();

        var new_id = Number(id) + 1;
        
        var tables_options = $('.tables').html();
        var plat_options = $('.plat').html();
        var cuisson_options = $('.cuisson').html();
        var accompagnement_options = $('.accompagnement').html();

        var a = '<td><div class="form-group"><div class="col-sm-12"><select class="form-control select2 plat" name="plat[]">'+ plat_options +'</select></div></div></td>';
        var b = '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control qte" name="qte[]" ></div></div></td>';
        var c = '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control prix" name="prix[]" ></div></div></td>';
        var d = '<td><div class="form-group"><div class="col-sm-12"><select class="form-control cuisson" name="cuisson[]"><option></option>' + cuisson_options + '</select></div></div></td>';
        var e = '<td><input type="hidden" class="accompagnement_supp" value="0"><table class="table table-bordered table-accompagnement"><tbody><tr><td><div class="form-group"><div class="col-sm-12"><select class="form-control select2 accompagnement" name="accompagnement[]"><option></option>'+ accompagnement_options +'</select></div></div></td><td><div class="form-group"><div class="col-sm-12"><select class="option form-control portion_accompagnement"><option value="0"></option><option value="1">1 portion</option><option value="0.5">1/2 portion</option></select><input type="number" class="hidden qte_accompagnement" name="qte_accompagnement[]" placeholder="Portion"></div></div></td><td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control prix_accompagnement" name="prix_accompagnement[]" placeholder="Prix"></div></div></td><td><button class="btn btn-white btn-full-width btn-remove-tr-accompagnement"><i class="fa fa-trash"></i></button></td></tr></tbody><tfoot><tr><td colspan="2"></td><td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control total_accompagnement" name="total_accompagnement[]" placeholder="Total" readonly=""></div></div></td><td><button class="btn btn-white btn-full-width btn-add-row-accompagnement" data-id="0"><i class="fa fa-plus"></i></button></td></tr></tfoot></table></td>';
        var f = '<td><div class="form-group"><div class="col-sm-12"><input type="number" class="form-control total" name="total[]" ></div></div></td>';
        var g = '<td><button class="btn btn-danger btn-full-width btn-remove-tr"><i class="fa fa-trash"></i></button><input type="hidden" class="statut_detail" value="1"></td>';


        var markup = '<tr data-id="'+ new_id +'">' + a + b + c + d + e + f + g + '</tr>';
        $("#table-emporter-add tbody#principal").append(markup);
        $('#id-row').val(new_id);

        $('.select2').select2();

        calculTotal();


    });

    $(document).on('click', '.btn-remove-row', function(event) {
        event.preventDefault();
        var produits = [];
        var isFind = false;

        var id     = parseInt($('#id-row').val());
        var new_id = id - 1;
        if (new_id >= 0) {
            $('#id-row').val(new_id);
            $('#table-emporter-add tbody tr:last').remove();
        } else {
            show_info("Attention", 'Le tableau devrait contenir au moins une ligne','error');
        }
        calculTotal();

    });

    var montant_total = 0;


    function calculTotal() {

        montant_total = 0;

        $('table#table-emporter-add > tbody  > tr').each(function(index, tr) { 
           var montant_selector = $(this).find(".total");

           var montant = montant_selector.val();

           montant_total += Number(montant);

           $('#montant_total').val(montant_total);
          
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

    function selToArray(selector, type = 'default') {
        var taskArray = new Array();
        $(selector).each(function() {

            if (type == 'summernote') {
                taskArray.push($(this).code());
            } else {
                taskArray.push($(this).val());
            }

        });
        return taskArray;
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
            .generateUInt8Array();

            // description
            if (data['description']) {
            	var __doc = new escpos.Document()
            	.font(escpos.FontFamily.B)
	            .align(escpos.TextAlignment.Center)
	            .style([escpos.FontStyle.Bold])
	            .size(0, 0)
	            .setCharacterCodeTable(16)
	            .setEncoding(1252)
	            .text(data['description'])
            	.generateUInt8Array();

            	doc = new Uint8Array([ ...doc, ...__doc ]);
            }

            // adresse
            if (data['adresse']) {
            	var __doc = new escpos.Document()
            	.font(escpos.FontFamily.B)
	            .align(escpos.TextAlignment.Center)
	            .style([escpos.FontStyle.Bold])
	            .size(0, 0)
	            .setCharacterCodeTable(16)
	            .setEncoding(1252)
	            .text(data['adresse'])
            	.generateUInt8Array();

            	doc = new Uint8Array([ ...doc, ...__doc ]);
            }

            // tel
            if (data['tel']) {
            	var __doc = new escpos.Document()
            	.font(escpos.FontFamily.B)
	            .align(escpos.TextAlignment.Center)
	            .style([escpos.FontStyle.Bold])
	            .size(0, 0)
	            .setCharacterCodeTable(16)
	            .setEncoding(1252)
	            .text(data['tel'])
            	.generateUInt8Array();

            	doc = new Uint8Array([ ...doc, ...__doc ]);
            }

            var __doc = new escpos.Document()
            .font(escpos.FontFamily.B)
            .size(0, 0)
            .newLine()
            .text(data['recu'])
            .text(data['type'])
            // .newLine()
            // .qrCode(data['qrcode'], new escpos.BarcodeQROptions(escpos.QRLevel.L, 6))
            .align(escpos.TextAlignment.LeftJustification)
            .newLine()
            .text(data['date'])
            .text('----------------------------------------')
            .newLine()
            .drawTable(data['thead'])
            .generateUInt8Array();

            for (var i = 0; i < data['tbody'].length; i++) {
            	var __doc = new escpos.Document()
                // .newLine()
                .font(escpos.FontFamily.B)
            	.size(0, 0)
            	.setCharacterCodeTable(16)
        		.setEncoding(1252)
                .drawTable( data['tbody'][i] )
                .generateUInt8Array();

                doc = new Uint8Array([ ...doc, ...__doc ]);

            }

            if (data['statut'][1] == "Non Payé") {
            	var __doc = new escpos.Document()
            	.newLine()
	            .font(escpos.FontFamily.B)
	            .setCharacterCodeTable(16)
	        	.setEncoding(1252)
	            .drawTable( data["tfoot"] )
	            // .newLine()
	            .drawTable(data['caissier'])
	            // .newLine()
	            .drawTable(data['statut'])
	            .feed(5)
	            .cut()
	            .generateUInt8Array();
            } else {
            	var __doc = new escpos.Document()
            	.newLine()
	            .font(escpos.FontFamily.B)
	            .setCharacterCodeTable(16)
	        	.setEncoding(1252)
	            .drawTable( data["tfoot"] )
	            // .newLine()
	            .drawTable( data["montant_recu"] )
	            // .newLine()
	            .drawTable( data["montant_rendu"] )
	            // .newLine()
	            .drawTable(data['caissier'])
	            // .newLine()
	            .drawTable(data['statut'])
	            .feed(5)
	            .cut()
	            .generateUInt8Array();
            }

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

		var url = Routing.generate('restaurant_emporter_ticket');

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

        var url = Routing.generate('restaurant_emporter_delete', { id : $('#id').val() });

        disabled_confirm(false); 

        swal({
              title: "SUPPRIMER",
              text: "Voulez-vous vraiment supprimer ? ",
              type: "error",
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
                    show_success('Succès', 'Suppression éffectué', Routing.generate('restaurant_emporter_index'));
                }
            });
        });
    });
});