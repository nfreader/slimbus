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
Plotly.d3.json("/tmp/db/{{hash}}",function(data){
    console.log(data);
    dates   = unpack(data.data,'date')
    hours   = unpack(data.data,'hour')
    servers = unpack(data.data,'server_port')
    players = unpack(data.data,'players')
    admins  = unpack(data.data,'admins')
    renderGraph()
})
function renderGraph(){
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
    }

    var data = [trace1, trace2]
    Plotly.plot('population', data, layout, {responsive: true})
}
</script>
{% endblock %}