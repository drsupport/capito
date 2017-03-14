$(function() {
  var ctx, data, myLineChart, options;
  Chart.defaults.global.responsive = true;
  ctx = $('#line-chart').get(0).getContext('2d');
  options = {
    scaleShowGridLines: true,
    scaleGridLineColor: "rgba(0,0,0,.05)",
    scaleGridLineWidth: 1,
    scaleShowHorizontalLines: true,
    scaleShowVerticalLines: true,
    bezierCurve: false,
    bezierCurveTension: 0.4,
    pointDot: true,
    pointDotRadius: 4,
    pointDotStrokeWidth: 1,
    pointHitDetectionRadius: 20,
    datasetStroke: true,
    datasetStrokeWidth: 2,
    datasetFill: true,
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
  };
  data = {
    labels: ['12.03', '13.03', '14.03'],
    datasets: [
      {
        label: "Сементал",
        color: "#161B48",
        fillColor: "rgba(34, 167, 240, 0)",
        strokeColor: "#161B48",
        pointColor: "#161B48",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#161B48",
        data: [29584, 29584, 28292]
      },
      {
        label: "Пиджеум",
        color: "#BB481C",
        fillColor: "rgba(34, 167, 240, 0)",
        strokeColor: "#BB481C",
        pointColor: "#BB481C",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#BB481C",
        data: [5213, 5144, 5069]
      },
      {
        label: "Ярсагумба",
        color: "#22A7F0",
        fillColor: "rgba(34, 167, 240, 0)",
        strokeColor: "#22A7F0",
        pointColor: "#22A7F0",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#22A7F0",
        data: [0, 0, 12884]
      }
    ]
  };
  myLineChart = new Chart(ctx).Line(data, options);
   //document.getElementById('js-legend').innerHTML = myLineChart.generateLegend();
});

