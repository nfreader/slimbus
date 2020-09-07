{% extends "base/index.html"%}
{%block content %}
{% if user.ckey %}
<div class="page-header">
  {% if statbus.auth.remote_auth %}
  <a class="badge badge-danger float-right" href="{{path_for('logout')}}">Logout</a>
  {% endif %}
  <h1><small class="text-muted">You are</small> {{user.label|raw}}</h1>
</div>
<hr>

<p class="lead">
Between your first connection {{user.firstseen|timestamp}} and your most recent connection {{user.lastseen|timestamp}}, you have connected {{user.connections}} times.
</p>

<p class="lead">You have wasted <span title="About {{user.hours}}, since we started tracking time spent in roles" data-toggle="tooltip">0</span> hours playing Space Station 13, because time spent doing something you enjoy isn't wasted time.</p>

<hr>
<h3><a href="{{path_for('me.roles')}}">Role Time</a></h3>
<h3><a href="{{path_for('me.rounds')}}">Your Rounds</a></h3>
<h3><a href="{{path_for('me.messages')}}">Your Notes and Messages</a></h3>
<h3><a href="{{path_for('me.tickets')}}">Your Ahelps</a></h3>
<hr>
<div class="card">
  <h3 class="card-header"><a data-toggle="collapse" href="#lastwords" role="button" aria-expanded="false" aria-controls="lastwords">Your Last Words</a></h3>
  <div class="card-body collapse" id="lastwords">
      <ul class="list-inline">
      {% for death in lastWords %}
        <li class="list-inline-item">
          <code><em><a href="{{path_for('death.single',{'id': death.id})}}">{{death.last_words|raw}}</a></em></code>
        </li>
      {% endfor %}
      </ul>
  </div>
</div>

{% else %}
<div class="page-header">
  <h1>Hmm...</h1>
</div>
<hr>
  <p class="lead">I'm not sure who you are. Can you please authenticate with the remote website for me?</p>
  <a href="{{path_for('auth')}}" class="btn btn-success btn-lg btn-block">Authenticate</a>
{% endif %}

{% endblock %}
