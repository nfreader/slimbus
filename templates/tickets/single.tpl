{% extends ('base/index.html') %}
  {% block content %}
  {% include 'tgdb/html/nav.html' %}

  <h1>Ticket #{{tickets[0].ticket}}</h1><small>Round {{tickets[0].round}} on {{tickets[0].server_data.name}}</small>
  <hr>
  {% for ticket in tickets %}
    {% if ticket.interval %}<div class="d-flex justify-content-end ticket-diff"><code title="Time elapsed" data-toggle="tooltip">- <i class="fas fa-stopwatch"></i>  {{ticket.interval}} -</code></div>{% endif %}
    {% include('tickets/html/ticket.html') %}
  {% endfor %}

  {% endblock %}
