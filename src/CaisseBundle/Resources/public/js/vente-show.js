$(document).ready(function(){

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
			commande_id : $('#commande_id').val(),
			printer_name : $('#printer_name').val(),
		};

		var url = Routing.generate('caisse_commande_print_ticket');

		$.ajax({
			url : url,
			type : 'POST',
			data : data,
			success: function(res) {
				doPrinting(res.data);
			}
		});

	})
});