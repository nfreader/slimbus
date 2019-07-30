<span class="display-4"><code>{{stat.key_name}}</code>:</span> 
<span class="display-1"><strong>{{stat.output}}</strong></span>

{% if stat.collated %}
<div id="chart"></div>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
var json = {{stat.js|json_encode|raw}}
var options = {
  chart: {
    type: 'line'
  },
  series: [{
    name: '{{stat.key_name}}',
    data: json
  }],
  xaxis: {
    type: "datetime"
  }
}

var chart = new ApexCharts(document.querySelector("#chart"), options);

chart.render();
</script>
{% endif %}