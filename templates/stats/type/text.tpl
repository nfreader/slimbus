<p class='display-4 text-center'>{{stat.label.splain}}</p>
{% if stat.output is not iterable %}
  <span class="display-4"><code>{{stat.key_name}}</code>:</span> 
  <span class="display-1"><strong>{{stat.output}}</strong></span>
{% else %}
  <ul class="list-inline">
  <span class="display-4"><code>{{stat.key_name}}</code>:</span> 
  {% for data in stat.output %}
    <li class="display-1 list-inline-item">{{data}}</li>
  {% endfor %}
  </ul>
{% endif %}

