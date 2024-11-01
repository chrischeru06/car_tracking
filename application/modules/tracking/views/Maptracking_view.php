<style>
  .mapboxgl-popup {
    max-width: 400px;
    font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
  }

  .jss408 {
    width: 100%;
    display: flex;
    padding: 0px 5px;
    -progress: [object Object];
    flex-flow: column;
    overflow-y: auto;
    transition: all .800ms ease-in-out;

/*    border: solid 1px rgba(128, 128, 128, 0.3);*/
}
/*.jss408:hover {
  border: solid 1px rgb(128, 128, 128);
}*/
.jss408:hover{
  border: solid 1px rgb(128, 128, 128,0.5);
  background: #EDEDED;

}
.jss408:active {
  transform: scale(0.94);
  background: #EDEDED;
  box-shadow: rgba(0, 0, 0, 0.44) 0px 3px 8px;


}
.jss408:focus {
  color:#4154f1;
}
.jss511 {
  display: flex;
  position: relative;
  align-items: stretch;
  margin-bottom: 15px;
}
.jss513 {
  color: #72848C;
  margin: 15px 0;
  font-size: 12px;
  padding-left: 96px;
  text-transform: uppercase;
}

.jss509 {
  color: #465157;
  float: left;
  width: 100%;
  padding: 20px;
}
.jss490 {
  right: 0;
  width: 117px;
  bottom: 0;
  display: flex;
  z-index: 1;
  position: absolute;
  align-items: center;
}
.jss517 {
  width: 61%;
  display: inline-block;
  font-size: 12px;
  padding-left: 15px;
/*    text-transform: uppercase;*/
}
.jss512 {
  width: 80px;
  display: inline-block;
  border-right: 3px solid #66C011;
  text-transform: uppercase;
}
.jss514 {
  width: 80px;
  display: inline-block;
  border-right: 3px solid #DE3930;
  text-transform: uppercase;
}
.jss510 {
  color: #7D7E7F;
}



.jss722 {
  color: #7D7E7F;
}

.jss507 {
  top: -16px;
  position: absolute;
  font-size: 8px;
}

.jss518 {
  width: 61%;
  display: inline-block;
  font-size: 12px;
  padding-left: 15px;
/*    text-transform: uppercase;*/
}
* {
  box-sizing: border-box;
}



.jss408 {
  width: 100%;
  display: flex;
  padding: 0px 5px;
  -progress: [object Object];
  flex-flow: column;
  overflow-y: auto;
}
.jss111 {
  float: left;
  width: 100%;
  display: flex;
  align-items: center;
}

.jss114 {
  width: 61%;
  display: inline-block;
  font-size: 12px;
  padding-left: 15px;
/*  text-transform: uppercase;*/
}

.jss112 {
  float: left;
  display: inline;
  position: relative;
}
.jss113 {
  border-left: 3px solid #1A73B8;
  padding-left: 15px;
}
.jss110 {
  color: #575962;
  float: left;
  width: 100%;
  padding: 20px;
  position: relative;
  background: #fff;
/*  border: solid 1px rgba(128, 128, 128, 0.3);*/
transition: all .800ms ease-in-out;
}
.jss110:hover{
  border: solid 1px rgb(128, 128, 128,0.5);
  background: #EDEDED;

}
.jss110:active {
  transform: scale(0.94);
  background: #EDEDED;
  box-shadow: rgba(0, 0, 0, 0.44) 0px 3px 8px;
  

}
.jss501 {
  top: -2px;
}

.jss500 {
  top: -2px;
  position: absolute;
  font-size: 8px;
}
.jss515 {
  color: #96999F;
  float: left;
  width: 100%;
  position: relative;
  font-size: 12px;
/*    text-transform: uppercase;*/
}

.jss119 {
  font-size: 7px;
}


.rounded-rect {
  background: white;
  border-radius: 30px;
  box-shadow: 0 0 2px 0px ;
  z-index: 1000;
}

.flex-center {
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
}

.flex-center.left {
  left: 3px;
}

.flex-center.right {
  right: 0px;
}

.sidebar-content {
  position: absolute;
  width: 95%;
  height: 95%;
/*    font-family: Arial, Helvetica, sans-serif;*/
/*    font-size: 32px;*/
color: gray;
/*position:relative;*/
}

.sidebar-toggle {
  position: absolute;
  width: 2em;
  height: 2em;
  overflow: visible;
  display: flex;
  justify-content: center;
  align-items: center;
}

.sidebar-toggle.left {
  right: -1.5em;

}

.sidebar-toggle.right {
  left: -1.5em;
}

.sidebar-toggle:hover {
  color: #0aa1cf;
  cursor: pointer;
}


.sidebar {
  transition: transform 1s;
  z-index: 100;
/*  width: 300px;*/
margin: 0px 0;
/*  height: 100%;*/

}

    /*
  The sidebar styling has them "expanded" by default, we use CSS transforms to push them offscreen
  The toggleSidebar() function removes this class from the element in order to expand it.
*/
.left.collapsed {
  transform: translateX(-285px);
}

.right.collapsed {
  transform: translateX(295px);
}

</style>

<style>
  .map-overlay {

    position: absolute;
    width: 300px;
    top: 10;
    left: 5;
    padding: 10px;
    z-index: 100;
  }

  .map-overlay2 {

   position: absolute;
   width: 170px;
   height: 100px;
   top: 150px;
   right: 17px;
   padding: 10px;
   z-index: 100;
 }


 .map-overlay .map-overlay-inner {
  background-color: #fff;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  border-radius: 3px;
  padding: 10px;
  margin-bottom: 10px;
  overflow-y: scroll;
  height: 600px;
  width: 100%;
}

.map-overlay-inner fieldset {
  display: flex;
  justify-content: space-between;
  border: none;

}

.map-overlay-inner label {
  font-weight: bold;
  margin-right: 10px;
}

.map-overlay-inner .select-fieldset {
  display: block;
}

.map-overlay-inner .select-fieldset label {
  display: block;
  margin-bottom: 5px;
}

.map-overlay-inner .select-fieldset select {
  width: 100%;
}

#btn_list{

  position: absolute;
  width: 50px; 
  margin-top: 8px;
  margin-left: 8px;
/*        padding: 10px;*/
z-index: 100;

}
#btn_list2{

 font-size: 10px;
 z-index: 200;
 position:absolute;
 top:150px;
 left: 95%;
 width: 40px;

}

.ligne-rouge {
  width: 10px; /* largeur de la ligne */
  height: 2px; /* épaisseur de la ligne */
  background-color: #FF0000; /* couleur de la ligne (rouge) */
  display: block; /* assure que la ligne occupe un espace */
}

.legend{
  font-size: 10px;
}


#button-container { position: absolute; top: 95%; right: 10px; z-index: 1; }
</style>
<script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>


<div class="card" style="border-radius: 20px;">
  <!-- <h5 class="card-title">Centre de situation</h5> -->
  <br>

  <div class="card-body">
    <div class="row">

      <!-- <button onclick="open_popup()" type="button"  id="btn_list" class="btn btn-default me-md-2" type="button" style="background-color: cadetblue; color:white;"> -->
        <button onclick="open_popup()" type="button"  id="btn_list" class="btn btn-outline-primary" type="button"> <font class="fa fa-list"></font>
        </button>


        <div id="map_maps" style="width: 100%;height: 720px;">
          <div id='button-container'>
            <button id='toggle-button' class="btn btn-outline-primary"><?=lang('visibilite_polygone')?></button>
            <!-- <button id='toggle-button' class="bouton-transparent" style="background-color: cadetblue; color:white; border:none;opacity: 0.5;box-shadow: 0px 2px 4px white;">Visibilité du polygone</button> -->
          </div>

          <div class="map-overlay top">
            <!-- <div class="scroller"> -->
              <div class="map-overlay-inner">
                <button onclick="close_popup()" style="float: right;" class="btn btn-outline-primary" type="button">X</button>
                <!-- <button onclick="close_popup()" style="float: right; background-color: cadetblue; color: white;" class="btn btn-default me-md-2" type="button">X</button> -->

                <h5 class="card-title"><?=lang('resume_courses')?> </h5>
                <input type="hidden" name="CODE" id="CODE" value="<?=$CODE?>">
                <input type="hidden" name="DATE_DAT" id="DATE_DAT" value="<?=$DATE?>">
                <input type="hidden" name="DATE_DAT_FIN" id="DATE_DAT_FIN" value="<?=$DATE_DAT_FIN?>">

                    <!-- <div class="row">
                      <div class="col-md-2 text-center">
                        <button onclick="show_shift_error()" class="btn btn-danger rounded-pill btn-sm fa fa-clock-o" type="button" title="Courses hors des heures de service"></button>
                      </div>

                      <div class="col-md-2 text-center">
                        <button onclick="show_shift_success()"  class="btn btn-success rounded-pill btn-sm fa fa-clock-o" type="button" title="Courses pendant les heures de service"></button>
                      </div>
                    </div> -->

                <?=$card_card1?>
                <?=$card_card?>

                <br>
                <div class="card">
                  <?=$card_card2?>

                </div>


              </div>
              <!-- </div> -->
            </div>

            
            <button onclick="open_popup2()" type="button"  id="btn_list2" class="btn btn-outline-primary" type="button"> <font class="fa fa-list"></font>
            </button>

            <div class="map-overlay2 top card" >
              <!-- <div class="scroller"> -->
                <div class="map-overlay2-inner">
                  <font onclick="close_popup2()" style="float: right;font-size: 10px;" class="btn btn-outline-secondary rounded-pill fa fa-close " type="button"></font>


                  <table class="table table-borderless legend">
                    <tr>
                      <td class="small text-muted"><a class="ligne-rouge"></a>

                      </td>
                      <td class="small text-muted"><?=lang('vitesse_exces')?></td>
                    </tr>



                  </table>



                </div>
                <!-- </div> -->
              </div>
            </div>

       <!--  <div id="meno">

          <input id="streets-v12" type="radio" name="rtoggle" value="streets" checked="checked">
          <label for="streets-v12">streets</label>
          <input id="satellite-streets-v12" type="radio" name="rtoggle" value="satellite">
         
          <label for="satellite-streets-v12">satellite streets</label>
          <input id="light-v11" type="radio" name="rtoggle" value="light">
          <label for="light-v11">light</label>
          <input id="dark-v11" type="radio" name="rtoggle" value="dark">
          <label for="dark-v11">dark</label>

          <input id="outdoors-v12" type="radio" name="rtoggle" value="outdoors">
          <label for="outdoors-v12">outdoors</label>
        </div> -->






      </div>

    </div>


    <script type="text/javascript">

      $(document).ready(function() {
        document.getElementsByClassName('map-overlay2')[0].style.display = 'none';

        $("#btn_list").hide();
        $('#btn_list2').hide();

        show_shift_error();
        show_shift_success();


      });

      function close_popup() {

       document.getElementsByClassName('map-overlay')[0].style.display = 'none';
       $("#btn_list").show();


     }

     function open_popup() {
          // body...

       $("#btn_list").hide();
       
          //document.getElementsByClassName('map-overlay')[0].style.display = 'block';

       $('.map-overlay').toggle('slow', function() {
              // Animation complete.
       });
     }


     function close_popup2() {
      document.getElementsByClassName('map-overlay2')[0].style.display = 'none';
      $("#btn_list2").show();
    }

    function open_popup2() {
      $("#btn_list2").hide();

          //document.getElementsByClassName('map-overlay')[0].style.display = 'block';

      $('.map-overlay2').toggle('slow', function() {
              // Animation complete.
      });
    }

  </script> 
  <?=$dataplace;?>
  <?=$datadist;?>
  <?=$calcul_card_dist_fin;?>
  

  <script type="text/javascript">


    mapboxgl.accessToken =
    "pk.eyJ1IjoiY2hyaXN3aG9uZ21hcGJveCIsImEiOiJjbGE5eTB0Y2QwMmt6M3dvYW1ra3pmMnNsIn0.ZfF6uOlFNhl6qoCR7egTSw";
    var map_map = new mapboxgl.Map({
  container: "map_maps", // container ID
  style: "mapbox://styles/mapbox/streets-v12", // style URL
  bounds: [29.383188,-3.384438, 29.377566,-3.369615],
  projection: "globe" // display the map as a 3D globe
});

    map_map.addControl(new mapboxgl.NavigationControl());

    map_map.addControl(new mapboxgl.FullscreenControl());


    map_map.on("style.load", () => {
  // https://en.wikipedia.org/wiki/Transpeninsular_Line
      const transpeninsularLine = {
        type: "Feature",
        geometry: {
          type: "LineString",
          properties: {},
          coordinates: [<?php echo $track; ?>]
        }
      };
      
      

      map_map.addSource('LineString', {
        'type': 'geojson',
        'data': transpeninsularLine
      });
      map_map.addLayer({
        'id': 'LineString',
        'type': 'line',
        'source': 'LineString',
        'layout': {
          'line-join': 'round',
          'line-cap': 'round'
        },
        'paint': {
          'line-color': '#310bf6',
          'line-width': 4,
          'line-opacity': 0.7
        }
      });

    });



    var polygonVisible = true;
    map_map.on('load', () => {


    //polygone pour la delimitation
      // Add a data source containing GeoJSON data.
      map_map.addSource('provinces',{

        'type': 'geojson',
        'data': {
          'type': 'FeatureCollection',
          'features': [
            <?php echo $limites ?>
            ]
        }
      });

        // Add a new layer to visualize the polygon.
      map_map.addLayer({
        'id': 'provinces',
        'type': 'fill',
            'source': 'provinces', // reference the data source
            'layout': {},
            'paint': {
                'fill-color': '#888888', // gris color fill, blue:#0080ff
                'fill-opacity': 0.4
              }
            });
      // Add a black outline around the polygon.
      map_map.addLayer({
        'id': 'outline',
        'type': 'line',
        'source': 'provinces',
        'layout': {},
        'paint': {
          'line-color': '#f40a0a',//rouge, noir:#000
          'line-width': 3
        }
      });

      document.getElementById('toggle-button').addEventListener('click', function() {
        if (polygonVisible) {
          map_map.removeLayer('provinces');
          map_map.removeLayer('outline');
          polygonVisible = false;
        } else {
          map_map.addLayer({
            'id': 'provinces',
            'type': 'fill',
            'source': 'provinces', // reference the data source
            'layout': {},
            'paint': {
                'fill-color': '#888888', // gris color fill, blue:#0080ff
                'fill-opacity': 0.4
              }
            });
          map_map.addLayer({
            'id': 'outline',
            'type': 'line',
            'source': 'provinces',
            'layout': {},
            'paint': {
          'line-color': '#f40a0a',//rouge, noir:#000
          'line-width': 3
        }
      });
          polygonVisible = true;
        }
      });
      
      // const geojsonexces = {
      //   type: "Feature",
      //   geometry: {
      //     type: "LineString",
      //     properties: {},
      //     coordinates: [<?php echo $vitesse_exces; ?>]
      //   }
      // };
      // map_map.addSource('point', {
      //   'type': 'geojson',
      //   'data': geojsonexces
      // });


      

      // map_map.addLayer({
      //   'id': 'point',
      //   'type': 'line',
      //   'source': 'point',
      //   'layout': {
      //     'line-join': 'round',
      //     'line-cap': 'round'
      //   },
      //   'paint': {
      //     'line-color': '#FF0000',
      //     'line-width': 4,
      //     'line-opacity': 0.7
      //   }
      // });

      map_map.addSource('pointpoint', {
        'type': 'geojson',
        'data': {
          'type': 'FeatureCollection',
          'features': [<?=$geojsonexces;?>]
        }
      });


      

      map_map.addLayer({
        'id': 'pointpointlayer',
        'type': 'circle',
        'source': 'pointpoint',
        'paint': {
          'circle-radius': 9,
          'circle-color': '#B42222',
          'circle-stroke-width': 1,
          'circle-stroke-color': '#fff'
        }
      });

      //Debut popup on click exces de vitesse
      // When a click event occurs on a feature in the places layer, open a popup at the
        // location of the feature, with description HTML from its properties.
      map_map.on('click', 'pointpointlayer', (a) => {
        // document.getElementsByClassName('map-overlay2')[0].style.display = 'block';
            // Copy coordinates array.
        const coordinates = a.features[0].geometry.coordinates.slice();
        const description = a.features[0].properties.description;

            // Ensure that if the map is zoomed out such that multiple
            // copies of the feature are visible, the popup appears
            // over the copy being pointed to.
        while (Math.abs(a.lngLat.lng - coordinates[0]) > 180) {
          coordinates[0] += a.lngLat.lng > coordinates[0] ? 360 : -360;
        }

        new mapboxgl.Popup()
        .setLngLat(coordinates)
        .setHTML(description)
        .addTo(map_map);
        

      });

        // Change the cursor to a pointer when the mouse is over the places layer.
      map_map.on('mouseenter', 'pointpointlayer', () => {
        map_map.getCanvas().style.cursor = 'pointer';
      });

        // Change it back to a pointer when it leaves.
      map_map.on('mouseleave', 'pointpointlayer', () => {
        map_map.getCanvas().style.cursor = '';
      });

///fin
    //Points pour accident
      const geojsonaccident = {
        'type': 'FeatureCollection',
        'features': [<?php echo $geojsonaccident?>]
      };
      map_map.addSource('pointaccident', {
        'type': 'geojson',
        'data': geojsonaccident
      });
      map_map.addLayer({
        'id': 'pointaccident',
        'type': 'circle',
        'source': 'pointaccident',
        'paint': {
          'circle-color': '#f6023f',
          'circle-radius': 6
        }
      });
    // Create a popup, but don't add it to the map yet.
      const popupup = new mapboxgl.Popup({
        closeButton: false,
        closeOnClick: false
      });

      


      map_map.on('mouseleave', 'pointaccident', () => {
        map_map.getCanvas().style.cursor = '';
        popupup.remove();
      });

      map_map.on('mouseenter', 'pointaccident', (e) => {
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
        popupup.setLngLat(coordinates).setHTML(description).addTo(map_map);
      });

      var donn='<?= $mark_vprim ?>';

      var donn=donn.split('@');

      for (var i = 0; i<(donn.length) - 1; i++) {

        var index=donn[i].split('<>');
        if(index[4]==0){

          var apiUrl = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' + index[2] + ',' + index[3] + '.json?access_token=' + mapboxgl.accessToken;
          fetch(apiUrl)
          .then(response => response.json())
          .then(data => {
            adress = data.features[0].place_name;
            const popup = new mapboxgl.Popup({ offset: 25 }).setHTML(
              '<i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp;'+ adress +'<br><i class="fa fa-clock-o">&nbsp;&nbsp;&nbsp;' + index[6] +''
              );
            const popupParking = new mapboxgl.Popup({ offset: 25 }).setHTML(
              '<a><img src="<?= base_url()?>/upload/chauffeur/'+ index[10] +'" style="width: 15px;height: 15px;border-radius: 50%;" class="zoomable-image" title="Chauffeur">&nbsp;&nbsp;&nbsp;'+index[9]+'</a><br><i class="bi bi-textarea-resize"></i>&nbsp;&nbsp;&nbsp;'+ index[11] +'<br><i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp;'+ adress +'<br><i class="fa fa-clock-o"></i>&nbsp;&nbsp;&nbsp;' + index[8] +'&nbsp;&nbsp;-&nbsp;&nbsp;' + index[7] +''
              );
            var couleur='';
          couleur='#0000FF';//bleu


          const marker2 = new FontawesomeMarker({
            icon: 'fa fa-product-hunt',
            iconColor: 'white',
                color: '#0000FF',//bleu

              })


          .setLngLat([index[2],index[3]]).setPopup(popupParking).addTo(map_map);



          map_map.flyTo({
            center: [index[2], index[3]],
            speed: 0.5
          });


        })
          .catch(error => {
            console.log('Une erreur s\'est produite :', error);
          });

        }
        if(index[4]!=0){

          var apiUrl_url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' + index[0] + ',' + index[1] + '.json?access_token=' + mapboxgl.accessToken;
          var apiUrl = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' + index[2] + ',' + index[3] + '.json?access_token=' + mapboxgl.accessToken;
          fetch(apiUrl)
          .then(response => response.json())
          .then(data => {
            adress = data.features[0].place_name;
            const popup = new mapboxgl.Popup({ offset: 25 }).setHTML(
              '<a><img src="<?= base_url()?>/upload/chauffeur/'+ index[10] +'" style="width: 15px;height: 15px;border-radius: 50%;" class="zoomable-image" title="Chauffeur">&nbsp;&nbsp;&nbsp;'+index[9]+'</a><br><i class="bi bi-textarea-resize"></i>&nbsp;&nbsp;&nbsp;'+ index[11] +'<br><i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp;'+ adress +'<br><i class="fa fa-clock-o">&nbsp;&nbsp;&nbsp;' + index[7] +''
              );

            var couleur='';
         couleur='#FF0000';//rouge
         const marker2 = new mapboxgl.Marker({ color: couleur})
         .setLngLat([index[2],index[3]]).setPopup(popup).addTo(map_map);


         map_map.flyTo({
          center: [index[2], index[3]],
          speed: 0.5
        });


       })
          fetch(apiUrl_url)
          .then(response => response.json())
          .then(data => {
            adresse = data.features[0].place_name;
            const popupup = new mapboxgl.Popup({ offset: 25 }).setHTML(
              '<a><img src="<?= base_url()?>/upload/chauffeur/'+ index[10] +'" style="width: 15px;height: 15px;border-radius: 50%;" class="zoomable-image" title="Chauffeur">&nbsp;&nbsp;&nbsp;'+index[9]+'</a><br><i class="bi bi-textarea-resize"></i>&nbsp;&nbsp;&nbsp;'+ index[11] +'<br><i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp;'+ adresse +'<br><i class="fa fa-clock-o">&nbsp;&nbsp;&nbsp;' + index[8] +''
              );

            const marker1 = new mapboxgl.Marker({ color:'#00FF00'})//vert
            .setLngLat([index[0],index[1]]).setPopup(popupup).addTo(map_map);

            map_map.flyTo({
              center: [index[0], index[1]],
              speed: 0.5
            });


          })
          .catch(error => {
            console.log('Une erreur s\'est produite :', error);
          });


        }


      }





    });


  </script>

  <script type="text/javascript">
    //Fonction pour verifier les courses hors des heures de service du vehicule
    function show_shift_error()
    {
      var CODE = $('#CODE').val();
      var SHIFT = 'SHIFT';

      $.ajax({
        url : "<?=base_url()?>tracking/Dashboard/tracking_chauffeur_filtres/",
        type : "POST",
        dataType: "JSON",
        cache:false,
        data: {
          CODE:CODE,
          SHIFT:SHIFT,

        },
        beforeSend:function () { 

        },
        success:function(data) {

        },
        error:function() {


        }
      });
    }
  </script>


  <script type="text/javascript">
    //Fonction pour verifier les courses hors des heures de service du vehicule
    function show_shift_success()
    {
      var CODE = $('#CODE').val();
      var DATE_DAT = $('#DATE_DAT').val();
      var DATE_DAT_FIN = $('#DATE_DAT_FIN').val();
      var SHIFT1 = 'SHIFT1';

      $.ajax({
        url : "<?=base_url()?>tracking/Dashboard/tracking_chauffeur_filtres/",
        type : "POST",
        dataType: "JSON",
        cache:false,
        data: {
          CODE:CODE,
          SHIFT1:SHIFT1,
          DATE_DAT:DATE_DAT,
          DATE_DAT_FIN:DATE_DAT_FIN,

        },
        beforeSend:function () { 

        },
        success:function(data) {

        },
        error:function() {


        }
      });
    }
  </script>