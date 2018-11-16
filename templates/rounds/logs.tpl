{% extends "base/index.html"%}
{% block pagetitle %}Logs - Round #{{round.id}}{% endblock %}
{% block content %}
{% include('rounds/html/header.tpl') %}

<h3>Available log files</h3>
<hr>
<div class="row">
  <div class="col-md-6">
    <h4>Parsed</h4>
    <hr>
    <ul class="list-group">
    {% for file in logs %}
      <a class="list-group-item" href="{{path_for('round.log',{'id': round.id, 'file': file.fileName})}}">{{file.fileName}}</a>
    {% endfor %}
    </ul>
  </div>
  <div class="col-md-6">
    <h4>Raw</h4>
    <hr>
    <ul class="list-group">
    {% for file in logs %}
      <a class="list-group-item" href="{{path_for('round.log',{'id': round.id, 'file': file.fileName, 'raw':'raw'})}}">{{file.fileName}}</a>
    {% endfor %}
    </ul>
  </div>
</div>

{% endblock %}

