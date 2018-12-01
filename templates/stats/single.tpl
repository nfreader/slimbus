{% extends "base/index.html"%}
{% block pagetitle %}Round #{{round.id}}{% endblock %}
{% block content %}
<h2><code>{{stat.key_name}}</code><small>
    <a href="{{path_for('round.single',{'id':round.id})}}">
      <i class="fas fa-circle"></i> {{round.id}}
    </a>
  </small>
</h2>
<hr>
  {% if stat.label.splain %}
  <div class="alert alert-secondary" role="alert">
    {{stat.label.splain}}
  </div>
  {% endif %}
  {% include ['stats/single/' ~ stat.key_name ~'-' ~ stat.version ~'.tpl', 'stats/single/' ~ stat.key_name ~'.tpl','stats/type/' ~ stat.key_type ~'.tpl', 'stats/generic.tpl'] %}
  <hr>
  <dl class="row">
    <dt class="col-sm-3">Stat ID</dt>
    <dd class="col-sm-9">{{stat.id}}</dd>

    <dt class="col-sm-3">Stat Type</dt>
    <dd class="col-sm-9"><code>{{stat.key_type}}</code></dd>

    <dt class="col-sm-3">Stat Name</dt>
    <dd class="col-sm-9"><code>{{stat.key_name}}</code></dd>

    <dt class="col-sm-3">Timestamp Recorded</dt>
    <dd class="col-sm-9">{{stat.datetime}}</dd>

    <dt class="col-sm-3">Stat Version</dt>
    <dd class="col-sm-9">{{stat.version}}</dd>

    <dt class="col-sm-3">Raw JSON</dt>
    <dd class="col-sm-9"><pre>{{stat.json}}</pre></dd>
  </dl>

{% endblock %}
