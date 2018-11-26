<h3>Crew Manifest</h3>
<hr>
<p class="text-muted">In no particular order</p>
<table class="table table-bordered table-condensed table-hover sort">
  <thead>
    <tr>
      <th>ckey</th>
      <th>Name</th>
      <th>Job</th>
      <th>Role</th>
      <th>Roundstart?</th>
    </tr>
  </thead>
  <tbody>
    {% for c in crew %}
      <tr class="{% if c.role %}table-danger{% endif %}">
        <td>
        {% if user.canAccessTGDB %}
          <a href="tgdb/player.php?ckey={{c.ckey}}">{{c.ckey}}</a>
        {% else %}
          {{c.ckey}}
        {% endif %}
       </td>
        <td>{{c.name}}</td>
        <td>{{c.job}}</td>
        <td>{{c.role}}</td>
        <td>
          {% if c.roundstart %}
            <span class="badge badge-success">Roundstart</span>
          {% else %}
            <span class="badge badge-warning">Latejoin</span>
          {% endif %}
        </td>
      </tr>
    {% endfor %}
  </tbody>
</table>
