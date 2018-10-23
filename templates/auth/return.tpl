{% extends "base/index.html"%}
{%block content %}
<div class="page-header">
  <h2>Success!</h2>
</div>
  <p><h1>{{user.label|raw}}</h1></p>
  <p><code>{{statbus.app_name}}</code> now recognizes you!</p>

<p><a class="btn btn-primary" href="{{path_for('statbus')}}">Continue</a>
{% endblock %}