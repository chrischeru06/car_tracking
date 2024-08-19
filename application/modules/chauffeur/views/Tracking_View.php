<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>

  <style>
    body {
      margin: 0;
      padding: 0;
    }

    #map {
      top: -35px;
      bottom: 0;
      width: 100%;
      height: 100vh;
      z-index: 1;
    }

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

    .mapboxgl-ctrl-logo,
    .mapboxgl-ctrl-attrib-inner,
    .mapboxgl-ctrl.mapboxgl-ctrl-attrib {
      display: none !important;
    }

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
      height: 100%;
      position: absolute;
      overflow-y: scroll;
      border-radius: 2px;
    }

    .profil-info {
      padding: .3rem;
      display: flex;
      align-items: center;
    }

    .profil-info .profil-text .bi {
      margin-right: .5rem;
      margin-left: .2rem;
    }

    .profil-info .profil-text p.profil-name,
    .profil-info .profil-text p.profil-phone {
      font-size: 13px;
      margin: 0 0 .1rem 0;
      margin-left: .4rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      cursor: pointer;
    }

    .profil-info .profil-img img {
      width: 4rem;
      height: auto;
      border-radius: 10%;
      background-color: white;
    }

    .text-success small.pt-1.fw-bold {
      font-size: 1rem;
    }

    .mapboxgl-popup {
      max-width: 400px;
      font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
    }

    #mena {
      font-size: .8rem;
      font-family: 'Open Sans', sans-serif;
    }

    #meno {
      padding: 10px;
      font-family: 'Open Sans', sans-serif;
    }

    @media (max-width: 768px) {
      .profil-info .profil-img img {
        width: 3rem;
      }

      .profil-info .profil-text p.profil-name {
        font-size: 1rem;
      }

      .profil-info .profil-text p.profil-phone {
        font-size: 0.9rem;
      }

      .text-success small.pt-1.fw-bold {
        font-size: 0.8rem;
      }

      .card-title {
        font-size: 0.8rem;
      }

      .card-icon img {
        width: 20px;
      }

      .col-md-6 {
        width: 100%;
      }

      #map {
        height: 60vh;
      }
    }

    @media (max-width: 576px) {
      .profil-info .profil-img img {
        width: 2.5rem;
      }

      .profil-info .profil-text p.profil-name {
        font-size: 0.9rem;
      }

      .profil-info .profil-text p.profil-phone {
        font-size: 0.8rem;
      }

      .text-success small.pt-1.fw-bold {
        font-size: 0.7rem;
      }

      .card-title {
        font-size: 0.7rem;
      }

      .card-icon img {
        width: 15px;
      }

      #map {
        height: 50vh;
      }
    }
  </style>

  <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.css" rel="stylesheet">
  <script src="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.js"></script>
  <script src="https://unpkg.com/@turf/turf/turf.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
  <script src='https://cdn.jsdelivr.net/npm/mapbox-gl-fontawesome-markers@0.0.1/dist/index.js'></script>
</head>

<body>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
  <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css">

  <!-- Header -->
  <?php include VIEWPATH . 'includes/nav_bar.php'; ?>
  <!-- End Header -->

  <!-- Sidebar -->
  <?php include VIEWPATH . 'includes/menu_left.php'; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">
    <div class="pagetitle">
      <div class="row">
        <div class="col-md-6">
          <h1><?= lang('resum_du_parcours') ?></h1>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><?= lang('p_chauffeur') ?></a></li>
            </ol>
          </nav>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
        </div>
        <div class="col-md-3">
          <div class="d-flex justify-content-sm-end">
            <h1><?= lang('estimation_parcours') ?></h1>
          </div>
        </div>
      </div>
    </div><!-- End Page Title -->

    <div class="row">
      <div class="form-group col-md-3">
        <label class="form-label"><?= lang('input_date_deb') ?></label>
        <input class="form-control" type="date" min="<?= $date_affectation['DATE_DEBUT_AFFECTATION'] ?>" max="<?= $date_affectation['DATE_FIN_AFFECTATION'] ?>" name="DATE_DAT" id="DATE_DAT" value="<?= $date_affectation['DATE_DEBUT_AFFECTATION'] ?>" onchange="change_carte(); viderh();">
      </div>
      <div class="form-group col-md-3">
        <label class="form-label"><?= lang('input_date_fin') ?></label>
        <input class="form-control" type="date" min="<?= $date_affectation['DATE_DEBUT_AFFECTATION'] ?>" max="<?= $date_affectation['DATE_FIN_AFFECTATION'] ?>" name="DATE_DAT_FIN" id="DATE_DAT_FIN" value="<?= $date_affectation['DATE_FIN_AFFECTATION'] ?>" onchange="change_carte();">
      </div>
      <div class="form-group col-md-3">
        <label class="form-label"><?= lang('hrs_dbut') ?></label>
        <select class="form-control" name="HEURE1" id="HEURE1">
          <option value=""><?= lang('selectionner') ?></option>
          <?php foreach ($heure_trajet as $heure) : ?>
            <option value="<?= $heure['HEURE'] ?>" <?= $heure['HEURE'] == $heure_debut ? 'selected' : '' ?>><?= $heure['HEURE'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label class="form-label"><?= lang('hrs_fin') ?></label>
        <select class="form-control" name="HEURE2" id="HEURE2">
          <option value=""><?= lang('selectionner') ?></option>
          <?php foreach ($heure_trajet as $heure) : ?>
            <option value="<?= $heure['HEURE'] ?>" <?= $heure['HEURE'] == $heure_fin ? 'selected' : '' ?>><?= $heure['HEURE'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div id="map"></div>
    <div id="animation-phase-container">
      <div id="mena"><?= lang('estimation_parcours') ?>:</div>
      <div id="animation-phase"><?= lang('parcours_en_cours') ?></div>
    </div>
  </main><!-- End #main -->
</body>

</html>
