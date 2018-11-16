<table class="table table-bordered table-condensed table-hover">
  <thead>
    <tr>
      <th>Timestamp</th>
      <th>uid</th>
      <th>X</th>
      <th>Y</th>
      <th>Z</th>
      <th>Content</th>
    </tr>
  </thead>
  <tbody>
  {% for line in file %}
    <tr>
      <td class="align-middle">{{line.timestamp}}</td>
      <td class="align-middle" style="background: #{{line.color}}"><code class="bg">{{line.device}}</code></td>
      <td class="align-middle">{{line.x}}</td>
      <td class="align-middle">{{line.y}}</td>
      <td class="align-middle">{{line.z}}</td>
      <td class="align-middle">{{line.text|raw}}</td>
    </tr>
  {% endfor %}
  </tbody>
</table>