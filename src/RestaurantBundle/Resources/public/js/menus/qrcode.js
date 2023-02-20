var qrcode = new QRCode(document.getElementById("qrcode"), {
	width : 100,
	height : 100
});

var generate = $('#generate').val();

makeCode();

function makeCode () {		
	var lien = $('#lien_menu').text();
	qrcode.makeCode(lien);

}

$(document).on('click','#btn-download',function(event) {

	event.preventDefault();
	
	var img = $('#qrcode img').attr('src');

	var a = document.createElement("a"); //Create <a>
    a.href = img; //Image Base64 Goes here
    a.download = "qrcode-menu.png"; //File name Here
    a.click(); //Downloaded file
})