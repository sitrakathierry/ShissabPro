var qrcode = new QRCode(document.getElementById("qrcode"), {
	width : 100,
	height : 100
});

$(document).on('input', '#code', function(event) {
	var data = event.target.value;
	makeCode(data)
})

function makeCode (data) {		
	qrcode.makeCode(data);
}