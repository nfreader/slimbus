<form action="{{path_for('library.delete',{'id': book.id})}}" method="POST">
  <input type="hidden" name="{{csrf.keys.name}}" value="{{csrf.name}}">
  <input type="hidden" name="{{csrf.keys.value}}" value="{{csrf.value}}">
{% if book.deleted %}
  <button class="btn btn-success float-right"><i class="fas fa-check"></i> Undelete Book</button>
</form>
{% else %}
  <button class="btn btn-danger float-right"><i class="fas fa-times"></i> Delete Book</button>
{% endif %}
</form>