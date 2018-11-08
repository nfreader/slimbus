{% extends ('base/index.html') %}
    {% block content %}
      <h1>Welcome to {{statbus.app_name}}!<br>
        {% if user.ckey and poly %}
          <div id="poly" class="engradio">
            [Poly] &ldquo;{{poly}}&rdquo;
            <img src="https://atlantaned.space/statbus/icons/mob/animal/parrot_sit.png" height="64" width="64"  alt="And now a word from Poly" />
          </div>
      </small>
      {% endif %}
      </h1>
      <hr>
      <div class="row">
        <div class="col">
          <div class="jumbotron">
            <h3 class="mb-0">
              <small class="text-muted">Cataloging</small>
            </h3>
            <h2 class="display-4 mb-0">{{numbers.deaths}}</h2>
            <h3><small class="text-muted">Total <a href="{{path_for('death.index')}}">Deaths</a></small></h3>
          </div>
        </div>
        <div class="col">
          <div class="jumbotron">
            <h3 class="mb-0">
              <small class="text-muted">With Data From</small>
            </h3>
            <h2 class="display-4 mb-0">{{numbers.rounds}}</h2>
            <h3><small class="text-muted">Total <a href="{{path_for('round.index')}}">Rounds</a></small></h3>
          </div>
        </div>
        <div class="col">
          <div class="jumbotron">
            <h3 class="mb-0">
              <small class="text-muted">And</small>
            </h3>
            <h2 class="display-4 mb-0">{{numbers.playtime}}</h2>
            <h3><small class="text-muted">Total Minutes Played</small></h3>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="jumbotron">
            <h3 class="mb-0">
              <small class="text-muted">Check Out</small>
            </h3>
            <h2 class="display-4 mb-0">{{numbers.books}}</h2>
            <h3><small class="text-muted">Books In The <a href="{{app.url}}library.php">Library</a></small></h3>
          </div>
        </div>
      </div>
    {% endblock %}