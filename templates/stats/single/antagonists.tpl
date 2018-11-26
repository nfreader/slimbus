{% if stat.version < 3 %}
{% for ckey, objs in stat.data %}
{% set type = objs|keys[0] %}
  <div class="card mb-4">
    <h4 class="card-header">{{ckey}} <small class="text-muted">{{type}}</small></h4>
    <ul class="list-group list-group-flush">
    {% for goals in objs %}
      {% for t, g in goals %}
      {% set goal = g|keys[0] %}
      {% set status = g[goal]|keys[0] %}
      {% if 'FAIL' == status %}
        <li class="list-group-item list-group-item-danger">
          <span class="badge badge-danger">FAIL</span>
      {% else %}
        <li class="list-group-item list-group-item-success">
          <span class="badge badge-success">SUCCESS</span>
      {% endif %}
        <strong>{{goal}}</strong><br>
        <small class="text-muted">{{t}}</small>
      </li>
      {% endfor %}
    {% endfor %}
    </ul>
  </div>
{% endfor %}

{% else %}
  {% if stat.extra %}
  <div class="card mb-4">
    <h4 class="card-header">Overall Results</h4>
    <ul class="list-group list-group-flush">
      <li class="list-group-item list-group-item-danger">
        <span class="badge badge-danger">FAIL</span>
        <strong>{{stat.extra.fail}}</strong>
      </li>
      <li class="list-group-item list-group-item-success">
        <span class="badge badge-success">SUCCESS</span>
        <strong>{{stat.extra.success}}</strong>
      </li>
    </ul>
    <div class="card-body">
      The antagonists
      {% if stat.extra.success < stat.extra.fail %}
      <span class="badge badge-danger">failed</span>
      {% elseif stat.extra.success > stat.extra.fail %}
      <span class="badge badge-success">succeeded</span>
      {% else %}
      <span class="badge badge-warning">did not succeed or fail</span>
      {% endif %}
      at the majority of their objectives
    </div>
  </div>
  {% endif %}
{% for antag in stat.data %}
  <div class="card mb-4">
    <h4 class="card-header"><span class="text-muted">{{antag.antagonist_name}}</span> {{antag.name}}<small>/{{antag.key}}</small></h4>
    <ul class="list-group list-group-flush">
      {% for o in antag.objectives %}
        {% if 'FAIL' == o.result %}
          <li class="list-group-item list-group-item-danger">
            <span class="badge badge-danger">FAIL</span>
        {% else %}
          <li class="list-group-item list-group-item-success">
            <span class="badge badge-success">SUCCESS</span>
        {% endif %}
          <strong>{{o.text|striptags}}</strong><br>
          <small class="text-muted">{{o.objective_type}}</small>
      {% endfor %}
    </ul>
  </div>
{% endfor %}
{% endif %}