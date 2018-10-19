{% for c in stat.data %}

<div class="card text-center mb-2">
  <div class="card-header">
    Official Nanotrasen Commendation
  </div>
  <div class="card-body">
    <h4 class="card-title"><em>{{c.medal}}</em> is awarded to {{c.commendee}}</h4>
    <p class="card-text">
      <blockquote class="blockquote">
      <p>{{c.reason}}</p>
      <footer class="blockquote-footer">Awarded by <cite>{{c.commender}}</cite></footer>
      </blockquote>
    </p>
  </div>
  {% if stat.round_id %}
  <div class="card-footer text-muted">
    <a href="{{app.url}}round.php?round={{stat.round_id}}">
      <i class="far fa-circle"></i> {{stat.round_id}}
    </a>
  </div>
  {% endif %}
</div>

{% endfor %}