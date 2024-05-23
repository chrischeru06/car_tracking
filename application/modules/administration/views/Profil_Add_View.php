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
      <div class="row">
        <div class="col-md-6">
          <h1><font class="fa fa-user" style="font-size:18px;"></font> Profil</h1>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.html"><font class="fa fa-save" style="font-size:15px;"></font>  Enregistrement</a></li>
              <!-- <li class="breadcrumb-item active">Liste</li> -->
            </ol>
          </nav>
        </div>

        <div class="col-md-6">

          <div class="justify-content-sm-end d-flex">
            <a class="btn btn-outline-primary" href="<?=base_url('administration/Profil/index')?>"><i class="bi bi-list"></i> Liste</a>
          </div>
        </div>
      </div>
    </div> <!-- End Page Title -->

    <section class="section">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"></h5>       
          <div class="col-md-12">
            <form  name="myform" enctype="multipart/form-data" method="POST" class="form-horizontal" action="<?= base_url('administration/Profil/save'); ?>" >

              <div class="row text-dark">
                <div class="col-md-6">
                  <input type="hidden" name="ID" id="ID">
                  <label for="description">DESCRIPTION DU PROFIL</label>
                  <input type="text" class="form-control" name="DESCRIPTION_PROFIL" id="DESCRIPTION_PROFIL" value="<?= set_value('DESCRIPTION_PROFIL') ?>">
                  <div id="descr_error">
                    <font color="red"><?php echo form_error('DESCRIPTION_PROFIL'); ?></font>
                  </div> 
                </div>

                <div class="col-md-6">
                  <label for="code_profil">CODE DU PROFIL</label>
                  <input type="text" class="form-control" name="CODE_PROFIL" id="CODE_PROFIL" value="<?= set_value('CODE_PROFIL') ?>">
                  <div id="code_error">
                    <font color="red"><?php echo form_error('CODE_PROFIL'); ?></font>
                  </div>
                </div>
              </div>
              <br>
              <div class="row text-dark">
                <label>ROLE </label>
                <textarea cols="80" id="ROLE" required="" name="ROLE" rows="10" data-sample-short></textarea>
              </div>
              <br>
              <div style="color: red"><?php echo $error; ?></div>
              <br>
              <div class="col-md-12" style="margin-top:50px;">
                <button type="submit" style="float: right;" class="btn btn-outline-primary"><span class="fa fa-save"></span> Enregistrer</button>
              </div>
            </form>
          </div>




        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <?php include VIEWPATH . 'includes/footer.php'; ?>

</body>



<script>
  $('#DESCRIPTION_PROFIL').on('input paste change keyup',function()
  {
    $('#descr_error').hide();
    $(this).val($(this).val().replace(/[^a-z-\s|A-Z]*$/gi, '').toUpperCase());
    $(this).val($(this).val().replace(' ', ''));
    
  });

  $('#CODE_PROFIL').on('input paste change keyup',function()
  {
    $('#code_error').hide();
    $(this).val($(this).val().replace(/[^a-z-\s|0-9]*$/gi, '').toUpperCase());
    $(this).val($(this).val().replace(' ', ''));
  });

</script>

</html>