<script src='https://api.mapbox.com/mapbox.js/v3.2.0/mapbox.js'></script>
<link href='https://api.mapbox.com/mapbox.js/v3.2.0/mapbox.css' rel='stylesheet' />
 <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/leaflet.markercluster.js'></script>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.css' rel='stylesheet' />
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.Default.css' rel='stylesheet' />
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.Default.css' rel='stylesheet' />

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> 

<link rel="stylesheet" href="https://opengeo.tech/maps/leaflet-search/src/leaflet-search.css" />
 
<script src="https://opengeo.tech/maps/leaflet-search/src/leaflet-search.js"></script> 

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


<style>
.legend label,
.legend span {
  display:block;
  float:left;
  height:15px;
  width:20%;
  text-align:center;
  font-size:9px;
  color:#808080;
  }


  .modal .modal-content {
            padding: 0px 0px 0px 0px;
            -webkit-animation-name: modal-animation;
            -webkit-animation-duration: 0.5s;
            animation-name: modal-animation;
            animation-duration: 0.5s;
        }

  @-webkit-keyframes modal-animation {
            from {
                top: -100px;
                opacity: 0;
            }
            to {
                top: 0px;
                opacity: 1;
            }
        }
          
        @keyframes modal-animation {
            from {
                top: -100px;
                opacity: 0;
            }
            to {
                top: 0px;
                opacity: 1;
            }
        }






</style>

<style type="text/css">
  .mapbox-improve-map{
display: none;
}
  
.leaflet-control-attribution{
display: none !important;
}
.leaflet-control-attribution{
    display: none !important;
}
.mapbox-logo{
  display: none !important;
}
</style>
  
<style>
 
.search-tip b {
  color: #fff;
}
.bar.search-tip b,
.bar.leaflet-marker-icon {
  background: #f66
}
 
.HOMME.search-tip b,
.HOMME.leaflet-marker-icon {
  background: #EE2109
}
.search-tip {
  white-space: nowrap;
}
.search-tip b {
  display: inline-block;
  clear: left;
  float: right;
  /*padding: 0 4px;
  margin-left: 4px;*/
  
}
  
.modal-backdrop{
  display: none;
}

.search-tooltip{
  width: 300px;
}

.FEMME.search-tip b,
.FEMME.leaflet-marker-icon {
  background: #66f
}
</style>


<?php
use_helper("I18N");

if($sf_user->mfHasCredential("managefees"))
{
?>

    
     <div class="modal fade" id="myModal">

<!-- modal-lg -->
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="" style="background-color: #455a64;padding: 20px;">
        <center><font style="color: white;text-align: center;"><font class="fa fa-briefcase"></font> &nbsp;&nbsp; Détails du requérant</font></center>
      </div>
      <div class="modal-body">

        <center><div id="attentedata"></div></center>
        <div id="div_info"></div>
       
       
      </div>
      <div class="" style="padding: 20px;text-align: center;">
         <hr>
        
          <a class="btn btn-primary ml-lg-2 btn-rounded" data-dismiss="modal" href="#">Fermer
             <i style="" class="fa fa-times-circle-o" aria-hidden="true"></i></a>
      </div>

    </div>
  </div>
</div>


<div class="contentpanel">
    <div class="panel panel-default">

        <div class="panel-heading">
            <h3 class="panel-title"><?php echo __('<i class="fa fa-globe"></i> Cartographie des process -- Système d\'Informations de Permis de Construire (BPS)'); ?></h3>
        </div>


       

        <div class="panel-body">
        <div class="table-responsive">

        <div id='legend' style='display:none;'>
    
        <small>Source: <a href="#link to source">OBUHA <?php echo $this->zoom; ?></a></small>
        </div>

        <div id='map' style='width:100%;height:500px;'></div>


        </div>
      </div>
    </div>
</div>




<script>



    var zoom = '<?= $zoom; ?>';
    var markers = new L.MarkerClusterGroup();

    L.mapbox.accessToken = 'pk.eyJ1IjoibWFydGlubWJ4IiwiYSI6ImNrMDc1MmozajAwcGczZW1sMjMwZWxtZDQifQ.u8xhrt1Wn4A82X38f5_Iyw';
    var map = L.mapbox.map('map', null, {
                  zoomControl: false
                }).setView([-3.3733275,29.3497415], zoom);

    L.control.zoom({
     // position:''
    }).addTo(map);

    var layers = {
      Streets : L.mapbox.styleLayer('mapbox://styles/mapbox/streets-v11'),
      Satellite: L.mapbox.styleLayer('mapbox://styles/mapbox/satellite-streets-v11')
    };

    layers.Streets.addTo(map);
    L.control.layers(layers).addTo(map);  

    
  
    map.legendControl.addLegend(document.getElementById('legend').innerHTML);




    // new dev



    geojsonOpts = {

    pointToLayer: function(feature, latlng) {


   if(feature.properties.type_data == '1'){

   var logoMarkerStyle = L.Icon.extend({
                                            options: {
                                            iconSize: [28, 28]
                                        }
                                    });
  
    var logoMarker = new logoMarkerStyle({iconUrl: 'http://chart.apis.google.com/chart?chst=d_simple_text_icon_below&chld=|5|0E2547|wc-male|24|1709EE|0E2547'});

  return L.marker(latlng,{icon: logoMarker,myCustomId: ''+feature.properties.idben+''}).on('click', onClick);


    }
    if(feature.properties.type_data == 'FEMME'){

  var logoMarkerStyle = L.Icon.extend({
                                      options: {
                                      iconSize: [28, 28]
                                        }
                                    });
  
  var logoMarker = new logoMarkerStyle({iconUrl: 'http://chart.apis.google.com/chart?chst=d_simple_text_icon_below&chld=|5|4A5AF7|wc-female|24|4A5AF7|4A5AF7'});

  return L.marker(latlng,{icon: logoMarker,myCustomId: ''+feature.properties.idben+''}).on('click', onClick);
    }
  
  }
  };





    var Individu = {
    "type": "FeatureCollection",
    "generator": "overpass-turbo",
    "copyright": "The data included in this document is from www.openstreetmap.org. The data is made available under ODbL.",
    "timestamp": "2015-08-08T19:02:02Z",
    "features": [

    <?php foreach($map as $zone1): ?>
 
      {
        "type": "Feature",
        "id": "node/1",
        "properties": {
          "idben": "<?= $zone1->getId() ?>",
          "amenity": "HOMME",
          "type_data": "1",
          "name": "<?= $zone1->getName() ?>"
        },
        "geometry": {
          "type": "Point",
          "coordinates": [
            <?= $zone1->getLon() ?>,
           <?= $zone1->getLat() ?>
          ]
         }
        },
    
    <?php endforeach; ?> 
 
    ]
  };


  var poiLayers = L.layerGroup([
  
  L.geoJson(Individu, geojsonOpts),
       
  ]);

  markers.addLayer(poiLayers);
  map.addLayer(markers);

  L.control.search({
      layer: poiLayers,
      initial: false,
      propertyName: 'name',
      zoom:18,
      buildTip: function(text, val) {
        var type = val.layer.feature.properties.amenity;
        return '<a href="#" class="'+type+'">'+text+'<b>'+type+'</b></a>';
      }
    })
  .addTo(map);




  function onClick(e) {

     
     $('#myModal').modal();

     let id = this.options.myCustomId;

      $.ajax({
            url: "<?php echo url_for('/backend.php/map/getdetails/info/') ?>"+id,
            type: "GET",
            dataType: "json",
          }).done(function(resp){


            $('#div_info').html(resp.htmls);
            // alert(JSON.stringify(resp.htmls));

            // console.log(JSON.stringify(resp));
          }).fail(function( xhr, status, errorThrown ) {
            //alert( "Sorry, there was a problem!" );
            console.log( "Error: " + errorThrown );
            console.log( "Status: " + status );
            console.dir( xhr );
          });

  }



</script>
 

<?php
}
else
{
  include_partial("settings/accessdenied");
}
?>
