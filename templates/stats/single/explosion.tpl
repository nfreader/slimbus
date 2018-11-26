{% if stat is iterable %}
  {% set stat = stat[0] %}
{% endif %}

<table class="table table-sm table-bordered sort">
  <thead>
    <tr>
      <th>Devastation</th>
      <th>Heavy</th>
      <th>Light</th>
      <th>Flash</th>
      <th>Flame</th>
      <th>Location</th>
    </tr>
  </thead>
  <tbody>
    {% for e in stat.output %}
    <tr>
      <td class="align-middle text-center">{{e.dev}} <span class='text-danger'>({{e.orig_dev}})</span></td>
      <td class="align-middle text-center">{{e.heavy}} <span class='text-danger'>({{e.orig_heavy}})</span></td>
      <td class="align-middle text-center">{{e.light}} <span class='text-danger'>({{e.orig_light}})</span></td>
      <td class="align-middle text-center">{{e.flash}}</td>
      <td class="align-middle text-center">{{e.flame}}</td>
      <td class="align-middle">{{e.area}}<br>
        <small>({{e.x}}, {{e.y}}, {{e.z}}) @ {{e.time}}</small>
      </td>
    </tr>
    {% endfor %}
  </tbody>
</table>
