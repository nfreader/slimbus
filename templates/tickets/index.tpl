{% extends ('base/index.html') %}
  {% block content %}
  {% include 'tgdb/html/nav.html' %}
  <div class="row">
    <div class="col">
    {% set vars = {
      'nbPages': ticket.pages,
      'currentPage': ticket.page,
      'url': path_for('ticket.index')
      } 
    %}
    {% include 'components/pagination.html' with vars %}
    </div>
  </div>
  <div class="list-group">
    {% for t in tickets %}
      <a class="list-group-item list-group-item-action list-group-item-{{t.status_class}}" href="{{path_for('ticket.single',{'round': t.round, 'ticket':t.ticket})}}">
        <div class="d-flex w-100 justify-content-between">
          <span><h5>{% if t.bwoink %}<i class="fa-fw fas fa-ticket-alt text-danger" title="*BWOINK*" data-toggle="tooltip"></i>{% else %}<i class="fa-fw fas fa-ticket-alt"></i>{% endif %} {{t.round}}-{{t.ticket}} <small>{% if t.recipient %}
              {{t.recipient.label|raw}} to 
            {% endif %}
            {{t.sender.label|raw}}</small></h5></span>
          <small>{{t.timestamp|timestamp}}<br>{{t.replies}} replies</small>
        </div>
        <div class="d-flex w-100 justify-content-between">
          <span class="flex-grow-1">{{t.message|raw}}</span>
          <span><strong>Last Status: </strong> {{t.status}}</span>
        </div>
      </a>
    {% endfor %}
  </div>
  <br>

  {% include 'components/pagination.html' with vars %}
  {% endblock %}
