{% extends "index.tpl"%}
{% block content%}
<h2>{{player.label|raw}}
  <small class="text-muted"><a href="http://www.byond.com/members/{{player.ckey}}" target="_blank" rel="noopener noreferrer"><i class="fars fa-external-link-alt"></i> Byond</a> | <a href="https://tgstation13.org/tgdb/playerdetails.php?ckey={{player.ckey}}" target="_blank" rel="noopener noreferrer"><i class="fars fa-external-link-alt"></i> tgdb</a></small>
</h2>
<hr>
<div class="row">
  <div class="col">
    <ul class="list-group">
      <li class="list-group-item">
        <strong>First Seen</strong> {{player.firstseen|timestamp|raw}}
      </li>
      <li class="list-group-item">
        <strong>Last Seen</strong> {{player.lastseen|timestamp|raw}}
      </li>
      <li class="list-group-item">
        <strong>Last IP Address</strong> <span class="tlp tlp-red">{{player.ip_real}}</span>
      </li>
      <li class="list-group-item">
        <strong>Last ComputerID</strong> <span class="tlp tlp-red">{{player.computerid}}</span>
      </li>
    </ul>
  </div>
  <div class="col">
    <ul class="list-group">
      <li class="list-group-item list-group-item-{{player.standingClass}}">
        <strong>Account Standing</strong> {{player.standing}}
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <strong>IPs seen</strong> <span class='badge badge-pill badge-primary'>{{player.ips|length}}</span>
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <strong>ComputerIDs seen</strong> <span class='badge badge-pill badge-primary'>{{player.cids|length}}</span>
      </li>
      {% if user.level >= 3 %}
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <strong>Watermark</strong><div style='position: relative;'>{{player.adminbarcode|raw}}</div>
      </li>
      {% endif %}
    </ul>
  </div>
  <div class="col">
    <ul class="list-group">
      <li class="list-group-item" style="background-color: {{player.design.backColor}}; color: {{player.design.foreColor}};">
        <span title="Ranks are currently not working reliably" data-toggle="tooltip"><strong>Rank</strong> {{player.rank}}</span>
      </li>
      <li class="list-group-item">
        <strong>Connection Count</strong> {{player.connections}}
      </li>
      <li class="list-group-item">
        <strong>Playtime</strong> ~{{player.hours}} hours<br>
        <small>Since role time tracking was enabled</small>
        {% set total = player.ghost + player.living %}
        <div class="progress">
          <div class="progress-bar bg-danger" role="progressbar" style="width: {{player.ghost/total * 100}}%" data-toggle="tooltip" title="Ghost: {{player.ghost}} minutes"></div>
          <div class="progress-bar bg-success" role="progressbar" style="width: {{player.living/total * 100}}%" data-toggle="tooltip" title="Living: {{player.living}} minutes"></div>
        </div>
      </li>
      <li class="list-group-item">
        <strong>Byond Account Join Date</strong> {{player.accountjoindate}}
      </li>
    </ul>
  </div>
</div>
<hr>
<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-header" data-target="#iplist" data-toggle="collapse">
        IP Addresses ({{player.ips|length}})
      </div>
      <ul class="list-group list-group-flush collapse" id="iplist">
      {% for ip in player.ips %}
        {% include 'tgdb/player/html/iplinks.html' %}
      {% endfor %}
      </ul>
    </div>
  </div>
  <div class="col">
    <div class="card">
      <div class="card-header" data-target="#cidlist" data-toggle="collapse">
        Computer IDs ({{player.cids|length}})
      </div>
      <ul class="list-group list-group-flush collapse" id="cidlist">
      {% for cid in player.cids %}
        {% include 'tgdb/player/html/cidlinks.html' %}
      {% endfor %}
      </ul>
    </div>
  </div>
</div>
<hr>
<div class="card">
  <h3 class="card-header">Role Time</h3>
  <div class="card-body">
    <div id="roletime">

    </div>
    <p>(Tracked over time since around July of 2017)</p>
  </div>
</div>
<hr>
<div class="card mb-4">
  {% if player.messages|length > 3 %}
    <h3 class="card-header" data-target="#msglist" data-toggle="collapse">Messages <small>({{player.messages|length}})</small></h3>
    <div class="card-body collapse" id="msglist">
  {% else %}
    <h3 class="card-header">Messages</h3>
    <div class="card-body" id="msglist">
  {% endif %}
    {% for message in player.messages %}
      {% include 'tgdb/notes/html/message_view.html' %}
    {% endfor %}
  </div>
</div>
{% endblock %}
{% block js %}
<script type="text/javascript" src="https://cdn.plot.ly/plotly-latest.min.js"></script>

<script>
var data = {{player.role_time|raw}};
var jobs = unpack(data,'job');
var minutes = unpack(data,'minutes');
var trace1 = {
  x: jobs,
  y: minutes,
  type: 'bar',
  name: 'Minutes'
}

var layout = {
  title: 'Role Time (Minutes)',
};
var data = [trace1]
Plotly.newPlot('roletime',data, layout)
</script>
{% endblock %}
