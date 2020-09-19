<li class="media mb-4" id="collection-{{collection.md5}}">
      <img class="mr-3" src="{{url}}/{{path}}/{{collection.md5}}.png" width="128" height="128" />
      <div class="media-body">
        <h3 class="mt-0 mb-1"><a href="#collection-{{collection.md5}}"><i class="fas fa-link"></i></a> {{collection.title|raw}}</h3>
        {% if collection.rating %}
          <span class="h5" id="rating">{{collection.rating}}</span><span class="text-muted">/5</span> (<span id="votes">{{collection.votes}}</span>)
        {% endif %}
        <form class="star_rating" action="{{path_for('gallery.index',{'server':server.name})}}" method="POST">
        <input type="hidden" class="sb_csrf_name" name="{{csrf.keys.name}}" value="{{csrf.name}}">
        <input type="hidden" class="sb_csrf_value" name="{{csrf.keys.value}}" value="{{csrf.value}}">
        <input type="hidden" name="artwork" value="{{collection.md5}}">
          <div class="rating">
            <input id="{{collection.md5}}_star5" name="rating" type="radio" value="5" class="radio-btn hide" />
            <label for="{{collection.md5}}_star5">☆</label>
            <input id="{{collection.md5}}_star4" name="rating" type="radio" value="4" class="radio-btn hide" />
            <label for="{{collection.md5}}_star4">☆</label>
            <input id="{{collection.md5}}_star3" name="rating" type="radio" value="3" class="radio-btn hide" />
            <label for="{{collection.md5}}_star3">☆</label>
            <input id="{{collection.md5}}_star2" name="rating" type="radio" value="2" class="radio-btn hide" />
            <label for="{{collection.md5}}_star2">☆</label>
            <input id="{{collection.md5}}_star1" name="rating" type="radio" value="1" class="radio-btn hide" />
            <label for="{{collection.md5}}_star1">☆</label>
        </div>
    </form>
      </div>
    </li>