{% extends "index.tpl"%}
{% block content %}
<h2>Historical Gamemode Win/Loss Ratios</h2>
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
{% set current = '' %}
<div class="card mb-2">
{% for m in modes %}
  {% if current != m.game_mode %}
  {% set current = m.game_mode %}
    </ul>
  </div>
  <div class="card mb-2" id="{{m.game_mode}}">
    <h3 class="card-header">
    <a href="#{{m.game_mode}}">{{m.game_mode|title}}</a></h3>
    <ul class="list-group list-group-flush">
  {% endif %}
      
    {% if 'halfwin' in m.game_mode_result %}
      <li class="list-group-item list-group-item-warning">
    {% elseif 'loss' in m.game_mode_result %}
      <li class="list-group-item list-group-item-danger">
    {% elseif 'win' in m.game_mode_result %}
      <li class="list-group-item list-group-item-success">
    {% else %}
      <li class="list-group-item list-group-item-warning">
    {% endif %}
      <span class="badge badge-light">{{m.rounds}}</span> {{m.game_mode_result|title}} | Average duration: {{m.duration}} minutes
    </li>
  </li>
{% endfor %}
</ul>
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
{% endblock %}
