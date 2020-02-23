{% extends ('base/index.html') %}
{% block pagetitle %}Tickets for {{user.ckey}}{% endblock %}
{% block content %}
<h2>Tickets for <code>{{user.ckey}}</code></h2>
<hr>
  {% include 'tickets/html/listing.html' %}
  <br>
  {% set vars = {
    'nbPages': ticket.pages,
    'currentPage': ticket.page,
    'url': path_for('me.tickets')
    } 
  %}
  <div class="d-flex justify-content-center">{% include 'components/pagination.html' with vars %}</div>
  {% endblock %}
