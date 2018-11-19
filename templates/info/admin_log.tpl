{% extends "base/index.html"%}
{% block content %}
<h2>Admin Rank Logs</h2>
<hr>
<p>Logs of changes to admin ranks</p>
{% set vars = {
  'nbPages': info.pages,
  'currentPage': info.page,
  'url': path_for('admin_logs')
  } 
%}
{% include 'components/pagination.html' with vars %}
<table class="table table-sm table-bordered">
  <thead>
    <tr>    
      <th>ID</th>   
      <th>Date</th>   
      <th>Admin</th>   
      <th>Action</th>    
      <th>Target</th>   
      <th>Log Entry</th>
    </tr>   
  </thead>
  <tbody>
    {% for l in logs %}
      <tr id="{{l.id}}" class="table-{{l.class}}">
        <td class="align-middle nw"><a href="#{{l.id}}">{{l.id}}</a></td>
        <td class="align-middle nw">{{l.datetime|timestamp}}</td>
        <td class="align-middle nw">{{l.admin.label|raw}}</td>
        <td class="align-middle nw"><i class="fas fa-{{l.icon}}"></i> {{l.operation}}</td>
        <td class="align-middle nw">{{l.target}}</td>
        <td class="align-middle">{{l.log}}</td>
      </tr>
    {% endfor %}
  </tbody>
</table>
{% include 'components/pagination.html' with vars %}

{% endblock %}