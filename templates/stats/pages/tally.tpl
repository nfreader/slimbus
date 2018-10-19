{% if stat.label.splain %}
<div class="alert alert-secondary" role="alert">
  {{stat.label.splain}}
</div>
{% endif %}
<table class="table table-sm table-bordered sort">
  <thead>
    <th>{{stat.label.key}}</th>
    <th>{{stat.label.value}}</th>
  </thead>
  <tbody>
    {% for key, value in stat.data %}
      <tr>
        <th>
          {{ key }}
        </th>
        <td>
          {{ value }}
        </td>
      </tr>
    {% endfor %}
    </tbody>
    <tfoot>
    <tr style="border-top: 2px solid grey;">
      <th>
        Total {{ stat.label.value }}
      </th>
      <td>
        {{ stat.total }}
      </td>
    </tr>
  </tfoot>
</table>
