{% if stat.key_name == "commendation" %}
  {% include "stats/special-cases/commendation.tpl" %}
{% elseif stat.key_name == "time_dilation_current" %}
  {% include "stats/special-cases/time_dilation_current.tpl" %}
{% elseif stat.key_name == "antagonists" %}
  {% include "stats/special-cases/antagonists.tpl" %}
{% elseif stat.key_name == "antagonists" %}
  {% include "stats/special-cases/antagonists.tpl" %}
{% else %}

{% endif %}