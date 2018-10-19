{% if stat is iterable %}
  {% set stat = stat[0] %}
{% endif %}
{%block content %}
{% if stat.label.splain %}
  <div class="alert alert-secondary" role="alert">
    {{stat.label.splain}}
  </div>
  <p class="display-4 text-center">
  {% if stat.data.1.holder %}
    Held by <strong>{{stat.data.1.holder}}</strong> at [{{stat.data.1.x}},{{stat.data.1.y}},{{stat.data.1.z}}]
  {% else %}
    [{{stat.data.1.x}},{{stat.data.1.y}},{{stat.data.1.z}}]
  {% endif %}
  </p>


{% endif %}
{% endblock %}
