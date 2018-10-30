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
<hr>
<div class="d-flex justify-content-between">
  <div>
    {% if round.prev %}
    <a class="btn btn-primary" href="{{path_for('round.single',{'id': round.prev})}}"><i class='fas fa-angle-left'></i> <i class='fa fa-circle'></i>  {{round.prev}}</a>
    {% endif %}
  </div>
  <div>
    {% if round.next %}
    <a class="btn btn-primary" href="{{path_for('round.single',{'id': round.next})}}"><i class='fa fa-circle'></i> {{round.next}} <i class='fas fa-angle-right'></i></a>
    {% endif %}
  </div>
</div>
<hr>
<h2>Basic Details</h2>
<hr>
<table class="table table-bordered table-sm">
  <tbody>
    <tr>
      <th class="text-right align-middle ">Station Name</th>
      <td class="align-middle ">{{round.station_name}}</td>
      <th class="align-middle text-right">Deaths</th>
      <td class="align-middle">
        <a class="btn btn-primary btn-sm" href="{{path_for('death.round',{'round': round.id})}}">{{round.deaths}}</a>
        <a href="{{path_for('round.map',{'id': round.id})}}" class="btn btn-primary btn-sm">Map</a>  
      </td>
    </tr>
    <tr>
      <th class="align-middle text-right">Escape Shuttle</th>
      <td class="align-middle">
        {% if not round.shuttle %}
          <em>No Escape Shuttle</em>
        {% else %}
          {{round.shuttle}}
        {% endif %}
      </td>
      <th class="align-middle text-right">Logs</th>
      <td class="align-middle">
        {% if round.logs %}
          <a class="btn btn-warning btn-sm" href="{{app.url}}round.php?round={{round.id}}&file=game">Parsed</a>
          <a class="btn btn-primary btn-sm" href="{{round.remote_logs_dir}}" target="_blank" rel="noopener noreferrer">Original <i class="fas fa-external-link-alt"></i></a>
        {% else %}
        <em>Not available</em>
        {% endif %}
      </td>
    </tr>
    {% if app.constants.REMOTE_LOG_SRC %}
    <tr>
      <td class="table-info" colspan="2" class="text-right">
        <a href="{{app.url}}round.php?round={{round.id}}&logs">View Additional Data</a>
      </td>
      <td class="table-info" colspan="2">
        <a href="{{app.url}}round.php?round={{round.id}}&map">View Positional Data On A Map</a>
      </td>
    </tr>
    {% endif %}
  </tbody>
</table>

<h2 data-target="#technical" data-toggle="collapse">Technical Details</h2>
  <hr>
  <table class="table table-bordered table-sm" id="technical">
    <tbody>
      <tr>
        <th class="align-middle text-right">Round Duration</th>
        <td class="align-middle">{{round.duration}}</td>
        {% if round.commit_hash %}
        <th class="align-middle text-right">Commit</th>
        <td class="align-middle">
          <code>
            <a class="btn btn-primary btn-sm" href="{{round.commit_href}}" target="_blank" rel="noopener noreferrer"> {{round.commit_hash}} <i class="fas fa-external-link-alt"></i></a>
          </code>
        </td>
        {% endif %}
      </tr>
      <tr>
        <th class="align-middle text-right">Initialization Duration</th>
        <td class="align-middle">{{round.init_time}}</td>
        <th class="align-middle text-right">Shutdown Duration</th>
        <td class="align-middle">{{round.shutdown_time}}</td>
      </tr>
      <tr>
        <th class="align-middle text-right">Initialization Time</th>
        <td class="align-middle">{{round.initialize_datetime}}</td>
        <th class="align-middle text-right">Shutdown Time</th>
        <td class="align-middle">{{round.shutdown_datetime}}</td>
      </tr>
      <tr>
        {% if round.data.byond_version %}
        <th class="align-middle text-right">Byond Version</th>
        <td class="align-middle">{{round.data.byond_version.json}}</td>
        {% endif %}
        {% if round.data.byond_build %}
        <th class="align-middle text-right">Byond Build</th>
        <td class="align-middle">{{round.data.byond_build.json}}</td>
        {% endif %}
      </tr>
      <tr>
        {% if round.data.dm_version %}
        <th class="align-middle text-right">DM Version</th>
        <td class="align-middle">{{round.data.dm_version.json}}</td>
        {% endif %}
        {% if round.data.byond_build %}
        <th class="align-middle text-right">Random Seed</th>
        <td class="align-middle">{{round.data.random_seed.json}}</td>
        {% endif %}
      </tr>
    </tbody>
  </table>