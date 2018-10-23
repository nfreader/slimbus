{% extends "base/index.html"%}
{%block content %}
<div class="page-header">
  <h2>You have logged out</h2>
</div>

<p><a class="btn btn-primary" href="{{path_for('statbus')}}">Continue</a>
{% endblock %}