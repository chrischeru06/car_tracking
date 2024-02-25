<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>

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
      <h1>Profil</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Modification</a></li>
          <!-- <li class="breadcrumb-item active">Liste</li> -->
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"></h5>       
          <div class="col-md-12">
            <form  name="myform" enctype="multipart/form-data" method="POST" class="form-horizontal" action="<?= base_url('administration/Profil/update'); ?>" >

              <div class="row text-dark">
                <div class="col-md-6">
                  <input type="hidden" name="ID" id="ID" value="<?= $profiles['PROFIL_ID'];?>">
                  <label for="description">DESCRIPTION DU PROFIL</label>
                  <input type="text" class="form-control" name="DESCRIPTION_PROFIL" id="DESCRIPTION_PROFIL" value="<?=$profiles['DESCRIPTION_PROFIL']?>">
                  <div id="descr_error">
                    <font color="red"><?php echo form_error('DESCRIPTION_PROFIL'); ?></font> 
                  </div>
                </div>

                <div class="col-md-6">
                  <label for="code_profil">CODE DU PROFIL</label>
                  <input type="text" class="form-control" name="CODE_PROFIL" id="CODE_PROFIL" value="<?=$profiles['CODE_PROFIL']?>">
                  <div id="code_error">
                    <font color="red"><?php echo form_error('CODE_PROFIL'); ?></font>
                  </div>
                </div>
              </div>
              <br>
              <div style="color: red"><?php echo $error; ?></div>
              <br>


              <div class="col-md-12" style="margin-top:50px;">
                <button type="submit" style="float: right;" class="btn btn-secondary"><span class="fas "></span> Modifier</button>
              </div>
            </form>
          </div>




        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <?php include VIEWPATH . 'includes/footer.php'; ?>

</body>





</html>