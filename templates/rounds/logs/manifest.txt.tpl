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
