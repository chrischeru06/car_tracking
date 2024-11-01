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
    height: 1000%;
    position: absolute;
    overflow-y: scroll;
    border-radius: 2px;
  }

  /*.table-responsive {
        width: 300px;
        border-radius: 10px;
    }*/

    .profil-info{
     padding: .3rem;

   }

   .profil-info .profil-text .bi{

    margin-right: .5rem;
    margin-left: .2rem;

  }
  .profil-info .profil-text p.profil-name{
   font-weight: 900;
   font-size:.6rem;
   margin: 0 0 .1rem 0;
   margin-left: .4rem;

   /* noms qui depassent l'espace prevu*/
   overflow-x: auto;
   white-space: nowrap;
   overflow: hidden;
   text-overflow: ellipsis;

   /* curseur*/

   cursor:pointer;


 }
 .mena .profil-info .profil-text p.profil-name{
   font-weight: 900;
   font-size:.6rem;
   margin: 0 0 .1rem 0;
   margin-left: .4rem;

   /* noms qui depassent l'espace prevu*/
   overflow-x: auto;
   white-space: nowrap;
   overflow: hidden;
   text-overflow: ellipsis;

   /* curseur*/

   cursor:pointer;

 }

 .profil-info .profil-text p.profil-phone{
  font-size: .6rem;
  margin: 0 0 .1rem 0;

  /* noms qui depassent l'espace prevu*/
  overflow-x: auto;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;

  /* curseur*/

  cursor:pointer;
}
.profil-info .profil-img img{
  width:4rem;
  height: auto;

}


.mapboxgl-popup {
  max-width: 400px;
  font:
  12px/20px 'Helvetica Neue',
  Arial,
  Helvetica,
  sans-serif;
}

#mena {
/*  position: absolute;*/
/*  font-weight: 900;*/
font-size:.6rem;
/*  margin: 0 0 0rem 0;*/
/*  margin-left: .4rem;*/
font-family: 'Open Sans', sans-serif;
}
#meno {
  position: absolute;
/*  background: #efefef;*/
/*-webkit-backdrop-filter:blur(15px);
backdrop-filter:blur(60px); */ 

padding: 10px;
font-family: 'Open Sans', sans-serif;
}


.arrow-right {
  width: 0;
  height: 0;
  border-top: 10px solid transparent;
  border-bottom: 10px solid transparent;
  border-left: 20px solid black; /* Ajustez la couleur et la taille selon vos besoins */
}

</style>


<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.js"></script>
<!-- <script src="https://unpkg.com/@turf/turf/turf.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


<script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script src='https://cdn.jsdelivr.net/npm/mapbox-gl-fontawesome-markers@0.0.1/dist/index.js'></script>
<script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>



</head>

<body>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
  <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css">
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

        <h1><?=lang('resum_du_parcours')?></h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><?=lang('p_chauffeur')?></a></li>
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
          <h1><?=lang('estimation_parcours')?></h1>
          
        </div>
      </div>
    </div>
  </div><!-- End Page Title -->
  <div class="row">
    <div class="form-group col-md-3">
      <label class="form-label"><?=lang('input_date_deb')?></label>
      <input class="form-control" type="date" max="<?= date('Y-m-d')?>" name="DATE_DAT" id="DATE_DAT" value="<?= date('Y-m-d')?>" onchange="change_carte();viderh();">
    </div>
    <div class="form-group col-md-3">
      <label class="form-label"><?=lang('input_date_fin')?></label>
      <input class="form-control" type="date" max="<?= date('Y-m-d')?>" name="DATE_DAT_FIN" id="DATE_DAT_FIN" value="<?= date('Y-m-d')?>" onchange="change_carte();">
    </div>

    <?php if(!empty($shift)){?>

      <div class="form-group col-md-2">
        <label class="form-label"><?=lang('hrs_dbut')?></label>
        <select class="form-control" name="HEURE1" id="HEURE1">
          <option value=""><?=lang('selectionner')?></option>
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


      <div class="form-group col-md-2">
        <label class="form-label"><?=lang('hrs_fin')?></label>
        <select class="form-control" name="HEURE2" id="HEURE2"  onchange="change_carte();" onclick="change_carte();">
          <option value=""><?=lang('selectionner')?></option>
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

      <div class="form-group col-md-2">
        <label class="form-label">Shift service</label>
        <select class="form-control" name="SHIFT" id="SHIFT"  onchange="change_carte();">
          <option value=""><?=lang('selectionner')?></option>
          <option value="1">Courses hors des heures de service</option>
          <option value="2">Courses pendant les heures de service</option>

        </select>

      </div>
      <?php
    }
    else
    {
      ?>
      <div class="form-group col-md-3">
        <label class="form-label"><?=lang('hrs_dbut')?></label>
        <select class="form-control" name="HEURE1" id="HEURE1">
          <option value=""><?=lang('selectionner')?></option>
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
        <label class="form-label"><?=lang('hrs_fin')?></label>
        <select class="form-control" name="HEURE2" id="HEURE2"  onchange="change_carte();" onclick="change_carte();">
          <option value=""><?=lang('selectionner')?></option>
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
      <?php
    }
    ?>
</div>
<br>
<input type="hidden" name="CODE" id="CODE" value="<?=$CODE_VEH?>">
<section class="section">
  <div class="row align-items-top">
    <div class="col-md-6">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title"><?=lang('btn_info_gnl')?></h5>
          <div class="row">
              <!-- <div class="col-lg-3">
              </div> -->
              <div class="col-md-6">
                <div class="card p-0" style="border-radius: 10%;">

                  <div class="card-body p-0">
                   <div class="row profil-info">
                    <div class="col-md-4 profil-img">

                      <?php
                      if(!empty($get_chauffeur['PHOTO_PASSPORT']))
                      {
                        ?>
                        <img class="img" style="border-radius: 10%;background-color: white;" class="img-fluid" src="<?=base_url('/upload/chauffeur/'.$get_chauffeur['PHOTO_PASSPORT'])?>">


                        <?php
                      }
                      else if(empty($get_chauffeur['PHOTO_PASSPORT']))
                      {
                        ?>
                        <img class="img" style="background-color: #829b35;border-radius: 10%" class="img-fluid" src="<?=base_url('upload/phavatar.png')?>">
                        <?php
                      }?>
                    </div>
                    <div class="col-md-8 profil-text">
                     <?php
                     if(!empty($get_chauffeur)){?>

                      <p class="profil-name" title="<?=$get_chauffeur['NOM'].'&nbsp;'. $get_chauffeur['PRENOM']?>"><?=$get_chauffeur['NOM'].'&nbsp;'. $get_chauffeur['PRENOM']?></p>
                      <p class="profil-phone" title="<?=$get_chauffeur['NUMERO_TELEPHONE']?>"> <span class="bi bi-phone"></span>&nbsp;<?=$get_chauffeur['NUMERO_TELEPHONE']?></span></p>
                      <p class="profil-phone" title="<?=$get_chauffeur['ADRESSE_MAIL']?>"><i class="bi bi-envelope"></i>&nbsp;<?=$get_chauffeur['ADRESSE_MAIL']?></span></p>
                      <p class="profil-phone" title="<?=$get_chauffeur['ADRESSE_PHYSIQUE']?>"><i class="bi bi-geo-fill"></i>&nbsp;<?=$get_chauffeur['ADRESSE_PHYSIQUE']?></span></p>
                      

                      <?php
                    }else{?>
                      <p class="profil-name" style="color: red;" title="<?=lang('chauf_non_affect')?>"> <?=lang('chauf_non_affect')?>
                    </p>

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

        <div class="col-md-6">
          <div class="card  p-0" style="border-radius: 10%;" >
            <div class="card-body p-0">
              <div class="row profil-info">
                <div class="col-md-4 profil-img">

                  <?php
                  if(!empty($get_vehicule['PHOTO']))
                  {
                    ?>
                    <img class="img"  style="background-color: white;border-radius: 10%;" class="img-fluid" src="<?=base_url('/upload/photo_vehicule/'.$get_vehicule['PHOTO'])?>">
                    <?php
                  }
                  else if(empty($get_vehicule['PHOTO']))
                  {
                    ?>
                    <img class="img" style="border-radius: 10%;" class="img-fluid"  src="<?=base_url('upload/car.png')?>">

                    <?php
                  }?>

                </div>
                <div class="col-md-8 profil-text">

                  <p class="profil-name" title="<?=$get_vehicule['DESC_MARQUE'].' / '. $get_vehicule['DESC_MODELE']?>"><?=$get_vehicule['DESC_MARQUE'].' / '. $get_vehicule['DESC_MODELE']?></p>
                  <p class="profil-phone" title="<?=$get_vehicule['PLAQUE']?>"> <i class="bi bi-textarea-resize"></i><?=$get_vehicule['PLAQUE']?></p>
                  <p class="profil-phone" title="<?php if(empty($get_vehicule['COULEUR'])){?> N/A <?php } ?>
                  <?php if(!empty($get_vehicule['COULEUR'])){?>  <?= $get_vehicule['COULEUR']?> <?php } ?>"> <i class="bi bi-palette"></i><?php if(empty($get_vehicule['COULEUR'])){?> N/A <?php } ?>
                  <?php if(!empty($get_vehicule['COULEUR'])){?>  <?= $get_vehicule['COULEUR']?> <?php } ?></p>
                  <p class="profil-phone" title="<?php if(empty($get_vehicule['KILOMETRAGE'])){?> N/A <?php } ?>
                  <?php if(!empty($get_vehicule['KILOMETRAGE'])){?>  <?= $get_vehicule['KILOMETRAGE']?> litres / Km <?php } ?>"><i class="bi bi-vector-pen"></i> <?php if(empty($get_vehicule['KILOMETRAGE'])){?> N/A <?php } ?>
                  <?php if(!empty($get_vehicule['KILOMETRAGE'])){?>  <?= $get_vehicule['KILOMETRAGE']?> litres / Km <?php } ?></p>

                  <p class="profil-phone" title="<?php if(empty($shift)){ echo 'Heures service N/A';} else {echo 'Heures service '. $shift['HEURE_DEBUT'].' à '.$shift['HEURE_FIN'];} ?>">&nbsp;&nbsp;<i class="fa fa-clock-o"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(empty($shift)){ echo 'N/A';} else {echo $shift['HEURE_DEBUT'].' à '.$shift['HEURE_FIN'];} ?></p>

                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="card" style="border-radius: 10%">
            <div class="card-body">
              <h5 class="card-title" style="font-size:.8rem;"><?=lang('dist_parcourue')?> <span style="font-size:.6rem;">| Km</span></h5>
              <div style="float: right;top: 150px;color: #012970;font-size:.8rem;"><a id="lieu_arret1"></a></div>
              
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle" >
                  <img style="background-color: #829b35;border-radius: 10%" class="img-fluid" width="50px" height="auto" src="<?=base_url('/upload/distance.jpg')?>">
                </div>
                <div class="ps-3">
                  <h6><span class="text-success small pt-1 fw-bold"><a id="distance_finale"></a> Km</span></h6>
                </div>
                <div style="position:relative;left: 40px; top: 5px;" id="fleche">&darr;</div>
              </div>


              <div style="float: right;top: 250px;color: #710101;font-size:.8rem;"><a id="lieu_arret"></a></div>

            </div>
          </div>
        </div>

        <div class="col-md-6">


          <div class="card" style="border-radius: 10%">
            <div class="card-body">
              <h5 class="card-title" style="font-size:.8rem;"><?=lang('carburant_mot')?> <span style="font-size:.6rem;">| <?=lang('consomme_mot')?></span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle">
                  <img style="background-color: #829b35;" class="img-fluid" width="50px" height="auto" src="<?=base_url('/upload/carburant_color.jfif')?>">
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

          <div class="col-md-6">


            <div class="card" style="border-radius: 10%">
              <div class="card-body">
                <h5 class="card-title" style="font-size:.8rem;"><?=lang('vitesse_max')?> <span style="font-size:.6rem;">| Max</span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle">
                    <img style="background-color: #829b35;border-radius: 50%" class="img-fluid" width="50px" height="auto" src="<?=base_url('/upload/vitesse.png')?>">
                  </div>
                  <div class="ps-3">
                    <h6><span class="text-success small pt-1 fw-bold"> <a id="vitesse_max"></a> Km/h</span></h6>


                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">


            <div class="card" style="border-radius: 10%">
              <div class="card-body">
                <h5 class="card-title" style="font-size:.8rem;">Score <span style="font-size:.6rem;">| 20</span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle">
                    <img style="background-color: #829b35;" class="img-fluid" width="50px" height="auto" src="<?=base_url('/upload/score.png')?>">
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

  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title"><?=lang('title_adresse_veh')?></h5>
        <br>     
        <div id="map" style="height: 360px;"></div>
        <div id="mena">

          <input id="streets-v12" type="radio" name="rtoggle" value="streets" checked="checked">
          <label for="streets-v12">streets</label>&nbsp;&nbsp;
          <!-- <input id="satellite-streets-v12" type="radio" name="rtoggle" value="satellite"> -->
          <!-- See a list of Mapbox-hosted public styles at -->
          <!-- https://docs.mapbox.com/api/maps/styles/#mapbox-styles -->
          <!-- <label for="satellite-streets-v12">satellite</label>&nbsp;&nbsp; -->
            <!-- <input id="light-v11" type="radio" name="rtoggle" value="light">
            <label for="light-v11">lumineux</label>&nbsp;&nbsp;
            <input id="dark-v11" type="radio" name="rtoggle" value="dark">
            <label for="dark-v11">nuit</label>&nbsp;&nbsp;

            <input id="outdoors-v12" type="radio" name="rtoggle" value="outdoors">
            <label for="outdoors-v12">En plein air</label> -->
            <div class="profil-info" style="float:right;">

              <div class="profil-text">
                <p class="profil-phone"> <i class="bi bi-dot" style="color:red;" ></i>&nbsp;<?=lang('veh_eteint')?></p>
                <p class="profil-phone"> <i class="bi bi-dot" style="color:blue;" ></i>&nbsp;<?=lang('veh_mvt')?></p>
              </div>
            </div>
          </div>
            

          </div>
        </div>

      </div>


    </div>

    <div class="row align-items-top">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <center><h6 class="card-title"><?=lang('trajet_parcouru')?></h6></center>

            <div id="map_filtre" ></div>

          </div>
        </div>
      </div>



      <input type="hidden" id="ignition">
      <input type="hidden" id="dist_finale">


      <!-- <textarea type="text" id="coord"></textarea> -->

    </div>
  </section>

</main><!-- End #main -->

<?php include VIEWPATH . 'includes/footer.php'; ?>
<script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>
</body>

<script>

  $(document).ready(function(){
    $('#fleche').hide();

    change_carte();

    // show_shift_error();
    // show_shift_success();

  });



  mapboxgl.accessToken = 'pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc0dnBzNzA3c3gzZmx2bnpqb2NwNXgifQ.D6Fm6UO9bWViernvxZFW_A';
  const map = new mapboxgl.Map({
    container: 'map',
        // Choose from Mapbox's core styles, or make your own style with Mapbox Studio
    style: 'mapbox://styles/mapbox/streets-v12',
    bounds: [29.383188,-3.384438, 29.377566,-3.369615],
    projection: "globe" // display the map as a 3D globe
  });


  

  //Points sur la carte d'adresse du vehicule
  const size = 120;

  const pulsingDot = {
    width: size,
    height: size,
    data: new Uint8Array(size * size * 4),

  // When the layer is added to the map,
    // get the rendering context for the map canvas.
    onAdd: function () {
      const canvas = document.createElement('canvas');
      canvas.width = this.width;
      canvas.height = this.height;
      this.context = canvas.getContext('2d');
    },

    // Call once before every frame where the icon will be used.
    render: function () {
      const duration = 1000;
      const t = (performance.now() % duration) / duration;

      const radius = (size / 2) * 0.3;
      const outerRadius = (size / 2) * 0.7 * t + radius;
      const context = this.context;

            // Draw the outer circle.
      context.clearRect(0, 0, this.width, this.height);
      context.beginPath();
      context.arc(
        this.width / 2,
        this.height / 2,
        outerRadius,
        0,
        Math.PI * 2
        );

      //get color from controller
      var ignition = $('#ignition').val(); 

      context.fillStyle = `rgba(`+ignition+`, ${1 - t})`;
      context.fill();

            // Draw the inner circle.
      context.beginPath();
      context.arc(
        this.width / 2,
        this.height / 2,
        radius,
        0,
        Math.PI * 2
        );
      context.fillStyle = 'rgba('+ignition+', 1)';
      context.strokeStyle = 'white';
      context.lineWidth = 2 + 4 * (1 - t);
      context.fill();
      context.stroke();

            // Update this image's data with data from the canvas.
      this.data = context.getImageData(
        0,
        0,
        this.width,
        this.height
        ).data;

            // Continuously repaint the map, resulting
            // in the smooth animation of the dot.
      map.triggerRepaint();

            // Return `true` to let the map know that the image was updated.
      return true;
    }
  };

  map.addControl(new mapboxgl.NavigationControl());
  map.addControl(new mapboxgl.FullscreenControl());

  function addAdditionalSourceAndLayer() {
    map.on('load', async () => {
        // Get the initial location of the International Space Station (ISS).
      const geojson = await getLocation();
        // Add the ISS location as a source.
      map.addSource('iss', {
        type: 'geojson',
        data: geojson
      });

    // Add the rocket symbol layer to the map.   
      map.addImage('pulsing-dot', pulsingDot, { pixelRatio: 2 });

      map.addSource('places', {
        'type': 'geojson',
        'data': {
          'type': 'FeatureCollection',
          'features': [
          {
            'type': 'Feature',
            'geometry': {
              'type': 'Point',
              'coordinates': [-77.007481, 38.876516]
            }
          }
          ]
        }
      });

      map.addLayer({
        'id': 'places',
        'type': 'symbol',
        'source': 'iss',
        'layout': {
          'icon-image': 'pulsing-dot',


        },

      });

      // Create a popup, but don't add it to the map yet.
      // const popup = new mapboxgl.Popup({
      //   closeButton: false,
      //   closeOnClick: false
      // });

      // map.on('mouseenter', 'places', (e) => {
            // Change the cursor style as a UI indicator.
        // map.getCanvas().style.cursor = 'pointer';

            // Copy coordinates array.
        // const coordinates = e.features[0].geometry.coordinates.slice();
        // const description = e.features[0].properties.description;

            // Ensure that if the map is zoomed out such that multiple
            // copies of the feature are visible, the popup appears
            // over the copy being pointed to.
        // while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
        //   coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
        // }
            // Populate the popup and set its coordinates
            // based on the feature found.
      //   popup.setLngLat(coordinates).setHTML(description).addTo(map);
      // });

      // map.on('mouseleave', 'places', () => {
      //   map.getCanvas().style.cursor = '';
      //   popup.remove();
      // });

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
          const { latitude, longitude, vitesse, ignition } = await response.json();

        // get color
          $('#ignition').val(ignition);

        // Fly the map to the location.
          map.flyTo({
            center: [longitude, latitude],
            speed: 0.5
          });
        // Return the location of the ISS as GeoJSON.
        // var apiUrl = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' + longitude + ',' + latitude + '.json?access_token=' + mapboxgl.accessToken;
        //   fetch(apiUrl)
        //   .then(response => response.json())
        //   .then(data => {
        //      adress = data.features[0].place_name;
        //      var adresse= adress



        //   })
        //   .catch(error => {
        //     console.log('Une erreur s\'est produite :', error);
        //   });
          

          
          function getReverseGeocode(feature) {
            var lat = latitude;
            var lng = longitude;
            var url = "https://api.mapbox.com/geocoding/v5/mapbox.places/" + lng + "," + lat + ".json?access_token=" + mapboxgl.accessToken;
            $.get(url, function(data){
              var myData = data;
              doAThing(data);
            });
            function doAThing(data){
        // Populate the popup and set its coordinates
        // based on the feature found.
              var popup = new mapboxgl.Popup()
              .setLngLat(feature.geometry.coordinates)
              .setHTML(makeHtml(data))
              .addTo(map);
            }

            function makeHtml(data) {
              var feature = data.features[0];
              var name = feature.text;
              var type = feature.type;

              var formattedHtml = "<i class='fa fa-map-marker'></i>&nbsp;&nbsp;&nbsp;" + name + "<br> <i class='fa fa-dashboard'></i>&nbsp;&nbsp;&nbsp;" +vitesse+ ' Km/h';
              return formattedHtml;
            }
          }

          map.on('click', function (e) {
            var features = map.queryRenderedFeatures(e.point, { layers: ['places'] });

            if (!features.length) {
              return;
            }

            var feature = features[0];
            getReverseGeocode(feature);


          });

          return {
            'type': 'FeatureCollection',
            'features': [
            {
              'type': 'Feature',
              'properties': {
                'description':
                '<strong>'+vitesse+' km/h</strong><p></p>'
              },
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

}

    // Add source and layer whenever base style is loaded
map.on('style.load', () => {
  addAdditionalSourceAndLayer();

});
  // function changeMapStyleToSatellite() {
const layerList = document.getElementById('mena');
const inputs = layerList.getElementsByTagName('input');

for (const input of inputs) {
  input.onclick = (layer) => {
    const layerId = layer.target.id;
    map.setStyle('mapbox://styles/mapbox/' + layerId);
  };
}
  // }
  // changeMapStyleToSatellite();



 // const url='https://nominatim.openstreetmap.org/reverse?lat='+latitude+'&lon='+longitude+'&accept-language=fr';
 //        const responsetwo = await fetch(url);
 //        const { json } = await responsetwo.json();
        // const res= setReverseGeocodingResult(json);
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
 function change_carte(CODE_COURSE='') {
  var DATE_DAT = $('#DATE_DAT').val(); 
  var DATE_DAT_FIN = $('#DATE_DAT_FIN').val(); 
  var CODE = $('#CODE').val(); 
  var HEURE1 = $('#HEURE1').val(); 
  var HEURE2 = $('#HEURE2').val(); 
  var CODE_COURSE = CODE_COURSE; 
  var dist_fin= $('#dist_finale').val();
  var SHIFT= $('#SHIFT').val();

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
      CODE_COURSE:CODE_COURSE,
      SHIFT:SHIFT,
      

    },
    beforeSend:function () { 

    },
    success:function(data) {


      $('#DATE_DAT').html(data.DATE);
      $('#CODE').html(data.CODE);
      $('#map_filtre').html(data.map_filtre);
      $('#score').html(data.score_finale);
      $('#vitesse_max').html(data.vitesse_max);
      
      var donna=data.mark_vprim;

      var donna=donna.split('@');

      for (var i = 0; i<(donna.length) - 1; i++) {

        var indexa=donna[i].split('<>');
        if(indexa[4]==0){

          var apiUrla = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' + indexa[2] + ',' + indexa[3] + '.json?access_token=' + mapboxgl.accessToken;
          fetch(apiUrla)
          .then(response => response.json())
          .then(data => {
            adressa = data.features[0];
            name2=adressa.text;
            name3='';
            $('#lieu_arret').html(name2);
            $('#lieu_arret1').html(name3);




          })
          .catch(error => {
            console.log('Une erreur s\'est produite :', error);
          });
        }

        if(indexa[4]!=0){

          var apiUrl_urla = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' + indexa[0] + ',' + indexa[1] + '.json?access_token=' + mapboxgl.accessToken;
          var apiUrlaa = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' + indexa[2] + ',' + indexa[3] + '.json?access_token=' + mapboxgl.accessToken;
          fetch(apiUrl_urla)
          .then(response => response.json())
          .then(data => {
            adressee = data.features[0];
            name1=adressee.text;
            $('#lieu_arret1').html(name1);
            $('#fleche').show();


            



          })
          .catch(error => {
            console.log('Une erreur s\'est produite :', error);
          });

          fetch(apiUrlaa)
          .then(response => response.json())
          .then(data => {
            adresseee = data.features[0];
            name11=adresseee.text;
            $('#lieu_arret').html(name11);



          })
          .catch(error => {
            console.log('Une erreur s\'est produite :', error);
          });

        }
      }
      if (data.track_dist!='') { 
        let newdata='['+data.track_dist+']'
        let newdata2=JSON.parse(newdata)
        var line = turf.lineString(newdata2);
        var length = turf.length(line, { units: "kilometers" });
        var distance_vrai2 = length.toLocaleString();
        let chaineAvecPointss = distance_vrai2.replace(/,/g, ".");

        $('#distance_finale').html(chaineAvecPointss);
        // $('#coord').val(data.track_dist);
        $('#dist_finale').val(distance_vrai2);
        if(data.carburant_val!=''){
          var distance_vrai3=$('#dist_finale').val();
          let chaineAvecPoints = distance_vrai3.replace(/,/g, ".");

          var consommation = Number(data.carburant_val) * chaineAvecPoints;
          if(consommation!=0){
            var cons_arrondi=consommation.toFixed(2);
            $('#carburant').html(cons_arrondi);

          }else{
           $('#carburant').html(consommation);

         }


       }else{
        var consommation = 'N/A';
        $('#carburant').html(consommation);

      }

    }else{
      distance_vrai2=0;
      var consommation = 'N/A';
      $('#carburant').html(consommation);

    }



  },
  error:function() {


  }
});

}

function change_trajet(CODE_COURSE){

  var CODE_COURSE = CODE_COURSE; 

  $.ajax({
    url : "<?=base_url()?>tracking/Dashboard/tracking_chauffeur_filtres/",
    type : "POST",
    dataType: "JSON",
    cache:false,
    data: {
      CODE_COURSE:CODE_COURSE,

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



</html>