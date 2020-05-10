{% extends "base/index.html"%}
{%block content %}

<h1>{{server.name}} Art Gallery</h1>
<hr>
<div class="row">
  <div class="col-md-4">
    <h2>Public Collection</h2>
    <hr>
    <ul class="list-unstyled">
    {% for collection in art.library %}
      <li class="media mb-2" id="{{collection.md5}}">
      <img class="mr-3" src="{{url}}/library/{{collection.md5}}.png" width="64" height="64" />
      <div class="media-body">
        <h5 class="mt-0 mb-1">{{collection.title|raw}}</h5>
        <small><a href="#{{collection.md5}}"><i class="fas fa-link"></i></a></small>
      </div>
    </li>
    {% endfor %}
    </ul>
  </div>
  <div class="col-md-4">
    <h2>Private Collection</h2>
    <hr>
    <ul class="list-unstyled">
    {% for collection in art.library_private %}
      <li class="media mb-2" id="{{collection.md5}}">
      <img class="mr-3" src="{{url}}/library_private/{{collection.md5}}.png" width="64" height="64" />
      <div class="media-body">
        <h5 class="mt-0 mb-1">{{collection.title|raw}}</h5>
        <p class="mb-0">{{collection.ckey}}</p>
        <small><a href="#{{collection.md5}}"><i class="fas fa-link"></i></a></small>
      </div>
    </li>
    {% endfor %}
    </ul>
  </div>
  <div class="col-md-4">
    <h2>Secure Collection</h2>
    <hr>
    <ul class="list-unstyled">
    {% for collection in art.library_secure %}
      <li class="media mb-2" id="{{collection.md5}}">
      <img class="mr-3" src="{{url}}/library_secure/{{collection.md5}}.png" width="64" height="64" />
      <div class="media-body">
        <h5 class="mt-0 mb-1">{{collection.title|raw}}</h5>
        <p class="mb-0">{{collection.ckey}}</p>
        <small><a href="#{{collection.md5}}"><i class="fas fa-link"></i></a></small>
      </div>
    </li>
    {% endfor %}
    </ul>
  </div>
</div>


{% endblock %}