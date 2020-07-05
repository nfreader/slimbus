{% extends "index.tpl"%}
{% block content %}
<h1>Mapularity</h1>
<hr>
<table class="table table-sm table-bordered sort">
  <thead>
    <tr>
      <th>Month</th>
      {% for map in maps %}
        <th>{{map}}</th>
      {% endfor %}
    </tr>
  </thead>
  <tbody>
    {% for month, data in mapularity %}
      <tr>
        <th>{{month}}</th>
        {% for m in maps %}
          <td>{{data[m]}}</td>
        {% endfor %}
      </tr>
    {% endfor %}
  </tbody>
</table>
{% endblock %}
