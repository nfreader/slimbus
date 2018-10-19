{% if stat is iterable %}
  {% set stat = stat[0] %}
{% endif %}

<div class='alert alert-danger'>{{stat.label.splain}}</div>
<ul class="list-group">
{% for data in stat.data %}
  {% if loop.last and not stat.aggregate %}
    <li class="list-group-item list-group-item-success" data-toggle="tooltip" title="Successful shuttle call">
  {% else %}
    <li class="list-group-item" data-toggle="tooltip" title="Unsuccessful shuttle call">
  {% endif %}
  </li>
{% endfor %}

