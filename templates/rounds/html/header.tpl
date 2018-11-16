<div class="row">
  <div class="col-md-2 col-sm-12 h3">
    <i class="fas fa-circle"></i> {{round.id}}<br>
    <small>{{round.map}}</small>
  </div>
  <div class="col-md-8 col-sm-12 h3 text-center">
    <i class="fas fa-{{round.icons.mode}}"></i> {{round.mode}} <br>
    <div class="badge badge-{{round.class}} d-block">
      <i class="fas fa-{{round.icons.result}}"></i> {{round.result}}
    </div>
    <small>Started {{round.start_datetime}}, Ended {{round.end_datetime}}</small>
  </div>
  <div class="col-md-2 col-sm-12 h3 text-right">
    {{round.server}}<br>
    {{round.duration}}
  </div>
</div>
