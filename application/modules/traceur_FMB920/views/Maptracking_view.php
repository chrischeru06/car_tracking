
<!DOCTYPE html>
<html>
<head>
<?php include VIEWPATH . 'includes/header.php'; ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MapView</title>


<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.js"></script>
<script src="https://unpkg.com/@turf/turf/turf.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

  <style type="text/css">
    body {
  margin: 0;
  padding: 0;
}
#map {top:-35px;bottom:0; width:100%;height:800px;z-index: 1; }

#animation-phase-container {
  position: absolute;
  top: 10px;
  left: 10px;
  background: white;
  padding: 10px;
  font-family: sans-serif;
  display: flex;
  align-items: center;
  border-radius: 8px;
}

#animation-phase {
  margin-left: 5px;
  font-weight: 600;
  font-size: 30px;
}


 .mapboxgl-ctrl-logo{
    display: none !important;
  }
  
   .mapboxgl-ctrl-attrib-inner{
    display: none !important;
  }
   
   .mapboxgl-ctrl mapboxgl-ctrl-attrib{
    display: none !important;
  }

  
  </style>
</head>
<body>
<?php include VIEWPATH . 'includes/preloader.php'; ?>
  <!--******************* Preloader end ********************-->

  <!--********************************** Main wrapper start ***********************************-->
  <div id="main-wrapper">
   <!--********************************** Header start ***********************************-->
     <?php include VIEWPATH . 'includes/menu_top.php'; ?>
    <!--********************************** Header end ***********************************-->

    <!--********************************** Sidebar start ***********************************-->
     <?php include VIEWPATH . 'includes/menu_left.php'; ?>
    <!--********************************** Sidebar end ***********************************-->

    
    <!--********************************** Content body start ***********************************-->



   <div id="map">


   
   </div>






<div id="animation-phase-container">




<!--   Temps :
  <div id="animation-phase"></div> -->


  <div id="menu">
<input id="satellite-streets-v12" type="radio" name="rtoggle" value="satellite">
<!-- See a list of Mapbox-hosted public styles at -->
<!-- https://docs.mapbox.com/api/maps/styles/#mapbox-styles -->
<label for="satellite-streets-v12">satellite</label>
  
<input id="streets-v12" type="radio" name="rtoggle" value="streets" checked="checked">
<label for="streets-v12">streets</label>
 

<hr>


<br>

    <img style="width: 80%;height: 150px;" src="<?= base_url() ?>upload/mbx2.jpeg"> 

    <br>

    <b>Itinerary FAITH & NANCY</b>
    <br>
    <br>
    <table>
      <tr>
        <td>AVG Speed</td><td><b><?= number_format($vit_moy['moy_vitesse'],2,',',' ')?> Km/h</b></td>
      </tr>
      <tr>
        <td>Begin</td><td><b><?= $date_debfin['datemin'] ?></b></td>
      </tr>
      <tr>
        <td>End</td><td><b><?= $date_debfin['datemax'] ?></b></td>
      </tr>
      
    </table> 
 
  </div>
  </div>
</div>
<?php include VIEWPATH . 'includes/footer.php'; ?>
    <!--********************************** Footer end ***********************************-->
  </div>
  <!--********************************** Main wrapper end ***********************************-->

  <!--********************************** Scripts start ***********************************-->
  <?php include VIEWPATH . 'includes/footer_scripts.php'; ?>
  <!--********************************** Scripts End ***********************************-->

</body>

  
  <script type="text/javascript">
      
      mapboxgl.accessToken =
  "pk.eyJ1IjoiY2hyaXN3aG9uZ21hcGJveCIsImEiOiJjbGE5eTB0Y2QwMmt6M3dvYW1ra3pmMnNsIn0.ZfF6uOlFNhl6qoCR7egTSw";
const map = new mapboxgl.Map({
  container: "map", // container ID
  style: "mapbox://styles/mapbox/streets-v12", // style URL
  bounds: [29.384095,-3.3830083, 29.3838133,-3.3844883],
  projection: "globe" // display the map as a 3D globe
});

map.addControl(new mapboxgl.NavigationControl());

map.on("style.load", () => {
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

  map.addSource("tp-line", {
    type: "geojson",
    data: transpeninsularLine,
    // Line metrics is required to use the 'line-progress' property
    lineMetrics: true
  });

  map.addLayer({
    id: "tp-line-line",
    type: "line",
    source: "tp-line",
    paint: {
      "line-color": "rgba(0,0,0,0)",
      "line-width": 4,
      "line-opacity": 0.7
    }
  });
  map.setFog({}); // Set the default atmosphere style

  let startTime;
  const duration = 16000;

  const frame = (time) => {
    if (!startTime) startTime = time;
    const animationPhase = (time - startTime) / duration;
    const animationPhaseDisplay = animationPhase.toFixed(2);
    $("#animation-phase").text(animationPhaseDisplay);

    // Reduce the visible length of the line by using a line-gradient to cutoff the line
    // animationPhase is a value between 0 and 1 that reprents the progress of the animation
    map.setPaintProperty("tp-line-line", "line-gradient", [
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
map.setStyle('mapbox://styles/mapbox/' + layerId);
};
}

  </script>

</html>