{% extends "base/index.html"%}
{% block pagetitle %}Map Data - Round #{{round.id}}{% endblock %}
{% block content %}

<div class="leaflet-map" id="map"></div>

{% endblock %}

{% block js %}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.1.0/nouislider.min.js" integrity="sha256-IB524Svhneql+nv1wQV7OKsccHNhx8OvsGmbF6WCaM0=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.1.0/nouislider.min.css" integrity="sha256-tkYpq+Xdq4PQNNGRDPtH3G55auZB4+kh/RA80Abngaw=" crossorigin="anonymous" />
<script>
var map = L.map("map", {
  zoomControl: false,
  attributionControl: false,
  minZoom: 1,
  maxZoom: 6,
  maxBounds: [[0,0],[-256,256]],
  crs: L.CRS.Simple,
}).setView([-128,128], 2);
L.control.zoom({position: "topleft"}).addTo(map);

var roundstart = timestamp('{{round.start_datetime}}');
var roundend = timestamp('{{round.end_datetime}}');

L.tileLayer("https://renderbus.s3.amazonaws.com/tiles/{{round.map_url}}/{z}/tile_{x}-{y}.png", {
  minZoom: 1,
  maxZoom: 6,
  maxNativeZoom: 5,
  continuousWorld: true,
  tms: false
}).addTo(map);

var corpses = L.layerGroup();
var deaths = {{deaths|raw}};

for (var d in deaths) {
  var corpse = L.polygon([
          tg2leaf(deaths[d].x,   deaths[d].y),
          tg2leaf(deaths[d].x-1,   deaths[d].y),
          tg2leaf(deaths[d].x-1,   deaths[d].y-1),
          tg2leaf(deaths[d].x,   deaths[d].y-1)
        ], {color: 'red'})
    .bindPopup(deaths[d].name + " at " + deaths[d].pod)
    corpse.options.time = deaths[d].tod
    corpse.addTo(corpses);
}
// L.control.layers().addOverlay(corpses,"Deaths").addTo(map);

var explosions = {{explosions|raw}}.data;
var exps = L.layerGroup();
for (var e in explosions){
  if (explosions[e].z == "2") {
    if(explosions[e].flash > 0){
      var flashCircle = L.circle(tg2leaf(explosions[e].x-.5,explosions[e].y-.5), {
          color: 'white',
          radius: +explosions[e].flash+.5
      }).bindPopup("Flash Range: " + explosions[e].flash + " from explosion at " + explosions[e].area).addTo(exps);
    }
    if(explosions[e].light > 0){
      var lightCircle = L.circle(tg2leaf(explosions[e].x-.5,explosions[e].y-.5), {
          color: 'yellow',
          radius: +explosions[e].light+.5
      }).bindPopup("Light Damage Range: " + explosions[e].light + " from explosion at " + explosions[e].area).addTo(exps);
    }
    if(explosions[e].heavy > 0){
      var heavyCircle = L.circle(tg2leaf(explosions[e].x-.5,explosions[e].y-.5), {
          color: 'orange',
          radius: +explosions[e].heavy+.5
      }).bindPopup("Heavy Damage Range: " + explosions[e].heavy + " from explosion at " + explosions[e].area).addTo(exps);
    }
    if(explosions[e].dev > 0){
      var devCircle = L.circle(tg2leaf(explosions[e].x-.5,explosions[e].y-.5), {
          color: 'red',
          radius: +explosions[e].dev+.5
      }).bindPopup("Devestation Range: " + explosions[e].dev + " from explosion at " + explosions[e].area).addTo(exps);
    }
  }
}
// L.control.layers().addOverlay(exps,"Explosions").addTo(map);

L.control.layers()
.addOverlay(corpses,"Deaths")
.addOverlay(exps,"Explosions")
.addTo(map);

</script>
{% endblock %}