{% extends "index.tpl"%}
{% block content %}
{% include('rounds/html/basic.tpl') %}
<div id="event-start"></div>
<div id="event-end"></div>
<div id="slider"></div>
<div class="leaflet-map" id="map"></div>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.1.0/nouislider.min.js" integrity="sha256-IB524Svhneql+nv1wQV7OKsccHNhx8OvsGmbF6WCaM0=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.1.0/nouislider.min.css" integrity="sha256-tkYpq+Xdq4PQNNGRDPtH3G55auZB4+kh/RA80Abngaw=" crossorigin="anonymous" />
<script src="assets/js/map.js"></script>
<script src="assets/js/timeline.js"></script>
<script>

  // var roundstart = date2unix('{{round.start_datetime}}');
  // var roundend = date2unix('{{round.end_datetime}}');
  // 
  var roundstart = timestamp('{{round.start_datetime}}');
  var roundend = timestamp('{{round.end_datetime}}');

  L.tileLayer("https://renderbus.s3.amazonaws.com/tiles/{{round.map_url}}/{z}/tile_{x}-{y}.png", {
    minZoom: 1,
    maxZoom: 6,
    maxNativeZoom: 5,
    continuousWorld: true,
    tms: false
  }).addTo(map);
  var slider = document.getElementById('slider');

  noUiSlider.create(slider, {
    start: [roundstart, roundend],
    connect: true,
    range: {
      'min': roundstart,
      'max': roundend
    },
  });

</script>
<script>

  var deaths = {{round.details.deaths|raw}};
  var explosions = {{round.details.explosions|raw}}.data;
  var exps = L.layerGroup();
  for (var e in explosions){
    if (explosions[e].z == "2") {
      if(explosions[e].dev < 0){
        explosions[e].dev = 0
      }
      var devCircle = L.circle(tg2leaf(explosions[e].x,explosions[e].y), {
          color: 'red',
          radius: explosions[e].dev
      }).bindPopup(explosions[e].area).addTo(exps);
      if(explosions[e].heavy < 0){
        explosions[e].heavy = 0
      }
      var heavyCircle = L.circle(tg2leaf(explosions[e].x,explosions[e].y), {
          color: 'orange',
          radius: explosions[e].heavy
      }).bindPopup(explosions[e].area).addTo(exps);
      if(explosions[e].light < 0){
        explosions[e].light = 0
      }
      var lightCircle = L.circle(tg2leaf(explosions[e].x,explosions[e].y), {
          color: 'yellow',
          radius: explosions[e].light
      }).bindPopup(explosions[e].area).addTo(exps);
      if(explosions[e].flash < 0){
        explosions[e].flash = 0
      }
      var flashCircle = L.circle(tg2leaf(explosions[e].x,explosions[e].y), {
          color: 'white',
          radius: explosions[e].flash
      }).bindPopup(explosions[e].area).addTo(exps);
    }
  }

  var corpses = L.layerGroup();

  for (var d in deaths) {
    var corpse = L.marker(tg2leaf(deaths[d].x_coord,deaths[d].y_coord))
      .bindPopup(deaths[d].name + " at " + deaths[d].pod)
      corpse.options.time = deaths[d].tod
      corpse.addTo(corpses);
  }

  var wires = L.layerGroup();
  fetch('{{app.url}}round.php?round={{round.id}}&file=wires.html&json=true')
    .then(function(response) {
      return response.json();
    })
    .then(function(wirejson) {
      for (var w in wirejson){
        if(wirejson[w].z != 2) {
          continue;
        }
        var x = wirejson[w].x - .5
        var y = wirejson[w].y - .5
        var wire = L.polygon([
          tg2leaf(x,   y),
          tg2leaf(x+1, y),
          tg2leaf(x+1, y+1),
          tg2leaf(x,   y+1)
        ])
        wire.options.time = wirejson[w].timestamp
        wire.bindPopup(wirejson[w].content + " at "+wirejson[w].timestamp).addTo(wires)
        
      }
    })

  var atmos = L.layerGroup();
  fetch('{{app.url}}round.php?round={{round.id}}&file=atmos.html&json=true')
    .then(function(response) {
      return response.json();
    })
    .then(function(atmosjson) {
      for (var w in atmosjson){
        // var wire = L.marker(tg2leaf(atmosjson[w].x, atmosjson[w].y)).addTo(wires)
        var x = atmosjson[w].x - .5
        var y = atmosjson[w].y - .5
        var wire = L.polygon([
          tg2leaf(x,   y),
          tg2leaf(x+1, y),
          tg2leaf(x+1, y+1),
          tg2leaf(x,   y+1)
        ]).bindPopup(atmosjson[w].content + " at "+atmosjson[w].timestamp).addTo(atmos)
      }
    })

  var pdalog = L.layerGroup();
      fetch('{{app.url}}round.php?round={{round.id}}&file=pda.txt&json=true')
        .then(function(response) {
          return response.json();
        })
        .then(function(pdajson) {
          for (var w in pdajson){
            var m = pdajson[w]
            if('object' != typeof(m)) {
              continue;
            }
            if(m.coords.z != 2) {
              continue;
            }
            var x = m.coords.x - .5
            var y = m.coords.y - .5
            var msg = L.polygon([
              tg2leaf(x,   y),
              tg2leaf(x+1, y),
              tg2leaf(x+1, y+1),
              tg2leaf(x,   y+1)
            ]).bindPopup("<strong>" + m.message + "</strong> from "+m.sender.name + " to "+m.recipient.name + " (" + m.recipient.job + ") at " + m.date).addTo(pdalog)
          }
        })

  L.control.layers()
  .addOverlay(corpses,"Deaths")
  .addOverlay(exps,"Explosions")
  .addOverlay(wires,"Cut Wires")
  .addOverlay(atmos,"Atmospherics")
  .addOverlay(pdalog,"PDA Messages")
  .addTo(map);

  wires.on('add', getMinMax)

  var dateValues = [
    document.getElementById('event-start'),
    document.getElementById('event-end')
  ];

  slider.noUiSlider.on('update', function( values, handle ) {
    var start = Number(values[0]);
    var end =   Number(values[1]);
    for (var i in map._layers){
      layer = map._layers[i]
      if(undefined != layer.options.time){
        var time = date2unix(layer.options.time)
        console.log(time, start)
        // console.log(date2unix(layer.options.time))
        if(time < start){
          console.log(map._layers[i])
          map.removeLayer(map._layers[i])
        }
      }
    }
    dateValues[handle].innerHTML = formatDate(new Date(+values[handle]));
  });

</script>
{% endblock %}