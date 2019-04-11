{% extends "base/index.html"%}
{% block pagetitle %}Round #{{round.id}}{% endblock %}
{% block content %}
{% if round.data.nuclear_challenge_mode %}
<div class="alert alert-danger">
  <strong>ALERT!</strong> WAR WERE DECLARED!
</div>
{% endif %}

{% if round.userWasAntag %}
<div class="alert alert-success">
  <strong>Hey, neat!</strong> Looks like you were an antagonist this round!
</div>
{% endif %}

{% include('rounds/html/basic.tpl') %}

{% if round.data %}
  {% for name, stat in round.data %}
  <div class="card mb-2" id="prs">
    <div class="card-header">
      <code>{{name}}</code>
    </div>
    <div class="card-body">
    {% include ['stats/single/' ~ stat.key_name ~'-' ~ stat.version ~'.tpl', 'stats/single/' ~ stat.key_name ~'.tpl','stats/type/' ~ stat.key_type ~'.tpl', 'stats/generic.tpl'] %}
      </div>
    </div>
  {% endfor %}
{% endif %}

<h3>Round Stats</h3>
<hr>
<ul class="list-inline">
  {% for key, stat in round.stats %}
  <li class="list-inline-item">
    <code>
      <a href="{{path_for('round.single',{'id': round.id, 'stat':key})}}">{{key}}</a>
    </code>
  </li>
{% endfor %}
</ul>
{% endblock %}
