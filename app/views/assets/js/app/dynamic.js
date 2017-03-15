$(function() {
  var ctx, data, myLineChart, options;
  Chart.defaults.global.responsive = true;
  Chart.defaults.global.maintainAspectRatio = false;
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
        color: "#22A7F0",
        fillColor: "rgba(34, 167, 240, 0)",
        strokeColor: "#22A7F0",
        pointColor: "#22A7F0",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#22A7F0",
        data: [29584, 29584, 28292, 28153]
      },
      {
        label: "Пиджеум",
        color: "#1ABC9C",
        fillColor: "rgba(34, 167, 240, 0)",
        strokeColor: "#1ABC9C",
        pointColor: "#1ABC9C",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#1ABC9C",
        data: [5213, 5144, 5069, 5029]
      },
      {
        label: "Ярсагумба",
        color: "#ffb400",
        fillColor: "rgba(34, 167, 240, 0)",
        strokeColor: "#ffb400",
        pointColor: "#ffb400",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#ffb400",
        data: [0, 0, 12884, 13013]
      },
      {
        label: "Ловелас Форте",
        color: "#FA2A00",
        fillColor: "rgba(34, 167, 240, 0)",
        strokeColor: "#FA2A00",
        pointColor: "#FA2A00",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#FA2A00",
        data: [0, 0, 0, 14381]
      }
    ]
  };
  myLineChart = new Chart(ctx).Line(data, options);

});

