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
    <tr id="{{log.id}}" data-line="{{log.id}}">
      <td class="align-middle timestamp">{{log.timestamp}}</td>
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
{% block js %}
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script>
  $('tr').click(function(e){
    start = $(this).attr('data-line')
    window.history.replaceState(null, null, "#" + start)
    console.log(this)
    $(this).toggleClass('targeted')
    if(e.shiftKey && start){
      end = $(this).attr('data-line')
      range = start+" to "+end
      console.log(range)
    }
  });
</script>
{% endblock %}
