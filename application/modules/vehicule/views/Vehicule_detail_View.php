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
    /*table td{
      text-align: center;
    }*/
    #tracking{
      font-size: 25px;
      color: gray;
    }
    #tracking:hover{
      color: cadetblue;
    }


    #map {width: 102%;height: 400px;border-radius: 20px; margin-top: -14px; margin-bottom: -10px;margin-left:-10px;z-index: 1;}

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
      border:solid 2px green;
      border-radius: 50%;
      background-color: rgba(95, 158, 160,0.3);
    }

    .custom-marker-icon2 {
      border:solid 2px red;
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
      transform: scale(3.2);
    }

    #eye{
      color: black;
    }
    #eye:hover {
      color: blue;
    }

    #image-container{
      position: relative;
      left:10px;
      width: 770px; 
      height: 700px;
      overflow: hidden;
    }

    #phot_v {
      position: relative;
      cursor: grab;
      transition: transform 0.2s;
      border-radius: 10px;

      width: 105%;
      height: 100%;
      margin-left: -12px;
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
                <h5 class="text-muted pt-2 ps-1 text-dark"> <i class="fa fa-dashboard"></i> <?=lang('title_dashboard_veh')?></h5>
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
                <div class="col-md-4">
                  <img src="<?= base_url()?>/upload/photo_vehicule/<?= $infos_vehicule['PHOTO']?>" style="width: 50px;height: 50px;border-radius: 10px; margin-top:10px;position:relative;margin-left:-50px;cursor: pointer;" class="" onclick="show_image2();" title="Cliquer pour visualiser l'image"><strong> <?= $infos_vehicule['DESC_MARQUE'].' - '.$infos_vehicule['DESC_MODELE']?></strong>&nbsp;&nbsp;&nbsp;<font class="text-muted small"><?=$infos_vehicule['PLAQUE']?></font>

                  <input type="hidden" id="phot_v2" value="<?= base_url()?>/upload/photo_vehicule/<?= $infos_vehicule['PHOTO']?>">
                  
                </div> 
                <div class="col-md-3">
                  <font class="text-muted small" style="position:relative;top:25%;">Enregistré <?=lang('franc_date_il_ya')?> <b><?=$nbr_jours?></b> </font>
                </div>
              </div>

              <div class="row">

                <div class="col-md-12 <?php if($this->session->userdata('PROFIL_ID') == 1){echo "table-responsive";}?>">

                  <table class="table table-borderless" style="width:123%">
                    <tr>
                      <td>
                       <ul class="nav nav-tabs nav-tabs-bordered">

                        <li class="nav-item">
                          <button class="nav-link active " data-bs-toggle="tab" data-bs-target="#info_generales"><i class="fa fa-info-circle"></i> <?=lang('btn_info_gnl')?></button>
                        </li>

                        <li class="nav-item">
                          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#etat_vehicule"><i class="fa fa-cog"></i> Etat véhicule</button>
                        </li>

                        <li class="nav-item">
                          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#assurance"><i class="fa fa-retweet"></i> <?=lang('dashboard_hist_assurance')?></button>
                        </li>

                        <li class="nav-item">
                          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#controle_technique"><i class="fa fa-tripadvisor"></i> <?=lang('dashboard_hist_ctrl_technique')?></button>
                        </li>

                        <?php
                        if($this->session->userdata('PROFIL_ID') == 1)
                        {
                          ?>
                          <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#active_desactive"><i class="fa fa-cog"></i> <?=lang('title_modal_activation')?> <?=lang('mot_et')?> <?=lang('modal_desactivation')?></button>
                          </li>
                          <?php
                        }

                        ?>


                      </ul>
                    </td>
                  </tr>
                </table>



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

            <div class="row table-responsive">

              <table class="table table-borderless">
                <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                <tr>

                  <?php
                  if($this->session->userdata('PROFIL_ID') == 1)
                  {
                    ?>
                    <td>
                      <i class="text-muted small pt-2 ps-1 fa fa-code"></i><font class="text-muted small pt-2 ps-1"><?=lang('mot_code')?></font><br>
                      <?php
                  if(!empty($infos_vehicule['CODE']))
                  {
                    ?>
                      <label class="text-muted small pt-2 ps-1 dash_v"><?= $infos_vehicule['CODE']?></label>
                      <?php
                    }else{
                      ?>
                      <font class="text-center text-muted small pt-2 ps-1 dash_v">&nbsp;&nbsp;<?=lang('lste_n_a')?></font>
                      <?php
                    }?>
                    </td>

                    <?php
                  }

                  ?>

                  <td >
                   <i class="text-muted small pt-2 ps-1 fa fa-bookmark-o"></i><font class="text-muted small pt-2 ps-1"><?=lang('label_marque')?></font><br>
                   <label class="text-muted small pt-2 ps-1 dash_v"><?= $infos_vehicule['DESC_MARQUE']?></label>
                 </td>

                 <td >
                  <i class="text-muted small pt-2 ps-1 fa fa-bolt"></i><font class="text-muted small pt-2 ps-1"><?=lang('label_modele')?></font><br>
                  <label class="text-muted small pt-2 dash_v"><?= $infos_vehicule['DESC_MODELE']?></label>
                </td>

                <td >
                  <i class="text-muted small pt-2 ps-1 fa fa-circle"></i><font class="text-muted small pt-2 ps-1"><?=lang('label_couleur')?></font><br>
                  <label class="text-muted small pt-2 ps-1 dash_v"><?= $infos_vehicule['COULEUR']?></label>
                </td>

                <td >
                  <i class="text-muted small pt-2 ps-1 fa fa-beer"> </i><font class="text-muted small pt-2 ps-1"><?=lang('mot_consommation')?></font><br>
                  <label class="text-muted small pt-2 ps-1 dash_v"><?= $infos_vehicule['KILOMETRAGE']?> <?=lang('l_kg')?></label>
                </td>

              </tr>

              <tr>
                <td >
                  <font>
                    <?php
                    if($infos_vehicule['TYPE_PROPRIETAIRE_ID'] == 1)
                    {
                      ?>
                      <a href="<?= base_url()?>proprietaire/Proprietaire/Detail/<?=md5($infos_vehicule['PROPRIETAIRE_ID'])?>"  title="<?=lang('title_visualisation')?>">
                      <img src="<?=base_url('/upload/proprietaire/photopassport/'.$infos_vehicule['LOGO'])?>" style="width: 40px;height: 40px;border-radius: 50%;margin-top: -5px;" class="zoomable-image">
                    </a>
                      <?php
                    }
                    else
                    {
                      ?>
                      <a href="<?= base_url()?>proprietaire/Proprietaire/Detail/<?=md5($infos_vehicule['PROPRIETAIRE_ID'])?>"  title="<?=lang('title_visualisation')?>">
                      <img src="<?=base_url('/upload/proprietaire/photopassport/'.$infos_vehicule['photo_pro'])?>" style="width: 40px;height: 40px;border-radius: 50%;margin-top: -5px;" class="zoomable-image">
                    </a>
                      <?php
                    }
                    ?>
                    
                    <i class="text-muted small pt-2 ps-1 fa fa-"></i><font class="text-muted small pt-2 ps-1"><?=lang('title_proprio_list')?></font><br>
                  </font>
                  <a href="<?= base_url()?>proprietaire/Proprietaire/Detail/<?=md5($infos_vehicule['PROPRIETAIRE_ID'])?>" class="dash" title="<?=lang('title_visualisation')?>">
                    <font class=" small pt-2 ps-1 dash_v"><?= $infos_vehicule['proprio_desc']?></font></a>
                  </td>

                  <td >
                    <?php if(!empty($infos_vehicule['CHAUFFEUR_ID'] && $infos_vehicule['STATUT_AFFECT'] == 1))
                    {
                      ?>
                      <font>
                        <a href="<?= base_url()?>chauffeur/Chauffeur_New/Detail/<?=md5($infos_vehicule['CHAUFFEUR_ID'])?>" class="" title="<?=lang('title_visualisation')?>">
                        <img src="<?= isset($infos_vehicule['photo_chauf'])?base_url('/upload/chauffeur/'.$infos_vehicule['photo_chauf']):base_url('/upload/iconecartracking-02.png')?>" style="width: 40px;height: 40px;border-radius: 50%;margin-top: -5px;" class="zoomable-image">
                      </a>
                        <i class="text-muted small pt-2 ps-1 fa fa-"></i><font class="text-muted small pt-2 ps-1"><?=lang('p_chauffeur')?></font><br>
                      </font>
                      
                      
                      <a href="<?= base_url()?>chauffeur/Chauffeur_New/Detail/<?=md5($infos_vehicule['CHAUFFEUR_ID'])?>" class="dash" title="<?=lang('title_visualisation')?>">
                        <font class=" small pt-2 ps-1 dash_v"><?= $infos_vehicule['chauffeur_desc']?></font></a>
                        <?php
                      }
                      else
                      {
                        ?>
                        <font>
                          <img src="<?= base_url('/upload/iconecartracking-02.png')?>" style="width: 40px;height: 40px;border-radius: 50%;margin-top: -5px;" class="zoomable-image">
                          <i class="text-muted small pt-2 ps-1 fa fa-"></i><font class="text-muted small pt-2 ps-1"><?=lang('p_chauffeur')?></font><br>
                        </font>
                        <font class="text-center text-muted small pt-2 ps-1 dash_v">&nbsp;&nbsp;<?=lang('lste_n_a')?></font>
                        <?php
                      }
                      ?>

                    </td>

                    <td >

                      <?php 
                      if($infos_vehicule['STATUT_VEH_AJOUT'] == 1)
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-cog"> </i><font class="text-muted small pt-2 ps-1"><?=lang('i_stat')?></font><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font class="text-warning small pt-2 ps-1 dash_v fa fa-spinner fa-spin" title="<?=lang('title_demande_attente')?>"></font>
                        

                        <?php
                      }
                      else if($infos_vehicule['STATUT_VEH_AJOUT'] == 2)
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-cog"> </i><font class="text-muted small pt-2 ps-1"><?=lang('i_stat')?></font><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font class="text-success small pt-2 ps-1 dash_v fa fa-check" title="<?=lang('title_veh_active')?>"></font>

                        <?php
                      }
                      else if($infos_vehicule['STATUT_VEH_AJOUT'] == 3)
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-cog"> </i><font class="text-muted small pt-2 ps-1"><?=lang('i_stat')?></font><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="text-danger small pt-2 ps-1 dash_v fa fa-ban" title="<?=lang('title_demande_refus')?>"></label>

                        <?php
                      }
                      else if($infos_vehicule['STATUT_VEH_AJOUT'] == 4)
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-cog"> </i><font class="text-muted small pt-2 ps-1"><?=lang('i_stat')?></font><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="text-danger small pt-2 ps-1 dash_v fa fa-close" title="<?=lang('title_veh_desactive')?>"></label>

                        <?php
                      }
                      ?> 
                    </td>

                    <td >

                      <?php

                      if(!empty($infos_vehicule['DATE_FIN_ASSURANCE']))
                      {
                        if($infos_vehicule['DATE_FIN_ASSURANCE'] >= date('Y-m-d'))
                        {
                          ?>
                          <i class="text-muted small pt-2 ps-1 fa fa-retweet"> </i><font class="text-muted small pt-2 ps-1"><?=lang('i_assurance')?></font><br>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="text-success small pt-2 ps-1 dash_v fa fa-check" title="<?=lang('title_valide')?>"></label>

                          <?php
                        }
                        else if($infos_vehicule['DATE_FIN_ASSURANCE'] < date('Y-m-d'))
                        {
                          ?>
                          <i class="text-muted small pt-2 ps-1 fa fa-retweet"> </i><font class="text-muted small pt-2 ps-1"><?=lang('i_assurance')?></font><br>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="text-danger small pt-2 ps-1 dash_v fa fa-close" title="<?=lang('title_expire')?>"></label>

                          <?php
                        }
                      }
                      else
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-retweet"> </i><font class="text-muted small pt-2 ps-1"><?=lang('i_assurance')?></font><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font class="text-center text-muted small pt-2 ps-1 dash_v"><?=lang('lste_n_a')?></font>

                        <?php
                      }
                      ?> 
                    </td>

                    <td>

                     <?php 
                     if(!empty($infos_vehicule['DATE_FIN_CONTROTECHNIK']))
                     {
                      if($infos_vehicule['DATE_FIN_CONTROTECHNIK'] >= date('Y-m-d'))
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-tripadvisor"> </i><font class="text-muted small pt-2 ps-1"><?=lang('td_ctrl_technique')?></font><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="text-success small pt-2 ps-1 dash_v fa fa-check" title="<?=lang('title_valide')?>"></label>

                        <?php
                      }
                      else if($infos_vehicule['DATE_FIN_CONTROTECHNIK'] < date('Y-m-d'))
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-tripadvisor"> </i><font class="text-muted small pt-2 ps-1"><?=lang('td_ctrl_technique')?></font><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="text-danger small pt-2 ps-1 dash_v fa fa-close" title="<?=lang('title_expire')?>"></label>

                        <?php
                      }
                    }
                    else
                    {
                      ?>
                      <i class="text-muted small pt-2 ps-1 fa fa-tripadvisor"> </i><font class="text-muted small pt-2 ps-1"><?=lang('td_ctrl_technique')?></font><br>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font class="text-center text-muted small pt-2 ps-1 dash_v"><?=lang('lste_n_a')?></font>

                      <?php
                    }
                    ?> 
                  </td>

                </tr>
              </tbody>
              </table>
              <center><a href="<?=base_url('centre_situation/Centre_situation/index/').$infos_vehicule['VEHICULE_ID']?>" id="tracking"> <i class="small pt-2 ps-1 fa fa-map-marker"> </i><font class=" small pt-2 ps-1"><?=lang('veh_emplacement')?></font></a></center>
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

         <div class="tab-pane fade " id="etat_vehicule">

              <div class="row">

                <div class="table-responsive">

                  <table id="table_etat_vehicule" class="table table-hover text-dark" style="width:100%">
                    <thead class="text-dark" style="background-color: rgba(0, 0, 0, 0.075);">
                      <tr>
                        <th class="text-dark">#</th>
                        <th class="text-dark">IMAGE&nbsp;AVANT</th>
                        <th class="text-dark">IMAGE&nbsp;ARRIERE</th>
                        <th class="text-dark">IMAGE&nbsp;LATERALE&nbsp;GAUCHE</th>
                        <th class="text-dark">IMAGE&nbsp;LATERALE&nbsp;DROITE</th>
                        <th class="text-dark">IMAGE&nbsp;TABLEAU&nbsp;DE&nbsp;BORD</th>
                        <th class="text-dark">IMAGE&nbsp;SIEGE&nbsp;AVANT</th>
                        <th class="text-dark">IMAGE&nbsp;SIEGE&nbsp;ARRIERE</th>
                        <th class="text-dark"><?=lang('list_profil_enreg')?></th>
                        <th class="text-dark"><?=lang('list_dte_enregistrement')?></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                    </tbody>
                  </table>
                  
                </div>
                
              </div>

            </div>

         <div class="tab-pane fade " id="assurance">

              <!-- <div class="row">
                <div class="col-md-12">
                  <table class="table table-borderless">
                  <tr>
                    <td>
                      <i class="text-muted small pt-2 ps-1 fa fa-"> </i><font class="text-muted small pt-2 ps-1"></font>

                      <img src="<?=base_url('/upload/photo_vehicule/'.$infos_vehicule['FILE_ASSURANCE'])?>" style="width: 50px;height: 50px;border-radius: 5px;margin-top: -5px;" class="zoomable-image">
                    </td>

                    <td >
                      
                      <?php

                      if($infos_vehicule['DATE_FIN_ASSURANCE'] > date('Y-m-d'))
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-history"> </i><font class="text-muted small pt-2 ps-1">Etat</font><br>
                        <label class="text-success small pt-2 ps-1 dash_v fa fa-check"></label><font class="text-success small pt-2 ps-1">Valide</font>
                      
                          <?php
                      }
                      else if($infos_vehicule['DATE_FIN_ASSURANCE'] < date('Y-m-d'))
                      {
                        ?>
                        <i class="text-muted small pt-2 ps-1 fa fa-history"> </i><font class="text-muted small pt-2 ps-1">Etat</font><br>
                        <label class="text-danger small pt-2 ps-1 dash_v fa fa-close"></label><font class="text-danger small pt-2 ps-1">Expirée</font>
                      
                          <?php
                      }
                      ?> 
                    </td>

                    <td>
                      <i class="text-muted small pt-2 ps-1 fa fa-calendar-o"> </i><font class="text-muted small pt-2 ps-1">Echéance</font><br>
                      <font class="text-muted small">Du&nbsp;&nbsp;&nbsp;</font> <label class="text-muted small pt-2 ps-1 dash_v"> <?= date('d-m-Y',strtotime($infos_vehicule['DATE_DEBUT_ASSURANCE']))?> </label><font class="text-muted small">&nbsp;&nbsp;&nbsp;au&nbsp;&nbsp;&nbsp;</font><label class="text-muted small pt-2 ps-1 dash_v">  <?= date('d-m-Y',strtotime($infos_vehicule['DATE_FIN_ASSURANCE']))?></label>
                    </td>
                  </tr>
                </table>
                </div>
              </div> -->


              <div class="row">

                <div class="table-responsive">

                  <table id="table_assurance" class="table table-hover text-dark" style="width:100%">
                    <thead class="text-dark" style="background-color: rgba(0, 0, 0, 0.075);">
                      <tr>
                        <th class="text-dark">#</th>
                        <th class="text-dark"><?=lang('list_doc')?></th>
                        <th class="text-dark"><?=lang('list_dte_dbut')?></th>
                        <th class="text-dark"><?=lang('list_dte_fin')?></th>
                        <th class="text-dark"><?=lang('list_assureur')?></th>
                        <th class="text-dark"><?=lang('list_profil_enreg')?></th>
                        <th class="text-dark"><?=lang('list_dte_enregistrement')?></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                    </tbody>
                  </table>
                  
                </div>
                
              </div>

            </div>

            <div class="tab-pane fade " id="controle_technique">

             <div class="row">

              <div class="table-responsive">

                <table id="table_controle" class="table table-hover text-dark" style="width:100%">
                  <thead class="text-dark" style="background-color: rgba(0, 0, 0, 0.075);">
                    <tr>
                      <th class="text-dark">#</th>
                      <th class="text-dark"><?=lang('list_doc')?></th>
                      <th class="text-dark"><?=lang('list_dte_dbut')?></th>
                      <th class="text-dark"><?=lang('list_dte_fin')?></th>
                      <th class="text-dark"><?=lang('list_profil_enreg')?></th>
                      <th class="text-dark"><?=lang('list_dte_enregistrement')?></th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                  </tbody>
                </table>

              </div>

            </div>

          </div>


          <div class="tab-pane fade " id="active_desactive">

           <div class="row">

            <div class="table-responsive">

              <table id="table_active_desactive" class="table table-hover text-dark" style="width:100%">
                <thead class="text-dark" style="background-color: rgba(0, 0, 0, 0.075);">
                  <tr>
                    <th class="text-dark">#</th>
                    <th class="text-dark"><?=lang('th_statut')?></th>
                    <th class="text-dark"><?=lang('list_fait_par')?></th>
                    <th class="text-dark"><?=lang('list_motif')?></th>
                    <th class="text-dark">&nbsp;<?=lang('list_date')?>&nbsp;</th>
                    <!-- <th></th> -->
                  </tr>
                </thead>
                <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                </tbody>
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



<!-- Modal photo du vehicule-->

<div class="modal fade" id="Modal_photo_vehicule">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style='background:cadetblue;color:white;'>
        <h6 class="modal-title"></h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
      </div>
      <div class="modal-body">

        <div class="row text-center" style="background-color:rgba(230,230,200,0.3);margin-top:-10px;border-radius:50%;">

          <div class="col-md-4">

          </div>

          <div class="col-md-1">
            <i onclick="zoomIn()" class="fa fa-plus-circle text-muted"></i>

            <input type="hidden" id="rotation" value="0">
          </div>

          <div class="col-md-1">
            <i onclick="zoomOut()" class="fa fa-minus-circle text-muted"></i>
          </div>

                <!-- <div class="col-md-1">
                  <i onclick="moveX(-1)" class="fa fa-arrow-circle-left text-muted"></i>
                </div>

                <div class="col-md-1">
                  <i onclick="moveX(1)" class="fa fa-arrow-circle-right text-muted"></i>
                </div>

                <div class="col-md-1">
                  <i onclick="moveY(-1)" class="fa fa-arrow-circle-up text-muted"></i>
                </div>

                <div class="col-md-1">
                  <i onclick="moveY(1)" class="fa fa-arrow-circle-down text-muted"></i>
                </div> -->

                <div class="col-md-1">
                  <i onclick="rotate_op()" class="fa fa-rotate-right text-muted"></i>
                </div>


              </div>

              <div class="row">

                <div class="col-md-12" id="image-container">
                  <img src="" id="phot_v" alt="Description de l'image">
                </div>

              </div>

            </div>
            <!-- footer here -->
          </div>
        </div>
      </div>

    </main><!-- End #main -->

    <?php include VIEWPATH . 'includes/footer.php'; ?>

  </body>

  <script>
    $(document).ready(function(){

      getmap();
      liste_etat_vehicule();
      liste_assurance();
      liste_controle();
      liste_active_desactive();

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

   <script >
  //Fonction pour afficher l'historique d'etat du vehicule
    function liste_etat_vehicule()
    {

      var VEHICULE_ID = $('#VEHICULE_TRACK').val();

      var row_count ="1000000";
      $("#table_etat_vehicule").DataTable({
        "destroy" : true,
        "processing":true,
        "serverSide":true,
        "destroy":true,
        "oreder":[[ 1, 'asc' ]],
        "ajax":{
          url: "<?php echo base_url('/vehicule/Vehicule/liste_etat_vehicule');?>", 
          type:"POST",
          data : {VEHICULE_ID:VEHICULE_ID},
          beforeSend : function() {
          }
        },
        lengthMenu: [[10,50, 100, -1], [10,50, 100, "All"]],
        pageLength: 10,
        "columnDefs":[{
          "targets":[],
          "orderable":false
        }],
        dom: 'Bfrtlip',
        buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'  ],
        language: {
          "sProcessing":     "Traitement en cours...",
          "sSearch":         "Recherche&nbsp;:",
          "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
          "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
          "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
          "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
          "sInfoPostFix":    "",
          "sLoadingRecords": "Chargement en cours...",
          "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
          "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
          "oPaginate": {
            "sFirst":      "Premier",
            "sPrevious":   "Pr&eacute;c&eacute;dent",
            "sNext":       "Suivant",
            "sLast":       "Dernier"
          },
          "oAria": {
            "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
            "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
          }
        }
      });

    }
  </script>

  <script >
  //Fonction pour afficher l'historique d'assurance
    function liste_assurance()
    {

      var VEHICULE_ID = $('#VEHICULE_TRACK').val();

      var row_count ="1000000";
      $("#table_assurance").DataTable({
        "destroy" : true,
        "processing":true,
        "serverSide":true,
        "destroy":true,
        "oreder":[[ 1, 'asc' ]],
        "ajax":{
          url: "<?php echo base_url('/vehicule/Vehicule/liste_assurance');?>", 
          type:"POST",
          data : {VEHICULE_ID:VEHICULE_ID},
          beforeSend : function() {
          }
        },
        lengthMenu: [[10,50, 100, -1], [10,50, 100, "All"]],
        pageLength: 10,
        "columnDefs":[{
          "targets":[],
          "orderable":false
        }],
        dom: 'Bfrtlip',
        buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'  ],
        language: {
          "sProcessing":     "Traitement en cours...",
          "sSearch":         "Recherche&nbsp;:",
          "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
          "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
          "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
          "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
          "sInfoPostFix":    "",
          "sLoadingRecords": "Chargement en cours...",
          "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
          "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
          "oPaginate": {
            "sFirst":      "Premier",
            "sPrevious":   "Pr&eacute;c&eacute;dent",
            "sNext":       "Suivant",
            "sLast":       "Dernier"
          },
          "oAria": {
            "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
            "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
          }
        }
      });

    }
  </script>


  <script >
  //Fonction pour afficher l'historique du controle technique
    function liste_controle()
    {

      var VEHICULE_ID = $('#VEHICULE_TRACK').val();

      var row_count ="1000000";
      $("#table_controle").DataTable({
        "destroy" : true,
        "processing":true,
        "serverSide":true,
        "destroy":true,
        "oreder":[[ 1, 'asc' ]],
        "ajax":{
          url: "<?php echo base_url('/vehicule/Vehicule/liste_controle');?>", 
          type:"POST",
          data : {VEHICULE_ID:VEHICULE_ID},
          beforeSend : function() {
          }
        },
        lengthMenu: [[10,50, 100, -1], [10,50, 100, "All"]],
        pageLength: 10,
        "columnDefs":[{
          "targets":[],
          "orderable":false
        }],
        dom: 'Bfrtlip',
        buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'  ],
        language: {
          "sProcessing":     "Traitement en cours...",
          "sSearch":         "Recherche&nbsp;:",
          "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
          "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
          "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
          "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
          "sInfoPostFix":    "",
          "sLoadingRecords": "Chargement en cours...",
          "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
          "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
          "oPaginate": {
            "sFirst":      "Premier",
            "sPrevious":   "Pr&eacute;c&eacute;dent",
            "sNext":       "Suivant",
            "sLast":       "Dernier"
          },
          "oAria": {
            "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
            "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
          }
        }
      });

    }
  </script>


  <script >
  //Fonction pour afficher l'historique activation et desactivation du vehicule
    function liste_active_desactive()
    {

      var VEHICULE_ID = $('#VEHICULE_TRACK').val();

      var row_count ="1000000";
      $("#table_active_desactive").DataTable({
        "destroy" : true,
        "processing":true,
        "serverSide":true,
        "destroy":true,
        "oreder":[[ 1, 'asc' ]],
        "ajax":{
          url: "<?php echo base_url('/vehicule/Vehicule/liste_active_desactive');?>", 
          type:"POST",
          data : {VEHICULE_ID:VEHICULE_ID},
          beforeSend : function() {
          }
        },
        lengthMenu: [[10,50, 100, -1], [10,50, 100, "All"]],
        pageLength: 10,
        "columnDefs":[{
          "targets":[],
          "orderable":false
        }],
        dom: 'Bfrtlip',
        buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'  ],
        language: {
          "sProcessing":     "Traitement en cours...",
          "sSearch":         "Recherche&nbsp;:",
          "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
          "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
          "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
          "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
          "sInfoPostFix":    "",
          "sLoadingRecords": "Chargement en cours...",
          "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
          "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
          "oPaginate": {
            "sFirst":      "Premier",
            "sPrevious":   "Pr&eacute;c&eacute;dent",
            "sNext":       "Suivant",
            "sLast":       "Dernier"
          },
          "oAria": {
            "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
            "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
          }
        }
      });

    }
  </script>


  <script>
    function show_image(VEHICULE_ID)
    {
      var imgElement = document.getElementById("phot_v");

      $.ajax({
        url: "<?= base_url() ?>centre_situation/Centre_situation/get_image_v/" + VEHICULE_ID,
        type: "POST",
        dataType: "JSON",
        success: function(data) {

          $('#Modal_photo_vehicule').modal('show');

                  //$('#phot_v').html(data.photo);

          imgElement.src = data.photo;
        },
      });

              //phot_v
    }
  </script>


  <script>
    function show_image2()
    {
      var phot_v2 = $('#phot_v2').val();
      var imgElement = document.getElementById("phot_v");
      imgElement.src = phot_v2;
      $('#Modal_photo_vehicule').modal('show');
    }
  </script>


  <script>
             //Operations photo avec les boutons

        var scale = 1; // Facteur de zoom initial
        var translateX = 0; // Décalage horizontal initial
        var translateY = 0; // Décalage vertical initial

        var photo = document.getElementById('phot_v');

        // Fonction pour zoomer la photo
        function zoomIn() {
          scale += 0.1;
          updateTransform();

        }

        // Fonction pour dézoomer la photo
        function zoomOut() {
          scale -= 0.1;
          updateTransform();
        }

        // Fonction pour déplacer la photo horizontalement
        function moveX(direction) {
          translateX += direction * 50; // Changer la valeur de décalage
          updateTransform();
        }

        // Fonction pour déplacer la photo verticalement
        function moveY(direction) {
          translateY += direction * 50; // Changer la valeur de décalage
          updateTransform();
        }

        // Fonction pour mettre à jour la transformation CSS de la photo
        function updateTransform() {
          photo.style.transform = `scale(${scale}) translate(${translateX}px, ${translateY}px)`;
        }

      //Rotation de l'image

        function rotate_op()
        {
          const image = document.getElementById('phot_v');
      // const rotateBtn = document.getElementById('rotate-btn');
          let rotation = Number($('#rotation').val());

      //rotateBtn.addEventListener('click', () => {
          rotation += 90;
          image.style.transform = `rotate(${rotation}deg)`;
          $('#rotation').val(rotation)
      //});
        }


      </script>


      <script>
             //Operations photo avec la sourie

        let container = document.getElementById('image-container');
        let image = document.getElementById('phot_v');
        let lastX, lastY;
        let isDragging = false;
        let rotationAngle = 0;

    // Zoomer/dézoomer sur double clic
        document.getElementById('phot_v').addEventListener('dblclick', function() {
          if (this.style.transform === "scale(2)") {
            this.style.transform = "scale(1)";
          } else {
            this.style.transform = "scale(2)";
          }
        });
    // Déplacer en maintenant le clic gauche
        image.addEventListener('mousedown', function(event) {
          if (event.button === 0) {
            isDragging = true;
            lastX = event.clientX;
            lastY = event.clientY;
            image.style.cursor = 'grabbing';
          }
        });

        document.addEventListener('mousemove', function(event) {
          if (isDragging) {
            let deltaX = event.clientX - lastX;
            let deltaY = event.clientY - lastY;
            let newX = image.offsetLeft + deltaX;
            let newY = image.offsetTop + deltaY;
            image.style.left = newX + 'px';
            image.style.top = newY + 'px';
            lastX = event.clientX;
            lastY = event.clientY;
          }
        });

        document.addEventListener('mouseup', function(event) {
          if (event.button === 0) {
            isDragging = false;
            image.style.cursor = 'grab';
          }
        });

    // Pivoter avec la molette de la souris
        document.addEventListener('wheel', function(event) {
          if (event.deltaY < 0) {
            rotationAngle += 10;
          } else {
            rotationAngle -= 10;
          }
          image.style.transform = `rotate(${rotationAngle}deg)`;
        });


             // Fonction pour mettre à jour la transformation CSS de la photo
        function updateTransform() {
          photo.style.transform = `scale(${scale}) translate(${translateX}px, ${translateY}px)`;
        }
      </script>


      </html>