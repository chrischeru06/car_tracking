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
  <!--******************* Preloader start ********************-->
  <div id="preloader">
    <div class="sk-three-bounce">
      <div class="sk-child sk-bounce1"></div>
      <div class="sk-child sk-bounce2"></div>
      <div class="sk-child sk-bounce3"></div>
    </div>
  </div>
  <!--*******************Preloader end ********************-->

  <!--********************************** Main wrapper start ***********************************-->
  <div id="main-wrapper">
    <!--********************************** Header start ***********************************-->
    <?php include VIEWPATH . 'includes/menu_top.php'; ?>

    <!--********************************** Sidebar start ***********************************-->
    <?php include VIEWPATH . 'includes/menu_left.php'; ?>
    <!--********************************** Sidebar end ***********************************-->

    <!--********************************** Content body start ***********************************-->
    <div class="content-body">
      <div class="container-fluid">
        <div class="row page-titles mx-0">
          <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
              Société Burundaise de la Gardinnage 
            </div>
          </div>
          <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="javascript:void(0)">Liste</a></li>
              <li class="breadcrumb-item active"><a href="javascript:void(0)">Agents</a></li>
            </ol>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">

               <div id="container"  class="col-md-12"></div>    
               
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
   <div class="row">
    <div id="nouveau"></div>
    



    <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog modal-lg" style ="width:1000px">
        <div class="modal-content  modal-lg">
          <div class="modal-header">
            <h4 class="modal-title"><span id="titre"></span></h4>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table id='mytable' class='table table-bordered table-striped table-hover table-condensed' style="width:1000px">
                <thead>
                  <tr>
                    <th class="text-dark">#</th>
                    <th class="text-dark">CLIENT</th>
                      <th class="text-dark">SITE</th>
                    <th class="text-dark">ADDRESSE</th>
                    <th class="text-dark">TELEPHONE&nbspCLIENT</th> 
                    <th class="text-dark">PERSONNE&nbspDE&nbspREFERENCE</th>
                    <th class="text-dark">TELEPHONE&nbspPERSONNE&nbspDE&nbspREFERENCE</th>   

                  </tr>
                </thead>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default text-dark" data-dismiss="modal">Fermer</button>
          </div>
        </div>
      </div>
    </div>  

  </div>
  <!--********************************** Content body end ***********************************-->


  <!--********************************** Footer start ***********************************-->
  <?php include VIEWPATH . 'includes/footer.php'; ?>
  <!--********************************** Footer end ***********************************-->
</div>
<!--********************************** Main wrapper end ***********************************-->

<!--********************************** Scripts ***********************************-->
<?php include VIEWPATH . 'includes/footer_scripts.php'; ?>


<script type="text/javascript">

  $(document).ready(function(){

    get_rapport();
  });
</script>



<script> 
  function get_rapport(){

    $.ajax({
      url : "<?=base_url()?>rapport/Rapport_sites_client/get_rapport",
      type : "POST",
      dataType: "JSON",
      cache:false,
      data:{},
      success:function(data){   
        $('#container').html("");
        
        $('#nouveau').html(data.rapp);
        
      },            

    });  
  }
</script>
</body>
</html>