<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>
  <script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
  <link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
  

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


  <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.4.10/leaflet.draw.css' rel='stylesheet' />
  <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-draw/v0.4.10/leaflet.draw.js'></script>
  <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-geodesy/v0.1.0/leaflet-geodesy.js'></script>

  <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/leaflet.markercluster.js'></script>
  <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.css' rel='stylesheet' />
  <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.Default.css' rel='stylesheet' />

  
  <script src="https://unpkg.com/georaster"></script>
  <script src="https://unpkg.com/proj4"></script>
  <script src="https://unpkg.com/georaster-layer-for-leaflet"></script>


  <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/leaflet.markercluster.js'></script>
  <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.css' rel='stylesheet' />
  <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.Default.css' rel='stylesheet' />

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

  <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-zoomslider/v0.7.0/L.Control.Zoomslider.js'></script>
  <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-zoomslider/v0.7.0/L.Control.Zoomslider.css' rel='stylesheet'/>

  <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
  <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />

  <style>
    #mapinfo {width: 103%;height: 700px;border-radius: 20px; margin-top: 10px;margin-left:-19px;z-index: 1;}
    

    .capitalize-first {
     color:  #899bbd;
     font-weight: bold;
     text-transform: lowercase;
     font-variant: small-caps;

   }
   .capitalize-first::first-letter {
    text-transform: uppercase;
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

    <div class="pagetitle">
      <h5 style="color:  #899bbd;">Affectation du véhicule <font class="text-primary"><?=$vehicule['DESC_MARQUE'].' - '.$vehicule['DESC_MODELE'].'</font> du plaque <font class="text-primary">'.$vehicule['PLAQUE'] ?></font> au chauffeur</h5>
      
    </div><!-- End Page Title -->
    <br>
    <section class="section">
      <!--  <div class="container text-center"> -->
        <div class="row">
          <!-- <div class="text-left col-sm-12"> -->
            <div class="card" style="border-radius: 20px;">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div  id="mapinfo"></div>
                  </div>
                </div>
              </div>


            </div>
            <!-- </div> -->
          </div>
          <!--   </div> -->
        </section>



        <!--******** Debut Modal pour attribue une voiture *********-->

        <div class="modal fade" id="affectation_modal" tabindex="-1" >
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
              <div class='modal-header' style='background:cadetblue;color:white;'>      
                <h5 class="modal-title" style="color: rgba(255, 255, 255, 0.8);">Affectation du véhicule <font class="text-warning"><?=$vehicule['DESC_MARQUE'].' - '.$vehicule['DESC_MODELE'].'</font> du plaque <font class="text-warning">'.$vehicule['PLAQUE'] ?></font> au chauffeur </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="attribution_form" enctype="multipart/form-data" action="#" method="post">
                  <div class="modal-body mb-1">
                    <div class="row">

                      <input type="hidden" name="CODE" id="CODE" value="<?=$vehicule['CODE']?>">

                      <div class="col-md-6">
                        <label for="description" class="text-dark">Chauffeur</label>
                        <select class="form-control" id="CHAUFFEUR_ID" name="CHAUFFEUR_ID">
                          <option value="">--Sélectionner--</option>
                          <?php
                          foreach ($chauffeur as $key) 
                          {
                            echo "<option value=".$key['CHAUFFEUR_ID'].">".$key['chauffeur_desc']."</option>";
                          }
                          ?>
                        </select>
                        <span id="errorCHAUFFEUR_ID" class="text-danger"></span>
                      </div>

                      <div class="col-md-6" id="coord">
                        <label>Coordonnées</label>
                        <input class="form-control" required readonly type="text" name="COORD" id="COORD" placeholder="Coordonnées gps" >
                        <font color="red" id="error_nom"></font>
                        <?php echo form_error('COORD', '<div class="text-danger">', '</div>'); ?>
                        <span class="help-block" style="color: red"></span> 
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-6">
                        <label type="date" class="text-dark">Date début</label>
                        <input type="date" name="DATE_DEBUT_AFFECTATION" autocomplete="off" id="DATE_DEBUT_AFFECTATION" value="<?= set_value('DATE_DEBUT_AFFECTATION') ?>" onchange="get_date_fin(this.value)" class="form-control"  min="<?= date('Y-m-d')?>">
                        <span id="errorDATE_DEBUT_AFFECTATION" class="text-danger"></span>
                      </div>
                      <div class="col-md-6">
                        <label type="date" class="text-dark">Date fin</label>
                        <input type="date" name="DATE_FIN_AFFECTATION" autocomplete="off" id="DATE_FIN_AFFECTATION" value="<?= set_value('DATE_FIN_AFFECTATION') ?>"  onchange="get_dates_deb(this.value)" class="form-control"  min="<?= date('Y-m-d')?>">
                        <span id="errorDATE_FIN_AFFECTATION" class="text-danger"></span>
                      </div>
                    </div>
                  </div> 
                  <div class="modal-footer">
                    <input type="button"class="btn btn-outline-primary rounded-pill" type="button" id="btn_add" value="Affecter" onclick="save_affect();" />

                  </div>
                </form>
              </div>
            </div>
          </div>
        </div><!-- End Modal-->

      </main><!-- End #main -->

      <?php include VIEWPATH . 'includes/footer.php'; ?>

    </body>


    <script>
   // initalize leaflet map

      // add OpenStreetMap basemap

      L.mapbox.accessToken = 'pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc1MmozajAwcGczZW1sMjMwZWxtZDQifQ.u8xhrt1Wn4A82X38f5_Iyw';

      var centre ='<?= $centre ?>';

      var centre = centre.split(',');

      var zoom = '<?= $zoom?>';



      var map = L.mapbox.map('mapinfo')
      .setView([centre[0],centre[1]], zoom);

      var layers = {
        Streets: L.mapbox.styleLayer('mapbox://styles/mapbox/streets-v11'),
        Satellite: L.mapbox.styleLayer('mapbox://styles/mapbox/satellite-streets-v11'),
      };

      layers.Streets.addTo(map);
      L.control.layers(null,layers,{position: 'topleft'}).addTo(map);

      var clusterGroup = new L.MarkerClusterGroup();


      var featureGroup = L.featureGroup().addTo(map);

      var drawControl = new L.Control.Draw({
        edit: {
          featureGroup: featureGroup
        },
        draw: {
          polygon: true,
          polyline: false,
          rectangle: false,
          circle: false,
          marker: false
        }
      }).addTo(map);


      map.on('draw:created', showPolygonArea);
      map.on('draw:created', mymodal);
      map.on('draw:edited', showPolygonAreaEdited);
      L.control.fullscreen().addTo(map);

      

      function showPolygonAreaEdited(e) {
        e.layers.eachLayer(function(layer) {

          showPolygonArea({ layer: layer });

        });
      }
      function showPolygonArea(e) {
        featureGroup.clearLayers();
        featureGroup.addLayer(e.layer);
        e.layer.bindPopup((LGeo.area(e.layer)/100).toFixed(2) + 'are');
        e.layer.openPopup();
        // / 1000000
        var layer = e.layer;

        var _ajax_cords = [];
        var _coords = layer.getLatLngs()[0];
        $.each(_coords,function(ind,val){
            //var _te_co = {x : val.lat, y : val.lng};
         _ajax_cords.push(val.lat,val.lng);
       });


        form = new FormData();
        form.append('ajax_cords',_coords);


        $('#SUP').val((LGeo.area(e.layer)/10000).toFixed(2));

        $('#COORD').val(_coords);


      }


      function mymodal()
      {

        $('#affectation_modal').modal('show');
      }


    </script>


    </html>


    <script>
      //Appel à l'enregistrement de l'affectation
      function save_affect()
      {
        var statut = 1;

        if($('#CHAUFFEUR_ID').val()=='')
        {
          $('#errorCHAUFFEUR_ID').html('Le champ est obligatoire');
          statut=2;
        }
        if($('#DATE_DEBUT_AFFECTATION').val()=='')
        {
          $('#errorDATE_DEBUT_AFFECTATION').html('Le champ est obligatoire');
          statut=2;
        } if($('#DATE_FIN_AFFECTATION').val()=='')
        {
          $('#errorDATE_FIN_AFFECTATION').html('Le champ est obligatoire');
          statut=2;
        }

        if(statut < 2)
        {
          var form_data = new FormData($("#attribution_form")[0]);
          var url="<?= base_url('vehicule/Vehicule/save_affect_vehicule')?>";
          $.ajax(
          {
            url: url,
            type: 'POST',
            dataType:'JSON',
            data: form_data ,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data)
            {
              if(data==1)
              {
                Swal.fire(
                {
                  icon: 'success',
                  title: 'Success',
                  text: 'Affectation faite avec succès',
                  timer: 1500,
                }).then(() =>
                {
                  window.location="<?=base_url('vehicule/Vehicule')?>";
                });
              }
              else
              {
                Swal.fire(
                {
                  icon: 'error',
                  title: 'Echec',
                  text: 'Affectation échouée',
                  timer: 1500,
                }).then(() =>
                {
                  window.location="<?=base_url('vehicule/Vehicule')?>";
                });
              }
            }
          });
        }
      }

    </script>

    <script>


      //verification des dates pour la modification de l'affectation

      function get_date_fin()
      {
        $("#DATE_FIN_AFFECTATION").prop('min',$("#DATE_DEBUT_AFFECTATION").val());

      }

      function get_dates_deb()
      {
        $("#DATE_DEBUT_AFFECTATION").prop('min',$("#DATE_FIN_AFFECTATION").val());

      }

      function get_date_fin_modif()
      {
        $("#DATE_FIN_AFFECTATION_MOD").prop('min',$("#DATE_DEBUT_AFFECTATION_MOD").val());

      }

      function get_dates_deb_modif()
      {
        $("#DATE_DEBUT_AFFECTATION_MOD").prop('min',$("#DATE_FIN_AFFECTATION_MOD").val());

      }
    </script>