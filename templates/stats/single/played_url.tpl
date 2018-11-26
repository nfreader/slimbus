{% if stat.label.splain %}
<div class="alert alert-secondary" role="alert">
  {{stat.label.splain}}
</div>
{% endif %}
<table class="table table-sm table-bordered sort">
  <thead>
    <th>Admin</th>
    <th>URL Played</th>
    <th>Times Played</th>
  </thead>
  <tbody>
  {% for k,v in stat.output %}
    {% for path, value in v %}
    <tr>
      <th>{{k}}</th>
      <td><a href="{{path}}" target="_blank" rel="noopener noreferrer"><i class='fas fa-external-link-alt'></i> {{path}}</a></td>
      <td>{{value}}</td>
    </tr>
    {% endfor %}
  {% endfor %} 
  </tbody>
</table>
