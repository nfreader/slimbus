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
    <tr>
      <td class="align-middle">{{line.timestamp}}</td>
      <td class="align-middle">{{line.ckey}}</td>
      <td class="align-middle">{{line.character}}</td>
      <td class="align-middle">{{line.job}}</td>
      <td class="align-middle">{{line.special}}</td>
      <td class="align-middle">{% if line.when %}Roundstart{% else %}Latejoin{% endif %}</td>
    </tr>
  {% endfor %}
  </tbody>
</table>