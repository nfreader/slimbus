{% extends "index.tpl"%}
{% block content %}
<h2>{{player.label|raw}}</h2>
</h2>
<hr>
<p>Current range: <strong><code>{{start}} - {{end}}</code></strong></p>
<br>
<form>
  <div class="row">
    <div class="col">
      <div id="slider"></div>
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col">
      <input type="text" class="form-control" placeholder="Start Date" id="start" name="start">
    </div>
    <div class="col">
      <input type="text" class="form-control" placeholder="End Date" id="end" name="end">
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col">
      <button type="submit" class="btn btn-primary">Apply Range</button>
    </div>
  </div>
</form>
<hr>
<h3>Role Time</h3>
  <div id="roletime">

  </div>

{% endblock %}
{% block js %}
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.1.0/nouislider.min.js" integrity="sha256-IB524Svhneql+nv1wQV7OKsccHNhx8OvsGmbF6WCaM0=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.1.0/nouislider.min.css" integrity="sha256-tkYpq+Xdq4PQNNGRDPtH3G55auZB4+kh/RA80Abngaw=" crossorigin="anonymous" />
<script>
  function timestamp(str){
    return new Date(str).getTime();   
  }

  function formatToYMD(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();
    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
    return [year, month, day].join('-');
  }
  min = timestamp('{{min}}')
  max = timestamp('{{max}}')
  start = timestamp('{{start}}')
  end   = timestamp('{{end}}')
  noUiSlider.create(slider, {
    start: [start, end],
    connect: true,
    range: {
      'min': min,
      'max': max
    },
  });
  slider.noUiSlider.on('update', function( values, handle ) {
    var dateValues = [
      document.getElementById('start'),
      document.getElementById('end')
    ];
    var start = Number(values[0]);
    var end =   Number(values[1]);
    dateValues[handle].value = formatToYMD(new Date(+values[handle]));
  });
</script>
<script type="text/javascript" src="https://cdn.plot.ly/plotly-latest.min.js"></script>

<script>
var data = {{player.role_time|raw}};
var jobs = unpack(data,'job');
var minutes = unpack(data,'minutes');
var trace1 = {
  x: jobs,
  y: minutes,
  type: 'bar',
  name: 'Minutes'
}

var layout = {
  title: 'Role Time (Minutes)',
};
var data = [trace1]
Plotly.newPlot('roletime',data, layout)
</script>
{% endblock %}
