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
      <h1>Détail</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Détail</a></li>
          <!-- <li class="breadcrumb-item active">Liste</li> -->
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <!-- <section class="section dashboard">
      
    </section> -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <!-- <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle"> -->

              <?php
              if(!empty($proprietaire['PHOTO_PASSPORT']) && $proprietaire['TYPE_PROPRIETAIRE_ID']==2)
              {
                ?>
                <img  class="rounded-circle" src="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['PHOTO_PASSPORT'])?>">
                <?php
              }
              else if(empty($proprietaire['PHOTO_PASSPORT']) && $proprietaire['TYPE_PROPRIETAIRE_ID']==2)
              {
                ?>
                <img style="background-color: #829b35;border-radius: 0%" class="img-fluid" width="65px" height="auto" src="<?=base_url('upload/img_agent/phavatar.png')?>">
                <?php
              }else  if($proprietaire['TYPE_PROPRIETAIRE_ID']==1){?>

                <span width="200px" height="auto" class="bi bi-bank"></span>

              <?php }
              ?>
              <h2><?=$proprietaire['NOM_PROPRIETAIRE'].' '. $proprietaire['PRENOM_PROPRIETAIRE']?></h2>
              <!-- <h3>Web Designer</h3> -->
              <div class="social-links mt-2">
                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info_generales">Informations générales</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#voitures">Véhicules</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="info_generales">

                  <h5 class="card-title">A propos</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Nom</div>
                    <div class="col-lg-9 col-md-8"><?=$proprietaire['NOM_PROPRIETAIRE'].' '. $proprietaire['PRENOM_PROPRIETAIRE']?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">E-mail</div>
                    <div class="col-lg-9 col-md-8"><?=$proprietaire['EMAIL']?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Téléphone</div>
                    <div class="col-lg-9 col-md-8"><?=$proprietaire['TELEPHONE']?></div>
                  </div>
                  <?php
                  if($proprietaire['TYPE_PROPRIETAIRE_ID']==2)
                  {
                    ?>
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Localité</div>
                      <div class="col-lg-9 col-md-8"><?=$proprietaire['PROVINCE_NAME'].' / '.$proprietaire['COMMUNE_NAME'].' / '.$proprietaire['ZONE_NAME'].' / '.$proprietaire['COLLINE_NAME']?></div>
                    </div>
                    <?php
                  }

                  ?>
                  
                  <?php
                  if(!empty($proprietaire['CNI_OU_NIF']))
                  {
                    ?>
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">CNI / NIF</div>
                      <div class="col-lg-9 col-md-8"><?=$proprietaire['CNI_OU_NIF']?></div>
                    </div>
                    <?php
                  }?>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Addresse</div>
                    <div class="col-lg-9 col-md-8"><?=$proprietaire['ADRESSE']?></div>
                  </div>

                  

                </div>


                <div class="tab-pane fade pt-3" id="voitures">

                  <!-- <h5 class="card-title">A propos</h5> -->
                  <input type="hidden" name="PROPRIETAIRE_ID" id="PROPRIETAIRE_ID" value="<?=$PROPRIETAIRE_ID?>">

                  <div class="table-responsive">

                    <table id="mytable" class="table table-bordered table-hover text-dark" style="width:100%">
                      <thead class="text-dark" style="background-color: rgba(0, 0, 0, 0.075);">
                        <tr>
                          <th class="text-dark">#</th>
                          <th class="text-dark">MARQUE</th>
                          <th class="text-dark">MODELE</th>
                          <th class="text-dark">COULEUR</th>
                          <th class="text-dark">PLAQUE</th>
                          <th class="text-dark">PHOTO</th>
                          <th class="text-dark">Localisation</th>

                          <!-- <th class="text-dark"></th> -->



                        </tr>
                      </thead>
                      <tbody class="text-dark">
                      </tbody>
                    </table>
                  </div>

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <?php include VIEWPATH . 'includes/footer.php'; ?>

</body>

<script >
  $(document).ready( function ()
  {

   liste();
 });

  function liste()
  {

    var PROPRIETAIRE_ID = $('#PROPRIETAIRE_ID').val();

    var row_count ="1000000";
    $("#mytable").DataTable({
      "destroy" : true,
      "processing":true,
      "serverSide":true,
      "destroy":true,
      "oreder":[[ 1, 'asc' ]],
      "ajax":{
        url: "<?php echo base_url('proprietaire/Proprietaire/detail_vehicule_client');?>", 
        type:"POST",
        data : {PROPRIETAIRE_ID:PROPRIETAIRE_ID},
        beforeSend : function() {
        }
      },
      lengthMenu: [[10,50, 100, -1], [10,50, 100, "All"]],
      pageLength: 10,
      "columnDefs":[{
        "targets":[],
        "orderable":false
      }],
      dom: 'Bfrtlip',
      buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'  ],
      language: {
        "sProcessing":     "Traitement en cours...",
        "sSearch":         "Recherche&nbsp;:",
        "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
        "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
        "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
        "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        "sInfoPostFix":    "",
        "sLoadingRecords": "Chargement en cours...",
        "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
        "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
        "oPaginate": {
          "sFirst":      "Premier",
          "sPrevious":   "Pr&eacute;c&eacute;dent",
          "sNext":       "Suivant",
          "sLast":       "Dernier"
        },
        "oAria": {
          "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
          "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
        }
      }
    });





  }
</script>


</html>