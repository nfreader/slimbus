{% extends "base/index.html"%}
{%block content %}
{% if not user %}
<div class="page-header">
  <h1>Hmm...</h1>
</div>
<hr>
  <p class="lead">You need to be logged in in oder to vote on names</p>
  <a href="{{path_for('auth')}}" class="btn btn-success btn-lg btn-block">Authenticate</a>
{% else %}
<h1>Name Rater 5000</h1>
<hr>
<p class="lead">Just for funsies*, select whether or not the name below is good or bad, in your opinion. Remember that we're simply judging the name, not the player behind the name.</p>
<div class="jumbotron jumbotron-fluid">
  <div class="container text-center">
    <h1 class="display-1" id="name">{{name.name}}</h1>
    <h2 class="display-4" id="job">({{name.job}})</h2>
    <hr>
    <form method="POST">
    <div class="row">
      <div class="col">
      <button class="btn btn-danger btn-block" value="nay"><i class="fas fa-ban"></i> Bad Name</button>
      </form>
    </div>
    <div class="col">
      <button class="btn btn-success btn-block" value="yea"><i class="fas fa-check"></i> Good Name</button>
    </div>
    </div>
    <input type="hidden" name="name" value="{{name.name}}" id="nameField">
    </form>
  </div>
</div>
<p>* No action will be taken against anyone based on any votes cast here. Names are only being shown for the following jobs:</p>
<code>Assistant, Atmospheric Technician, Bartender, Botanist, Captain, Cargo Technician, Chaplain, Chemist, Chief Engineer, Chief Medical Officer, Cook, Curator, Detective, Geneticist, Head of Personnel, Head of Security, Janitor, Lawyer, Librarian, Medical Doctor, Quartermaster, Research Director, Roboticist, Scientist, Security Officer, Shaft Miner, Station Engineer, Virologist, Warden</code>
<p>Meaning antagonist roles, ghost spawns, etc are not being shown.</p>
<p>As species data is not tracked, there is no way to differentiate between human/lizard/moth/fly person names.</p>
{% endif %}
{% endblock %}

{% block js %}
<script>
$('button').on('click', function(e){
  e.preventDefault();
  if('yea' === $(this).attr('value')){
    vote = '1'
  } else {
    vote = '0'
  }
  name = $('#nameField').attr('value');
  $('#name').html("<i class='fas fa-cog fa-spin'></i>")
  $('#job').html("<i class='fas fa-cog fa-spin'></i>")
  data = {
    vote: vote,
    name: name
  }
  // console.log(data)
  $.ajax({
    url: {{path_for('nameVoter.cast')}}/,
    method: 'post',
    data: data,
    dataType: 'json'
  }).done(function(r) {
    console.log(r)
    $('#name').html(r.name.name);
    $('#nameField').attr('value',r.name.name)
    $('#job').html(r.name.job);
  }).fail(function(r){
    console.log(r.responseText);
  })
});
</script>
{% endblock %} 