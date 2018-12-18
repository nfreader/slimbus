{% extends "base/index.html"%}
{%block content %}
{% if not user %}
<div class="page-header">
  <h1>Hmm...</h1>
</div>
<hr>
  <p class="lead">You need to be logged in in oder to vote on names</p>
  <a href="{{path_for('auth')}}" class="btn btn-success btn-lg btn-block">Authenticate</a>
{% else %}
<h1>Name Rater 5000</h1>
<hr>
<div class="btn-group" role="group" aria-label="Basic example">
  <a class="btn btn-primary text-white" href="{{path_for('nameVoter')}}">Vote on Names</a>
  <a class="btn btn-primary text-white" href="{{path_for('nameVoter.results',{'rank':'best'})}}">Best Names</a>
  <a class="btn btn-primary text-white" href="{{path_for('nameVoter.results',{'rank':'worst'})}}">Worst Names</a>
</div>
<hr>
  <table class="table table-sm table-bordered">
    <thead>
      <tr>    
        <th>Name</th>   
        <th>'No' Votes</th>   
        <th>'Yes' Votes</th>   
      </tr>   
    </thead>
    <tbody>
      {% for r in ranking %}
        <tr>
          <td>{{r.name}}</td>
          <td>{{r.no}}</td>
          <td>{{r.yes}}</td>
        </tr>
      {% endfor %}
    </tbody>
  </table>
{% endif %}
{% endblock %}
