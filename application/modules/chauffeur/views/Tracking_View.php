<!-- 
 Code modifié par CERUBALA CHRISTIAN WANN'Y
LE 14/06/2024
Ce code permet de visualiser l'historique des chauffeurs
les modifications on été faites pour la partie des informations générales 

-->

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
   font-weight: 80;
   font-size:13px;
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
   font-weight: 80;
   font-size:1rem;
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
  font-size: 6px;
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
  width:5rem;
  height: 40px;

}
/* nouveau styles pour l'afichage de l'historiques du traject chauffeur */
.text-success small pt-1 fw-boldd {

  font-size:4rem;
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
</style>


<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.js"></script>
<script src="https://unpkg.com/@turf/turf/turf.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script src='https://cdn.jsdelivr.net/npm/mapbox-gl-fontawesome-markers@0.0.1/dist/index.js'></script>


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
      <div class="col-md-6"style=" width: auto;">
      </div>
      <div class="col-md-3">
        <div class="justify-content-sm-end d-flex">
          <h1><?=lang('estimation_parcours')?></h1>
          
        </div>
      </div>
    </div>
  </div><!-- End Page Title -->
  <div class="row"style="width:auto">
    <div class="form-group col-md-3">
      <label class="form-label"><?=lang('input_date_deb')?></label>
      <input class="form-control" type="date" min="<?= $date_affectation['DATE_DEBUT_AFFECTATION']?>" max="<?= $date_affectation['DATE_FIN_AFFECTATION']?>" name="DATE_DAT" id="DATE_DAT" value="<?= $date_affectation['DATE_DEBUT_AFFECTATION']?>" onchange="change_carte();viderh();">
    </div>
    <div class="form-group col-md-3">
      <label class="form-label"><?=lang('input_date_fin')?></label>
      <input class="form-control" type="date" min="<?= $date_affectation['DATE_DEBUT_AFFECTATION']?>" max="<?= $date_affectation['DATE_FIN_AFFECTATION']?>" name="DATE_DAT_FIN" id="DATE_DAT_FIN" value="<?= $date_affectation['DATE_FIN_AFFECTATION']?>" onchange="change_carte();">
    </div>
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
  </div>
  <br>
  <input type="hidden" name="CODE" id="CODE" value="<?=$CODE_VEH?>">
  <input type="hidden" name="CHAUFFEUR_VEHICULE_ID" id="CHAUFFEUR_VEHICULE_ID" value="<?=$CHAUFFEUR_VEHICULE_ID?>">

  <section class="section">
    <div class="row align-items-top">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <center>
          <h5 class="card-title"><?=lang('btn_info_gnl')?></h5>
        </center>
        <div class="d-flex flex-wrap justify-content-between" style="gap: 10px;">
          
          <!-- Driver Info Section -->
          <div class="card" style="flex: 1; min-width: 250px; height: 110px;">
            <div class="card-body p-0 d-flex">
              <div class="profil-img">
                <img class="img-fluid rounded" style="background-color: white;" src="<?=!empty($get_chauffeur['PHOTO_PASSPORT']) ? base_url('/upload/chauffeur/'.$get_chauffeur['PHOTO_PASSPORT']) : base_url('upload/phavatar.png')?>">
              </div>
              <div class="profil-text" style="padding-left: 10px; padding-top: 10px;">
                <?php if (!empty($get_chauffeur)): ?>
                <p class="profil-name" title="<?=$get_chauffeur['NOM'].'&nbsp;'. $get_chauffeur['PRENOM']?>"><?=$get_chauffeur['NOM'].'&nbsp;'. $get_chauffeur['PRENOM']?></p>
                <p class="profil-phone" title="<?=$get_chauffeur['NUMERO_TELEPHONE']?>"><span class="bi bi-phone"></span>&nbsp;<?=$get_chauffeur['NUMERO_TELEPHONE']?></p>
                <p class="profil-phone" title="<?=$get_chauffeur['ADRESSE_MAIL']?>"><i class="bi bi-envelope"></i>&nbsp;<?=$get_chauffeur['ADRESSE_MAIL']?></p>
                <p class="profil-phone" title="<?=$get_chauffeur['ADRESSE_PHYSIQUE']?>"><i class="bi bi-geo-fill"></i>&nbsp;<?=$get_chauffeur['ADRESSE_PHYSIQUE']?></p>
                <?php else: ?>
                <p class="profil-name" style="color: red;" title="<?=lang('chauf_non_affect')?>"><?=lang('chauf_non_affect')?></p>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Vehicle Info Section -->
          <div class="card" style="flex: 1; min-width: 250px; height: 110px;">
            <div class="card-body p-0 d-flex">
              <div class="profil-img">
                <img class="img-fluid rounded" style="background-color: white;" src="<?=!empty($get_vehicule['PHOTO']) ? base_url('/upload/photo_vehicule/'.$get_vehicule['PHOTO']) : base_url('upload/car.png')?>">
              </div>
              <div class="profil-text" style="padding-left: 10px; padding-top: 10px;">
                <p class="profil-name" title="<?=$get_vehicule['DESC_MARQUE'].' / '. $get_vehicule['DESC_MODELE']?>"><?=$get_vehicule['DESC_MARQUE'].' / '. $get_vehicule['DESC_MODELE']?></p>
                <p class="profil-phone" title="<?=$get_vehicule['PLAQUE']?>"><i class="bi bi-textarea-resize"></i><?=$get_vehicule['PLAQUE']?></p>
                <p class="profil-phone" title="<?=!empty($get_vehicule['COULEUR']) ? $get_vehicule['COULEUR'] : 'N/A'; ?>"><i class="bi bi-palette"></i> <?=!empty($get_vehicule['COULEUR']) ? $get_vehicule['COULEUR'] : 'N/A'; ?></p>
                <p class="profil-phone" title="<?=!empty($get_vehicule['KILOMETRAGE']) ? $get_vehicule['KILOMETRAGE'].' litres / Km' : 'N/A'; ?>"><i class="bi bi-vector-pen"></i> <?=!empty($get_vehicule['KILOMETRAGE']) ? $get_vehicule['KILOMETRAGE'].' litres / Km' : 'N/A'; ?></p>
              </div>
            </div>
          </div>

          <!-- Distance Section -->
          <div class="card" style="flex: 1; min-width: 100px; height: 110px;">
            <div class="card-body">
              <h5 class="card-title" style="font-size:.6rem;"><?=lang('dist_parcourue')?> <span style="font-size:.5rem;">| Km</span></h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle">
                  <img class="img-fluid" width="30px" height="auto" style="background-color: #829b35; border-radius: 10%;" src="<?=base_url('/upload/distance.jpg')?>">
                </div>
                <div class="ps-3">
                  <h6 class="text-success small pt-1 fw-bold" style="font-size:.5rem"><a id="distance_finale"></a> Km</h6>
                </div>
              </div>
            </div>
          </div>

          <!-- Fuel Section -->
          <div class="card" style="flex: 1; min-width: 100px; height: 110px;">
            <div class="card-body">
              <h5 class="card-title" style="font-size:.6rem;"><?=lang('carburant_mot')?> <span style="font-size:.5rem;">| <?=lang('consomme_mot')?></span></h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle">
                  <img class="img-fluid" width="30px" height="auto" style="background-color: #829b35;" src="<?=base_url('/upload/carburant_color.jfif')?>">
                </div>
                <div class="ps-3">
                  <h6 class="text-success small pt-1 fw-bold" style="font-size:.5rem"><a id="carburant"></a> <?=lang('litre_mot')?></h6>
                </div>
              </div>
            </div>
          </div>

          <!-- Speed Section -->
          <div class="card" style="flex: 1; min-width: 100px; height: 110px;">
            <div class="card-body">
              <h5 class="card-title" style="font-size:.6rem;"><?=lang('vitesse_max')?> <span style="font-size:.5rem;">| Max</span></h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle">
                  <img class="img-fluid" width="30px" height="auto" style="background-color: #829b35; border-radius: 50%;" src="<?=base_url('/upload/vitesse.png')?>">
                </div>
                <div class="ps-3">
                  <h6 class="text-success small pt-1 fw-bold" style="font-size:.5rem"><a id="vitesse_max"></a> Km/h</h6>
                </div>
              </div>
            </div>
          </div>

          <!-- Score Section -->
          <div class="card" style="flex: 1; min-width: 100px; height: 110px;">
            <div class="card-body">
              <h5 class="card-title" style="font-size:.6rem; padding-top: 15px;">Score <span style="font-size:.5rem;">| 20</span></h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle">
                  <img class="img-fluid" width="30px" height="auto" style="background-color: #829b35;" src="<?=base_url('/upload/score.png')?>">
                </div>
                <div class="ps-3">
                  <h6 class="text-success small pt-1 fw-bold" style="font-size:.5rem"><a id="score"></a> Points</h6>
                </div>
              </div>
            </div>
          </div>

        </div> <!-- End of flex container -->
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




</div>
</section>

</main><!-- End #main -->

<?php include VIEWPATH . 'includes/footer.php'; ?>

</body>

<script>

  $(document).ready(function(){

    change_carte();   

  });

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
      alert('<?=lang('msg_erreur')?>');
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
  var CHAUFFEUR_VEHICULE_ID=$('#CHAUFFEUR_VEHICULE_ID').val();
  //alert(CODE_COURSE)

  $.ajax({
    url : "<?=base_url()?>chauffeur/Chauffeur_New/tracking_chauffeur_filtres/",
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
      CHAUFFEUR_VEHICULE_ID:CHAUFFEUR_VEHICULE_ID
    },
    beforeSend:function () { 

    },
    success:function(data) {

      $('#distance_finale').html(data.distance_finale);
      $('#carburant').html(data.carburant);
      $('#DATE_DAT').html(data.DATE);
      $('#CODE').html(data.CODE);
      $('#map_filtre').html(data.map_filtre);
      // $('#ligne_arret').html(data.ligne_arret);
      $('#score').html(data.score_finale);
      $('#vitesse_max').html(data.vitesse_max);

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
