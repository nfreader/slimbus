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
      <th><a href="{{path}}" target="_blank" rel="noopener noreferrer"><i class='fas fa-external-link-alt'></i> {{path}}</a></th>
      <td>{{value}}</td>
      <td>{{k}}</td>
    </tr>
    {% endfor %}
  {% endfor %} 
  </tbody>
</table>
