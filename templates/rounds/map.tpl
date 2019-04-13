{% extends "base/dashboard.html"%}
{% block pagetitle %}Map Data - Round #{{round.id}}{% endblock %}
{% block dashtitle %}Map - Round #{{round.id}}{% endblock %}
{% block content %}

<div class="leaflet-map" id="map"></div>

{% endblock %}

{% block sidebar %}

<ul class="nav flex-column">
  <li class="nav-item">
    <a class="nav-link invisible" href="#" id="deaths">
      <i class="fas fa-user-times"></i> Deaths (<span id="deathCount"></span>)
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link invisible" href="#" id="bombs">
      <i class="fas fa-bomb"></i> Explosions
    </a>
  </li>
</ul>

{% endblock %}

{% block js %}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
  integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
  crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
  integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
  crossorigin=""></script>
<script>
let roundID = window.location.pathname.split('/')[2];

fetch('/rounds/'+roundID+'?format=json')
  .then(function(response) {
    return response.json();
  })
  .then(function(data) {
    var map = L.map("map", {
      attributionControl: false,
      minZoom: 1,
      maxZoom: 6,
      maxBounds: [[0,0],[-256,256]],
      crs: L.CRS.Simple,
      preferCanvas: true,
    }).setView([-128,128], 2);
    L.tileLayer("https://renderbus.s3.amazonaws.com/tiles/"+data.map_url+"/{z}/tile_{x}-{y}.png", {
      minZoom: 1,
      maxZoom: 6,
      maxNativeZoom: 5,
      continuousWorld: true,
      tms: false
    }).addTo(map);
    if(data.deaths){
      $('#deaths').removeClass('invisible')
      $('#deathCount').text(data.deaths)
    }
    if(data.stats.explosion){
      $('#bombs').removeClass('invisible')
    }
    return map;
  })
  .then(function(map){
    var corpses = L.layerGroup();
    var loaded = false
    $('#deaths').click(function(e){
      e.preventDefault();
      $(this).toggleClass('active')
      if(loaded){
        if(map.hasLayer(corpses)){
          map.removeLayer(corpses)
          return
        } else {
          map.addLayer(corpses)
          return
        }
      }
      loaded = true
      fetch('/deaths/round/'+roundID+'?format=json')
        .then(function(response) {
          return response.json();
        })
        .then(function(data){
          data.forEach(function(d){
            if(d.z != 2) return;
            death = L.polygon([
              tg2leaf(d.x,   d.y),
              tg2leaf(d.x-1, d.y),
              tg2leaf(d.x-1, d.y-1),
              tg2leaf(d.x,   d.y-1)
            ], {color: 'red'})
            .bindPopup("<table class='table table-sm table-bordered'><tr><th>ID</th><td><a target='_blank' href='"+window.location.origin+"/deaths/"+d.id+"'>"+d.id+"</a></td></tr><tr><th>Name</th><td>"+d.name+"/"+d.byondkey+"</td></tr><tr><th>Job</th><td>"+d.job+"</td></tr><tr><th>At</th><td>"+d.pod+"</td></tr><tr><th>Timestamp</th><td>"+d.tod+"</td></tr><tr><th>Vitals</th><td><span class='brute'>"+d.vitals.brute+"</span> / <span class='brain'>"+d.vitals.brain+"</span> / <span class='fire'>"+d.vitals.fire+"</span> / <span class='oxy'>"+d.vitals.oxy+"</span> / <span class='tox'>"+d.vitals.tox+"</span> / <span class='clone'>"+d.vitals.clone+"</span> / <span class='stamina'>"+d.vitals.stamina+"</span></td></tr></table>").addTo(corpses)
          })
        })
      corpses.addTo(map);
    })
    return map
  })
  .then(function(map){
    var bombs = L.layerGroup();
    var loaded = false
    $('#bombs').click(function(e){
      e.preventDefault();
      $(this).toggleClass('active')
      if(loaded){
        if(map.hasLayer(bombs)){
          map.removeLayer(bombs)
          return
        } else {
          map.addLayer(bombs)
          return
        }
      }
      loaded = true
      fetch('/rounds/'+roundID+'/explosion?format=json')
        .then(function(response) {
          return response.json();
        })
        .then(function(data){
          var data = data.data
          // console.log(data)
          data = Object.values(data)
          data.forEach(function(e){
            if (e.z != "2") {
              return
            }
            if(e.flash > 0){
              var flashCircle = L.circle(tg2leaf(e.x-.5,e.y-.5), {
                  color: 'white',
                  radius: +e.flash+.5
              }).bindPopup("Flash Range: " + e.flash + " from explosion at " + e.area+" ("+e.time+")").addTo(bombs);
            }
            if(e.light > 0){
              var lightCircle = L.circle(tg2leaf(e.x-.5,e.y-.5), {
                  color: 'yellow',
                  radius: +e.light+.5
              }).bindPopup("Light Damage Range: " + e.light + " from explosion at " + e.area+" ("+e.time+")").addTo(bombs);
            }
            if(e.heavy > 0){
              var heavyCircle = L.circle(tg2leaf(e.x-.5,e.y-.5), {
                  color: 'orange',
                  radius: +e.heavy+.5
              }).bindPopup("Heavy Damage Range: " + e.heavy + " from explosion at " + e.area+" ("+e.time+")").addTo(bombs);
            }
            if(e.dev > 0){
              var devCircle = L.circle(tg2leaf(e.x-.5,e.y-.5), {
                  color: 'red',
                  radius: +e.dev+.5
              }).bindPopup("Devestation Range: " + e.dev + " from explosion at " + e.area+" ("+e.time+")").addTo(bombs);
            }
          })
        })
      bombs.addTo(map);
    })
  })

</script>
{% endblock %}