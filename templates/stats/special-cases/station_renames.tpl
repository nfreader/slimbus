{% if stat.aggregate %}
  <p class="display-3">The N.T.S.S...</p>
  {% for d in stat.data %}
  <p class="display-3 text-center">
    <em>{{d}}</em>
  </p>
  {% endfor %}
  </p>
{% else %}
<p class='display-4 text-center'>{{stat.label.splain}}</p>
<p class="display-3 text-center">N.T.S.S. <em>{{stat.data}}</em></p>
{% endif %}