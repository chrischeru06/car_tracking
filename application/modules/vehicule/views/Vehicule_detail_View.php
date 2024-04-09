<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>

  <script src='https://api.mapbox.com/mapbox.js/v3.2.0/mapbox.js'></script>
  <link href='https://api.mapbox.com/mapbox.js/v3.2.0/mapbox.css' rel='stylesheet' />
  <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/leaflet.markercluster.js'></script>
  <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.css' rel='stylesheet' />
  <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.Default.css' rel='stylesheet' />

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

  <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-zoomslider/v0.7.0/L.Control.Zoomslider.js'></script>
  <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-zoomslider/v0.7.0/L.Control.Zoomslider.css' rel='stylesheet'/>

  <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
  <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />

  <style type="text/css">
    .dash_v{
    /*  border: solid 1px rgb(128, 128, 128, 0.3);
      border-radius: 50%;
      padding: 10%;*/
      text-align: center;
      font-weight:bold;
    }
    table td{
      text-align: center;
    }
    #tracking{
      font-size: 25px;
      color: gray;
    }
    #tracking:hover{
      color: cadetblue;
    }


    #map {width: 102%;height: 600px;border-radius: 20px; margin-top: -14px; margin-bottom: -10px;margin-left:-10px;z-index: 1;}

    .mapbox-improve-map{
      display: none;
    }

    .leaflet-control-attribution{
      display: none !important;
    }
    .leaflet-control-attribution{
      display: none !important;
    }
    .search-ui {

    }

    .circle-green {
      background-color: #829b35;
      border-radius: 50%
    }

    .custom-marker-icon {
      border:solid 2px;
     border-radius: 50%;
     background-color: rgba(95, 158, 160,0.3);

    }

    .dash{
      color: rgba(95, 158, 200,0.7);
    }

    .dash:hover {
      cursor: pointer;
      color: cadetblue;
      background-color: rgba(95, 158, 160,0.3);
      border-radius: 15px;
      padding: 2%;

    }

    .zoomable-image {
      transition: transform 0.3s ease;
    }

    .zoomable-image:hover {
      transform: scale(4.2);
    }
  </style>

</head>

<body>

  <!-- ======= Header ======= -->
  <?php include VIEWPATH . 'includes/nav_bar.php'; ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php include VIEWPATH . 'includes/menu_left.php'; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="row page-titles mx-0">
      <div class="col-sm-10 p-md-0">
        <div class="welcome-text">
          <table>
            <tr>
              <td> 
                <!-- <img src="<?= base_url()?>template/imagespopup/IconeMuyingajdfss-04.png" width="60px" height="60px" alt=""> -->
              </td>
              <td>  
                <h5 class="text-muted pt-2 ps-1 text-dark"> <i class="fa fa-dashboard"></i> Dashboard véhicule</h5>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <!-- <li class="breadcrumb-item"><a href="#">Centre de situation</a></li> -->
                    <!-- <li class="breadcrumb-item"><a href="#">Carte</a></li> -->
                    <!-- <li class="breadcrumb-item active" aria-current="page">Saving slides</li> -->
                  </ol>
                </nav>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div class="col-md-2">

        <!-- <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('vehicule/Vehicule')?>" class="nav-link position-relative"><i class="bi bi-list"></i> Liste</a> -->

      </div>
    </div>


    <section class="section">
     <div class="container text-center">
      <div class="row">
        <div class="text-left col-sm-12">
          <div class="card" style="border-radius: 20px;">

            <!-- <br> -->

            <div class="card-body">
              <div class="row">
                <div class="col-md-3">
                  <img src="<?= base_url()?>/upload/photo_vehicule/<?= $infos_vehicule['PHOTO']?>" style="width: 50px;height: 50px;border-radius: 10px; margin-top:10px;" class="zoomable-image"><strong> <?= $infos_vehicule['DESC_MARQUE'].' - '.$infos_vehicule['DESC_MODELE']?></strong>
                  
                </div> 
                <div class="col-md-3">
                  <font class="text-muted small" style="position:relative;top:15px;">Enregistré il ya 19 jour(s)</font>
                </div>
              </div>

              <div class="row">

                <div class="col-md-12">

                  <ul class="nav nav-tabs nav-tabs-bordered">

                    <li class="nav-item">
                      <button class="nav-link active " data-bs-toggle="tab" data-bs-target="#info_generales"><i class="fa fa-info-circle"></i> Informations générales</button>
                    </li>

                    <li class="nav-item">
                      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#assurance"><i class="fa fa-retweet"></i> Assurances</button>
                    </li>

                    <li class="nav-item">
                      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#controle_technique"><i class="fa fa-tripadvisor"></i> Contrôle technique</button>
                    </li>
                    

                  </ul>

                </div>


              </div>

            </div>
            
            <!-- </div> -->

          </div>
        </div>
      </div>
    </div>
    <!-- </div> -->
  </section>


  <section class="section">
   <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="card" style="border-radius: 20px;">

          <br>

          <div class="card-body">

           <!-- begin tab content -->
           <div class="tab-content pt-2"> 

            <div class="tab-pane fade show active" id="info_generales">

              <div class="row">

                <table class="table table-borderless">
                  <tr>

                    <td>
                      <i class="text-muted small pt-2 ps-1 fa fa-code"> Code</i><br>
                      <label class="text-muted small pt-2 ps-1 dash_v"><?= $infos_vehicule['CODE']?></label>
                    </td>

                    <td >
                       <i class="text-muted small pt-2 ps-1 fa fa-bookmark-o"> Marque</i><br>
                       <label class="text-muted small pt-2 ps-1 dash_v"><?= $infos_vehicule['DESC_MARQUE']?></label>
                    </td>

                    <td >
                      <i class="text-muted small pt-2 ps-1 fa fa-bolt"> Modèle</i><br>
                      <label class="text-muted small pt-2 dash_v"><?= $infos_vehicule['DESC_MODELE']?></label>
                    </td>

                    <td >
                      <i class="text-muted small pt-2 ps-1 fa fa-circle"> Couleur</i><br>
                      <label class="text-muted small pt-2 ps-1 dash_v"><?= $infos_vehicule['COULEUR']?></label>
                    </td>

                    <td >
                      <i class="text-muted small pt-2 ps-1 fa fa-beer"> Consommation</i><br>
                      <label class="text-muted small pt-2 ps-1 dash_v"><?= $infos_vehicule['KILOMETRAGE']?> Litres / km</label>
                    </td>

                  </tr>

                  <tr>
                    <td >
                      <font>
                        <img src="<?=base_url('/upload/proprietaire/photopassport/'.$infos_vehicule['photo_pro'])?>" style="width: 20px;height: 20px;border-radius: 50%;margin-top: -5px;" class="zoomable-image">
                      <i class="text-muted small pt-2 ps-1 fa fa-"> Propriétaire</i><br>
                      </font>
                      <a href="<?= base_url()?>proprietaire/Proprietaire/Detail/<?=md5($infos_vehicule['PROPRIETAIRE_ID'])?>" class="dash" title="Cliquer pour visualiser le détail">
                      <font class=" small pt-2 ps-1 dash_v"><?= $infos_vehicule['proprio_desc']?></font></a>
                    </td>

                    <td >
                      <font>
                      <img src="<?=base_url('/upload/chauffeur/'.$infos_vehicule['photo_chauf'])?>" style="width: 20px;height: 20px;border-radius: 50%;margin-top: -5px;" class="zoomable-image">
                      <i class="text-muted small pt-2 ps-1 fa fa-"> Chauffeur</i><br>
                      </font>
                      <a href="#" class="dash" title="Cliquer pour visualiser le détail">
                      <font class=" small pt-2 ps-1 dash_v"><?= $infos_vehicule['chauffeur_desc']?></font></a>
                    
                    </td>

                    <td >
                      
                      <?php 
                      if($infos_vehicule['IS_ACTIVE'] == 1)
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-cog"> Statut</i><br>
                        <label class="text-success small pt-2 ps-1 dash_v fa fa-check">Actif</label>
                      
                          <?php
                      }
                      else
                      {
                        ?>
                        <i class="text-small small pt-2 ps-1 fa fa-close"> Statut</i><br>
                        <label class="text-danger small pt-2 ps-1 dash_v fa fa-close">Désactif</label>
                      
                          <?php
                      }
                      ?> 
                    </td>

                    <td >
                      
                      <?php

                      if($infos_vehicule['DATE_FIN_ASSURANCE'] >= date('Y-m-d'))
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-retweet"> Assurance</i><br>
                        <label class="text-success small pt-2 ps-1 dash_v fa fa-check">Valide</label>
                      
                          <?php
                      }
                      else if($infos_vehicule['DATE_FIN_ASSURANCE'] < date('Y-m-d'))
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-retweet"> Assurance</i><br>
                        <label class="text-danger small pt-2 ps-1 dash_v fa fa-close">Expirée</label>
                      
                          <?php
                      }
                      ?> 
                    </td>

                    <td>

                     <?php 
                      if($infos_vehicule['DATE_FIN_CONTROTECHNIK'] >= date('Y-m-d'))
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-tripadvisor"> Contrôle technique</i><br>
                        <label class="text-success small pt-2 ps-1 dash_v fa fa-check">Valide</label>
                      
                          <?php
                      }
                      else if($infos_vehicule['DATE_FIN_CONTROTECHNIK'] < date('Y-m-d'))
                      {
                        ?>
                        <i class="text-small small pt-2 ps-1 fa fa-tripadvisor"> Contrôle technique</i><br>
                        <label class="text-danger small pt-2 ps-1 dash_v fa fa-close">Expirée</label>
                      
                          <?php
                      }
                      ?> 
                    </td>

                  </tr>
                </table>
                <center><a href="<?=base_url('centre_situation/Centre_situation/index/').$infos_vehicule['VEHICULE_ID']?>" id="tracking"> <i class="small pt-2 ps-1 fa fa-map-marker"> Emplacement du véhicule</i></a></center>
                <input type="hidden" value="<?=$infos_vehicule['VEHICULE_ID']?>" id="VEHICULE_TRACK">
                <input type="hidden" value="<?=$infos_vehicule['latitude'].','.$infos_vehicule['longitude']?>" id="COORD_TRACK">
              </div>

              <hr>

              <br>

              <div class="row">

                <div id="mapView">
                </div>

              </div>


            </div>

            <div class="tab-pane fade " id="assurance">
              <div class="row">
                <div class="col-md-12">
                  <table class="table table-borderless">
                  <tr>
                    <td>
                      <i class="text-muted small pt-2 ps-1 fa fa-picture-o"> Photo</i><br>

                      <img src="<?=base_url('/upload/photo_vehicule/'.$infos_vehicule['FILE_ASSURANCE'])?>" style="width: 50px;height: 50px;border-radius: 5px;margin-top: -5px;" class="zoomable-image">
                    </td>

                    <td >
                      
                      <?php

                      if($infos_vehicule['DATE_FIN_ASSURANCE'] > date('Y-m-d'))
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-history"> Etat</i><br>
                        <label class="text-success small pt-2 ps-1 dash_v fa fa-check">Valide</label>
                      
                          <?php
                      }
                      else if($infos_vehicule['DATE_FIN_ASSURANCE'] < date('Y-m-d'))
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-history"> Etat</i><br>
                        <label class="text-danger small pt-2 ps-1 dash_v fa fa-close">Expirée</label>
                      
                          <?php
                      }
                      ?> 
                    </td>

                    <td>
                      <i class="text-muted small pt-2 ps-1 fa fa-calendar-o"> Echéance</i><br>
                      <font class="text-muted small">Du&nbsp;&nbsp;&nbsp;</font> <label class="text-muted small pt-2 ps-1 dash_v"> <?= date('d-m-Y',strtotime($infos_vehicule['DATE_DEBUT_ASSURANCE']))?> </label><font class="text-muted small">&nbsp;&nbsp;&nbsp;au&nbsp;&nbsp;&nbsp;</font><label class="text-muted small pt-2 ps-1 dash_v">  <?= date('d-m-Y',strtotime($infos_vehicule['DATE_FIN_ASSURANCE']))?></label>
                    </td>
                  </tr>
                </table>
                </div>
              </div>
            </div>

             <div class="tab-pane fade " id="controle_technique">
              <div class="row">
                <div class="col-md-12">
                  <table class="table table-borderless">
                  <tr>

                     <td>
                      <i class="text-muted small pt-2 ps-1 fa fa-picture-o"> Photo</i><br>

                      <img src="<?=base_url('/upload/photo_vehicule/'.$infos_vehicule['FILE_CONTRO_TECHNIQUE'])?>" style="width: 50px;height: 50px;border-radius: 5px;margin-top: -5px;" class="zoomable-image">
                    </td>

                    <td >
                      
                      <?php

                      if($infos_vehicule['DATE_FIN_CONTROTECHNIK'] > date('Y-m-d'))
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-history"> Etat</i><br>
                        <label class="text-success small pt-2 ps-1 dash_v fa fa-check">Valide</label>
                      
                          <?php
                      }
                      else if($infos_vehicule['DATE_FIN_CONTROTECHNIK'] < date('Y-m-d'))
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-history"> Etat</i><br>
                        <label class="text-danger small pt-2 ps-1 dash_v fa fa-close">Expirée</label>
                      
                          <?php
                      }
                      ?> 
                    </td>

                    <td>
                      <i class="text-muted small pt-2 ps-1 fa fa-calendar-o"> Echéance</i><br>
                      <font class="text-muted small">Du&nbsp;&nbsp;&nbsp;</font> <label class="text-muted small pt-2 ps-1 dash_v"> <?= date('d-m-Y',strtotime($infos_vehicule['DATE_DEBUT_CONTROTECHNIK']))?> </label><font class="text-muted small">&nbsp;&nbsp;&nbsp;au&nbsp;&nbsp;&nbsp;</font><label class="text-muted small pt-2 ps-1 dash_v">  <?= date('d-m-Y',strtotime($infos_vehicule['DATE_FIN_CONTROTECHNIK']))?></label>
                    </td>
                  </tr>
                </table>
                </div>
              </div>
            </div>

          </div>
          <!-- end tab content -->

        </div>

        <!-- </div> -->

      </div>
    </div>
  </div>
</div>
<!-- </div> -->
</section>

</main><!-- End #main -->

<?php include VIEWPATH . 'includes/footer.php'; ?>

</body>

<script>
  $(document).ready(function(){

    getmap();

  });

</script>


<script>

    // Fonction pour afficher la carte

  function getmap(id=2){

    var VEHICULE_TRACK = $('#VEHICULE_TRACK').val();
    var COORD_TRACK = $('#COORD_TRACK').val();

    $.ajax({
      url : "<?=base_url()?>centre_situation/Centre_situation/getmap/",
      type : "POST",
      dataType: "JSON",
      cache:false,
      data: {
        id:id,
        VEHICULE_TRACK:VEHICULE_TRACK,
        COORD_TRACK:COORD_TRACK,
      },

      success:function(data) {

        $('#mapView').html(data.carte_view);

      },
    });
  }

</script>


</html>