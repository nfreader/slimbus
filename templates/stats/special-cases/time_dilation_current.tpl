{% if stat is iterable %}
  {% set stat = stat[0] %}
{% endif %}

{%block content %}
<strong>HINT</strong> Click and drag on the graph to zoom. 
<div id="dilationgraph">

</div>
{% endblock %}

{% block js %}
<script type="text/javascript" src="https://cdn.plot.ly/plotly-latest.min.js"></script>

<script>
var data = {{stat.data|json_encode()|raw}};
var dates = unpack(data,'date');
var current = unpack(data,'current');
var avgF = unpack(data,'avg_fast');
var avg = unpack(data,'avg');
var avgS = unpack(data,'avg_slow');

var trace1 = {
  x: dates,
  y: current,
  type: 'line',
  name: 'Time Dilation (Current)'
}

var trace2 = {
  x: dates,
  y: avgF,
  type: 'line',
  name: 'Time Dilation (Avg. Fast)'
}

var trace3 = {
  x: dates,
  y: avg,
  type: 'line',
  name: 'Time Dilation (Avg.)'
}

var trace4 = {
  x: dates,
  y: avgS,
  type: 'line',
  name: 'Time Dilation (Avg. Slow)'
}

var layout = {
  title: 'Time Dilation',
  xaxis: {
    title: 'Date'
  },
  yaxis: {
    title: 'Time Dilation',
    range: [0, 100]
  }
};

var data = [trace1, trace2, trace3, trace4];
Plotly.plot('dilationgraph', data, layout)

</script>
{% endblock %}