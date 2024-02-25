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
  <?php include VIEWPATH . 'includes/preloader.php'; ?>
  <!--******************* Preloader end ********************-->

  <!--********************************** Main wrapper start ***********************************-->
  <div id="main-wrapper">
    <!--********************************** Header start ***********************************-->
    <?php include VIEWPATH . 'includes/menu_top.php'; ?>
    <!--********************************** Header end ***********************************-->
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
        </div>

        <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog modal-lg" style ="width:1000px">
            <div class="modal-content  modal-lg">
              <div class="modal-header">
                <!--   <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title"><span id="titre"></span></h4>
              </div>
              <div class="modal-body">
                <div class="table-responsive">
                  <table id='mytable' class='table table-bordered table-striped table-hover table-condensed' style="width:1000px"> 
                    <thead> 
                      <th class="text-center text-dark">#</th>

                      <th class="text-center text-dark">AGENT</th>
                      <th class="text-center text-dark">TELEPHONE</th>
                      <th class="text-center text-dark">NUMERO&nbspCARTE </th>

                      <th class="text-center text-dark">STATUT </th>
                      <!-- <th class="text-center text-dark">CODE_POINTAGE</th> -->
                      <!-- <th class="text-center text-dark">CODE_AGENT</th> -->
                 <!--      <th class="text-center text-dark">DATE_ENTREE</th>
                      <th class="text-center text-dark">DATE_SORTIE</th> -->
                      <th class="text-center text-dark">ENTREE </th>
                      <th class="text-center text-dark">SORTIE </th>
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

        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <!--  <h4 class="card-title">LISTE DES PRESENCES </h4> -->
              </div>
              <div class="card-body">
                <?=$this->session->flashdata('message');?>
                <div id="container"  class="col-md-12"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div id="nouveau"></div>
    </div>
    <!--********************************** Content body end ***********************************-->

    <!--********************************** Footer start ***********************************-->
    <?php include VIEWPATH . 'includes/footer.php'; ?>
    <!--********************************** Footer end ***********************************-->
  </div>
  <!--********************************** Main wrapper end ***********************************-->

  <!--********************************** Scripts start ***********************************-->
  <?php include VIEWPATH . 'includes/footer_scripts.php'; ?>
  <!--********************************** Scripts End ***********************************-->
</body>
</html>
<script type="text/javascript">
  $(document).ready(function(){
    get_rapport();
  });
</script>
<script> 
  function get_rapport()
  {
    $.ajax(
    {
      url : "<?=base_url()?>rapport/Rapport_Presences/get_rapport",
      type : "POST",
      dataType: "JSON",
      cache:false,
      data:{},
      success:function(data)
      {   
        $('#container').html("");
        $('#nouveau').html(data.rapp);
      },            
    });  
  }
</script>