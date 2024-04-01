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

    <div class="row page-titles mx-0">
      <div class="col-sm-10 p-md-0">
        <div class="welcome-text">
          <table>
            <tr>
              <td> 
                <!-- <img src="<?= base_url()?>template/imagespopup/IconeMuyingajdfss-04.png" width="60px" height="60px" alt=""> -->
              </td>
              <td>  
                <h5 class="text-muted pt-2 ps-1 text-dark"> <i class="fa fa-dashboard"></i> Dashboard véhicule</h5>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <!-- <li class="breadcrumb-item"><a href="#">Centre de situation</a></li> -->
                    <!-- <li class="breadcrumb-item"><a href="#">Carte</a></li> -->
                    <!-- <li class="breadcrumb-item active" aria-current="page">Saving slides</li> -->
                  </ol>
                </nav>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div class="col-md-2">

        <!-- <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('vehicule/Vehicule')?>" class="nav-link position-relative"><i class="bi bi-list"></i> Liste</a> -->

      </div>
    </div>


    <section class="section">
     <div class="container text-center">
      <div class="row">
        <div class="text-left col-sm-12">
          <div class="card" style="border-radius: 20px;">

            <!-- <br> -->

            <div class="card-body">
              <div class="row">
                <div class="col-md-3">
                  <img src="<?= base_url()?>/upload/photo_vehicule/<?= $infos_vehicule['PHOTO']?>" style="width: 50px;height: 50px;border-radius: 10px;"><strong> <?= $infos_vehicule['DESC_MARQUE'].' - '.$infos_vehicule['DESC_MODELE']?></strong>
                </div>  
              </div>

              <div class="row">

                <div class="col-md-12">

                  <ul class="nav nav-tabs nav-tabs-bordered">

                    <li class="nav-item">
                      <button class="nav-link active " data-bs-toggle="tab" data-bs-target="#info_generales"><i class="fa fa-info-circle"></i> Informations générales</button>
                    </li>

                    <li class="nav-item">
                      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#voitures">Assurances</button>
                    </li>
                    

                  </ul>

                </div>


              </div>

            </div>
            
            <!-- </div> -->

          </div>
        </div>
      </div>
    </div>
    <!-- </div> -->
  </section>


  <section class="section">
   <div class="container text-center">
    <div class="row">
      <div class="text-left col-sm-12">
        <div class="card" style="border-radius: 20px;">

          <br>

          <div class="card-body">

           <!-- begin tab content -->
           <div class="tab-content pt-2"> 

            <div class="tab-pane fade show active" id="info_generales">
              Info general
            </div>

            <div class="tab-pane fade " id="voitures">
              Assurances
            </div>

          </div>
          <!-- end tab content -->

        </div>

        <!-- </div> -->

      </div>
    </div>
  </div>
</div>
<!-- </div> -->
</section>

</main><!-- End #main -->

<?php include VIEWPATH . 'includes/footer.php'; ?>

</body>


</html>