load_graph()

$(document).on('click', '#btn_search_graph', function(event) {
  event.preventDefault();

  load_graph();
});

function load_graph() {
  var data = {
      produit_id : $('#id_produit').val(),
      annee : $('#annee').val(),
  };

  var url = Routing.generate('produit_graph');

  $.ajax({
      url: url,
      type: 'POST',
      data: data,
      success: function(res) {
          instance_graph(res)
      }
  })
}


function instance_graph(series) {
  Highcharts.chart('container', {
    chart: {
      // type: 'area'
      type: 'line'
    },
    accessibility: {
      description: ''
    },
    title: {
      text: $('#nom').val() + ' #' + $('#code').val()
    },
    // subtitle: {
    //   text: 'Sources: <a href="https://thebulletin.org/2006/july/global-nuclear-stockpiles-1945-2006">' +
    //     'thebulletin.org</a> & <a href="https://www.armscontrol.org/factsheets/Nuclearweaponswhohaswhat">' +
    //     'armscontrol.org</a>'
    // },
    xAxis: {
      categories : ['Jan.', 'Fev.', 'Mars.', 'Avr.', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.']
    },
    yAxis: {
      title: {
        text: 'Montant ('+ $('#devise_symbole').val() +')'
      },
      labels: {
        formatter: function () {
          return Number(this.value).toLocaleString() ;
        }
      }
    },
    tooltip: {
      pointFormat: '{series.name} : <b>{point.y:,.0f} '+ $('#devise_symbole').val() +'</b>'
    },
    plotOptions: {
                  series: {
                      label: {
                          enabled: false
                      },
                  },
              },
    // series: [{
    //   name: 'ACHATS',
    //   data: [ 1, 2, 3, 4, 5, 6, 7, 8 ]
    // }, {
    //   name: 'VENTES',
    //   data: [ 8,7,6,5,4,3,2,1 ]
    // }]

    series : series
  });
}
