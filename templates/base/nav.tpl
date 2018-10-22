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
        <!-- <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="" role="button" aria-haspopup="true" aria-expanded="farlse">
            <i class="fas fa-table"></i></i> Data</a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{app.url}}stat.php">Stats</a>
            <a class="dropdown-item" href="{{app.url}}library.php">Library</a>
            <a class="dropdown-item" href="{{path_for('death.index')}}">Deaths</a>
            {% if app.constants.PUBLIC_BANS %}
              <a class="dropdown-item" href="{{app.url}}ban.php">Ban List</a>
            {% endif %}
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="" role="button" aria-haspopup="true" aria-expanded="farlse"><i class="fas fa-code"></i> Coderbus</a>
          <div class="dropdown-menu">
            {% if app.constants.CODERBUS_GBP %}
            <a class="dropdown-item" href="{{app.url}}coderbus.php"><i class="farb fa-github"></i>
              GBP Status
            </a>
            {% endif %}
            <a class="dropdown-item" href="{{app.url}}coderbus.php?repo">
              Local Repo Status
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{app.url}}coderbus.php?icons">
              Codebase Icons
            </a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="" role="button" aria-haspopup="true" aria-expanded="farlse"><i class="fas fa-info-circle"></i> Info</a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{app.url}}info.php?admins">Admin Activity</a>
            <a class="dropdown-item" href="{{app.url}}info.php?pop">Server Population</a>
            <a class="dropdown-item" href="{{app.url}}info.php?winloss">Game Mode Win/Loss Ratios</a>
            <a class="dropdown-item" href="{{app.url}}poll.php">Polls</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="" role="button" aria-haspopup="true" aria-expanded="farlse">
            <i class="fas  fa-snowflake"></i> Extra</a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{app.url}}extra.php?myrounds"><i class="fas  fa-location-arrow"></i> My Rounds</a>
            <a class="dropdown-item" href="{{app.url}}extra.php?news"><i class="fas fa-newspaper"></i> Newscasters</a>
          </div>
        </li> -->
        <!-- {% if user.ckey and user.level >= 2 %}
        <li class="nav-item dropdown">
          <a class="nav-link text-danger dropdown-toggle" data-toggle="dropdown" href="{{ app.url }}tgdb" role="button" aria-haspopup="true" aria-expanded="farlse">
            <i class="fas  fa-shield-alt"></i> TGDB</a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ app.url }}tgdb">TGDB Home</a>
            <a class="dropdown-item" href="{{ app.url }}tgdb/notes.php">Note Database</a>
            <a class="dropdown-item" href="{{ app.url }}tgdb/ban.php">Ban Database</a>
            <a class="dropdown-item" href="{{app.url}}tgdb/info.php?admins">Admin Connections</a>
          </div>
        </li>
        {% endif %} -->
    </div>
    {% if user.level >= 2 %}
      <form class="form-inline">
          <input class="form-control mr-sm-2 form-control-sm" type="search" placeholder="ckey" aria-label="Search" id="tgdbsearch">
        </form>
    {% endif %}
    <span class="navbar-text">
      {% spaceless %}
      {% if user.ckey %}
        <a href="{{app.url}}me.php">{{user.label|raw}}</a>
      {% elseif 'REMOTE' == app.constants.AUTH_MODE %}
      <a href="{{app.url}}auth.php">
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