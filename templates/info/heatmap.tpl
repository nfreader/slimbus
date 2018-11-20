{% extends "base/index.html"%}
{% block content %}

{% if fromCache %}
<div class="alert alert-info">This data was loaded from a cached file</div>
{% endif %}

<div id="population" style="height: 512px;"></div>

{% endblock %}

{% block js %}
<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<script>
var pop = {{data|raw}};
var dates   = unpack(pop,'date');
var hours   = unpack(pop,'hour');
var servers = unpack(pop,'server_port');
var players = unpack(pop,'players');
var admins  = unpack(pop,'admins');

var trace1 = {
  x: dates,
  y: players,
  type: 'line',
  transforms: [{
    type: 'groupby',
    groups: servers,
  }],
  name: 'Players'
}

var trace2 = {
  x: dates,
  y: admins,
  type: 'line',
  transforms: [{
    type: 'groupby',
    groups: servers
  }],
  name: 'Admins'
}

var layout = {
  title: 'Server Population',
  xaxis: {
    title: 'Date',
    type: 'date'
  },
  yaxis: {
    title: 'Population'
  }
};

var data = [trace1, trace2]
Plotly.plot('population', data, layout, {responsive: true})
</script>
{% endblock %}