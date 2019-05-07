{% for r in poll.results %}
{% if r.replytext == 'ABSTAIN' %}
  <p class="text-center text-muted">&laquo; Abstained &raquo;</p>
{% else %}
  <dl class="row" id="{{r.id}}">
    <dt class="col-md-2">
      {{r.datetime}}<br>
      <a href="#{{r.id}}">#{{r.id}}</a>
      {% if user.ckey and user.level >= 2 %}
        | <a href="{{app.url}}tgdb/player.php?ckey={{r.ckey}}">
          {{r.ckey}}
        </a>
      {% endif %}
    </dt>
    <dd class="col-md-10">
      <blockquote class="blockquote">
        {{r.replytext|nl2br}}
      </blockquote>
    </dd>
  </dl>
  {% endif %}
  {% if loop.last %}
  {% else %}
  <hr>
  {% endif %}

{% endfor %}