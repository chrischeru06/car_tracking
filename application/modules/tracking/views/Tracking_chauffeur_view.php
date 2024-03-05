<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>

  <style>
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

  /* Activity */
  .dashboard .activity {
    font-size: 14px;
  }
  .dashboard .activity .activity-item .activite-label {
    color: #888;
    position: relative;
    flex-shrink: 0;
    flex-grow: 0;
    min-width: 64px;
  }
  .dashboard .activity .activity-item .activite-label::before {
    content: "";
    position: absolute;
    right: -11px;
    width: 4px;
    top: 0;
    bottom: 0;
    background-color: #eceefe;
  }
  .dashboard .activity .activity-item .activity-badge {
    margin-top: 3px;
    z-index: 1;
    font-size: 11px;
    line-height: 0;
    border-radius: 50%;
    flex-shrink: 0;
    border: 3px solid #fff;
    flex-grow: 0;
  }
  .dashboard .activity .activity-item .activity-content {
    padding-left: 10px;
    padding-bottom: 20px;
  }
  .dashboard .activity .activity-item:first-child .activite-label::before {
    top: 5px;
  }
  .dashboard .activity .activity-item:last-child .activity-content {
    padding-bottom: 0;
  }

  .scroller {
    height: 400px;
    overflow-y: scroll;
    border-radius: 10px;
  }

  /*.table-responsive {
        width: 300px;
        border-radius: 10px;
    }*/

  </style>


  <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.css" rel="stylesheet">
  <script src="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.js"></script>
  <script src="https://unpkg.com/@turf/turf/turf.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>





</head>

<body>

  <!-- ======= Header ======= -->
  <?php include VIEWPATH . 'includes/nav_bar.php'; ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php include VIEWPATH . 'includes/menu_left.php'; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

   <div class="pagetitle">
    <div class="row">
      <div class="col-md-6">

        <h1>Résumé du parcours</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Chauffeur</a></li>
            <!-- <li class="breadcrumb-item active">Liste</li> -->
          </ol>
        </nav>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
      </div>
      <div class="col-md-3">
        <div class="justify-content-sm-end d-flex">
          <h1>Estimation parcours</h1>
          
        </div>
      </div>
    </div>
  </div><!-- End Page Title -->
  <div class="row">
    <div class="form-group col-md-3">
      <label class="form-label">Date début</label>
      <input class="form-control" type="date" max="<?= date('Y-m-d')?>" name="DATE_DAT" id="DATE_DAT" value="<?= date('Y-m-d')?>" onchange="change_carte();viderh();">
    </div>
    <div class="form-group col-md-3">
      <label class="form-label">Date fin</label>
      <input class="form-control" type="date" max="<?= date('Y-m-d')?>" name="DATE_DAT_FIN" id="DATE_DAT_FIN" value="<?= date('Y-m-d')?>" onchange="change_carte();">
    </div>
    <div class="form-group col-md-3">
      <label class="form-label">Heure début</label>
      <select class="form-control" name="HEURE1" id="HEURE1">
        <option value="">Séléctionner</option>
        <?php
        foreach ($heure_trajet as $key_heure_trajet)
        {
          ?>
          <option value="<?=$key_heure_trajet['HEURE_ID']?>"><?=$key_heure_trajet['HEURE']?></option>
          <?php
        }
        ?>
      </select>

    </div>


    <div class="form-group col-md-3">
      <label class="form-label">Heure fin</label>
      <select class="form-control" name="HEURE2" id="HEURE2"  onchange="change_carte();" onclick="change_carte();">
        <option value="">Séléctionner</option>
        <?php
        foreach ($heure_trajet as $key_heure_trajet)
        {
          ?>
          <option value="<?=$key_heure_trajet['HEURE_ID']?>"><?=$key_heure_trajet['HEURE']?></option>
          <?php
        }
        ?>
      </select>

    </div>
  </div>
  <br>
  <input type="hidden" name="CODE" id="CODE" value="<?=$CODE_VEH?>">
  <section class="section">
    <div class="row align-items-top">
      <div class="col-md-6">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Informations générales</h5>
            <div class="row">
              <div class="col-lg-12">
                <div class="card" style="border-radius: 10%;">
                  <div class="card-body profile-card pt-4 d-flex flex-column">

                    <div>
                      <div>
                        <!-- <img style="background-color: #829b35;border-radius: 0%" class="img-fluid" width="100px" height="auto" src="<?=base_url('/upload/distance.jpg')?>"> -->


                        <?php
                        if(!empty($get_chauffeur['PHOTO_PASSPORT']))
                        {
                          ?>
                          <img  class="card-icon" style="border-radius: 10%;" class="img-fluid" width="120px" height="auto" src="<?=base_url('/upload/chauffeur/'.$get_chauffeur['PHOTO_PASSPORT'])?>">&nbsp;&nbsp;

                          <?php
                        }
                        else if(empty($get_chauffeur['PHOTO_PASSPORT']))
                        {
                          ?>
                          <img class="card-icon rounded-circle" style="background-color: #829b35;border-radius: 10%" class="img-fluid" width="120px" height="auto" src="<?=base_url('upload/phavatar.png')?>">&nbsp;&nbsp;
                          <?php
                        }?>



                        <?php
                        if(!empty($get_chauffeur)){?>

                          <!-- <font style="float: right;"> -->
                            <!-- <div class="row"> -->
                                <font style="float: right;">

                              <b><?=$get_chauffeur['NOM'].'&nbsp;'. $get_chauffeur['PRENOM']?></b><br>
                              <!-- </div> -->

                                  <span class="bi bi-phone"></span>&nbsp;<?=$get_chauffeur['NUMERO_TELEPHONE']?><br>
                                  <i class="bi bi-envelope"></i>&nbsp;<?=$get_chauffeur['ADRESSE_MAIL']?><br>
                                  <i class="bi bi-circle"></i>&nbsp;<?=$get_chauffeur['ADRESSE_PHYSIQUE']?><br>
                                </font>

                              <?php
                            }else{?>
                              <font style="float: right; color: red;"> Pas de chauffeur affecté à cette voiture!
                             </font>

                             <?php
                           }
                           ?>



                         </div>


                       </div>

                       <div>


                       </div>
                     </div>
                   </div>
                 </div>

                 <div class="col-lg-12">
                  <div class="card" style="border-radius: 10%;" >
                   <div class="card-body profile-card pt-4 d-flex flex-column">

                    <div>
                      <div>

                        <?php
                        if(!empty($get_vehicule['PHOTO']))
                        {
                          ?>
                          <img  class="card-icon" style="background-color: white;border-radius: 10%;" class="img-fluid" width="120px" height="auto" src="<?=base_url('/upload/photo_vehicule/'.$get_vehicule['PHOTO'])?>">&nbsp;&nbsp;
                          <?php
                        }
                        else if(empty($get_vehicule['PHOTO']))
                        {
                          ?>
                          <img class="card-icon" style="border-radius: 10%;" class="img-fluid" width="120px" height="auto" src="<?=base_url('upload/car.png')?>">&nbsp;&nbsp;

                          <?php
                        }?>
                        <font style="float: right;">
                          <span class="text-muted small pt-2 ps-1"></span><b><?=$get_vehicule['DESC_MARQUE'].' / '. $get_vehicule['DESC_MODELE']?></b><br>&nbsp;&nbsp;
                          <i class="bi bi-textarea-resize"></i> <span class="text-muted small pt-2 ps-1"><?=$get_vehicule['PLAQUE']?></span><br>&nbsp;&nbsp;
                          <i class="bi bi-palette"></i><span class="text-muted small pt-2 ps-1"><?php if(empty($get_vehicule['COULEUR'])){?> N/A <?php } ?>
                          <?php if(!empty($get_vehicule['COULEUR'])){?>  <?= $get_vehicule['COULEUR']?> <?php } ?></span><br>&nbsp;&nbsp;
                        </font>

                      </div>



                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="card" style="border-radius: 10%">
                  <div class="card-body">
                    <h5 class="card-title">Distance parcourue <span>| Km</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle" >
                        <img style="background-color: #829b35;border-radius: 10%" class="img-fluid" width="100px" height="auto" src="<?=base_url('/upload/distance.jpg')?>">
                      </div>
                      <div class="ps-3">
                        <h6><span class="text-success small pt-1 fw-bold"><a id="distance_finale"></a> Km</span></h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-6">


                <div class="card" style="border-radius: 10%">
                  <div class="card-body">
                    <h5 class="card-title">Carburant <span>| consommé</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle">
                        <img style="background-color: #829b35;" class="img-fluid" width="100px" height="auto" src="<?=base_url('/upload/carburant_color.jfif')?>">
                      </div>
                      <div class="ps-3">
                        <h6><span class="text-success small pt-1 fw-bold"> <a id="carburant"></a> litres</span></h6>


                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <!-- <div class="col-lg-12"> -->

                <div class="col-lg-6">


                  <div class="card" style="border-radius: 10%">
                    <div class="card-body">
                      <h5 class="card-title">Vitesse <span>| Max</span></h5>

                      <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle">
                          <img style="background-color: #829b35;border-radius: 50%" class="img-fluid" width="100px" height="auto" src="<?=base_url('/upload/vitesse.png')?>">
                        </div>
                        <div class="ps-3">
                          <h6><span class="text-success small pt-1 fw-bold"> <a id="vitesse_max"></a> Km/h</span></h6>


                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-6">


                  <div class="card" style="border-radius: 10%">
                    <div class="card-body">
                      <h5 class="card-title">Score <span>| 20</span></h5>

                      <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle">
                          <img style="background-color: #829b35;" class="img-fluid" width="100px" height="auto" src="<?=base_url('/upload/score.png')?>">
                        </div>
                        <div class="ps-3">
                          <h6><span class="text-success small pt-1 fw-bold"> <a id="score"></a> Points</span></h6>


                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- </div> -->
              </div>


            </div>
          </div>



        </div>

        <div class="col-lg-6">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Adresse de la voiture</h5>
              <br>
              <br>
              <div id="map" style="width: 100%;height: 780px;"></div>


              <form method="POST" action="<?= base_url('tracking/Dashboard/tracking_chauffeur/'.$CODE_VEH.'') ?>"  >

                <div id="menu"> 

                  <?php $carte; ?>


                  <input onchange="submit()" id="satellite-streets-v12" type="radio" name="rtoggle" value="satellite" <?php if($info == 'satellite') echo "checked"; $carte = 'satellite-streets-v12'; ?>>

                  <label for="satellite-streets-v12">satellite</label>

                  <input onchange="submit()" id="streets-v12" type="radio" name="rtoggle" value="streets" <?php if($info == 'streets') echo "checked"; $carte = 'streets-v12'; ?> >
                  <label for="streets-v12">streets</label>

                </div>
              </form>
            </div>
          </div>

        </div>
      </div>

      <div class="row align-items-top">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <center><h6 class="card-title">Trajet parcouru</h6></center>

              <br>
              <br>
              <div id="map_filtre"></div>

            </div>
          </div>



        </div>

       <!--  <div class="col-lg-3">
          <section class="section dashboard">

            <div class="card">

             <div class="card-body">
              <h5 class="card-title">Points d'arrêt <span>| Today</span></h5>
              <div class="scroller">

                <div class="activity">

                  <div id="ligne_arret"></div>

                </div>
              </div>
            </div>
          </div>
        </section>
      </div> -->



    </div>
  </section>

</main><!-- End #main -->

<?php include VIEWPATH . 'includes/footer.php'; ?>

</body>

<script>

  $(document).ready(function(){

    change_carte();   

  });

  

  mapboxgl.accessToken = 'pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc0dnBzNzA3c3gzZmx2bnpqb2NwNXgifQ.D6Fm6UO9bWViernvxZFW_A';
  const map = new mapboxgl.Map({
    container: 'map',
        // Choose from Mapbox's core styles, or make your own style with Mapbox Studio
    style: 'mapbox://styles/mapbox/streets-v12',
    bounds: [29.383188,-3.384438, 29.377566,-3.369615],
    projection: "globe" // display the map as a 3D globe
  });

  map.addControl(new mapboxgl.NavigationControl());

  map.on('load', async () => {
        // Get the initial location of the International Space Station (ISS).
    const geojson = await getLocation();
        // Add the ISS location as a source.
    map.addSource('iss', {
      type: 'geojson',
      data: geojson
    });
        // Add the rocket symbol layer to the map.   
        // http://161.97.118.14/iotplatform/Map/getmap



    map.loadImage(
      '<?= base_url() ?>upload/voll.png',
      (error, image) => {
        if (error) throw error;
        
        // Add the image to the map style.
        map.addImage('care', image);
        
        // Add a data source containing one point feature.
        map.addSource('point', {
          'type': 'geojson',
          'data': {
            'type': 'FeatureCollection',
            'features': [
            {
              'type': 'Feature',
              'properties': {
                'description':
                '<strong>Make it Mount Pleasant</strong><p><a href="http://www.mtpleasantdc.com/makeitmtpleasant" target="_blank" title="Opens in a new window">Make it Mount Pleasant</a> is a handmade and vintage market and afternoon of live entertainment and kids activities. 12:00-6:00 p.m.</p>',
                'icon': 'theatre'
              },
              'geometry': {
                'type': 'Point',
                'coordinates': [-77.4144, 25.0759]
              }
            }
            ]
          }
        });
        
        // Add a layer to use the image to represent the data.
        // map.addLayer({
        // 'id': 'points',
        // 'type': 'symbol',
        // 'source': 'point', // reference the data source
        // 'layout': {
        // 'icon-image': 'cat', // reference the image
        // 'icon-size': 0.25
        // }
        // });


        map.addLayer({
          'id': 'iss',
          'type': 'symbol',
          'source': 'iss',
          'layout': {
                // This icon is a part of the Mapbox Streets style.
                // To view all images available in a Mapbox style, open
                // the style in Mapbox Studio and click the "Images" tab.
                // To add a new image to the style at runtime see
                // https://docs.mapbox.com/mapbox-gl-js/example/add-image/
            'icon-image': 'care',
            'icon-size': 0.05
          }
        });

      }
      );




// TOYOTA TI C3625A



        // Update the source from the API every 2 seconds.
    const updateSource = setInterval(async () => {
      const geojson = await getLocation(updateSource);
      map.getSource('iss').setData(geojson);
    }, 2000);

    async function getLocation(updateSource) {
            // Make a GET request to the API and return the location of the ISS.
      try {
        var CODE = $('#CODE').val(); 

        const response = await fetch(
          '<?= base_url() ?>tracking/Dashboard/getmap/'+CODE,
          { method: 'GET' }
          );
        const { latitude, longitude } = await response.json();
                // Fly the map to the location.
        map.flyTo({
          center: [longitude, latitude],
          speed: 0.5
        });
                // Return the location of the ISS as GeoJSON.
        return {
          'type': 'FeatureCollection',
          'features': [
          {
            'type': 'Feature',
            'geometry': {
              'type': 'Point',
              'coordinates': [longitude, latitude]
            }
          }
          ]
        };
      } catch (err) {
                // If the updateSource interval is defined, clear the interval to stop updating the source.
        if (updateSource) clearInterval(updateSource);
        throw new Error(err);
      }
    }
  });

  map.setStyle('mapbox://styles/mapbox/<?= $carte; ?>');


</script>


<script>
  function viderh(){

   $('#HEURE1').html('');
   $('#HEURE2').html('');

   $.ajax(
   {
    url:"<?=base_url('tracking/Dashboard/get_heures/')?>",
    type: "GET",
    dataType:"JSON",
    success: function(data)
    {
      $('#HEURE1').html(data);
      $('#HEURE2').html(data);

    },
    error: function (jqXHR, textStatus, errorThrown)
    {
      alert('Erreur');
    }
  });
 }
 function change_carte() {
  var DATE_DAT = $('#DATE_DAT').val(); 
  var DATE_DAT_FIN = $('#DATE_DAT_FIN').val(); 
  var CODE = $('#CODE').val(); 
  var HEURE1 = $('#HEURE1').val(); 
  var HEURE2 = $('#HEURE2').val(); 

  $.ajax({
    url : "<?=base_url()?>tracking/Dashboard/tracking_chauffeur_filtres/",
    type : "POST",
    dataType: "JSON",
    cache:false,
    data: {
      DATE_DAT:DATE_DAT,
      CODE:CODE,
      HEURE1:HEURE1,
      HEURE2:HEURE2,
      DATE_DAT_FIN:DATE_DAT_FIN,



    },
    beforeSend:function () { 

    },
    success:function(data) {

        // alert(data.vitesse_max)


      $('#distance_finale').html(data.distance_finale);
      $('#carburant').html(data.carburant);
      $('#DATE_DAT').html(data.DATE);
      $('#CODE').html(data.CODE);
        // alert(data.distance_finale)
      $('#map_filtre').html(data.map_filtre);
      $('#ligne_arret').html(data.ligne_arret);
      $('#score').html(data.score_finale);
      $('#vitesse_max').html(data.vitesse_max);






    },
    error:function() {


    }
  });

}

</script>



</html>