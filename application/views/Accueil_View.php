<!DOCTYPE html>
<html lang="en">
<head>
  <?php include VIEWPATH .'includes/header.php';?>
</head>
<body>
  <!--******************* Preloader start ********************-->
  <?php include VIEWPATH .'includes/preloader.php';?>
  <!--******************* Preloader end ********************-->

  <!--********************************** Main wrapper start ***********************************-->
  <div id="main-wrapper">
    <!--********************************** Header start ***********************************-->
    <?php include VIEWPATH .'includes/menu_top.php';?>
    <!--********************************** Header end ***********************************-->

    <!--********************************** Sidebar start ***********************************-->
    <?php include VIEWPATH .'includes/menu_left.php';?>
    <!--********************************** Sidebar end ***********************************-->

    <!--********************************** Content body start ***********************************-->
    <div class="content-body">
      <div class="container-fluid">
        <div class="container-fluid" id="photo_d_acceil"> 
          <div id="top_color"> </div>
          <div class="#" id="welcome_title">
            <h1>Bienvenue Sur la plateforme SBG </h1>
            <p class="lead mb-0">Société Burundaise de Gardiennage</p>
          </div>
        </div>
      </div>
    </div>
    <!--***************************** Content body end ***********************************-->
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