{% extends "base/index.html"%}
{% block pagetitle %}Library Catalog{% endblock %}
{% block content %}
<h1>Library</h1>
<hr>
<div class="row">
  <div class="col">
  {% set vars = {
    'nbPages': library.pages,
    'currentPage': library.page,
    'url': library.url,
    'query': library.query
    } 
  %}
  {% include 'components/pagination.html' with vars %}
  </div>
  <div class="col">
    <form class="form-inline float-right" action="{{path_for('library.index')}}" method="GET">
       <input name="query" class="form-control mr-2" type="text" value="{{library.search}}">
       <button class="btn btn-primary">Search</button>
     </form>
   </div>
</div>
<table class="table table-sm table-bordered">
    <thead>
      <tr>
        <th><abbr title="Nanotrasen Book Number" class="initialism" data-toggle="tooltip">NTBN</abbr></th>
        <th>Title</th>
        <th>Author</th>
        <th>Category</th>
      </tr>
    </thead>
    <tbody>
    {% for book in books %}
      <tr class="table-{{book.class}}">
        <td>
          <a href="{{path_for('library.single',{'id': book.id})}}">
            <i class="fas fa-book"></i> {{book.id}}
          </a>
        </td>
        <td>{{book.title|raw}}</td>
        <td>{{book.author}}</td>
        <td>{{book.category}}</td>
      </tr>
    {% endfor %}
    </tbody>
  </table>
  {% include 'components/pagination.html' with vars %}
{% endblock %}