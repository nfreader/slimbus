<tr class="table-{{round.class}}">
  <td>
      <a href="{{path_for('round.single',{'id': round.id})}}">
        <i class="fas fa-circle"></i> {{round.id}}
      </a>
  </td>
  <td>
    {% if round.newscaster %}
      <p class="float-right mb-0" data-toggle="tooltip" title="Newscaster stories!"><i class="far fa-newspaper"></i></p>
    {% endif %}
    <i class="fas fa-fw fa-{{round.icons.mode}}"></i> {{round.mode}}</td>
  <td><i class="fas fa-fw fa-{{round.icons.result}}"></i> {{round.result}}</td>
  <td>{{round.map}}</td>
  <td>{{round.start_datetime}}</td>
  <td>{{round.duration}}</td>
  <td>{{round.end_datetime}}</td>
  <td>{{round.server}}</td>
</tr>
