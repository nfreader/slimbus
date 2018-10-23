<nav class="navbar navbar-expand-md navbar-dark navbar-{{app.bodyClass}} fixed-top bg-dark">
  {% if wide is defined and wide %}
    <div class="container-fluid" id="navcontainer">
  {% else %}
    <div class="container" id="navcontainer">
  {% endif %}
    <a class="navbar-brand" href="{{path_for('statbus')}}"> {{ statbus.app_name }} </a>
    <div class="collapse navbar-collapse" id="navbar">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="{{path_for('round.index')}}">
            <i class="fas fa-circle"></i>
            Rounds
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{path_for('death.index')}}">
            <i class="fas fa-user-times"></i>
            Deaths
          </a>
        </li>
        {% if user.canAccessTGDB %}
        <li class="nav-item">
          <a class="nav-link text-danger" href="{{path_for('tgdb')}}">
            <i class="fas fa-shield-alt"></i>
            TGDB
          </a>
        </li>
        {% endif %}
    </div>
    {% if user.level >= 2 %}
      <form class="form-inline">
          <input class="form-control mr-sm-2 form-control-sm" type="search" placeholder="ckey" aria-label="Search" id="tgdbsearch">
        </form>
    {% endif %}
    <span class="navbar-text">
      {% spaceless %}
      {% if user.ckey %}
        <a href="{{path_for('me')}}">{{user.label|raw}}</a>
      {% else %}
      <a href="{{path_for('auth')}}">
        <span class="badge badge-secondary ml-2">Authenticate</span>
      </a>
      {% endif %}
      {% if app.constants.DEBUG %}
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