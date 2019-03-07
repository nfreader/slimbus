{% extends "base/index.html"%}
{% block content %}
<h2>Admin Play Activity</h2>
<hr>
<form class="form-inline" method="GET" action="">
  <div class="form-group">
    <input type="number" max='{{maxRange}}' min='2' class="form-control mx-sm-3" id="interval" placeholder="Days" value="{{interval}}" name="interval">
    <small class="text-muted">
      Days
    </small>
  </div>
  <button type="submit" class="btn btn-primary ml-4">View Activity</button>
</form>
<hr>
<style>
.perm-flag {
  font-size: 25%;
}

.perm-flag:before {
  display: none;
}
</style>
<table class="table table-sm table-bordered sort">
  <thead>
    <tr>
      <th>ckey</th>
      <th data-toggle="tooltip" title="Time spent as ghost is roughly equated with active adminning. Time spent living is time roughly equated with playing instead of adminning.">Play time</th>
      <th>Connections</th>
      <th>Rank</th>
      <th class="perm-flag">Feedback</th>
      {% for name, bits in perms %}
      <th class="perm-flag">{{name}}</th>
      {% endfor %}
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
        <td>{% if a.feedback %}<a href="{{a.feedback}}" target="_blank" rel="noopener noreferrer">Thread</a>{% endif %}</td>
        {% for name, bits in perms %}
          {% if a.flags b-and bits %}
          <td class="table-success text-success text-center" data-toggle="tooltip" title="{{a.ckey}} has {{name}}"><i class="far fa-check-circle"></i></td>
          {% else %}
          <td class="table-danger text-danger text-center" data-toggle="tooltip" title="{{a.ckey}} does not have {{name}}"><i class="far fa-times-circle"></i></td>
          {% endif %}
        {% endfor %}
      </tr>
    {% endfor %}
  </tbody>
</table>

{% endblock %}