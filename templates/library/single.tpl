{% extends "index.tpl"%}
{% block content %}
{% if book.deleted and not user.canAccessTGDB %}
  <div class="card">
    <h3 class="card-header">[Book Deleted]</h3>
    <div class="card-body">
      <p class="text-center">&laquo; No content &raquo;</p>
    </div>
  </div>
{% else %}
  {% if book.deleted and user.canAccessTGDB %}
    <div class="alert alert-danger"><strong>Deleted!</strong> This book has been deleted</div>
  {% endif %}
  <div class="card border-{{book.class}}">
    <h3 class="card-header border-{{book.class}}">{{book.title|raw}}
      <small class="text-muted">By {{book.author}}
        {% if user.canAccessTGDB %}
        | <a href="{{path_for('player.single',{'ckey': book.ckey})}}">
          {{book.ckey}}
        </a>
        {% endif %}
      </small>
    </h3>
    <div class="card-body">
      {% if user.ckey %}
        {{book.content|raw}}
      {% elseif book.nsfw %}
        <div class="alert alert-danger"><strong>Censored!</strong> You must be <a href="{{path_for('auth')}}">logged in</a> to read books in this category!</div>
        {{book.content|censor|nl2br}}
      {% else %}
        {{book.content|raw}}
      {% endif %}
    </div>
    <div class="card-footer">
      Published {{book.datetime|timestamp}}
      {% if book.round_id_created %}
      during <a href="{{path_for('round.single',{'id': book.round_id_created})}}"><i class="fas fa-circle"></i> {{book.round_id_created}}</a>
      {% endif %}
      {% if user.canAccessTGDB %}
        {% include 'library/html/delete.tpl' %}
      {% endif %}
    </div>
  </div>
{% endif %}
{% endblock %}