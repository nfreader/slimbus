{% extends ('base/index.html') %}
  {% block content %}
    {% include 'messages/html/single.html' %}
    {% if message.lasteditor %}
    <h2>Edit History</h2>
    <hr>
    {{message.edits|raw}}
    {% endif %}
  {% endblock %}