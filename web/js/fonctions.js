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