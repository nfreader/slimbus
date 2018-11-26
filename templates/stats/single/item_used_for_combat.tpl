<table class="table table-sm table-bordered sort">
  <thead>
    <th>{{stat.label.key}}</th>
    <th>{{stat.label.value}}</th>
    <th>{{stat.label.value2}}</th>
    <th>Total Damage By Item</th>
  </thead>
  <tbody>
  {% set grandTotal = 0 %}
  {% for k, v in stat.data %}
    {% for path, value in v %}
    <tr>
      <th>{{path}}</th>
      <td>{{value}}</td>
      <td>{{k}}</td>
      <td>{{value * k}}{% set grandTotal = grandTotal + (value * k) %}</td>
    </tr>
    {% endfor %}
  {% endfor %} 
    <tfoot>
    <tr style="border-top: 2px solid grey;">
      <th colspan="3">
        Total Damage Caused
      </th>
      <td>
        {{ grandTotal }}
      </td>
    </tr>
  </tfoot>
  </tbody>
</table>
