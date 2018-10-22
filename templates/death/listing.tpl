{% extends "base/index.html"%}
{% block pagetitle %}Death Listing{% endblock %}
{% block content %}
<div class="row">
  <div class="col">
    {% set vars = {
      'nbPages': death.pages,
      'currentPage': death.page,
      'url': death.url
      } 
    %}
  {% include 'components/pagination.html' with vars %}
  </div>
  <div class="col text-right">
    <a class="btn btn-primary" href="{{path_for('death.lastwords')}}">Some famous last words</a>
  </div>
</div>
<table class="table table-sm table-bordered">
  {% include 'death/html/table-header.html' %}
  <tbody>
  {% for death in deaths %}
    {% include 'death/html/listingrow.tpl' %}
  {% endfor %}
  </tbody>
</table>
  {% include 'components/pagination.html' with vars %}
{% endblock %}
