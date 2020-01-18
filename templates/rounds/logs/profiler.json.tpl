<div class="alert alert-secondary">Procs with 0 across the board and 1 call have been removed from this display for the sake of efficency</div>
<table class="table table-bordered table-condensed table-sm table-hover sort">
  <thead>
    <tr>
      <th>Name</th>
      <th>Self</th>
      <th>Total</th>
      <th>Real</th>
      <th>Over</th>
      <th>Calls</th>
    </tr>
  </thead>
  <tbody>
  {% for line in file %}
    <tr>
      <td>{{line.name}}</td>
      <td>{{line.self}}</td>
      <td>{{line.total}}</td>
      <td>{{line.real}}</td>
      <td>{{line.over}}</td>
      <td>{{line.calls}}</td>
    </tr>
  {% endfor %}
  </tbody>
</table> 