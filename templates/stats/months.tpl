{% extends "index.tpl"%}
{% block content %}
<h2>Months With Stats</h2>
<hr>
<table class="table table-bordered table-sm">
  <thead>
    <tr>
      <th>Date</th>
      <th>Datapoints</th>
      <th># of Rounds</th>
      <th>First Round ID</th>
      <th>Last Round ID</th>
    </tr>
  </thead>
  <tbody>
    {% for s in stats %}
      <tr>
        <td>
          <a href="{{app.url}}stat.php?year={{s.year}}&month={{s.month}}">
          {{s.date}}
          </a>
        </td>
        <td>{{s.stats}}</td>
        <td>{{s.rounds}}</td>
        <td>{{s.firstround}}</td>
        <td>{{s.lastround}}</td>
      </tr>
    {% endfor %}
  </tbody>
</table>
{% endblock %}