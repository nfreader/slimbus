{% extends "index.tpl"%}
{% block content%}
{% include('rounds/html/basic.tpl') %}

{% if round.extraData == "manifest.txt" %}
  {% set crew = round.logs %}
  {% include "rounds/detail/manifest.tpl" with crew %}
{% elseif round.extraData == "pda.txt" %}
  {% include "rounds/detail/pda.tpl" %}
{% elseif round.extraData == "newscaster.json" %}
  {% include "rounds/detail/newscaster.tpl" %}
{% elseif round.extraData == "game" %}
  {% include "rounds/detail/gameLogs.tpl" %}
{% elseif round.extraData == "round_end_data.json" %}
  {% include "rounds/detail/round_end.tpl" %}
{% else %}
  <h3><code>{{round.extraData}}</code></h3>
  <hr>
  <p class="text-muted">Lines are color-coded for your convenience!</p>
  <table class="table table-bordered table-condensed table-hover">
    <thead>
      <tr>
        <th>Timestamp</th>
        <th>uid</th>
        <th>X</th>
        <th>Y</th>
        <th>Z</th>
        <th>Content</th>
      </tr>
    </thead>
    <tbody>
      {% for d in round.logs %}
        <tr>
          <td>{{d.timestamp}}</td>
          <td style="background: #{{d.color}}"><code>{{d.uid}}</code></td>
          <td>{{d.x}}</td>
          <td>{{d.y}}</td>
          <td>{{d.z}}</td>
          <td>{{d.content|raw}}</td>
        </tr>
      {% endfor %}
    </tbody>
  </table>
{% endif %}

{% endblock %}

{% block js %}
<script>
  $.ajax({
    url: '{{app.url}}api/parsedLogStatus.php',
    data: {
      round: {{round.id}}
    },
    method: 'GET'
  })
  .done(function(e){
    console.log(e);
    if (e){
      $('#logStatus').toggleClass('btn-warning').toggleClass('disabled').toggleClass('btn-success').text('View parsed');
    } else {
      $('#logStatus').text('Parsing...');
      $.ajax({
        url: '{{app.url}}api/processRoundLogFile.php',
        data: {
          round: {{round.id}}
        },
        method: 'GET'
      })
      .done(function(e){
        console.log(e);
        $('#logStatus').toggleClass('btn-warning').toggleClass('disabled').toggleClass('btn-success').text('View parsed').attr('data-toggle','tooltip').attr('title',e);
      })
    }
  });
</script>
{% endblock %}