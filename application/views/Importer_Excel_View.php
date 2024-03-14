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
      <h1>Chauffeur</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="">Chauffeur</a></li>
        
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <!--  <div class="container text-center"> -->
        <div class="row">
          <div class="text-left col-sm-12">
            <div class="card">
              <!-- <div class="card-header">
                <h4 class="card-title lead"> <?=$title?></h4>
              </div> -->
              
              <div class="card-body text-left">

                <?= $this->session->flashdata('message'); ?>

           <form method="POST" enctype="multipart/form-data" action="<?=base_url('Importer_Excel/visa')?>">
            <div class="row">
              <div class="col-md-3">
                Choisissez un fichier Excel
              </div>
              <div class="col-md-4">
                <input type="file" required name="file" id="file" accept=".xsl, .xlsx" class="form-control">
              </div>
              <div class="col-md-2">
                <input type="submit" value="Importer" class="btn btn-primary">    
              </div>                          
            </div>                          
          </form>

                </div>
              </div>
            </div>
          </div>
      <!--   </div> -->
    </section>

  </main><!-- End #main -->

  <?php include VIEWPATH . 'includes/footer.php'; ?>

</body>




</html>