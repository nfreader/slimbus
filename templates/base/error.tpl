{% extends "base/index.html"%}

{% block content %}

<div class="jumbotron">
  <h1 class="display-3"><small class="text-muted" style="font-size: 2rem;">error code</small> {{code}}</h1>
  <p class="lead">{{message}}</p>
  <p class="lead">
    {% if link %}
    <a class="btn btn-success btn-lg" href="{{link}}" role="button">{{linkText}}</a>
    {% endif %}
    <a class="btn btn-primary btn-lg" href="{{path_for('statbus')}}" role="button">Go Home</a>
  </p>
</div>


{% endblock %}