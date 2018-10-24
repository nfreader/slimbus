{% macro ckey(name, ckey) %}
  {% if name is null %}
    {% if user.canAccessTGDB %}
      <a href="{{path_for('player.single',{'ckey': ckey})}}">{{ckey}}</a>
    {% else %}
      {{ckey}}
    {% endif %}
  {% else %}
    {% if user.canAccessTGDB %}
      <a href="{{path_for('player.single',{'ckey': ckey})}}">{{name}}/<small class="text-muted">{{ckey}}</small></a>
    {% else %}
      {{name}}/<small class="text-muted">{{ckey}}</small>
    {% endif %}
  {% endif %}
{% endmacro %}