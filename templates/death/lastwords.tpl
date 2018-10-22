{% extends "base/index.html"%}
{% block pagetitle %}Last Words{% endblock %}
{% block content %}
<h2>Last Words</h2>
<hr>
<ul class="list-inline">
{% for death in deaths %}
  <li class="list-inline-item">
    <code><em><a href="{{path_for('death.single',{'id': death.id})}}">{{death.last_words|raw}}</a></em></code>
  </li>
{% endfor %}
</ul>
{% endblock %}
