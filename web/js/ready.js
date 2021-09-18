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
