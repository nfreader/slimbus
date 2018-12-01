{% if stat is iterable %}
  {% set stat = stat[0] %}
{% endif %}
<ul class="list-group">
{% for data in stat.output %}
  <a  class="list-group-item" href="https://github.com/{{statbus.github}}/pull/{{data.number}}" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt"></i> {{data.title}} by {{data.author}}</a>
{% endfor %}
</ul>