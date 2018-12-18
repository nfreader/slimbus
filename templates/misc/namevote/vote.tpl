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
<div class="modal fade show" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Hey! Listen!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        This is <strong>JUST FOR FUN</strong>. Votes cast here will have no effect on policy and will not be used to change anything. If it becomes un fun it will be removed.
      </div>
      <div class="modal-footer">
        <a href="#" class="btn btn-primary" data-dismiss="modal">I Understand This Is Just For Fun</a>
      </div>
    </div>
  </div>
</div>
<h1>Name Rater 5000</h1>
<hr>
<div class="btn-group" role="group">
  <a class="btn btn-primary text-white" href="{{path_for('nameVoter')}}">Vote on Names</a>
  <a class="btn btn-primary text-white" href="{{path_for('nameVoter.results',{'rank':'best'})}}">Best Names</a>
  <a class="btn btn-primary text-white" href="{{path_for('nameVoter.results',{'rank':'worst'})}}">Worst Names</a>
</div>
<hr>
<p class="lead">Just for funsies*, select whether or not the name below is good or bad, in your opinion. Remember that we're simply judging the name, not the player behind the name.</p>
<div class="jumbotron jumbotron-fluid">
  <div class="container text-center">
    <h3 class="display-4">Is this a good name?</h3>
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
<p>Duplicate votes are discarded, so dont worry if you see the same name twice.</p>
{% endif %}
{% endblock %}

{% block js %}
<script>
$('#exampleModalCenter').modal('toggle')
$('button').on('click', function(e){
  $('button').toggleClass('disabled')
  e.preventDefault();
  if('yea' == $(this).attr('value')){
    vote = 'yea'
  } else {
    vote = 'nay'
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
    $('button').toggleClass('disabled')
  }).fail(function(r){
    console.log(r.responseText);
  })
});
</script>
{% endblock %} 