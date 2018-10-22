{% extends "base/index.html"%}
{% block pagetitle %}Round #{{round.id}}{% endblock %}
{% block content %}
{% if round.data.nuclear_challenge_mode %}
<div class="alert alert-danger">
  <strong>ALERT!</strong> WAR WERE DECLARED!
</div>
{% endif %}

{% if round.userWasAntag %}
<div class="alert alert-success">
  <strong>Hey, neat!</strong> Looks like you were an antagonist this round!
</div>
{% endif %}

{% include('rounds/html/basic.tpl') %}

<h3>Basic Information</h3>
<hr>
{% if round.data.round_end_stats %}
<div class="card mb-2" id="pop">
  <div class="card-header">
    Population Stats
  </div>
  <div class="card-body">
  {% set stat = [round.data.round_end_stats] %}
  {% include('stats/special-cases/round_end_stats.tpl') with stat %}
  </div>
</div>
{% endif %}

{% if round.data.shuttle_reason %}
<div class="card mb-2" id="reason">
  <div class="card-header">
    Shuttle Reason
  </div>
  <div class="card-body">
  {% set stat = [round.data.shuttle_reason] %}
  {% include('stats/special-cases/shuttle_reason.tpl') with stat %}
  </div>
</div>
{% endif %}

{% if round.data.testmerged_prs %}
<div class="card mb-2" id="prs">
  <div class="card-header">
    Testmerged PRs
  </div>
  <div class="card-body">
  {% set stat = [round.data.testmerged_prs] %}
  {% include('stats/special-cases/prs.tpl') with stat %}
  </div>
</div>
{% endif %}

{% if round.data.antagonists %}
<div class="card mb-2" id="antags">
  <div class="card-header" data-target="#antagCollapse" data-toggle="collapse">
    Antagonists
  </div>
  <div class="card-body collapse" id="antagCollapse">
  {% set stat = [round.data.antagonists] %}
  {% include('stats/special-cases/antagonists.tpl') with stat %}
  </div>
</div>
{% endif %}

{% if round.data.explosion %}
<div class="card mb-2" id="explosions">
  <div class="card-header" data-target="#expCollapse" data-toggle="collapse">
    Explosions
  </div>
  <div class="card-body collapse" id="expCollapse">
  {% set stat = [round.data.explosion] %}
  {% include('stats/special-cases/explosion.tpl') with stat %}
  </div>
</div>
{% endif %}

{% if round.deaths %}
<div class="card mb-2" id="antags">
  <div class="card-header" data-target="#deathCollapse" data-toggle="collapse">
    Deaths
  </div>
  <div class="card-body collapse" id="deathCollapse">
  <table class="table table-sm table-bordered">
      {% include 'death/html/table-header.html' %}
    <tbody>
    {% for death in round.deaths %}
      {% include 'death/html/listingrow.tpl' %}
    {% endfor %}
    </tbody>
  </table>
  </div>
</div>
{% endif %}

{% if round.details %}
  {% if round.details.manifest %}
  <div class="card mb-2" id="manifest">
    <div class="card-header" data-target="#manifestCollapse" data-toggle="collapse">
      Station Crew Manifest
    </div>
    <div class="card-body collapse" id="manifestCollapse">
    {% set crew = round.details.manifest %}
    {% include('rounds/detail/manifest.tpl') with crew %}
    </div>
  </div>
  {% endif %}
{% endif %}

<h3>Round Stats</h3>
<hr>
<ul class="list-inline">
  {% for key, stat in round.stats %}
  <li class="list-inline-item">
    <code>
      <a href="{{path_for('round.single',{'id': round.id, 'stat':stat.key_name})}}">{{stat.key_name}}</a>
    </code>
  </li>
{% endfor %}
</ul>
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
