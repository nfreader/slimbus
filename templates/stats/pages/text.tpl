<p class='display-4 text-center'>{{stat.label.splain}}</p>
{% if stat.data is not iterable %}
  <p class="display-1 text-center">{{stat.data}}</p>
{% else %}
  <ul class="list-inline">
  {% for data in stat.data %}
    <li class="list-inline-item">{{data}}</li>
  {% endfor %}
  </ul>
{% endif %}

