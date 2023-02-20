/**
 * Created by SITRAKA on 16/01/2017.
 */
var lastsel = null,
    isReady = false,
    chargement = true;

$(document).ready(function(){
    show_loading(false);
    isReady = true;
});

function show_loading(actif)
{
    actif = typeof actif !== 'undefined' ? actif : true;
    if (actif && chargement) $('body').loadingModal({text: 'Chargement...'});
    else $('body').loadingModal('destroy');
}

/**
 * verrou et progressbar
 */
$(document).ajaxStart(function(){
    show_loading(true);
});
$(document).ajaxStop(function(){
    show_loading(false);
});

function verrou_fenetre(verrou)
{
    if(verrou) show_loading(true);
    else show_loading(false);
}

function show_info(titre, message, type, timeout) {
    type = typeof type === 'undefined' ? 'success' : type;
    timeout = typeof timeout !== 'undefined' ? timeout : 5000;
    setTimeout(function () {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: timeout,
            preventDuplicates: true
        };
        if (type === 'success') toastr.success(message, titre);
        if (type === 'warning') toastr.warning(message, titre);
        if (type === 'error') toastr.error(message, titre);
        if (type === 'info') toastr.info(message, titre);
    }, 500);
}

$('.input-datepicker').datepicker({
      todayBtn: "linked",
      keyboardNavigation: false,
      forceParse: false,
      calendarWeeks: true,
      autoclose: true,
      format: 'dd/mm/yyyy',
      language: 'fr',
});

function show_success(titre, message, url = 'reload') {
    show_info(titre,message);

    setTimeout(function() {
        if (url == 'reload') {
            location.reload();
        } else {
            window.location.href = url
        }
    },2000)

}

function disabled_confirm(disabled = true) {
    $(".confirm").prop('disabled', disabled); 
}

function clean_pasted_html(input) {
  // 1. remove line breaks / Mso classes
  var stringStripper = /(\n|\r| class=(")?Mso[a-zA-Z]+(")?)/g;
  var output = input.replace(stringStripper, ' ');
  // 2. strip Word generated HTML comments
  var commentSripper = new RegExp('<!--(.*?)-->','g');
  var output = output.replace(commentSripper, '');
  var tagStripper = new RegExp('<(/)*(meta|link|span|\\?xml:|st1:|o:|font)(.*?)>','gi');
  // 3. remove tags leave content if any
  output = output.replace(tagStripper, '');
  // 4. Remove everything in between and including tags '<style(.)style(.)>'
  var badTags = ['style', 'script','applet','embed','noframes','noscript'];

  for (var i=0; i< badTags.length; i++) {
    tagStripper = new RegExp('<'+badTags[i]+'.*?'+badTags[i]+'(.*?)>', 'gi');
    output = output.replace(tagStripper, '');
  }
  // 5. remove attributes ' style="..."'
  var badAttributes = ['style', 'start'];
  for (var i=0; i< badAttributes.length; i++) {
    var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"','gi');
    output = output.replace(attributeStripper, '');
  }
  return output;
}