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

  <!--   @neda  lien pour ajouter les btn pour exporter(excel;print,pdf)(noire) -->
 <!--  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.bootstrap4.min.css"> -->
 <!--    fin@neda -->
</head>
<body>
  <!--******************* Preloader start ********************-->
  <?php include VIEWPATH . 'includes/preloader.php'; ?>
  <!--******************* Preloader end ********************-->

  <!--********************************** Main wrapper start ***********************************-       ->
  <div id="main-wrapper"-->
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
          <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="javascript:void(0)">Liste</a></li>
              <li class="breadcrumb-item active"><a href="javascript:void(0)">agents</a></li>
            </ol>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div id="container"  class="col-md-12"></div> 
                <div class="row">
                  <div id="nouveau"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--***************************** Content body end ***********************************-->

    <!--***************************** Modal datail start ***********************************-->
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
                  <th class="text-center text-dark">#</th>
                  <th class="text-center text-dark">AGENT</th>
                  <th class="text-center text-dark">NUMERO&nbspCARTE</th>
                  <th class="text-center text-dark">STATUT</th>
                 <!--  <th class="text-center text-dark">ADRESSE</th> -->
                  <th class="text-center text-dark">DATE&nbspAFFECTATION</th>
                 <!--  <th class="text-center text-dark">EMAIL</th> -->
                 <!--  <th class="text-center text-dark">DATE </th> -->
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
    <!--***************************** Modal datail end ***********************************-->

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
      url : "<?=base_url()?>rapport/Carte_Statut/get_rapport",
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