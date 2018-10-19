{% if stat.label.splain %}
<div class="alert alert-secondary" role="alert">
  {{stat.label.splain}}
</div>
{% endif %}
<table class="table table-sm table-bordered sort">
  <thead>
    <th>{{stat.label.key}}</th>
    <th>{{stat.label.value}}</th>
    <th>{{stat.label.value2}}</th>
  </thead>
  <tbody>
  {% for k,v in stat.data %}
    {% for path, value in v %}
    <tr>
      <th>{{path}}</th>
      <td>{{value}}</td>
      <td>{{k}}</td>
    </tr>
    {% endfor %}
  {% endfor %} 
  </tbody>
</table>
