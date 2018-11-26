<table class="table table-sm table-bordered sort">
  <thead>
    <th>Security Level</th>
    <th>Times Escalated</th>
  </thead>
  <tbody>
    {% for key, value in stat.data %}
      <tr>
        <th class="seclevel-{{key}}">
          {{ key|capitalize }}
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
        Total Escalations
      </th>
      <td>
        {{ stat.total }}
      </td>
    </tr>
  </tfoot>
</table>
