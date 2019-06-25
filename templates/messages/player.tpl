{% extends "base/index.html"%}
{% block pagetitle %}Messages for {{player.ckey}}{% endblock %}
{% block content %}
<h2>Messages for <code>{{player.ckey}}</code></h2>
<hr>
  {% set vars = {
    'nbPages': message.pages,
    'currentPage': message.page,
    'url': message.url
    } 
  %}
  {% include 'components/pagination.html' with vars %}
  {% for message in messages %}
    {% include 'messages/html/single.html' %}
  {% endfor %}

  {% include 'components/pagination.html' with vars %}
{% endblock %}

