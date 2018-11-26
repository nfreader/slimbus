{% if stat is iterable %}
  {% set stat = stat[0] %}
{% endif %}

<div class='alert alert-danger'>The Emergency Shuttle Has Been Called!</div>
<ul class="list-group">
{% for data in stat.output %}
  {% if loop.last and not stat.aggregate %}
    <li class="list-group-item list-group-item-success text-center" data-toggle="tooltip" title="Successful shuttle call">
  {% else %}
    <li class="list-group-item text-center" data-toggle="tooltip" title="Unsuccessful shuttle call">
  {% endif %}
  {{data|raw}}
  </li>
{% endfor %}

