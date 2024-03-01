<style>
  .mapboxgl-popup {
    max-width: 400px;
    font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
  }

</style>


<div id="map_maps" style="width: 100%;height: 720px;"> 

</div>
<br>




<div id="animation-phase-container">

 <div id="menu" style="float:right;" > 

  <?php $carte2; ?>
  <br>
  <br>
  <br>
  <br>
  <br>
  <section class="section dashboard" id="liste">
    <h5 class="card-title">Points d'arrêt <span>| Jour</span></h5>
    <div class="scroller">
      <div class="activity">

        <?=$ligne_arret?>

      </div>
    </div>
  </section> 
</div>

</div>
<form method="POST" action="<?= base_url('tracking/Dashboard/tracking_chauffeur/'.$CODE.'') ?>"  >

 <div id="menu">


  <input onchange="submit()" id="satellite-streets-v12" type="radio" name="rtoggle" value="satellite" <?php if($info == 'satellite') echo "checked"; $carte2 = 'satellite-streets-v12'; ?>>

  <label for="satellite-streets-v12">satellite</label>

  <input onchange="submit()" id="streets-v12" type="radio" name="rtoggle" value="streets" <?php if($info == 'streets') echo "checked"; $carte2 = 'streets-v12'; ?> >
  <label for="streets-v12">streets</label>
</div>
</form>


<script type="text/javascript">

  mapboxgl.accessToken =
  "pk.eyJ1IjoiY2hyaXN3aG9uZ21hcGJveCIsImEiOiJjbGE5eTB0Y2QwMmt6M3dvYW1ra3pmMnNsIn0.ZfF6uOlFNhl6qoCR7egTSw";
  var map_map = new mapboxgl.Map({
  container: "map_maps", // container ID
  style: "mapbox://styles/mapbox/<?= $carte2; ?>", // style URL
  bounds: [29.384095,-3.3830083, 29.3838133,-3.3844883],
  projection: "globe" // display the map as a 3D globe
});

  // var nav = new mapboxgl.NavigationControl();
  // map_map.addControl(nav, "top-left");
  map_map.addControl(new mapboxgl.NavigationControl());

  map_map.addControl(new mapboxgl.FullscreenControl());

  map_map.on("style.load", () => {
  // https://en.wikipedia.org/wiki/Transpeninsular_Line
    const transpeninsularLine = {
      type: "Feature",
      properties: {
        stroke: "#555555",
        "stroke-width": 1,
        "stroke-opacity": 1
      },
      geometry: {
        type: "LineString",
        coordinates: [<?php echo $track; ?>]
      }
    };

    map_map.addSource("tp-line", {
      type: "geojson",
      data: transpeninsularLine,
    // Line metrics is required to use the 'line-progress' property
      lineMetrics: true
    });

    map_map.addLayer({
      id: "tp-line-line",
      type: "line",
      source: "tp-line",
      paint: {
        "line-color": "rgba(0,0,0,0)",
        "line-width": 4,
        "line-opacity": 0.7
      }
    });
  map_map.setFog({}); // Set the default atmosphere style


  let startTime;
  const duration = 16000;

  const frame = (time) => {
    if (!startTime) startTime = time;
    const animationPhase = (time - startTime) / duration;
    const animationPhaseDisplay = animationPhase.toFixed(2);
    $("#animation-phase").text(animationPhaseDisplay);

    // Reduce the visible length of the line by using a line-gradient to cutoff the line
    // animationPhase is a value between 0 and 1 that reprents the progress of the animation
    map_map.setPaintProperty("tp-line-line", "line-gradient", [
      "step",
      ["line-progress"],
      "blue",
      
      animationPhase,
      "rgba(0, 0, 0, 0)"
      ]);

    if (animationPhase > 1) {
      return;
    }
    window.requestAnimationFrame(frame);
  };

  window.requestAnimationFrame(frame);

  // repeat
  setInterval(() => {
    startTime = undefined;
    window.requestAnimationFrame(frame);
  }, duration + 7500);


});



  var layerList = document.getElementById('menu');

  var inputs = layerList.getElementsByTagName('input');

  for (const input of inputs) {
    input.onclick = (layer) => {
      const layerId = layer.target.id;
      map_map.setStyle('mapbox://styles/mapbox/' + layerId);
    };
  }
  // var hoveredPolygonId = null;

 //  map_map.on('carteload', () => {
 //   map_map.addSource('states', <?= $delimit_prov; ?>);

 //   map_map.addLayer({
 //    'id': 'state-fills',
 //    'type': 'fill',
 //    'source': 'states',
 //    'layout': {},
 //    'paint': {
 //      'fill-color': '#627BC1',
 //      'fill-opacity': [
 //        'case',
 //        ['boolean', ['feature-state', 'hover'], false],
 //        1,
 //        0.5
 //        ]
 //    }
 //  });

 //   map_map.addLayer({
 //    'id': 'state-borders',
 //    'type': 'line',
 //    'source': 'states',
 //    'layout': {},
 //    'paint': {
 //      'line-color': '#627BC1',
 //      'line-width': 2
 //    }
 //  });

 //        // When the user moves their mouse over the state-fill layer, we'll update the
 //        // feature state for the feature under the mouse.
 //   map_map.on('mousemove', 'state-fills', (e) => {
 //    if (e.features.length > 0) {
 //      if (hoveredPolygonId !== null) {
 //        map_map.setFeatureState(
 //          { source: 'states', id: hoveredPolygonId },
 //          { hover: false }
 //          );
 //      }
 //      hoveredPolygonId = e.features[0].id;
 //      map_map.setFeatureState(
 //        { source: 'states', id: hoveredPolygonId },
 //        { hover: true }
 //        );
 //    }
 //  });

 //        // When the mouse leaves the state-fill layer, update the feature state of the
 //        // previously hovered feature.
 //   map_map.on('mouseleave', 'state-fills', () => {
 //    if (hoveredPolygonId !== null) {
 //      map_map.setFeatureState(
 //        { source: 'states', id: hoveredPolygonId },
 //        { hover: false }
 //        );
 //    }
 //    hoveredPolygonId = null;
 //  });

 // });


  map_map.on('load', () => {

   map_map.addSource('places', {
    'type': 'geojson',
    'data': {
      'type': 'FeatureCollection',
      'features': [<?= $arret; ?>]
    }
  });

// Add a layer showing the places.
   map_map.addLayer({
    'id': 'places',
    'type': 'circle',
    'source': 'places',
    'paint': {
      'circle-color': '#FF0000',
      'circle-radius': 6,
      'circle-stroke-width': 2,
      'circle-stroke-color': '#ffffff'
    }
  });


// Create a popup, but don't add it to the map yet.
   const popup = new mapboxgl.Popup({
    closeButton: false,
    closeOnClick: false
  });

   map_map.on('mouseenter', 'places', (e) => {
// Change the cursor style as a UI indicator.
    map_map.getCanvas().style.cursor = 'pointer';

// Copy coordinates array.
    const coordinates = e.features[0].geometry.coordinates.slice();
    const description = e.features[0].properties.description;

// Ensure that if the map is zoomed out such that multiple
// copies of the feature are visible, the popup appears
// over the copy being pointed to.
    while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
      coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
    }

// Populate the popup and set its coordinates
// based on the feature found.
    popup.setLngLat(coordinates).setHTML(description).addTo(map_map);
  });

   map_map.on('mouseleave', 'places', () => {
    map_map.getCanvas().style.cursor = '';
    popup.remove();
  });

     // When a click event occurs on a feature in the places layer, open a popup at the
        // location of the feature, with description HTML from its properties.
   map_map.on('click', 'places', (e) => {
            // Copy coordinates array.
    const coordinates = e.features[0].geometry.coordinates.slice();
    const description = e.features[0].properties.description;

            // Ensure that if the map is zoomed out such that multiple
            // copies of the feature are visible, the popup appears
            // over the copy being pointed to.
    while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
      coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
    }

    new mapboxgl.Popup()
    .setLngLat(coordinates)
    .setHTML(description)
    .addTo(map_map);
  });

        // Change the cursor to a pointer when the mouse is over the places layer.
   map_map.on('mouseenter', 'places', () => {
    map_map.getCanvas().style.cursor = 'pointer';
  });

        // Change it back to a pointer when it leaves.
   map_map.on('mouseleave', 'places', () => {
    map_map.getCanvas().style.cursor = '';
  });
 });



</script>