{% extends "base/index.html" %}
{% import('components/macros.tpl') as m %}
{% block pagetitle %}Logs - Round #{{round.id}}{% endblock %}
{% block content %}
{% include('rounds/html/header.tpl') %}
{% set gameLogs = ['game.txt','game.html','attack.txt','attack.html'] %}
<h3>Viewing <code>{{filename}}</code>
<span class="float-right">
  {% if format == 'raw' %}
    <a href="{{path_for('round.log',{'id': round.id, 'file': filename})}}" class="btn btn-primary btn-sm">View Parsed</a>
  {% else %}
    {% if filename in gameLogs %}
    {% else %}
    <a href="{{path_for('round.log',{'id': round.id, 'file': filename, 'format': 'raw'})}}" class="btn btn-primary btn-sm">View Raw</a>
    {% endif %}
  {% endif %}
  <a href="{{round.remote_logs_dir}}/{{filename}}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm">View Original <i class="fas fa-external-link-alt"></i></a>
</span>
</h3>
<hr>
{% if format == 'raw' %}
  <pre>{{file}}</pre>
{% else %}
  {% include ['rounds/logs/' ~ filename ~'.tpl', 'rounds/logs/generic.tpl'] %}
{% endif %}

{% endblock %}

