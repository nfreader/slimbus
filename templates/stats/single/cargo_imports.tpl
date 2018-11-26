{% if stat.label.splain %}
<div class="alert alert-secondary" role="alert">
  {{stat.label.splain}}
</div>
{% endif %}
{% set grandTotal = 0 %}
{% set totalOrdered = 0 %}
<table class="table table-sm table-bordered sort">
  <thead>
    <th>Crate</th>
    <th>Number Ordered</th>
    <th>Cost</th>
    <th>Total Spent</th>
  </thead>
  <tbody>
  {% for k,v in stat.output %}
    {% for path, value in v %}
    <tr>
      <th>{{path}}</th>
      <td>{{value}}{% set totalOrdered = totalOrdered + value %}</td>
      <td>{{k}}</td>
      <td>{% set rowTotal = value * k %}{% set grandTotal = grandTotal + rowTotal %}{{rowTotal}}</td>
    </tr>
    {% endfor %}
  {% endfor %} 
    <tfoot>
    <tr style="border-top: 2px solid grey;">
      <th>
        Total Crates Ordered
      </th>
      <td>{{ totalOrdered }}</td>
      <th>Total Spent</th>
      <td>
        {{ grandTotal }}
      </td>
    </tr>
  </tfoot>
  </tbody>
</table>
