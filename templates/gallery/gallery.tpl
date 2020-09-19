{% extends "base/index.html"%}
{%block content %}
<style>
.hide {
    display: none;
}

.clear {
    float: none;
    clear: both;
}

.rating {
    width: 90px;
    unicode-bidi: bidi-override;
    direction: rtl;
    text-align: center;
    position: relative;
}

.rating > label {
    float: right;
    display: inline;
    padding: 0;
    margin: 0;
    position: relative;
    width: 1.1em;
    cursor: pointer;
    color: #000;
}

.rating > label:hover,
.rating > label:hover ~ label,
.rating > input.radio-btn:checked ~ label {
    color: transparent;
}

.rating > label:hover:before,
.rating > label:hover ~ label:before,
.rating > input.radio-btn:checked ~ label:before,
.rating > input.radio-btn:checked ~ label:before {
    content: "\2605";
    position: absolute;
    left: 0;
    color: #FFD700;
}
</style>
<h1>{{server.name}} Art Gallery</h1>
<hr>
<div class="row">
  <div class="col-md-4">
    <h2>Public Collection</h2>
    <hr>
    <ul class="list-unstyled">
    {% set path = 'library' %}
    {% for collection in art.library %}
      {% include 'gallery/html/artwork.tpl' %}
    {% endfor %}
    </ul>
  </div>
  <div class="col-md-4">
    <h2>Private Collection</h2>
    <hr>
    <ul class="list-unstyled">
    {% set path = 'library_private' %}
    {% for collection in art.library_private %}
      {% include 'gallery/html/artwork.tpl' %}
    {% endfor %}
    </ul>
  </div>
  <div class="col-md-4">
    <h2>Secure Collection</h2>
    <hr>
    <ul class="list-unstyled">
    {% set path = 'library_secure' %}
    {% for collection in art.library_secure %}
      {% include 'gallery/html/artwork.tpl' %}
    {% endfor %}
    </ul>
  </div>
</div>

{% endblock %}

{% block js %}
<script>

$('form.star_rating .radio-btn').change(function(e){
  var form = $(this).parent().parent()
  $.ajax({
    url: form.attr('action'),
    method: form.attr('method'),
    data: form.serialize()
  }).done(function(reply){
    $('.sb_csrf_name').val(reply.csrf.csrf.name)
    $('.sb_csrf_value').val(reply.csrf.csrf.value)
    $('#collection-'+reply.votes.artwork+' #rating').text(reply.votes.rating)
    $('#collection-'+reply.votes.artwork+' #votes').text(reply.votes.votes)
  })
})

</script>
{% endblock %}