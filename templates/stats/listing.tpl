{% extends "base/index.html"%}
{% block pagetitle %}{% endblock %}
{% block content %}
<table class="table table-sm table-bordered">
  <thead>
    <tr>    
      <th>Stat</th>   
      <th>Type</th>  
      <th>Version</th>
      <th>Rounds Recorded</th>    
    </tr>   
  </thead>
  <tbody>
{% for stat in stats %}
    <tr>
      <td><code><a href="{{path_for('stat.collate',{'stat': stat.key_name,'version': stat.version})}}">{{stat.key_name}}</a></code></td>
      <td>{{stat.key_type}}</td>
      <td>{{stat.version}}</td>
      <td>{{stat.rounds}}</td>
{% endfor %}
  </tbody>
</table>
{% endblock %}
