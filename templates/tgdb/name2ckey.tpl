{% extends ('base/index.html') %}
    {% block content %}
    {% include 'tgdb/html/nav.html' %}
      <div class="row">
        <div class="col-md-12">
          <h2>Resolve Character Name to a ckey</h2>
          <hr>
          <form action="{{path_for('name2ckey')}}" method="POST" class="form-inline">
            <label class="sr-only" for="name">Character Name</label>
              <input type="text" class="form-control mb-2 mr-sm-2 col-md-6" id="name" placeholder="Character Name" name="name" value="{{name}}">
              <button type="submit" class="btn btn-primary mb-2">Search</button>
          </form>
          <hr>
          <table class="table table-sm table-bordered">
            <thead>
              <tr>
                <th>Times Seen</th>
                <th>Character Name</th>
                <th>ckey</th>
              </tr>
            </thead>
            <tbody>
              {% for r in results %}
              <tr>
                <td>{{r.count}}</td>
                <td>{{r.name}}</td>
                <td><a href="{{path_for('player.single',{'ckey': r.byondkey})}}">{{r.byondkey}}</a></td>
              </tr>
              {% else %}
              <tr><td colspan='3' class='text-center'>No results</td></tr>
              {% endfor %}
            </tbody>
          </table>
    {% endblock %}