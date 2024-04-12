<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>

  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/highcharts-more.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
  <script src="https://code.highcharts.com/modules/lollipop.js"></script>

</head>

<body>

  <!-- ======= Header ======= -->
  <?php include VIEWPATH . 'includes/nav_bar.php'; ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php include VIEWPATH . 'includes/menu_left.php'; ?>
  <!-- End Sidebar-->
  <?php header("refresh:180; url=$reflesh/dashboard/Dashboard_Anomalies");?>


  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashbord</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
          <li class="breadcrumb-item active">Dashbord Anomalies</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <!--***************************** Modal datail start ***********************************-->
    
    <!--***************************** Modal datail end ***********************************-->

    <section class="section dashboard">
      <div class="row align-items-top">
      <fieldset class="border p-2">
        <legend  class="float-none w-auto p-2">Dashboard des anomalies</legend>
        <div class="row">
          <div class="col-md-4 projet_par_mid">
            <!-- vehicule actifs vs inactif biggin -->
                     <div class="card">
                      <div class="card-body pb-0">
                        <h5 class="card-title"></h5>
                        <div id="container9" style="min-height: 280px;"></div>
                        <div id="nouveau"></div>
                      </div>
                    </div>
                    <!--  vehicule actifs vs inactif biggin -->
               </div>

                 <div class="col-md-4 projet_par_mid">
                  
                  <!--vehicule allumé vs en eteint begin -->
                  <div class="card">
                    <div class="card-body pb-0">
                      <h5 class="card-title"></h5>
                      <div id="container2" style="min-height: 280px;"></div>
                      <div id="nouveau2"></div>
                    </div>
                  </div>
                  <!--vehicule allumé vs en eteint begin -->
                </div>
                 <div class="col-md-4 projet_par_mid">
                  <!--vehicule en stationnemenr vs en mouvement begin -->
                  <div class="card">
                    <div class="card-body pb-0">
                      <h5 class="card-title"></h5>
                      <div id="container10" style="min-height: 280px;"></div>
                      <div id="nouveau10"></div>
                    </div>
                  </div>
                  <!--vehicule en stationnemenr vs en mouvement begin -->
                </div>
                 <div class="col-md-8 ">
                    <!-- vehicule par marque begin -->
                   <!--  <div class="card">
                      <div class="card-body pb-0">
                        <h5 class="card-title"></h5>
                        <div id="container2" style="min-height: 280px;"></div>
                        <div id="nouveau2"></div>
                      </div>
                    </div> -->
                    <!-- vehicule par marque end -->
           
                 </div>
                 <div class="col-md-4 ">
            <!-- proprietaire begin -->
        <!--     <div class="card">
              <div class="card-body pb-0">
                <h5 class="card-title"></h5>
                <div id="container7" style="min-height: 280px;"></div>
                <div id="nouveau7"></div>
              </div>
            </div> -->
            <!-- proprietaireend --> 
          </div>
             </div>
          


</div>
</section>

<div class="modal fade" id="myModal" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'>      
        <h5 class="modal-title">LISTE DES VEHICULES
        </a></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <div class="table-responsive">
         <table id="mytable" class="table table-hover" style="width:100%;">
          <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
            <tr>
              <th class="">#</th>
              <th class="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
              <th class="">MARQUE</th>
              <th class="">MODELE</th>
              <th class="">PLAQUE</th>
              <th class="">COULEUR</th>
              <th class="">LITRE&nbsp;/&nbsp;KM</th>
              <th class="">PROPRIETAIRE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
              <th class="">CHAUFFEUR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
              <th class="">Nombre</th>


            </tr>
          </thead>
          <tbody class="text-dark">
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>


  <!--                
          debut modal des axces -->
   <div class="modal fade" id="myModal_exces" tabindex="-1" >
   <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'>      
        <h5 class="modal-title">LISTE DES EXCES
        </a></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <div class="table-responsive">
         <table id="mytableEXCES" class="table table-hover" style="width:100%;">
          <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
            <tr>
              <th class="">#</th>
              <!-- <th class="text-dark">#</th> -->
              <th class="text-dark">CODE COURSE</th>
              <th class="text-dark">VITESSE</th>
              <th class="text-dark">DATE</th>            

            </tr>
          </thead>
          <tbody class="text-dark">
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
<!--  fin modal exces -->
<!-- debut modal_accident
 -->
   <div class="modal fade" id="myModal_acc" tabindex="-1" >
   <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'>      
        <h5 class="modal-title">LISTE DES EXCES
        </a></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <div class="table-responsive">
         <table id="mytable_acc" class="table table-hover" style="width:100%;">
          <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
            <tr>
              <th class="">#</th>
              <!-- <th class="text-dark">#</th> -->
              <th class="text-dark">CODE COURSE</th>
              <th class="text-dark">VITESSE</th>
              <th class="text-dark">DATE</th>            

            </tr>
          </thead>
          <tbody class="text-dark">
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
<!-- fin modal accident -->




</main><!-- End #main -->

<?php include VIEWPATH . 'includes/footer.php'; ?>

</body>

</html>

<script type="text/javascript">
  $(document).ready(function()
  {
    get_rapport();
  });
</script>

<script>
  function get_rapport()
  {

    $.ajax(
    {
      url : "<?=base_url()?>dashboard/Dashboard_Anomalies/get_rapport",
      type : "POST",
      dataType: "JSON",
      cache:false,
      data:{},
      success:function(data)
      {   
        $('#nouveau').html(data.rapp);
        $('#nouveau2').html(data.rapp2);
        $('#nouveau10').html(data.rapp10);
        // $('#nouveau4').html(data.rapp4);
        // $('#nouveau5').html(data.rapp5);
        // $('#nouveau6').html(data.rapp6);
        // $('#nouveau7').html(data.rapp7);


      },            
    });  
  }
</script>
<script type="text/javascript">
  
 function listing_exces(CODE)
  {
    $('#myModal_exces').modal('show');
    var row_count ="1000000";
    $("#mytableEXCES").DataTable({
      "destroy" : true,
      "processing":true,
      "serverSide":true,
      "destroy":true,
      "oreder":[[ 1, 'asc' ]],
      "ajax":{
        url: "<?= base_url() ?>dashboard/Dashboard_Anomalies/listing_exces/" + CODE, 
        type:"POST",
        data : {},
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
  function listing_acc(CODE)
  {
    $('#myModal_acc').modal('show');
    var row_count ="1000000";
    $("#mytable_acc").DataTable({
      "destroy" : true,
      "processing":true,
      "serverSide":true,
      "destroy":true,
      "oreder":[[ 1, 'asc' ]],
      "ajax":{
        url: "<?= base_url() ?>dashboard/Dashboard_Anomalies/listing_acc/" + CODE, 
        type:"POST",
        data : {},
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
