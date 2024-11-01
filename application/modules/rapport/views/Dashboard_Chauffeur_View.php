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
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
          <li class="breadcrumb-item active">Dashboard chauffeur</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

     <!--***************************** Modal datail start ***********************************-->
    
    <!--***************************** Modal datail end ***********************************-->

    <section class="section dashboard">

      <div class="row align-items-top">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class='row'>

               <div  class="col-md-12 col-sm-12 col-xs-12" id="container" ></div>

             </div>  
           </div>  
         </div>  
       </div>

       <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <div class='row'>
             <div  class="col-md-12 col-sm-12 col-xs-12" id="container2"  ></div>
           </div>  
         </div>  
       </div>  
     </div>

 <div class="row">
  <div id="nouveau2"></div>
  <div id="nouveau"></div>
 
</div>

</div>
     

    </section>
    
  <div class="modal fade" id="myModal" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class='modal-header' style='background:cadetblue;color:white;'>      
        <h5 class="modal-title">LISTE DES CHAUFFEURS
        </a></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <div class="table-responsive">
              <table id='mytable' class='table table-bordered table-striped table-hover' > 
                <thead> 
                  <th class="text-center text-dark">#</th>
                  <th class="text-center text-dark">Chauffeur&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                  <th class="text-center text-dark">Adresse&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                  <th class="text-center text-dark">Téléphone&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                  <th class="text-center text-dark">Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                  <th class="text-center text-dark">Identité&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                  <th class="text-center text-dark">Localité&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                </thead>
              </table>
            </div>
      
       </div>
    </div>
  </div>
</div>


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
                        url : "<?=base_url()?>rapport/Dashboard_Chauffeur/get_rapport",
                        type : "POST",
                        dataType: "JSON",
                        cache:false,
                        data:{},
                        success:function(data)
                        {   
                          $('#nouveau').html(data.rapp);
                          $('#nouveau2').html(data.rapp2);

                        },            
                      });  
                    }
                  </script>
