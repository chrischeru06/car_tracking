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

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Rapport</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Rapport</a></li>
          <li class="breadcrumb-item active">Rapport véhicules par propriétaire</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

     <!--***************************** Modal datail start ***********************************-->
    
    <!--***************************** Modal datail end ***********************************-->

    <section class="section dashboard">

      <!-- <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog modal-lg" style ="width:1000px">
        <div class="modal-content  modal-lg">
          <div class="modal-header">
            <h4 class="modal-title"><span id="titre"></span></h4>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table id='mytable' class='table table-bordered table-striped table-hover table-condensed' style="width:1000px"> 
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
          <div class="modal-footer">
            <button type="button" class="btn btn-default text-dark" data-dismiss="modal">Fermer</button>
          </div>
        </div>
      </div>
    </div> -->


  <div class="modal fade" id="myModal" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class='modal-header' style='background:cadetblue;color:white;'>      
        <h5 class="modal-title">LISTE DES PROPRIETAIRES
        </a></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <div class="table-responsive">
              <table id='mytable' class='table table-bordered table-striped table-hover' > 
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
<div class="row">
  <!-- Left side columns -->
  <div class="col-lg-12">
    <div class="row">
      <!-- Reports -->
      <div class="col-12">
        <div class="card">
          <div class="card-body">
           <div id="container"  class="col-md-12"></div> 
           <div class="row">
            <div id="nouveau"></div>
          </div>

        </div>

      </div>
    </div><!-- End Reports -->
  </div>
</div><!-- End Left side columns -->
</div>















    </section>


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
                        url : "<?=base_url()?>rapport/Rapport_Proprietaire/get_rapport",
                        type : "POST",
                        dataType: "JSON",
                        cache:false,
                        data:{},
                        success:function(data)
                        {   
                          $('#nouveau').html(data.rapp);
                        },            
                      });  
                    }
                  </script>
