{% extends "base/index.html"%}
{% block pagetitle %}Logs - Round #{{round.id}}{% endblock %}
{% block content %}
{% include('rounds/html/header.tpl') %}

{% set gameLogs = ['game.txt','game.html','attack.txt','attack.html'] %}
<h3>Available log files <a class="btn btn-primary btn-sm" href="{{round.remote_logs_dir}}" target="_blank" rel="noopener noreferrer">Original <i class="fas fa-external-link-alt"></i></a></h3>
<hr>

<div class="row">
  <div class="col-md-6">
    <h4>Parsed</h4>
    <hr>
    <ul class="list-group">
    {% for file in logs %}
      {% if file.fileName in gameLogs %}
      <a class="list-group-item" href="{{path_for('round.gamelogs',{'id': round.id})}}">{{file.fileName}}</a>
      {% else %}
      <a class="list-group-item" href="{{path_for('round.log',{'id': round.id, 'file': file.fileName})}}">{{file.fileName}}</a>
      {% endif %}
    {% endfor %}
    </ul>
  </div>
  <div class="col-md-6">
    <h4>Raw</h4>
    <hr>
    <ul class="list-group">
    {% for file in logs %}
      {% if file.fileName in gameLogs %}
      {% else %}
      <a class="list-group-item" href="{{path_for('round.log',{'id': round.id, 'file': file.fileName, 'raw':'raw'})}}">{{file.fileName}}</a>
      {% endif %}
    {% endfor %}
    </ul>
  </div>
</div>

{% endblock %}

