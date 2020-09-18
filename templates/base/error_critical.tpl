{% extends "base/index.html"%}

{% block nav %}

<nav class="navbar navbar-expand-md navbar-dark navbar-{{app.bodyClass}} fixed-top bg-dark">
  {% if wide is defined and wide %}
    <div class="container-fluid" id="navcontainer">
  {% else %}
    <div class="container" id="navcontainer">
  {% endif %}
    <a class="navbar-brand" href="{{path_for('statbus')}}"> {{ statbus.app_name }} </a>
    <div class="collapse navbar-collapse" id="navbar">
    <span class="navbar-text">
      {% spaceless %}
      {% if settings.debug %}
        <span class="badge badge-danger ml-2 px-2">
          <span class="text-light">DEBUG MODE</span>
        </span>
      {% endif %}
      {% endspaceless %}
    </span>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="farlse" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>
{% endblock %}

{% block content %}

<div class="card text-white bg-danger mb-3">

  <div class="card-body">
    <h5 class="card-title mb-0"><i class="fas fa-exclamation-triangle"></i> {{statbus.app_name}} has encountered a critical error code {{code}}: <br><br>{{message}}</h5>
    <hr>
    <p class="card-text">{{text}}</p>
  </div>
</div>


{% endblock %}