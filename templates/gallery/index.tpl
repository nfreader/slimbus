{% extends "base/index.html"%}
{%block content %}

<h1>Art Gallery</h1>
<hr>
<p class="lead">Selected works from various station community art galleries.</p>
<div class="list-group">
{% for server in servers %}
  <a href="{{path_for('gallery.index',{'server': server.name})}}" class="list-group-item list-group-item-action">{{server.name}}</a>
{% endfor %}
</div>

{% endblock %}