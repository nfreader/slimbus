{% extends "base/index.html"%}
{% block content %}

{% if fromCache %}
<div class="alert alert-info">This data was loaded from a cached file</div>
{% endif %}

<div id="population"></div>

{% endblock %}

{% block js %}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
var json = {{data|raw}}
console.log(json)
var options = {
  chart: {
    type: 'line'
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