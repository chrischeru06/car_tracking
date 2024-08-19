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

    #map {
      top: -35px;
      bottom: 0;
      width: 100%;
      height: 800px;
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

    .mapboxgl-ctrl-logo {
      display: none !important;
    }

    .mapboxgl-ctrl-attrib-inner {
      display: none !important;
    }

    .mapboxgl-ctrl mapboxgl-ctrl-attrib {
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

    .profil-info {
      padding: .3rem;
    }

    .profil-info .profil-text .bi {
      margin-right: .5rem;
      margin-left: .2rem;
    }

    .profil-info .profil-text p.profil-name {
      font-weight: 900;
      font-size: 13px;
      margin: 0 0 .1rem 0;
      margin-left: .4rem;

      /* noms qui depassent l'espace prevu*/
      overflow-x: auto;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;

      /* curseur*/
      cursor: pointer;
    }

    .mena .profil-info .profil-text p.profil-name {
      font-weight: 900;
      font-size: 1rem;
      margin: 0 0 .1rem 0;
      margin-left: .4rem;

      /* noms qui depassent l'espace prevu*/
      overflow-x: auto;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;

      /* curseur*/
      cursor: pointer;
    }

    .profil-info .profil-text p.profil-phone {
      font-size: 10px;
      margin: 0 0 .1rem 0;

      /* noms qui depassent l'espace prevu*/
      overflow-x: auto;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;

      /* curseur*/
      cursor: pointer;
    }

    .profil-info .profil-img img {
      width: 5rem;
      height: 80px;
    }

    .mapboxgl-popup {
      max-width: 400px;
      font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
    }

    #mena {
      font-size: .6rem;
      font-family: 'Open Sans', sans-serif;
    }

    #meno {
      position: absolute;
      padding: 10px;
      font-family: 'Open Sans', sans-serif;
    }

    /* Responsive Adjustments */
    @media (max-width: 767px) {
      .profil-info .profil-text p.profil-name,
      .mena .profil-info .profil-text p.profil-name {
        font-size: 12px;
      }

      .profil-info .profil-text p.profil-phone {
        font-size: 8px;
      }

      .profil-info .profil-img img {
        width: 4rem;
        height: 60px;
      }

      .card-title {
        font-size: 0.8rem;
      }

      .card-icon img {
        width: 20px;
        height: auto;
      }

      .ps-3 h6 span {
        font-size: 0.4rem;
      }

      .card-body {
        padding: 10px;
      }

      .col-md-6 {
        width: 100%;
        margin-bottom: 15px;
      }

      #main {
        padding-left: 10px;
        padding-right: 10px;
      }
    }

    @media (min-width: 768px) and (max-width: 1023px) {
      .profil-info .profil-text p.profil-name,
      .mena .profil-info .profil-text p.profil-name {
        font-size: 14px;
      }

      .profil-info .profil-text p.profil-phone {
        font-size: 10px;
      }

      .profil-info .profil-img img {
        width: 4.5rem;
        height: 70px;
      }

      .card-title {
        font-size: 1rem;
      }

      .card-icon img {
        width: 25px;
        height: auto;
      }

      .ps-3 h6 span {
        font-size: 0.5rem;
      }

      .card-body {
        padding: 15px;
      }

      .col-md-6 {
        width: 50%;
      }

      #main {
        padding-left: 15px;
        padding-right: 15px;
      }
    }

    @media (min-width: 1024px) {
      .profil-info .profil-text p.profil-name,
      .mena .profil-info .profil-text p.profil-name {
        font-size: 1rem;
      }

      .profil-info .profil-text p.profil-phone {
        font-size: 12px;
      }

      .profil-info .profil-img img {
        width: 5rem;
        height: 80px;
      }

      .card-title {
        font-size: 1.2rem;
      }

      .card-icon img {
        width: 30px;
        height: auto;
      }

      .ps-3 h6 span {
        font-size: 0.6rem;
      }

      .card-body {
        padding: 20px;
      }

      .col-md-6 {
        width: 50%;
      }

      #main {
        padding-left: 20px;
        padding-right: 20px;
      }
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
  <div id="map"></div>
  <div id="animation-phase-container">
    <span id="animation-phase">Initial phase</span>
  </div>

  <div class="col-lg-4 col-md-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title" id="mena"><i class="bi bi-person-circle"></i> Chauffeur Informations</h5>
        <div class="profil-info d-flex align-items-center">
          <div class="profil-img">
            <img src="<?php echo base_url(); ?>assets/img/chauffeurs/<?php echo $chauffeurs[0]['photo'] ?>" alt="Chauffeur Photo">
          </div>
          <div class="profil-text">
            <p class="profil-name" id="profil-name"><i class="bi bi-person"></i> <?php echo $chauffeurs[0]['nom']; ?></p>
            <p class="profil-phone" id="profil-phone"><i class="bi bi-telephone"></i> <?php echo $chauffeurs[0]['telephone']; ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title" id="mena"><i class="bi bi-person-circle"></i> Chauffeur Informations</h5>
        <div class="profil-info d-flex align-items-center">
          <div class="profil-img">
            <img src="<?php echo base_url(); ?>assets/img/chauffeurs/<?php echo $chauffeurs[0]['photo'] ?>" alt="Chauffeur Photo">
          </div>
          <div class="profil-text">
            <p class="profil-name" id="profil-name"><i class="bi bi-person"></i> <?php echo $chauffeurs[0]['nom']; ?></p>
            <p class="profil-phone" id="profil-phone"><i class="bi bi-telephone"></i> <?php echo $chauffeurs[0]['telephone']; ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    mapboxgl.accessToken = 'YOUR_MAPBOX_ACCESS_TOKEN';
    const map = new mapboxgl.Map({
      container: 'map',
      style: 'mapbox://styles/mapbox/streets-v11',
      center: [YOUR_LONGITUDE, YOUR_LATITUDE],
      zoom: 12
    });

    const animationPhases = ['Initial phase', 'Phase 1', 'Phase 2', 'Final phase'];
    let phaseIndex = 0;

    function updateAnimationPhase() {
      document.getElementById('animation-phase').textContent = animationPhases[phaseIndex];
      phaseIndex = (phaseIndex + 1) % animationPhases.length;
    }

    setInterval(updateAnimationPhase, 3000);
  </script>
</body>

</html>
