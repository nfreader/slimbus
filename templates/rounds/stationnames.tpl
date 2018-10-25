{% extends "index.tpl"%}
{% block content %}
<h2>Station Names</h2>
<hr>
<ul class="list-inline">
{% for word in names %}
  <li class="list-inline-item">
    <code><em><a href="{{path_for('round.single',{'id': word.id})}}">{{word.station_name|raw}}</a></em></code>
  </li>
{% endfor %}
</ul>
{% endblock %}