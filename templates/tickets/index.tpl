{% extends ('base/index.html') %}
  {% block content %}
  {% include 'tgdb/html/nav.html' %}
  <div class="row">
    <div class="col">
    </div>
  </div>
  <div class="list-group">
    {% for t in tickets %}
      <a class="list-group-item list-group-item-action pl-2" href="{{path_for('ticket.single',{'round': t.round, 'ticket':t.ticket})}}">
        <div class="d-flex">
          <h3 class="pr-2"><i class="fa-fw fas fa-{{t.icon}} text-{{t.status_class}}" title="Last Action: {{t.status}}" data-toggle="tooltip"></i></h3> <span>{{t.message|raw}}{% if t.bwoink %} <span class='badge badge-danger'>*BWOINK*</span>
      {% endif %} <small class="d-block text-muted ">#{{t.round}}-{{t.ticket}} by {{t.sender.label|raw}} {% if t.recipient %} to {{t.recipient.label|raw}}
            {% endif %} {{t.timestamp|timestamp}} on {{t.server_data.name}}</small></span>
          <span class="ml-auto align-self-center text-muted"><i class="fas fa-comment-alt"></i> {{t.replies}}</span>
        </div>
          
      </a>
    {% endfor %}
  </div>
  <br>
  {% set vars = {
    'nbPages': ticket.pages,
    'currentPage': ticket.page,
    'url': path_for('ticket.index')
    } 
  %}
  <div class="d-flex justify-content-center">{% include 'components/pagination.html' with vars %}</div>
  {% endblock %}
