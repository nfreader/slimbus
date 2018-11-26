<table class="table table-sm table-bordered sort">
  <thead>
    <th>{{stat.label.key}}</th>
    <th>{{stat.label.subvalue}}</th>
    <th>{{stat.label.value}}</th>
  </thead>
  <tbody>
  {% for k,v in stat.data %}
    {% for path, value in v %}
    <tr>
      <th>{{k}}</th>
      <td>{{path}}</td>
      <td>{{value}}</td>
    </tr>
    {% endfor %}
  {% endfor %} 
  </tbody>
</table>
