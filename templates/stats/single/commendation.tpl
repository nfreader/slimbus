{% for c in stat.output %}

<div class="card text-center mb-2" id="{{c.id}}">
  <div class="card-header">
    <i class="fas fa-certificate"></i> Official Nanotrasen Commendation <a href="#{{c.id}}">#{{c.id}}</a>
  </div>
  <div class="card-body">
    <h4 class="card-title"><em>{{c.medal}}</em><br>is awarded to <br>{{c.commendee}}</h4>
    <p class="card-text">
      <blockquote class="blockquote">
      <p>&mdash; For &mdash;</p>
      <p>{{c.reason}}</p>
      <footer class="blockquote-footer">Awarded by <cite>{{c.commender}}</cite></footer>
      </blockquote>
    </p>
  </div>
  {% if round.id %}
  <div class="card-footer text-muted">
    <a href="{{path_for('round.single',{'id':round.id})}}">
      <i class="fas fa-circle"></i> {{stat.round_id}}
    </a>
  </div>
  {% endif %}
</div>

{% endfor %}