{% extends "index.tpl"%}
{% block content %}
<h2>Stats for {{stat.date}}</h2>
<hr>
<table class="table table-bordered table-sm">
  <thead>
    <tr>
      <th>Stat Name</th>
      <th># of times recorded</th>
      <th>Stat Type</th>
    </tr>
  </thead>
  <tbody>
    {% for s in stats %}
      <tr>
        <td><a href="{{app.url}}stat.php?stat={{s.key_name}}&year={{stat.year}}&month={{stat.month}}">{{s.key_name}}</a></td>
        <td>{{s.times}}</td>
        <td>{{s.key_type}}</td>
      </tr>
    {% endfor %}
  </tbody>
</table>
{% endblock %}