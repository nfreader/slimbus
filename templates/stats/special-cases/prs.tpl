{% if stat is iterable %}
  {% set stat = stat[0] %}
{% endif %}
<ul class="list-inline">
{% for data in stat.data|keys %}
  <li class="list-inline-item">
    <a href="https://github.com/{{app.project_github}}/pull/{{data}}"
    target="_blank" rel="noopener noreferrer" class="display-4"><i class="fas fa-external-link-alt"></i> {{data}}</a>
  </li>
{% endfor %}
</ul>