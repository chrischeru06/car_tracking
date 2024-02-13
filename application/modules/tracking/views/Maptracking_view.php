<style type="text/css">
  .mapboxgl-popup {
    position: absolute;
    top: 0;
    left: 0;
    display: flex;
    will-change: transform;
    pointer-events: none;
    z-index: 1;
  }

  .school-popup {
    color: #fff;
    z-index: 1;
  }
</style>

<style>
  .legend label,
  .legend span {
    display:block;
    float:right;
    height:15px;
    width:20%;
    text-align:center;
    font-size:9px;
    color:#808080;
  }

  .mapbox-improve-map{
    display: none;
    z-index: 1;
  }
  .leaflet-control-attribution{
    display: none !important;
    z-index: 1;
  }
  .leaflet-control-attribution{
    display: none !important;
    z-index: 1;
  }
  .mapbox-logo {
    display: none !important;
    z-index: 1;
  }
  #moncercles{
    background:#3e4676;
    border-radius:50%;
    width:15px;
    height:15px;
    border:2px solid #3e4676; 
  }

  #moncercle{
    background:#EB821C;
    border-radius:50%;
    width:15px;
    height:15px;
    border:2px solid #EB821C; 
  }

</style>

<style>
  #hover_compte:hover{
    background:#A000fB;
  }

</style>
<div class="col-lg-8"> 
 <div style=" z-index: 1" id="map2"></div>
</div>

<script type="text/javascript">

  mapboxgl.accessToken =
  "pk.eyJ1IjoiY2hyaXN3aG9uZ21hcGJveCIsImEiOiJjbGE5eTB0Y2QwMmt6M3dvYW1ra3pmMnNsIn0.ZfF6uOlFNhl6qoCR7egTSw";
  const map2 = new mapboxgl.Map({
  container: "map2", // container ID
  style: "mapbox://styles/mapbox/<?= $carte2; ?>", // style URL
  bounds: [29.384095,-3.3830083, 29.3838133,-3.3844883],
  projection: "globe" // display the map as a 3D globe
});

  map2.addControl(new mapboxgl.NavigationControl());

  map2.on("style.load", () => {
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

    map2.addSource("tp-line", {
      type: "geojson",
      data: transpeninsularLine,
    // Line metrics is required to use the 'line-progress' property
      lineMetrics: true
    });

    map2.addLayer({
      id: "tp-line-line",
      type: "line",
      source: "tp-line",
      paint: {
        "line-color": "rgba(0,0,0,0)",
        "line-width": 4,
        "line-opacity": 0.7
      }
    });
  map2.setFog({}); // Set the default atmosphere style


  let startTime;
  const duration = 16000;

  const frame = (time) => {
    if (!startTime) startTime = time;
    const animationPhase = (time - startTime) / duration;
    const animationPhaseDisplay = animationPhase.toFixed(2);
    $("#animation-phase").text(animationPhaseDisplay);

    // Reduce the visible length of the line by using a line-gradient to cutoff the line
    // animationPhase is a value between 0 and 1 that reprents the progress of the animation
    map2.setPaintProperty("tp-line-line", "line-gradient", [
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



  const layerList = document.getElementById('menu');
  const inputs = layerList.getElementsByTagName('input');

  for (const input of inputs) {
    input.onclick = (layer) => {
      const layerId = layer.target.id;
      map2.setStyle('mapbox://styles/mapbox/' + layerId);
    };
  }







  map2.on('load', () => {
    map2.addSource('places', {
      'type': 'geojson',
      'data': {
        'type': 'FeatureCollection',
        'features': [<?= $arret; ?>]
      }
    });
// Add a layer showing the places.
    map2.addLayer({
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

    map2.on('mouseenter', 'places', (e) => {
// Change the cursor style as a UI indicator.
      map2.getCanvas().style.cursor = 'pointer';

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
      popup.setLngLat(coordinates).setHTML(description).addTo(map2);
    });

    map2.on('mouseleave', 'places', () => {
      map2.getCanvas().style.cursor = '';
      popup.remove();
    });
  });



</script>