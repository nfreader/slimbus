{% extends "base/index.html"%}
{% block content %}

{% if fromCache %}
<div class="alert alert-info">This data was loaded from a cached file</div>
{% endif %}

<div id="population"></div>
<p class="lead">
  This chart shows the average number of players, admins, and completed rounds, by hour, across all servers, for the last 30 days.
</p>
{% endblock %}

{% block js %}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
var json = {{data|raw}}
var options = {
  chart: {
    type: 'line',
    height: 512,
    animations: {
      enabled: false
    }
  },
  series: [{
    name: 'Players',
    data: unpack(json, 'players')
  },{
    name: 'Admins',
    data: unpack(json, 'admins')
  },{
    name: 'Rounds',
    data: unpack(json, 'rounds')
  }],
  xaxis: {
    type: "datetime",
    categories: unpack(json, 'date'),
  },
  tooltip: {
    x: {
      format: 'dd MMM yyyy HH:00'
    }
  }
}
var chart = new ApexCharts(document.querySelector("#population"), options);
chart.render();
</script>
{% endblock %}