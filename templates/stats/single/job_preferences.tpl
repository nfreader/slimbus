<table class="table table-sm table-bordered sort">
  <thead>
    <th>Job</th>
    <th>High</th>
    <th>Medium</th>
    <th>Low</th>
    <th>Never</th>
    <th>Banned</th>
    <th>Too Young</th>
  </thead>
  <tbody>
    {% for j, p in stat.output %}
    <tr>
      <th>
        {{j}}
      </th>
      {% for v in p %}
        <td>{{v}}</td>
      {% endfor %}
    </tr>
    {% endfor %}
  </tbody>
</table>

