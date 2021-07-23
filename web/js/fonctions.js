/**
 * MODAL Bootstrap
 */
$(document).on('click', '.js_close_modal', function () {
    close_modal();
});
$('#modal').on('hidden.bs.modal', function () {
    close_modal();
});
function close_modal() {
    $('#modal-body').empty();
    $('#modal').modal('hide');
}

/**
 * Modal boostrap
 *
 * @param contenu
 * @param titre
 * @param animated
 * @param size
 */
function show_modal(contenu, titre, animated, size) {
    //size
    size = typeof size !== 'undefined' ? size : '';
    $('#modal-size').removeClass('modal-lg');
    $('#modal-size').addClass(size);

    //animation
    $('#modal-animated').addClass('modal-content animated ' + animated);

    //header
    $('#modal-header').html(titre);

    //content
    $('#modal-body').html(contenu);

    //show modal
    $('#modal').modal('show');
    $('.modal-dialog').draggable({ handle:'.deplacer'});
}

var lastsel = null;
var isReady = false;
var chargement = true;

$(document).ready(function(){
    show_loading(false);
    isReady = true;
});

function show_loading(actif)
{
    if(chargement) {
        actif = typeof actif !== 'undefined' ? actif : true;
        if (actif) $('body').loadingModal({text: 'Chargement...'});
        else $('body').loadingModal('destroy');
    }
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

function test_security(response)
{
    if(response.trim().toLowerCase() === 'security') location.reload();
}

function set_table_jqgrid(mydata, height, colNames, colModel, table, caption, width, editurl, rownumbers, rowNum, grouping, groupingView, firstSort, firtsColSorter, shrinkToFit, userdata, autoContent,sortable) {
    var id_table = table.attr('id');
    $('#' + id_table).after('<table id="' + id_table + '_temp"></table>')
        .jqGrid("clearGridData")
        .jqGrid('GridDestroy')
        .remove();
    $('#' + id_table + '_pager').remove();
    $('#' + id_table + '_temp').attr('id', id_table);
    $('#' + id_table).after('<table id="' + id_table + '_temp"></table>')
        .after('<div id="' + id_table + '_pager"></div>');

    var id_pager = '';
    if (typeof rowNum !== 'undefined') id_pager = "#" + id_table + '_pager';
    else rowNum = 1000000;

    grouping = (typeof grouping === 'undefined') ? false : grouping;
    groupingView = (typeof groupingView === 'undefined') ? null : groupingView;
    rownumbers = (typeof rownumbers === 'undefined') ? true : rownumbers;
    shrinkToFit = (typeof shrinkToFit === 'undefined') ? true : shrinkToFit;
    autoContent = (typeof autoContent === 'undefined') ? false : autoContent;
    sortable = (typeof sortable === 'undefined') ? false : sortable;

    var footerRow = (typeof userdata !== 'undefined'),
        userDataOnFooter = (typeof userdata !== 'undefined');
    userdata = (typeof userdata !== 'undefined') ? userdata : [];


    if (typeof editurl === 'undefined') editurl = '';

    var current_jqgrid = $('#' + id_table).jqGrid({
        edit:false,add:false,del:false,
        data: mydata,
        datatype: "local",
        rownumbers: rownumbers,
        firstsortorder: 'asc',
        height: height,
        width: width,
        autowidth: false,
        shrinkToFit: shrinkToFit,
        rowNum: rowNum,
        rowList: [20, 50, 100, rowNum],
        colNames: colNames,
        colModel: colModel,
        viewrecords: true,
        caption: caption,
        hidegrid: true,
        pager: id_pager,
        editurl: editurl,
        grouping: grouping,
        groupingView: groupingView,
        ajaxRowOptions: {async: true},
        footerrow: footerRow,
        userDataOnFooter: userDataOnFooter,
        userdata:userdata,
        sortable: sortable,
        scroll:1,
        loadonce:true,
        //frozenStaticCols : true,
        onSelectRow: function (id) {
            if (typeof lastsel !== 'undefined')
                if (id) {
                    $('#' + id_table).restoreRow(lastsel).editRow(id, true);
                    lastsel = id;
                }
            //specifique pour chaque tableau
            if (typeof jqGridOnSelectRow === "function") jqGridOnSelectRow($(this).find('#' + id));

            //action after save
            if (typeof jqGridAfterSave === "function") {
                var self = $(this);
                var savedRows = self.jqGrid("getGridParam", "savedRow");
                if (savedRows.length > 0) self.jqGrid("restoreRow", savedRows[0].id);

                self.jqGrid("editRow", id, {
                    keys: true,
                    aftersavefunc: function (id) {
                        jqGridAfterSave('#' + id_table);
                    }
                });
            }
        },
        beforeSelectRow: function (rowid, e) {
            var target = $(e.target);
            var cell_action = target.hasClass('js-entite-action');
            var item_action = (target.closest('td').children('.js_jqgrid_save_row').length > 0);
            return !(cell_action || item_action);
        },
        aftersavefunc: function () {
            //alert('test');
        },
        loadComplete: function () {
            if(autoContent)
            {
                var $this = $(this), iCol, iRow, rows, row, cm, colWidth,
                    $cells = $this.find(">tbody>tr>td"),
                    $colHeaders = $(this.grid.hDiv).find(">.ui-jqgrid-hbox>.ui-jqgrid-htable>thead>.ui-jqgrid-labels>.ui-th-column>div"),
                    colModel = $this.jqGrid("getGridParam", "colModel"),
                    n = $.isArray(colModel) ? colModel.length : 0,
                    idColHeadPrexif = "jqgh_" + this.id + "_";

                $cells.wrapInner("<span class='mywrapping'></span>");
                $colHeaders.wrapInner("<span class='mywrapping'></span>");

                for (iCol = 0; iCol < n; iCol++) {
                    cm = colModel[iCol];
                    colWidth = $("#" + idColHeadPrexif + $.jgrid.jqID(cm.name) + ">.mywrapping").outerWidth() + 25; // 25px for sorting icons
                    for (iRow = 0, rows = this.rows; iRow < rows.length; iRow++) {
                        row = rows[iRow];
                        if ($(row).hasClass("jqgrow"))
                        {
                            colWidth = Math.max(colWidth, $(row.cells[iCol]).find(".mywrapping").outerWidth());
                        }
                    }
                    $this.jqGrid("setColWidth", iCol, colWidth - 13);
                }
                $this.jqGrid('setGridWidth',width - 20);
            }

            if(typeof loadCompleteJQgrid == 'function') loadCompleteJQgrid();
        }
    });

    if (typeof firstSort !== 'undefined') {
        current_jqgrid.sortGrid(firtsColSorter);
        if (firstSort == 'asc') current_jqgrid.sortGrid(firtsColSorter);
    }
    if (caption == 'hidden') $('#gview_' + id_table + ' div.ui-jqgrid-caption').remove();

    //current_jqgrid.jqGrid('setFrozenColumns');
}

/**
 *
 * @param titre
 * @param message
 * @param type
 * @param timeout
 */
function show_info(titre, message, type, timeout) {
    type = typeof type === 'undefined' ? 'success' : type;
    timeout = typeof timeout !== 'undefined' ? timeout : 5000;
    setTimeout(function () {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: timeout
        };
        if (type === 'success') toastr.success(message, titre);
        if (type === 'warning') toastr.warning(message, titre);
        if (type === 'error') toastr.error(message, titre);
        if (type === 'info') toastr.info(message, titre);
    }, 500);
}

/**
 *
 * @param number
 * @param decimals
 * @param dec_point
 * @param thousands_sep
 * @returns {*}
 */
function number_format(number, decimals, dec_point, thousands_sep) {
    if (number === 0) return '';
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k).toFixed(prec);
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }

    return s.join(dec);
}

function number_to_letter(nombre) {
    var letter = {
        0 : "zéro",
        1 : "un",
        2 : "deux",
        3 : "trois",
        4 : "quatre",
        5 : "cinq",
        6 : "six",
        7 : "sept",
        8 : "huit",
        9 : "neuf",
        10: "dix",
        11: "onze",
        12: "douze",
        13: "treize",
        14: "quatorze",
        15: "quinze",
        16: "seize",
        17: "dix-sept",
        18: "dix-huit",
        19: "dix-neuf",
        20: "vingt",
        30: "trente",
        40: "quarante",
        50: "cinquante",
        60: "soixante",
        70: "soixante-dix",
        80: "quatre-vingt",
        90: "quatre-vingt-dix",
    };
        
    var i, j, n, quotient, reste, nb;
    var ch
    var numberToLetter = '';
    //__________________________________

    if (nombre.toString().replace(/ /gi, "").length > 15) return "dépassement de capacité";
    if (isNaN(nombre.toString().replace(/ /gi, ""))) return "Nombre non valide";

    nb = parseFloat(nombre.toString().replace(/ /gi, ""));
    //if (Math.ceil(nb) != nb) return "Nombre avec virgule non géré.";
    if(Math.ceil(nb) != nb){
        nb = nombre.toString().split('.');
        return number_to_letter(nb[0]) + " virgule " + number_to_letter(nb[1]);
    }

    n = nb.toString().length;
    switch (n) {
        case 1:
            numberToLetter = letter[nb];
            break;
        case 2:
            if (nb > 19) {
                quotient = Math.floor(nb / 10);
                reste = nb % 10;
                if (nb < 71 || (nb > 79 && nb < 91)) {
                    if (reste == 0) numberToLetter = letter[quotient * 10];
                    if (reste == 1) numberToLetter = letter[quotient * 10] + "-et-" + letter[reste];
                    if (reste > 1) numberToLetter = letter[quotient * 10] + "-" + letter[reste];
                } else numberToLetter = letter[(quotient - 1) * 10] + "-" + letter[10 + reste];
            } else numberToLetter = letter[nb];
            break;
        case 3:
            quotient = Math.floor(nb / 100);
            reste = nb % 100;
            if (quotient == 1 && reste == 0) numberToLetter = "cent";
            if (quotient == 1 && reste != 0) numberToLetter = "cent" + " " + number_to_letter(reste);
            if (quotient > 1 && reste == 0) numberToLetter = letter[quotient] + " cents";
            if (quotient > 1 && reste != 0) numberToLetter = letter[quotient] + " cent " + number_to_letter(reste);
            break;
        case 4 :
        case 5 :
        case 6 :
            quotient = Math.floor(nb / 1000);
            reste = nb - quotient * 1000;
            if (quotient == 1 && reste == 0) numberToLetter = "mille";
            if (quotient == 1 && reste != 0) numberToLetter = "mille" + " " + number_to_letter(reste);
            if (quotient > 1 && reste == 0) numberToLetter = number_to_letter(quotient) + " mille";
            if (quotient > 1 && reste != 0) numberToLetter = number_to_letter(quotient) + " mille " + number_to_letter(reste);
            break;
        case 7:
        case 8:
        case 9:
            quotient = Math.floor(nb / 1000000);
            reste = nb % 1000000;
            if (quotient == 1 && reste == 0) numberToLetter = "un million";
            if (quotient == 1 && reste != 0) numberToLetter = "un million" + " " + number_to_letter(reste);
            if (quotient > 1 && reste == 0) numberToLetter = number_to_letter(quotient) + " millions";
            if (quotient > 1 && reste != 0) numberToLetter = number_to_letter(quotient) + " millions " + number_to_letter(reste);
            break;
        case 10:
        case 11:
        case 12:
            quotient = Math.floor(nb / 1000000000);
            reste = nb - quotient * 1000000000;
            if (quotient == 1 && reste == 0) numberToLetter = "un milliard";
            if (quotient == 1 && reste != 0) numberToLetter = "un milliard" + " " + number_to_letter(reste);
            if (quotient > 1 && reste == 0) numberToLetter = number_to_letter(quotient) + " milliards";
            if (quotient > 1 && reste != 0) numberToLetter = number_to_letter(quotient) + " milliards " + number_to_letter(reste);
            break;
        case 13:
        case 14:
        case 15:
            quotient = Math.floor(nb / 1000000000000);
            reste = nb - quotient * 1000000000000;
            if (quotient == 1 && reste == 0) numberToLetter = "un billion";
            if (quotient == 1 && reste != 0) numberToLetter = "un billion" + " " + number_to_letter(reste);
            if (quotient > 1 && reste == 0) numberToLetter = number_to_letter(quotient) + " billions";
            if (quotient > 1 && reste != 0) numberToLetter = number_to_letter(quotient) + " billions " + number_to_letter(reste);
            break;
    }//fin switch
    /*respect de l'accord de quatre-vingt*/
    if (numberToLetter.substr(numberToLetter.length - "quatre-vingt".length, "quatre-vingt".length) == "quatre-vingt") numberToLetter = numberToLetter + "s";

    return numberToLetter.toUpperCase();
}

function select2_matcher(params, data) {
  params.term = params.term || '';

  term = params.term.toUpperCase().replace(/ /g,"");
  text = data.text.toUpperCase().replace(/ /g,"");

  if (text.indexOf(term) > -1 ) {
    return data;
  }
  return false;
}

function NumberToLetter(nombre) {
    var letter = {
        0 : "zéro",
        1 : "un",
        2 : "deux",
        3 : "trois",
        4 : "quatre",
        5 : "cinq",
        6 : "six",
        7 : "sept",
        8 : "huit",
        9 : "neuf",
        10: "dix",
        11: "onze",
        12: "douze",
        13: "treize",
        14: "quatorze",
        15: "quinze",
        16: "seize",
        17: "dix-sept",
        18: "dix-huit",
        19: "dix-neuf",
        20: "vingt",
        30: "trente",
        40: "quarante",
        50: "cinquante",
        60: "soixante",
        70: "soixante-dix",
        80: "quatre-vingt",
        90: "quatre-vingt-dix",
    };
        
    var i, j, n, quotient, reste, nb;
    var ch
    var numberToLetter = '';
    //__________________________________

    if (nombre.toString().replace(/ /gi, "").length > 15) return "dépassement de capacité";
    if (isNaN(nombre.toString().replace(/ /gi, ""))) return "Nombre non valide";

    nb = parseFloat(nombre.toString().replace(/ /gi, ""));
    //if (Math.ceil(nb) != nb) return "Nombre avec virgule non géré.";
    if(Math.ceil(nb) != nb){
        nb = nombre.toString().split('.');
        return NumberToLetter(nb[0]) + " virgule " + NumberToLetter(nb[1]);
    }

    n = nb.toString().length;
    switch (n) {
        case 1:
            numberToLetter = letter[nb];
            break;
        case 2:
            if (nb > 19) {
                quotient = Math.floor(nb / 10);
                reste = nb % 10;
                if (nb < 71 || (nb > 79 && nb < 91)) {
                    if (reste == 0) numberToLetter = letter[quotient * 10];
                    if (reste == 1) numberToLetter = letter[quotient * 10] + "-et-" + letter[reste];
                    if (reste > 1) numberToLetter = letter[quotient * 10] + "-" + letter[reste];
                } else numberToLetter = letter[(quotient - 1) * 10] + "-" + letter[10 + reste];
            } else numberToLetter = letter[nb];
            break;
        case 3:
            quotient = Math.floor(nb / 100);
            reste = nb % 100;
            if (quotient == 1 && reste == 0) numberToLetter = "cent";
            if (quotient == 1 && reste != 0) numberToLetter = "cent" + " " + NumberToLetter(reste);
            if (quotient > 1 && reste == 0) numberToLetter = letter[quotient] + " cents";
            if (quotient > 1 && reste != 0) numberToLetter = letter[quotient] + " cent " + NumberToLetter(reste);
            break;
        case 4 :
        case 5 :
        case 6 :
            quotient = Math.floor(nb / 1000);
            reste = nb - quotient * 1000;
            if (quotient == 1 && reste == 0) numberToLetter = "mille";
            if (quotient == 1 && reste != 0) numberToLetter = "mille" + " " + NumberToLetter(reste);
            if (quotient > 1 && reste == 0) numberToLetter = NumberToLetter(quotient) + " mille";
            if (quotient > 1 && reste != 0) numberToLetter = NumberToLetter(quotient) + " mille " + NumberToLetter(reste);
            break;
        case 7:
        case 8:
        case 9:
            quotient = Math.floor(nb / 1000000);
            reste = nb % 1000000;
            if (quotient == 1 && reste == 0) numberToLetter = "un million";
            if (quotient == 1 && reste != 0) numberToLetter = "un million" + " " + NumberToLetter(reste);
            if (quotient > 1 && reste == 0) numberToLetter = NumberToLetter(quotient) + " millions";
            if (quotient > 1 && reste != 0) numberToLetter = NumberToLetter(quotient) + " millions " + NumberToLetter(reste);
            break;
        case 10:
        case 11:
        case 12:
            quotient = Math.floor(nb / 1000000000);
            reste = nb - quotient * 1000000000;
            if (quotient == 1 && reste == 0) numberToLetter = "un milliard";
            if (quotient == 1 && reste != 0) numberToLetter = "un milliard" + " " + NumberToLetter(reste);
            if (quotient > 1 && reste == 0) numberToLetter = NumberToLetter(quotient) + " milliards";
            if (quotient > 1 && reste != 0) numberToLetter = NumberToLetter(quotient) + " milliards " + NumberToLetter(reste);
            break;
        case 13:
        case 14:
        case 15:
            quotient = Math.floor(nb / 1000000000000);
            reste = nb - quotient * 1000000000000;
            if (quotient == 1 && reste == 0) numberToLetter = "un billion";
            if (quotient == 1 && reste != 0) numberToLetter = "un billion" + " " + NumberToLetter(reste);
            if (quotient > 1 && reste == 0) numberToLetter = NumberToLetter(quotient) + " billions";
            if (quotient > 1 && reste != 0) numberToLetter = NumberToLetter(quotient) + " billions " + NumberToLetter(reste);
            break;
    }//fin switch
    /*respect de l'accord de quatre-vingt*/
    if (numberToLetter.substr(numberToLetter.length - "quatre-vingt".length, "quatre-vingt".length) == "quatre-vingt") numberToLetter = numberToLetter + "s";

    return numberToLetter;
}

function get_picture_b64() {
    return "data:image/png;base64,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";
}