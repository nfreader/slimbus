<table class="table table-sm table-bordered sort">
<thead>
  <tr>    
    <th>Votes</th>   
    <th>Percentage</th>   
    <th>Option</th>   
  </tr>   
</thead>
<tbody>
{% for r in poll.results %}
<tr>
  <th>
    {{r.votes}}
  </th>
  <td>
    {{r.percent}}
  </td>
  <td>
    {{r.option|nl2br}}
  </td>
</tr>
{% endfor %}
</tbody>
</table>
<p class="text-muted"><em>Percentages are rounded down to the nearest whole number for the sake of readability</em></p>