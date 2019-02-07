{% extends ('base/index.html') %}
  {% block content %}
  {% include 'tgdb/html/nav.html' %}

    <div class="row">
      <div class="col-md-12">
        <h2>Update Your Feedback Link</h2>
        <hr>
        <form action="{{path_for('admin.feedback')}}" method="POST" class="form-inline">
          <input type="hidden" name="{{csrf.keys.name}}" value="{{csrf.name}}">
          <input type="hidden" name="{{csrf.keys.value}}" value="{{csrf.value}}">
          <label class="sr-only" for="feedback">Feedback URL</label>
            <input type="text" class="form-control mb-2 mr-sm-2 col-md-6" id="feedback" placeholder="URL of your Feedback Thread" name="feedback" value="{{feedback}}">
            <button type="submit" class="btn btn-primary mb-2">Submit</button>
        </form>
        <p>This link will be visible on the admin activity pages, and may in the future be made available in-game via adminwho.</p>

  {% endblock %}