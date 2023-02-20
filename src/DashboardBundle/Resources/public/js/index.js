$(function() {
	square_menu();
	dash_client();
	dash_facture();
    dash_vente();
    dash_credit();
    dash_heb();
    dash_resto();
});

function square_menu() {
	$('#side-menu li a').each(function(n,v){
		if ($(this).parent().hasClass('item-menu')) {
	        var icon = $(this).children('i').attr('class') ;
	        var libelle = $(this).children('span').html() ;
	        var lien = $(this).attr('href');

	        var menu = '<div class="col-sm-6 col-md-4">';
	        menu += '<a href=" '+ lien +'" class="tile-link">';
	        menu += '<div class="tile-menu">';
	        menu += '<h2 class="tile-title">' + libelle + '</h2>';
	        menu += '<div class="tile-icon">';
	        menu += '<i class="'+ icon +'"></i>';
	        menu += '</div>';
	        menu += '</div>';
	        menu += '</a>';
	        menu += '</div>';

	        $('#dash-square-menu').append(menu);
		}
	});
}

function dash_client() {
    var data = JSON.parse( $('#data-client').val() );

    var plotObj = $.plot($("#client-pie-chart"), data, {
        series: {
            pie: {
                show: true
            }
        },
        grid: {
            hoverable: true
        },
        tooltip: true,
        tooltipOpts: {
            content: "%p.0%, %s",
            shifts: {
                x: 20,
                y: 0
            },
            defaultTheme: false
        }
    });
}

function dash_facture() {
    var pie = JSON.parse( $('#data-pie-facture').val() );
    var morris = JSON.parse( $('#data-morris-facture').val() );
    var line = JSON.parse( $('#data-line-facture').val() );

    var plotObj = $.plot($("#facture-pie-chart"), pie, {
        series: {
            pie: {
                show: true
            }
        },
        grid: {
            hoverable: true
        },
        tooltip: true,
        tooltipOpts: {
            content: function(label, xval, yval, flotItem) {
			   return label  + ' : ' + yval;
			},
            shifts: {
                x: 20,
                y: 0
            },
            defaultTheme: false
        }
    });

    Morris.Bar({
        element: 'facture-bar-chart',
        data: morris,
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Devis', 'Définitive'],
        hideHover: 'auto',
        resize: true,
        barColors: ['#1c84c6', '#23c6c8'],
    });

    var months = ["Jan", "Fev", "Mar", "Avr", "Mai", "Juin", "Juil", "Aou", "Sep", "Oct", "Nov", "Dec"];

    Morris.Line({
        element: 'facture-line-chart',
        data: line,
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Devis', 'Définitive'],
        hideHover: 'auto',
        resize: true,
        lineColors: ['#1c84c6','#23c6c8'],
        xLabelFormat: function (x) {
            
            return months[x.getMonth()];
        },
    });
}

function dash_vente() {
    var line = JSON.parse( $('#data-line-vente').val() );
    var months = ["Jan", "Fev", "Mar", "Avr", "Mai", "Juin", "Juil", "Aou", "Sep", "Oct", "Nov", "Dec"];

    Morris.Bar({
        element: 'vente-line-chart',
            data: line,
        xkey: 'y',
        ykeys: ['value'],
        resize: true,
        barWidth:2,
        labels: ['Vente'],
        barColors: ['#f8ac59'],
        // xLabelFormat: function (x) {
        //     return months[x.getMonth()];
        // },
    });
}

function dash_credit() {
    var donut = JSON.parse( $('#data-donut-credit').val() );

    Morris.Donut({
        element: 'credit-donut-chart',
        data: donut,
        resize: true,
        colors: ['#f8ac59', '#1ab394'],
    });
}

function dash_heb() {
    var line = JSON.parse( $('#data-line-heb').val() );
    var months = ["Jan", "Fev", "Mar", "Avr", "Mai", "Juin", "Juil", "Aou", "Sep", "Oct", "Nov", "Dec"];

    Morris.Line({
        element: 'heb-line-chart',
        data: line,
        xkey: 'y',
        ykeys: ['value'],
        labels: ['Total'],
        hideHover: 'auto',
        resize: true,
        lineColors: ['#1ab394'],
        xLabelFormat: function (x) {
            return months[x.getMonth()];
        },
    });
}

function dash_resto() {
    var line = JSON.parse( $('#data-line-resto').val() );
    var months = ["Jan", "Fev", "Mar", "Avr", "Mai", "Juin", "Juil", "Aou", "Sep", "Oct", "Nov", "Dec"];

    Morris.Line({
        element: 'resto-line-chart',
        data: line,
        xkey: 'y',
        ykeys: ['value'],
        labels: ['Total'],
        hideHover: 'auto',
        resize: true,
        lineColors: ['#f8ac59'],
        xLabelFormat: function (x) {
            return months[x.getMonth()];
        },
    });
}


