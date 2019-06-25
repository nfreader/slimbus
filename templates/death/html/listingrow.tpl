<tr class="table-{{death.class}}" data-id="{{death.id}}">
  <td class="align-middle">
    <a href="{{path_for('death.single',{'id': death.id})}}">
      <i class="fas fa-skull-crossbones"></i> {{death.id}}
    </a>
  </td>
  <td class="align-middle">{{death.name}} - {{death.job}} {% if death.special %}<span class='badge badge-danger'>{{death.special}}</span> {% endif %}<br>
    <small>
    {% if user.level >= 2 %}
      <a href="{{app.url}}tgdb/player.php?ckey={{death.byondkey}}">{{death.byondkey}}</a>
    {% else %}
      {{death.byondkey}}
    {% endif %}
    <!-- {% if death.suicide %}
      <span class="badge badge-inverse">SUICIDE</span>
    {% endif %}
    {% if death.lakey %}
      <span class="badge badge-danger">MURDER</span>
    {% endif %} --></small>
  </td>
  <td class="align-middle">{{death.mapname}} - {{death.pod}} ({{death.x}}, {{death.y}}, {{death.z}})<br>
      {% if death.last_words %} <small><em>{{death.last_words}}</em></small> {% endif %}
  </td>
  <td class="align-middle">{{death.tod|timestamp}}<br>
    <small><a href="{{path_for('round.single',{'id': death.round})}}"><i class="far fa-circle"></i> {{death.round}}</a> - {{death.server}}</small>
  </td>
  <td class="align-middle">
    {% include 'death/html/vitals.html' %}
  </td>  
</tr>