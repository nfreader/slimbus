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
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="" role="button" aria-haspopup="true" aria-expanded="farlse"><i class="fas fa-university"></i> Library</a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{path_for('library.index')}}"><i class="fas fa-book"></i> Book Catalog</a>
            <a class="dropdown-item" href="{{path_for('gallery.index')}}"><i class="fas fa-palette"></i> Art Gallery</a>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="" role="button" aria-haspopup="true" aria-expanded="farlse"><i class="fas fa-info-circle"></i> Info</a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{path_for('population')}}"><i class="fas fa-users"></i> Server Population</a>
            <a class="dropdown-item" href="{{path_for('playtime')}}"><i class="fas fa-chart-line"></i> Playtime</a>
            <a class="dropdown-item" href="{{path_for('winloss')}}"><i class="fas fa-percent"></i> Gamemode Win Loss Ratios</a>
            <a class="dropdown-item" href="{{path_for('mapularity')}}"><i class="fas fa-map-marked-alt"></i> Mapularity</a>
            <a class="dropdown-item" href="{{path_for('admin_connections')}}"><i class="fas fa-user-clock"></i> Admin Activity</a>
            <a class="dropdown-item" href="{{path_for('admin_logs')}}"><i class="fas fa-user-times"></i> Admin Rank Activity</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="" role="button" aria-haspopup="true" aria-expanded="farlse"><i class="fas fa-globe"></i> Extra</a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="{{path_for('stat.rounds',{'stat': 'newscaster_stories'})}}"><i class="fas fa-newspaper"></i> Newscaster Stories</a>
          </div>
        </li>
        {% if settings.election_mode %}
        <li class="nav-item">
          <a class="nav-link text-info" href="{{path_for('election')}}">
            <i class="fas fa-vote-yea"></i>
            Elections
          </a>
        </li>
        {% endif %}
        {% if user.canAccessTGDB %}
        <li class="nav-item">
          <a class="nav-link text-danger" href="{{path_for('tgdb')}}">
            <i class="fas fa-shield-alt"></i>
            TGDB
          </a>
        </li>
        {% endif %}
    </div>
    {% if user.canAccessTGDB %}
      <form class="form-inline">
        <div class="typeahead__container">
          <input class="form-control mr-sm-2 form-control-sm js-typeahead" type="search" placeholder="ckey" aria-label="Search" id="tgdbsearch">
        </div>
      </form>
    {% endif %}
    <span class="navbar-text">
      {% spaceless %}
      {% if user.ckey %}
        <a href="{{path_for('me')}}">{{user.label|raw}}</a>
      {% elseif statbus.auth.remote_auth %}
      <a href="{{path_for('auth')}}">
        <span class="badge badge-secondary ml-2">Authenticate</span>
      </a>
      {% endif %}
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
