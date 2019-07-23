{% extends "base/index.html"%}
{% block pagetitle %}Round #{{round.id}}{% endblock %}
{% block content %}
<h2><small>Collated stats for </small><code>{{stat.key_name}}</code></h2>
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
  {% if stat.label.splain %}
  <div class="alert alert-secondary" role="alert">
    {{stat.label.splain}}
  </div>
  {% endif %}
  {% include ['stats/single/' ~ stat.key_name ~'-' ~ stat.version ~'.tpl', 'stats/single/' ~ stat.key_name ~'.tpl','stats/type/' ~ stat.key_type ~'.tpl', 'stats/generic.tpl'] %}
  <hr>
  <dl class="row">
    <dt class="col-sm-3">Start Date/Round</dt>
    <dd class="col-sm-9"><code>{{stat.first_date}} / {{stat.first_round}}</code></dd>

    <dt class="col-sm-3">End Date/Round</dt>
    <dd class="col-sm-9"><code>{{stat.last_date}} / {{stat.last_round}}</code></dd>

    <dt class="col-sm-3">Rounds Seen</dt>
    <dd class="col-sm-9">{{stat.rounds|length}}</dd>

    <dt class="col-sm-3">Stat Name</dt>
    <dd class="col-sm-9"><code>{{stat.key_name}}</code></dd>

    <dt class="col-sm-3">Stat Version</dt>
    <dd class="col-sm-9">{{stat.version}}</dd>
  </dl>
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
        month = '' + (d.getUTCMonth() + 1),
        day = '' + d.getUTCDate(),
        year = d.getUTCFullYear();
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
