{% if stat is iterable %}
  {% set stat = stat[0] %}
{% endif %}

{% set totalPeeps = stat.data.ghosts + stat.data.survivors.total %}
{% set totalDead = stat.data.ghosts %}
{% set totalSurvivors = stat.data.survivors.total %}

{% if stat.data.escapees is iterable %}
  {% set totalEscapees = stat.data.escapees.total %}
{% else %}
  {% set totalEscapees = 0 %}
{% endif %}

<div class="progress">
  <div class="progress-bar bg-danger" role="progressbar" style="width:{{totalDead / totalPeeps * 100}}%;" data-toggle="tooltip" title="{{totalDead}} dead">{{totalDead}} dead</div>
  <div class="progress-bar bg-success" role="progressbar" style="width:{{(totalSurvivors - totalEscapees) / totalPeeps * 100}}%;" data-toggle="tooltip" title="{{totalSurvivors}} survivors">{{totalSurvivors}} survivors</div>
  {% if stat.data.escapees > 0 %}
  <div class="progress-bar bg-success progress-bar-striped" role="progressbar" style="width:{{totalEscapees / totalPeeps * 100}}%;" data-toggle="tooltip" title="{{totalEscapees}} escaped alive">
  {{totalEscapees}} escaped alive</div>
  {% endif %}
</div>
<small><p class="text-muted text-right pt-2 mb-0">(This is an approximation and may not reflect actual station population)</p></small>
