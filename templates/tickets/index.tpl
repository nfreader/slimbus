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
    <table class="table table-sm table-bordered table-nowrap">
      <thead>
        <tr>
          <th>Ticket #</th>
          <th>Server</th>
          <th>To</th>
          <th>From</th>
          <th>Message</th>
          <th>Timestamp</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        {% for t in tickets %}
          <tr class="table-{{t.status_class}}">
            <td class="align-middle text-center"><a href="{{path_for('ticket.single',{'round': t.round, 'ticket':t.ticket})}}">{{t.ticket}}</a></td>
            <td class="align-middle">{{t.server_data.name}}<br><small><a href="{{path_for('round.single',{'id': t.round})}}"><i class="far fa-circle"></i> {{t.round}}</a></small></td>
            <td class="align-middle">
              {% if t.recipient %}
              <a href="{{path_for('player.single',{'ckey': t.recipient.ckey})}}">
                {{t.recipient.label|raw}}
              </a>
            {% endif %}
            </td>
            <td class="align-middle">
              <a href="{{path_for('player.single',{'ckey': t.sender.ckey})}}">
                {{t.sender.label|raw}}
              </a>
            </td>
            <td class="align-middle table-wrap">{{t.message|raw}}</td>
            <td class="align-middle">{{t.timestamp|timestamp}}</td>
            <td class="align-middle">{{t.status}}<br><small>{{t.replies}} replies</small></td>
          </tr>
        {% else %}
        {% endfor %}
      </tbody>
    </table>
  {% include 'components/pagination.html' with vars %}
  {% endblock %}
