$(document).on('change','#logo',function(event) {
  var file = document.querySelector('#logo').files[0];
  getBase64(file).then((src)=>{
      $('#logo_img').attr('src',src);
      $('#logo_agence').val(src);
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