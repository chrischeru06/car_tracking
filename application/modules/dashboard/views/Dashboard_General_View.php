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
  <?php header("refresh:180; url=$reflesh/dashboard/Dashboard_General");?>


  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashbord</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
          <li class="breadcrumb-item active">Dashbord général</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <!--***************************** Modal datail start ***********************************-->
    
    <!--***************************** Modal datail end ***********************************-->

    <section class="section dashboard">
      <div class="row align-items-top">
      <fieldset class="border p-2">
        <legend  class="float-none w-auto p-2">Dashboard des véhicules</legend>
        <div class="row">
          <div class="col-md-4 projet_par_mid">
            <!-- vehicule actifs vs inactif biggin -->
                     <div class="card">
                      <div class="card-body pb-0">
                        <h5 class="card-title"></h5>
                        <div id="container" style="min-height: 280px;"></div>
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
                      <div id="container4" style="min-height: 280px;"></div>
                      <div id="nouveau4"></div>
                    </div>
                  </div>
                  <!--vehicule allumé vs en eteint begin -->
                </div>
                 <div class="col-md-4 projet_par_mid">
                  <!--vehicule en stationnemenr vs en mouvement begin -->
                  <div class="card">
                    <div class="card-body pb-0">
                      <h5 class="card-title"></h5>
                      <div id="container3" style="min-height: 280px;"></div>
                      <div id="nouveau3"></div>
                    </div>
                  </div>
                  <!--vehicule en stationnemenr vs en mouvement begin -->
                </div>
                 <div class="col-md-8 ">
                    <!-- vehicule par marque begin -->
                    <div class="card">
                      <div class="card-body pb-0">
                        <h5 class="card-title"></h5>
                        <div id="container2" style="min-height: 280px;"></div>
                        <div id="nouveau2"></div>
                      </div>
                    </div>
                    <!-- vehicule par marque end -->
           
                 </div>
                 <div class="col-md-4 ">
            <!-- proprietaire begin -->
            <div class="card">
              <div class="card-body pb-0">
                <h5 class="card-title"></h5>
                <div id="container7" style="min-height: 280px;"></div>
                <div id="nouveau7"></div>
              </div>
            </div>
            <!-- proprietaireend --> 
          </div>
             </div>
          </fieldset>
          <fieldset class="border p-2">
        <legend  class="float-none w-auto p-2">Dashboard des chauffeurs</legend>
         <div class="row">
          <div class="col-md-4 ">
            <!-- chauffeur par statut begin -->
            <div class="card">
              <div class="card-body pb-0">
                <h5 class="card-title"></h5>
                <div id="container5" style="min-height: 280px;"></div>
                <div id="nouveau5"></div>
              </div>
            </div>
            <!-- chauffeur par statut end --> 
          </div>
            <div class="col-md-8 ">
            <!-- chauffeur par statut begin -->
            <div class="card">
              <div class="card-body pb-0">
                <h5 class="card-title"></h5>
                <div id="container6" style="min-height: 280px;"></div>
                <div id="nouveau6"></div>
              </div>
            </div>
            <!-- chauffeur par statut end --> 
          </div>
           
        </div>

      </fieldset>


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

<!--debut modal dashboard chauffeurs-->
<div class="modal fade" id="myModal5" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'>      
        <h5 class="modal-title5">LISTE DES VEHICULES
        </a></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <div class="table-responsive">
         <table id="mytable5" class="table table-hover" style="width:100%;">
          <thead> 
                  <th class="text-center text-dark">#</th>
                  <th class="text-center text-dark">Chauffeur&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                  <th class="text-center text-dark">Adresse&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                  <th class="text-center text-dark">Téléphone&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                  <th class="text-center text-dark">Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                  <th class="text-center text-dark">Identité&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                  <th class="text-center text-dark">Localité&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                </thead>
        
          <tbody class="text-dark">
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>

<!--fin modal dashboard chauffeurs-->
<!--devt modal dashboard vehicules par proprietaires-->

  <div class="modal fade" id="myModal7" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class='modal-header' style='background:cadetblue;color:white;'>      
        <h5 class="modal-title7">LISTE DES VEHICULES
        </a></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <div class="table-responsive">
              <table id='mytable7' class='table table-bordered table-striped table-hover' > 
                <thead> 
                  <th class="text-center text-dark">#</th>
                  <th class="text-center text-dark">Modele</th>
                  <th class="text-center text-dark">Marque</th>
                  <th class="text-center text-dark">Couleur</th>
                  <th class="text-center text-dark">Plaque</th>
                </thead>
              </table>
            </div>
      
       </div>
    </div>
  </div>
</div>
<!--fin modal dashboard vehicules par proprietaires-->



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
      url : "<?=base_url()?>dashboard/Dashboard_General/get_rapport",
      type : "POST",
      dataType: "JSON",
      cache:false,
      data:{},
      success:function(data)
      {   
        $('#nouveau').html(data.rapp);
        $('#nouveau2').html(data.rapp2);
        $('#nouveau3').html(data.rapp3);
        $('#nouveau4').html(data.rapp4);
        $('#nouveau5').html(data.rapp5);
        $('#nouveau6').html(data.rapp6);
        $('#nouveau7').html(data.rapp7);





      },            
    });  
  }
</script>
