{% extends "index.tpl"%}
{% block content%}
<h4><small class="text-muted">#{{poll.id}} {{poll.createdby_ckey}} asks:</small><br>
  {{poll.question|nl2br}}</h4>
<hr>

<div class="row mb-4">
  <div class="col text-right lead">
    <strong>Started</strong><br>
    {{poll.starttime|timestamp|raw}}
  </div>
  <div class="col text-center lead">
    <strong>Duration</strong><br>
    {{poll.duration}}
  </div>
  <div class="col lead text-center">
    <strong>Responses</strong><br>
    {{poll.totalVotes}}
    {% if poll.filtered %}
    <br><small>Does NOT reflect filtered votes!</small>
    {% endif %}
  </div>
  <div class="col lead">
    <strong>Ended</strong><br>
    {{poll.endtime|timestamp|raw}}
  </div>
</div>
{% if poll.ended %}
<div class="alert alert-info">This poll has ended</div>
{% endif %}

{% if poll.filtered %}
<div class="alert alert-danger"><strong>VIEWING FILTERED RESULTS</strong></div>
{% endif %}
<hr>

{% if poll.polltype == 'TEXT' %}
  {% include 'polls/types/text.tpl' %}
{% elseif poll.polltype == 'MULTICHOICE' %}
  {% include 'polls/types/option.tpl' %}  
{% elseif poll.polltype == 'OPTION' %}
  {% include 'polls/types/option.tpl' %}  
{% elseif poll.polltype == 'IRV' %}
  {% include 'polls/types/irv.tpl' %}  
{% endif %}


{% endblock %}