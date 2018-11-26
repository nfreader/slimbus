
<table class="table table-bordered table-condensed table-hover">
  <thead>
    <tr>
      <th>Timestamp</th>
      <th>ckey</th>
      <th>Character Name</th>
      <th>Job</th>
      <th>Special Role</th>
      <th>Roundstart/Latejoin</th>
    </tr>
  </thead>
  <tbody>
  {% for line in file %}
    <tr class="{% if line.special %}table-danger{% endif %}">
      <td class="align-middle">{{line.timestamp}}</td>
      <td class="align-middle">
        {% if user.canAccessTGDB %}
        <a href="{{path_for('player.single',{'ckey': line.ckey})}}">{{line.ckey}}</a>
        {% else %}
        {{line.ckey}}
        {% endif %}
      </td>
      <td class="align-middle">{{line.character}}</td>
      <td class="align-middle">{{line.job}}</td>
      <td class="align-middle">{{line.special}}</td>
      <td class="align-middle">
        {% if line.when %}
          <span class="badge badge-success">Roundstart</span>
        {% else %}
          <span class="badge badge-warning">Latejoin</span>
        {% endif %}
      </td>
    </tr>
  {% endfor %}
  </tbody>
</table>

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
