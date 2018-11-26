{% for c in stat.output %}
  <div class="alert alert-info">Colony Dropped At {{c.x}}, {{c.y}}, {{c.z}}</div>
{% endfor %}