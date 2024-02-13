<link href="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.js"></script>
<script src="https://unpkg.com/@turf/turf/turf.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>




            <div id="map2" style="width: 100%;height: 720px;"> </div>
            <br>

            <form method="POST" action="<?= base_url('tracking/Dashboard/tracking_chauffeur/'.$CODE.'') ?>"  >

              <div id="menu"> 

                <?php $carte2; ?>


                <input onchange="submit()" id="satellite-streets-v12" type="radio" name="rtoggle" value="satellite" <?php if($info == 'satellite') echo "checked"; $carte2 = 'satellite-streets-v12'; ?>>

                <label for="satellite-streets-v12">satellite</label>

                <input onchange="submit()" id="streets-v12" type="radio" name="rtoggle" value="streets" <?php if($info == 'streets') echo "checked"; $carte2 = 'streets-v12'; ?> >
                <label for="streets-v12">streets</label>


                <br>
                <br>

                <!-- <img style="width: 100%;height: 150px;" src="<?= base_url() ?>upload/mbx2.jpeg">          -->

              </div>
            </form>


            <div id="animation-phase-container">

<!--   Temps :
  <div id="animation-phase"></div> -->


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