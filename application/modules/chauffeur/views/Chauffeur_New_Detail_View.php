<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>
  <style>
    .profile .profile-card img 
    {
      max-width: 290px;
    }
    .zoomable-image{
      transition: transform 0.2s ease;
    }
    .zoomable-image:hover{
      transform: scale(2.0);

    }
  </style>
</head>

<body>
  <!-- ======= Header ======= -->
  <?php include VIEWPATH . 'includes/nav_bar.php'; ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php include VIEWPATH . 'includes/menu_left.php'; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

   <!--  <div class="pagetitle">
      <h1>Détail</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Détail</a></li>
      
        </ol>
      </nav>
    </div>

  -->
  <div class="pagetitle">
    <div class="row">

      <div class="col-md-6">
       <!--  <li class="breadcrumb-item"><a href="index.html">Détail</a></li>  -->
       <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Détail du cahuffeur <strong><?=$chauff['NOM'].' '. $chauff['PRENOM']?></strong></a></li>

        </ol>
      </nav>
    </div>

    <div class="col-md-6">

      <div class="justify-content-sm-end d-flex">
        <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('chauffeur/Chauffeur_New')?>"><i class="bi bi-plus"></i> Liste</a>
      </div>
    </div><!-- End Page Title -->
  </div>
</div>
<!-- End Page Title -->

    <!-- <section class="section dashboard"> 
    </section> -->

    <section class="section profile">
      <div class="row">
         <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <!-- <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle"> -->
              

              <?php
              if(!empty($chauff['PHOTO_PASSPORT']))
              {
                ?>
                <img style='border-radius: 10px;height: 320px;width: 200%;' src='<?=base_url("upload/chauffeur/".$chauff['PHOTO_PASSPORT'])?>'>
                <?php
              }
              else if(empty($chauff['PHOTO_PASSPORT']))
              {
                ?>
                <img style="border-radius: 10px;height: 290px;width: 200%;" class="img-fluid" width="65px" height="auto" src="<?=base_url('upload/img_agent/phavatar.png')?>">
                <?php
              }
              ?>
              <h2><?=$chauff['NOM'].' '. $chauff['PRENOM']?></h2>
            
            </div>
          </div>

        </div>


        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">
               <?php  
               if($chauff['STATUT_VEHICULE']==2) 
                 {?>
                   <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info_generales">Informations générales</button>
                  </li>

                  <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#voitures">Informaton véhicules</button>
                  </li>

                  <li class="nav-item">
                    <button class="nav-link " data-bs-toggle="tab" data-bs-target="#doc_uploader">Documents</button>
                  </li>
                  <?php
                }elseif($chauff['STATUT_VEHICULE']==1)
                {
                  ?>
                  <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info_generales">Informations générales</button>
                  </li>

                  <li class="nav-item">
                    <button class="nav-link " data-bs-toggle="tab" data-bs-target="#doc_uploader">Documents</button>
                  </li>


                  <?php
                }
                ?>

              </ul>
              <div class="tab-content pt-2">
                <div class="tab-pane fade show active profile-overview" id="info_generales">
                   <h5 class="card-title">A propos</h5>
                 
                    <div class="row">
                    <div class="col-lg-3 col-md-4 label "><span class="fa fa-book"></span>  Carte d'identité</div>
                    <div class="col-lg-9 col-md-8"><?=$chauff['NUMERO_CARTE_IDENTITE']?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label"> <span class="fa fa-envelope-o">  E-mail</div>
                    <div class="col-lg-9 col-md-8"><?=$chauff['ADRESSE_MAIL']?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label"><span class="fa fa-phone"></span>  Téléphone</div>
                    <div class="col-lg-9 col-md-8"><?=$chauff['NUMERO_TELEPHONE']?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label"><span class="fa fa-calendar">  Date naissance</div>
                    <div class="col-lg-9 col-md-8"><?=$chauff['DATE_NAISSANCE']?></div>
                  </div>
                 
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label"><span class="fa fa-map-marker"></span>  Adresse physique</div>
                    <div class="col-lg-9 col-md-8"><?=$chauff['ADRESSE_PHYSIQUE']?></div>
                  </div>
                  <div class="row">
                      <div class="col-lg-3 col-md-4 label"><span class="fa fa-map-marker"></span>  Localité</div>
                      <div class="col-lg-9 col-md-8"><?=$chauff['PROVINCE_NAME'].' / '.$chauff['COMMUNE_NAME'].' / '.$chauff['ZONE_NAME'].' / '.$chauff['COLLINE_NAME']?></div>
                    </div>
                </div>

               <div class="tab-pane fade pt-3" id="voitures">
                  <div class="row">
                  <div class="col-md-6">
                    <?php
                    if(!empty($info_vehicul['PHOTO']))
                    {
                      ?>
                      <!-- <img style="border-radius: 10px;height: 290px;width: 200%;" class="img-fluid" width="65px" height="auto" src="<?=base_url('upload/photo_vehicule'.$info_vehicul['PHOTO'])?>"> -->
                      <img style='border-radius: 10px;height: 300px;width: 100%;' src='<?=base_url("upload/photo_vehicule/".$info_vehicul['PHOTO'])?>'>

                      <?php
                    }
                    else if(empty($info_vehicul['PHOTO']))
                    {
                      ?>
                      <img style="border-radius: 10px;height: 300px;width: 100%;" class="img-fluid" width="65px" height="auto" src="<?=base_url('upload/img_agent/phavatar.png')?>">
                      <?php
                    }
                    ?>
                    
                  </div>
                  <div class="col-md-1" >
                  </div>
                  
                  <div class="col-md-5" >
                    <table class='table table-borderless  text-dark' >
                      <tr>
                        <td><span class="fa fa-bookmark-o"></span>&nbsp;Marque</td>
                       <th><?=$info_vehicul['DESC_MARQUE']?></th>
                      </tr>
                       <tr>
                        <td><span class="fa fa-bolt"></span>&nbsp;Modèle</td>
                       <th><?=$info_vehicul['DESC_MODELE']?></th>
                      </tr> 
                      <tr>
                        <td><span class="fa fa-circle"></span>&nbsp;Couleur</td>
                       <th><?=$info_vehicul['COULEUR']?></th>
                      </tr>
                       <tr>
                        <td><span class="fa fa-calendar"></span>&nbsp;Plaque</td>
                       <th><?=$info_vehicul['PLAQUE']?></th>
                      </tr>
                    </table>
                </div>
                </div>
                </div>
                 <div class="tab-pane fade pt-3" id="doc_uploader">
                    <div class="row">
                  <div class="col-md-6">
                    <?php
                    if(!empty($chauff['FILE_PERMIS']))
                    {
                      ?>
                      <img class="zoomable-image" style='border-radius: 5px;height: 250px;width: 70%;' src='<?=base_url("upload/chauffeur/".$chauff['FILE_PERMIS'])?>'>

                      <?php
                    }
                    else if(empty($chauff['FILE_PERMIS']))
                    {
                      ?>
                      <img class="zoomable-image" style="border-radius: 5px;height: 250px;width: 70%;" class="img-fluid" width="65px" height="auto" src="<?=base_url('upload/img_agent/phavatar.png')?>">
                      <?php
                    }
                    ?>
                      <h2>Permis de conduire</h2>
                  </div>
                 
                  
                  <div class="col-md-6" >
                     <?php
                    if(!empty($chauff['FILE_CARTE_IDENTITE']))
                    {
                      ?>
                      <img class="zoomable-image" style='border-radius: 5px;height: 250px;width: 70%;' src='<?=base_url("upload/chauffeur/".$chauff['FILE_CARTE_IDENTITE'])?>'>

                      <?php
                    }
                    else if(empty($chauff['FILE_CARTE_IDENTITE']))
                    {
                      ?>
                      <img class="zoomable-image"  style="border-radius: 5px;height: 250px;width: 70%;" class="img-fluid" width="65px" height="auto" src="<?=base_url('upload/img_agent/phavatar.png')?>">
                      <?php
                    }
                    ?>
                    <h2>Carte d'identité</h2>
                </div>
                </div>
                 
                </div>
        </div>

      </div>
    </section>

  </main><!-- End #main -->

  <?php include VIEWPATH . 'includes/footer.php'; ?>

</body>
<!------------------------ Modal detail chauff type physique' ------------------------>


<div class="modal fade" id="myModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'>            
        <h5 class="modal-title">Détails</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <div class="row">
            <div class='col-md-6' id="div_info">
            </div>
            <div class='col-md-6'>
              <table class="table table-hover text-dark">
                <tr>            
                  <td><span class="fa fa-user"></span> &nbsp;&nbsp; Nom & Prénom</td>
                  <td><a id="NOM"></a></td>
                </tr>

                <tr>
                  <td><span class="fa fa-user-plus"></span> &nbsp;&nbsp; Date de naissance</td>
                  <td><a id="DATE_NAISSANCE"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-newspaper-o"></span> &nbsp;&nbsp; Téléphone</td>
                  <td><a id="NUMERO_TELEPHONE"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-phone"></span> &nbsp;&nbsp; Email</td>
                  <td><a id="ADRESSE_MAIL"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-envelope-o"></span> &nbsp;&nbsp; CNI / Passport</td>
                  <td><a id="NUMERO_CARTE_IDENTITE"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-bank"></span> &nbsp;&nbsp; Adresse</td>
                  <td><a id="ADRESSE_PHYSIQUE"></a></td>
                </tr>


              </table>
            </div>
          </div>


          <!-- <div id="CNI"></div> -->



        </div>

      </div>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div> -->
    </div>
  </div>
</div><!-- End Modal-->
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
        url: "<?php echo base_url('chauff/Proprietaire/detail_vehicule_client');?>", 
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

<script>
 function get_detail_chauffeur(CODE)
 {
   $("#myModal").modal("show");
   $.ajax({
    url: "<?= base_url() ?>chauff/Proprietaire/get_detail_chauffeur/" + CODE,
    type: "POST",
    dataType: "JSON",
    success: function(data) {

      // alert(data.CNI)

      $('#NOM').html(data.NOM);
      $('#DATE_NAISSANCE').html(data.DATE_NAISSANCE);
      $('#NUMERO_TELEPHONE').html(data.NUMERO_TELEPHONE);
      $('#ADRESSE_MAIL').html(data.ADRESSE_MAIL);
      $('#NUMERO_CARTE_IDENTITE').html(data.NUMERO_CARTE_IDENTITE);
      $('#ADRESSE_PHYSIQUE').html(data.ADRESSE_PHYSIQUE);
      $('#div_info').html(data.div_info);



    },

  });
 }
</script>
<script type="text/javascript">
  function popup_nif()
  {
    $('#Modal_nif').modal('show');
    
  }
  function popup_rc()
  {
    $('#Modal_rc').modal('show');
    
  } 
  function popup_logo()
  {
    $('#Modal_logo').modal('show');
    
  }

</script>
</html>