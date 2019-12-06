{% extends "index.tpl"%}
{% block content %}
<h2>{{player.ckey}} 
  <small class="text-muted"> | <a href="http://www.byond.com/members/{{player.ckey}}" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt"></i> Byond</a></small>
</h2>
<hr>
<h3>Achievements</h3>
{% for a in player.achievements %}
  <div class='alert alert{% if a.type == 'achievement' %}-warning text-center {% else%}-primary{% endif %}'><strong>{{a.key}}</strong>{% if a.type == 'score' %} - {{a.value}}{% endif %}</div>
  {% else %}
  <p class="lead text-center">No achievements yet</p>
{% endfor %}
{% endblock %}