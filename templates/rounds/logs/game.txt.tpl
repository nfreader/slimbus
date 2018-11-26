{% block content %}
{% set vars = {
  'nbPages': round.pages,
  'currentPage': round.page,
  'url': path_for('round.gamelogs',{'id':round.id})
  } 
%}
{% include 'components/pagination.html' with vars %}
<table class="table table-sm table-bordered roundLogs">
  <thead>
    <tr>
      <th>Timestamp</th>
      <th>X</th>
      <th>Y</th>
      <th>Z</th>
      <th>Area</th>
      <th>Type</th>
      <th>Text</th>
    </tr>
  </thead>
  <tbody>
    {% if not file %}
    <tr>
      <td colspan='7' class="text-center">Alt DB not configured. No parsed logs available</td>
    </tr>
    {% endif %}
    {% for log in file %}
    <tr id="{{log.id}}">
      <td class="align-middle timestamp"><a href='#{{log.id}}'>{{log.timestamp}}</a></td>
      <td class="align-middle coord x">{{log.x}}</td>
      <td class="align-middle coord y">{{log.y}}</td>
      <td class="align-middle coord z">{{log.z}}</td>
      <td class="align-middle area">{{log.area|raw}}</td>
      <td class="align-middle type">{{log.type}}</td>
      <td class="align-middle log-{{log.type}}">{{log.text|raw}}</td>
    </tr>
    {% endfor %}
  </tbody>
</table>
{% include 'components/pagination.html' with vars %}
{% endblock %}
