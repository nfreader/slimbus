<h3>Newscaster Logs</h3>
<hr>

{% for channel in round.logs %}
<div class="card mb-3">
  <h3 class="card-header">{{attribute(channel, 'channel name')}} <small class='text-muted'>{{channel.author}}</small></h3>
  <ul class="list-group list-group-flush">
  {% for m in channel.messages %}
    <li class="list-group-item list-group-item-action flex-column align-items-start" id="{{m.id}}">
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1">{{m.author}}</h5>
          <small class="text-muted">
            <a href="#{{m.id}}">{{attribute(m, 'time stamp')}}</a>
          </small>
        </div>
        {% if attribute(m, 'photo file') %}
        <img class="img-thumbnail float-right" width='256' height='256' src="data:image/png;base64,{{attribute(m, 'photo file')}}" />
        {% endif %}
        <p class="mb-1">{{m.body|raw}}</p>
        {% if m.comments %}
          <hr>
          <ul class="list-unstyled">
          {% for c in m.comments %}
            <li><strong>{{c.author}}</strong>: {{c.body}}</li>
          {% endfor %}
          </ul>
        {% endif %}
      </li>
  {% endfor %}
  </ul>
</div>
{% endfor %}