<table class="table table-bordered table-condensed table-hover">
  <thead>
    <tr>
      <th>Timestamp</th>
      <th>Type</th>
      <th>From</th>
      <th>To</th>
      <th>Message</th>
    </tr>
  </thead>
  <tbody>
  {% for line in file %}
    <tr id="{{line.id}}">
      <td class="align-middle"><a href="#{{line.id}}">{{line.timestamp}}</td>
      <td class="align-middle">{{line.type}}</td>
      <td class="align-middle">{{line.from_character}}/<small>{{line.from_ckey}}</small><br>
        <small>With PDA {{line.from_device}} at {{line.from_area}} ({{line.from_x}}, {{line.from_y}}, {{line.from_z}})</small></td>
      <td class="align-middle">{{line.to_character}} ({{line.to_job}})</td>
      <td class="align-middle">{{line.message|raw}}</td>
    </tr>
  {% endfor %}
  </tbody>
</table>