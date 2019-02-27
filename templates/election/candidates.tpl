{% extends "base/index.html"%}
{% block content %}
<h2>Candidate Activity</h2>
<hr>
<form class="form-inline" method="GET" action="">
  <div class="form-group">
    <input type="number" max='180' min='2' class="form-control mx-sm-3" id="interval" placeholder="Days" value="{{interval}}" name="interval">
    <small class="text-muted">
      Days
    </small>
  </div>
  <button type="submit" class="btn btn-primary ml-4">View Activity</button>
</form>
<hr>
<p>This page shows the time spent as a ghost and time spent living for the Headmin Candidates listed below, along with the number of times their ckey has connected to the servers.</p>

<hr>
<table class="table table-sm table-bordered sort">
  <thead>
    <tr>
      <th>ckey</th>
      <th>Play time</th>
      <th>Connections</th>
      <th>Rank</th>
    </tr>
  </thead>
  <tbody>
    {% for a in admins %}
      <tr>
        <td>{{a.label|raw}}</td>
        <td>
          {% if a.total %}
          <div class="progress" style="border: 1px solid black;">
            <div class="progress-bar" role="progressbar" style="width: {{a.ghost/a.total * 100}}%; background: #EEE;" data-toggle="tooltip" title="Ghost: {{a.ghost}} minutes"></div>
            <div class="progress-bar" role="progressbar" style="width: {{a.living/a.total * 100}}%; background: #444;" data-toggle="tooltip" title="Living: {{a.living}} minutes"></div>
          </div>
          {% else %}
          [Data Unavailable]
          {% endif %}
        </td>
        <td>{{a.connections}}</td>
        <td>{{a.rank}}</td>
      </tr>
    {% endfor %}
  </tbody>
</table>

{% if settings.election_officer == user.ckey %}
<hr>
<p class="lead">Paste in a list of ckeys to show on the board above. Each ckey should match <code>[a-zA-Z0-9]</code> and should be separated by a comma (<code>,</code>)</p>
<form class="form" action="{{path_for('election')}}" method="POST">
<input type='text' class="form-control" name="candidates" value="{{list}}">
  <hr>
  <button type="submit" class="btn btn-primary">Update Candidate Listing</button>
</form>
{% endif %}
{% endblock %}