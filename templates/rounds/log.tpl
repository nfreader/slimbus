{% extends "base/index.html" %}
{% import('components/macros.tpl') as m %}
{% block pagetitle %}Logs - Round #{{round.id}}{% endblock %}
{% block content %}
{% include('rounds/html/header.tpl') %}
<h3>Viewing <code>{{filename}}</code>
<span class="float-right">
  {% if raw %}
    <a href="{{path_for('round.log',{'id': round.id, 'file': filename})}}" class="btn btn-primary btn-sm">View Parsed</a>
  {% else %}
    <a href="{{path_for('round.log',{'id': round.id, 'file': filename, 'raw': 'raw'})}}" class="btn btn-primary btn-sm">View Raw</a>
  {% endif %}
  <a href="{{round.remote_logs_dir}}/{{filename}}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm">View Original <i class="fas fa-external-link-alt"></i></a>
</span>
</h3>
<hr>
{% if raw %}
  <pre>{{file}}</pre>
{% else %}
  {% include ['rounds/logs/' ~ filename ~'.tpl', 'rounds/logs/generic.tpl'] %}
{% endif %}

{% endblock %}

