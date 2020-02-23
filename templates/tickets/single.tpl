{% extends ('base/index.html') %}
  {% block content %}
  {% include 'tgdb/html/nav.html' %}
  <div class="row">
    <div class="col-md-9">
      <h3>Ticket #{{tickets[0].ticket}}</h3>
      <hr>
  {% for ticket in tickets %}
    {% if ticket.interval %}<div class="d-flex justify-content-end ticket-diff"><code title="Time elapsed" data-toggle="tooltip">- <i class="fas fa-stopwatch"></i>  {{ticket.interval}} -</code></div>{% endif %}
    {% include('tickets/html/ticket.html') %}
  {% endfor %}
    </div>
    <div class="col-md-3">
      <h3><small class="text-muted">Round:</small><a href="{{path_for('round.single', {'id': tickets[0].round})}}">{{tickets[0].round}}</a> (<a href="{{path_for('ticket.round', {'round': tickets[0].round})}}">T</a>)</h3>
      <hr>
      <hr>
      <h3><small class="text-muted">Server:</small><br>{{tickets[0].server_data.name}}</h3>
      <hr>
      <h3 class="text-{{tickets|last.class}}"><small class="text-muted">Status:</small><br>{{tickets|last.action}}</h3>
      <hr>
      <strong class="text-muted">Labels</strong>
      <span class="badge badge-{{tickets|last.class}} d-block mb-1">{{tickets|last.action}}</span>
      {% if tickets|first.bwoink %}
        <span class='badge badge-danger d-block mb-1'>*BWOINK*</span>
      {% endif %}
      {% if tickets|length == 1 %}
        <span class="badge badge-dark d-block mb-1">Unanswered <i class="fa fas fa-frown"></i></span>
      {% endif %} 
      {% if tickets|last.action == 'Reply' %}
        <span class="badge badge-secondary d-block mb-1">Unresolved</span>
      {% endif %}
      <hr>
    </div>
  </div>

  {% endblock %}
