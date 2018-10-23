{% extends ('base/index.html') %}
  {% block content %}
  {% include 'tgdb/html/nav.html' %}
    <div class="row">
      <div class="col-md-6">
        <h2>TGDB</h2>
        <hr>
        <p class="text-danger">VERY SECRET PAGE!!!!!</p>
        <span class="tlp tlp-green">1234567890</span>
        <span class="tlp tlp-amber">1234567890</span>
        <span class="tlp tlp-red">1234567890</span>
      </div>
      <div class="col-md-6">
        <h2>Admin Memos</h2>
        <hr>
      {% for message in memos %}
        {% include 'messages/html/single.html' %}
      {% endfor %}
    </div>
  {% endblock %}