{% extends "index.tpl"%}
{% block content %}
<h2><code>{{stat.key_name}}</code><small>
  {% if stat.aggregate %}
    across {{stat.round_count}} rounds<br>between {{stat.earliest}} and {{stat.latest}}
  {% else %}
    <a href="{{app.url}}round.php?round={{stat.round_id}}">
      <i class="far fa-circle"></i> {{stat.round_id}}
    </a>
  {% endif %}
  </small>
</h2>
<hr>
{% if stat.special %}
  {% include 'stats/special-cases/' ~ stat.key_name ~'.tpl' ignore missing %}
{% elseif stat.key_type == "nested tally" %}
  {% include "stats/pages/nested-tally.tpl" %}
{% elseif stat.key_type == "tally" %}
  {% include "stats/pages/tally.tpl" %}
{% elseif stat.key_type == "text" %}
  {% include "stats/pages/text.tpl" %}
{% elseif stat.key_type == "associative" %}
  {% include "stats/pages/associative.tpl" %}
{% elseif stat.key_type == "amount" %}
  {% include "stats/pages/amount.tpl" %}
{% else %}
  <div class="alert alert-info">This stat type doesn't have a view yet.</div>
{% endif %}

<h3 class="mt-2">Technical Data</h3>
<hr>
{% if not stat.aggregate %}

<dl class="row">
  <dt class="col-sm-3">Stat ID</dt>
  <dd class="col-sm-9">{{stat.id}}</dd>

  <dt class="col-sm-3">Stat Type</dt>
  <dd class="col-sm-9"><code>{{stat.key_type}}</code></dd>

  <dt class="col-sm-3">Stat Name</dt>
  <dd class="col-sm-9"><code>{{stat.key_name}}</code></dd>

  <dt class="col-sm-3">Timestamp Recorded</dt>
  <dd class="col-sm-9">{{stat.datetime}}</dd>

  <dt class="col-sm-3">Stat Version</dt>
  <dd class="col-sm-9">{{stat.version}}</dd>

  <dt class="col-sm-3">Raw JSON</dt>
  <dd class="col-sm-9"><pre>{{stat.json}}</pre></dd>
</dl>
{% else %}
<dl class="row">

  <dt class="col-sm-3">Stat Type</dt>
  <dd class="col-sm-9"><code>{{stat.key_type}}</code></dd>

  <dt class="col-sm-3">Stat Name</dt>
  <dd class="col-sm-9"><code>{{stat.key_name}}</code></dd>

  <dt class="col-sm-3">Rounds Seen</dt>
  <dd class="col-sm-9"><code>{{stat.round_count}}</code></dd>

  <dt class="col-sm-3">Round Range</dt>
  <dd class="col-sm-9"><code>{{stat.earliest_round}} - {{stat.latest_round}}</code></dd>

  <dt class="col-sm-3">Date Range</dt>
  <dd class="col-sm-9"><code>{{stat.earliest}} - {{stat.latest}}</code></dd>

</dl>
{% endif %}
{% if app.constants.DEBUG %}
{{dump(stat)}}
{% endif %}
{% endblock %}