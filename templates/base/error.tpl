{% extends "index.tpl"%}

{% block content %}

<div class="jumbotron">
  <h1 class="display-3">{{app.error.code}}</h1>
  <p class="lead">{{app.error.message|raw}}</p>
  <p class="lead">
    {% if app.error.link %}
    <a class="btn btn-primary btn-lg" href="{{app.error.link}}" role="button">{{app.error.linkText}}</a>
    {% endif %}
    <a class="btn btn-primary btn-lg" href="{{app.url}}" role="button">Go Home</a>
  </p>
</div>


{% endblock %}