{% extends "base/index.html"%}
{% block content %}
  <div style="height: 400px">
    <canvas id="minutes" width="400" height="400"></canvas>
  </div>
  <p class="lead">
    This chart shows the accumulated time spent in the Living role (i.e. alive and playing) and the Ghost role (i.e. dead or observing) over the last 30 days, across all servers.
  </p>
{% endblock %}

{% block js %}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script>
var data = {{minutes|raw}}
var minutes = {
  living : [],
  ghost : [],
  dates : []
}

data.forEach(function(row){
  if(row.job == 'Living'){
    minutes.living.push(row.minutes)
    minutes.dates.push(row.date)
  }
  if (row.job == 'Ghost'){
    minutes.ghost.push(row.minutes)
  }
})

var ctx = document.getElementById('minutes').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: minutes.dates,
      datasets: [{
        data: minutes.living,
        label: "Living Minutes",
        borderColor: 'rgb(0,0,255)',
        backgroundColor: 'rgb(0,0,255)',  
        fill: false
      },
      {
        data: minutes.ghost,
        label: "Ghost Minutes",
        borderColor: 'rgb(255,0,0)',
        backgroundColor: 'rgb(255,0,0)',
        fill: false
      }]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: 'index',
        intersect: true
      },
      scales: {
        xAxes: [{
          type: 'time',
            time: {
              unit: 'day'
            }
          }]
        },
        elements: {
          line: {
              tension: 0 // disables bezier curves
          }
        }
      }
});
</script>
{% endblock %}