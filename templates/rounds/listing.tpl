{% extends "base/index.html"%}
{% block pagetitle %}Round Index{% endblock %}
{% block content %}
<div class="row">
  <div class="col">
  {% set vars = {
    'nbPages': round.pages,
    'currentPage': round.page,
    'url': path_for('round.index')
    } 
  %}
  {% include 'components/pagination.html' with vars %}
  </div>
  <div class="col">
    <p class="text-muted text-right">Showing rounds between {{round.firstListing}} UTC and {{round.lastListing}} UTC<br>
      <a href="{{path_for('round.stations')}}">Some famous Nanotrasen Space Stations</a>
    </p>
  </div>
</div>
  <table class="table table-sm table-bordered">
    <thead>
      <tr>    
        <th>ID</th>   
        <th>Mode</th>   
        <th>Result</th>   
        <th>Map</th>    
        <th>Start</th>
        <th>Duration</th>
        <th>End</th>    
        <th>Server</th>   
      </tr>   
    </thead>
    <tbody>
    {% for round in rounds %}
      {% include('rounds/html/listingrow.tpl') %}
    {% endfor %}
    </tbody>
  </table>
  {% include 'components/pagination.html' with vars %}
{% endblock %}

