{% extends "base/index.html"%}
{% import('components/macros.tpl') as macros %}
{% block pagetitle %}Death #{{death.id}}{% endblock %}
{% block content %}
<h2>
  <small class='text-muted'><i class='fas fa-user-times'></i> {{death.id}}</small> {{macros.ckey(death.name, death.byondkey)}}
</h2>
<hr>

{% if death.lakey %}
<div class="row">
  <div class="col">
    <div class="card mb-4">
      <h3 class="card-header bg-danger text-white">Murder Suspect</h3>
      <div class="card-body h4">
        {{macros.ckey(death.laname, death.lakey)}}
      </div>
    </div>
  </div>
</div>
<hr>
{% endif %}

{% if death.suicide %}
<div class="row">
  <div class="col">
    <div class="card mb-4">
      <h3 class="card-header bg-dark text-white">Suicide</h3>
    </div>
  </div>
</div>
<hr>
{% endif %}

<div class="row">
  <div class="col-lg-4 col-md-12">
    <div class="card mb-4">
      <h3 class="card-header">Cause of Death</h3>
      <div class="card-body h4">
        {{death.cause}}
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-12">
    <div class="card mb-4">
      <h3 class="card-header">Vital Signs</h3>
      <div class="card-body h4">
        {% include 'death/html/vitals.html' %}
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-12">
    <div class="card mb-4">
      <h3 class="card-header">Rank</h3>
      <div class="card-body h4">
        {{death.job}} 
        {% if death.special %}
        <span class="badge badge-danger">{{death.special}}</span>
        {% endif %}
      </div>
    </div>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-lg-6">
    <div class="card mb-4">
      <h3 class="card-header">Location</h3>
      <div class="card-body h4">
        {{death.mapname}} - {{death.pod}} ({{death.x}}, {{death.y}}, {{death.z}})
        {% if death.z == 2 %}
        <iframe src="https://atlantaned.space/renderbus/#5/{{death.x}}/{{death.y}}/{{death.map_url}}"></iframe><br>
            <a href="https://atlantaned.space/renderbus/#5/{{death.x}}/{{death.y}}/{{death.map_url}}" target="_blank" rel="noopener noreferrer">Full view</a>
        {% endif %}
      </div>
    </div>
  </div>
  <div class="col-lg-6 col-md-12">
    <div class="card mb-4">
      <h3 class="card-header">Time of Death</h3>
      <div class="card-body h4">
        {{death.tod}}
        <small>{{death.server}} - <a href="{{path_for('round.single',{'id': death.round})}}">
      <i class="fas fa-circle"></i> {{death.round}}</a>
        </small>
      </div>
    </div>
  </div>
</div>
{% if death.last_words %}
<hr>
<div class="row">
  <div class="col">
    <div class="card mb-4">
      <h3 class="card-header">Last Words</h3>
      <div class="card-body">
        <blockquote class="blockquote text-right">
          <p class="mb-0">{{death.last_words}}</p>
          <footer class="blockquote-footer">{{macros.ckey(death.name, death.byondkey)}}, {{death.last_line}}</footer>
        </blockquote>
    </div>
  </div>

</div>
</div>
{% endif %}

{% endblock %}
