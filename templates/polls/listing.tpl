{% extends "base/index.html"%}
{% block pagetitle %}Polls{% endblock %}
{% block content %}
<div class="row">
  <div class="col">
  {% set vars = {
    'nbPages': poll.pages,
    'currentPage': poll.page,
    'url': path_for('poll.index')
    } 
  %}
  {% include 'components/pagination.html' with vars %}
  </div>
</div>
  <table class="table table-sm table-bordered">
      <thead>
        <tr>    
          <th>ID</th>   
          <th>Type</th>   
          <th>Question</th>   
          <th>Duration</th>    
          <th>Responses</th>  
        </tr>   
      </thead>
      <tbody>
      {% for poll in polls %}
        <tr>
          <td><a href="{{path_for('poll.single',{'id': poll.id})}}">{{poll.id}}</a></td>
          <td>{{poll.type}}</td>
          <td>{{poll.question|raw}}</td>
          <td>{{poll.duration}}</td>
          <td>{{poll.totalVotes}}</td>
        </tr>
      {% endfor %}
      </tbody>
    </table>

  {% include 'components/pagination.html' with vars %}
{% endblock %}