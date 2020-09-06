{% extends ('base/index.html') %}
  {% block content %}
  
  <h1>Ticket #{{tickets[0].ticket}}</h1><small>Round {{tickets[0].round}} on {{tickets[0].server_data.name}}</small>
  <hr>
  <div class="row">
    <div class="col-md-9">
  {% for ticket in tickets %}
    {% if ticket.interval %}<div class="d-flex justify-content-end ticket-diff"><code title="Time elapsed" data-toggle="tooltip">- <i class="fas fa-stopwatch"></i>  {{ticket.interval}} -</code></div>{% endif %}
    {% include('tickets/html/ticket.html') %}
  {% endfor %}
    </div>
    <div class="col-md-3">
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
