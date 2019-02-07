{% extends ('base/index.html') %}
  {% block content %}
  {% include 'tgdb/html/nav.html' %}
    <div class="row">
      <div class="col-md-6">
        <h2>TGDB</h2>
        <hr>
        <p class="lead"><span class="label label-danger">NEW!</span> Update your <a href="{{path_for('admin.feedback')}}"> Feedback Link</a></p>
      </div>
      <div class="col-md-6">
        <h2>Admin Memos</h2>
        <hr>
      {% for message in memos %}
        {% include 'messages/html/single.html' %}
      {% endfor %}
    </div>
  {% endblock %}