<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>
  <style>
    .profile .profile-card img {
      max-width: 290px;
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
          <li class="breadcrumb-item"><i class="fa fa-dashboard"> </i> <a href="index.html"> Détail</a></li>

        </ol>
      </nav>
    </div>

    <div class="col-md-6">

      <div class="justify-content-sm-end d-flex">
        <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('proprietaire/Proprietaire/liste')?>"><i class="bi bi-plus"></i> Liste</a>
      </div>
    </div><!-- End Page Title -->
  </div>
</div>
<!-- End Page Title -->

    <!-- <section class="section dashboard"> 
    </section> -->

    <section class="section profile">
      <div class="row">


        <div class="col-xl-12">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">
               <?php
               if ($proprietaire['TYPE_PROPRIETAIRE_ID']==1) 
                {?>
                  <li class="nav-item">
                    <button class="nav-link <?php if($VEHICULE_PRO == " "){echo "active";}else{echo "";}?>" data-bs-toggle="tab" data-bs-target="#info_generales">Informations générales</button>
                  </li>

                  <li class="nav-item">
                    <button class="nav-link <?php if($VEHICULE_PRO !=" "){echo "active";}else{echo "";}?>" data-bs-toggle="tab" data-bs-target="#voitures">Véhicules<span class="badge bg-primary rounded-pill nbr_vehicule" style="font-size:10px;position:relative;top:-10px;left:-2px;">4</span></button>
                  </li>

                  <li class="nav-item">
                    <button class="nav-link " data-bs-toggle="tab" data-bs-target="#doc_uploader">Documents</button>
                  </li>
                  <?php
                }elseif($proprietaire['TYPE_PROPRIETAIRE_ID']==2)
                {
                  ?>
                  <li class="nav-item">
                    <button class="nav-link <?php if($VEHICULE_PRO ==" "){echo "active";}else{echo "";}?>" data-bs-toggle="tab" data-bs-target="#info_generales">Informations générales</button>
                  </li>

                  <li class="nav-item">
                    <button class="nav-link <?php if($VEHICULE_PRO !=" "){echo "active";}else{echo "";}?>" data-bs-toggle="tab" data-bs-target="#voitures">Véhicules<span class="badge bg-primary rounded-pill nbr_vehicule" style="font-size:10px;position:relative;top:-10px;left:-2px;">4</span></button>
                  </li>
                  <?php

                  
                }

                ?>
                

              </ul>
              <div class="tab-content pt-2">
               <div class="tab-pane fade  <?php if($VEHICULE_PRO == " "){echo " show active";}else{echo "";}?> profile-overview" id="info_generales">
                <div class="row">
                <div class="col-xl-4">

                  <!-- <div class="card"> -->
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                      <!-- <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle"> -->

                      <?php
                      if(!empty($proprietaire['PHOTO_PASSPORT']) && $proprietaire['TYPE_PROPRIETAIRE_ID']==2)
                      {
                        ?>
                        <img style="border-radius: 10px;height: 320px;width: 200%;" src="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['PHOTO_PASSPORT'])?>">
                        <?php
                      }
                      else if(empty($proprietaire['PHOTO_PASSPORT']) && $proprietaire['TYPE_PROPRIETAIRE_ID']==2)
                      {
                        ?>
                        <img  style="border-radius: 10px;height: 290px;width: 200%;" class="img-fluid" width="65px" height="auto" src="<?=base_url('upload/img_agent/phavatar.png')?>">
                        <?php
                      }else  if($proprietaire['TYPE_PROPRIETAIRE_ID']==1 && empty($proprietaire['LOGO']))
                      {?>

                        <span width="200px" height="auto" class="bi bi-bank"></span>

                      <?php }elseif ($proprietaire['TYPE_PROPRIETAIRE_ID']==1 && !empty($proprietaire['LOGO'])) 
                      {?>
                        <img  style="border-radius: 10px;height: 290px;width: 200%;"  src="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['LOGO'])?>">
                      <?php }
                      ?>
                      <h2><?=$proprietaire['NOM_PROPRIETAIRE'].' '. $proprietaire['PRENOM_PROPRIETAIRE']?></h2>
                      <!-- <h3>Web Designer</h3> -->
             <!--  <div class="social-links mt-2">
                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
              </div> -->
            </div>
          <!-- </div> -->

        </div>
        <div class="col-xl-2">
        </div>
        <div class="col-xl-6">
          <h5 class="card-title">A propos</h5>
          <?php 
          if ($proprietaire['TYPE_PROPRIETAIRE_ID']==1)
          {
            ?>
            <div class="row">
              <div class="col-lg-3 col-md-4 label "><span class="fa fa-user"></span> Nom & Prénom</div>
              <div class="col-lg-9 col-md-8"><?=$proprietaire['NOM_PROPRIETAIRE'].' '. $proprietaire['PRENOM_PROPRIETAIRE']?></div>
            </div>

            <div class="row">
              <div class="col-lg-3 col-md-4 label"> <span class="fa fa-envelope-o"></span> E-mail</div>
                <div class="col-lg-9 col-md-8"><?=$proprietaire['EMAIL']?></div>
              </div>

              <div class="row">
                <div class="col-lg-3 col-md-4 label"><span class="fa fa-phone"></span> Téléphone</div>
                <div class="col-lg-9 col-md-8"><?=$proprietaire['TELEPHONE']?></div>
              </div>

              <?php
              if(!empty($proprietaire['CNI_OU_NIF']))
              {
                ?>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label"><span class="fa fa-newspaper-o"></span> <?=$label_cni?></div>
                  <div class="col-lg-9 col-md-8"><?=$proprietaire['CNI_OU_NIF']?></div>
                </div>
                <?php
              }?>

              <div class="row">
                <div class="col-lg-3 col-md-4 label"><span class="fa fa-map-marker"></span> Addresse</div>
                  <div class="col-lg-9 col-md-8"><?=$proprietaire['ADRESSE']?></div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label"><span class="fa fa-newspaper-o"></span> RC</div>
                  <div class="col-lg-9 col-md-8"><?=$proprietaire['RC']?></div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label"><span class="fa fa-newspaper-o"></span> Catégorie</div>
                  <div class="col-lg-9 col-md-8"><?=$proprietaire['DESC_CATEGORIE']?></div>
                </div>



                <?php


              }else
              {
                ?>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label "><span class="fa fa-user"></span> Nom & Prénom</div>
                    <div class="col-lg-9 col-md-8"><?=$proprietaire['NOM_PROPRIETAIRE'].' '. $proprietaire['PRENOM_PROPRIETAIRE']?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label"><span class="fa fa-envelope-o"></span> E-mail</div>
                    <div class="col-lg-9 col-md-8"><?=$proprietaire['EMAIL']?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label"><span class="fa fa-phone"></span> Téléphone</div>
                    <div class="col-lg-9 col-md-8"><?=$proprietaire['TELEPHONE']?></div>
                  </div>
                  <?php
                  if(!empty($proprietaire['CNI_OU_NIF']))
                  {
                    ?>
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label"><span class="fa fa-book"></span> <?=$label_cni?></div>
                      <div class="col-lg-9 col-md-8"><?=$proprietaire['CNI_OU_NIF']?></div>
                    </div>
                    <?php
                  }?>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label"><span class="fa fa-map-marker"></span> Addresse</div>
                    <div class="col-lg-9 col-md-8"><?=$proprietaire['ADRESSE']?></div>
                  </div>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label"><span class="fa fa-map-marker"></span> Localité</div>
                    <div class="col-lg-9 col-md-8"><?=$proprietaire['PROVINCE_NAME'].' / '.$proprietaire['COMMUNE_NAME'].' / '.$proprietaire['ZONE_NAME'].' / '.$proprietaire['COLLINE_NAME']?></div>
                  </div>

                  <?php

                }

                ?>


                <!--   <?php
                  if($proprietaire['TYPE_PROPRIETAIRE_ID']==2)
                  {
                    ?>
                   
                    <?php
                  }

                ?> -->




              </div>
            </div>
          </div>
            <div class="tab-pane fade pt-3  profile-overview" id="doc_uploader">

              <div class="row">

                <div class="col-md-6">  <h5 class="card-title">Logo</h5>
                 <!--  <div class="col-lg-3 col-md-4 label ">Nom & Prénom</div> -->
                 <?php
                 if(!empty($proprietaire['LOGO']) && $proprietaire['TYPE_PROPRIETAIRE_ID']==1)
                 {
                  ?>
                  <img style="border-radius: 5px;height: 100px;width:50%;" onclick="popup_logo();" src="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['LOGO'])?>">
                  <?php
                }
                else if(empty($proprietaire['LOGO']) && $proprietaire['TYPE_PROPRIETAIRE_ID']==1)
                {
                  ?>
                  <img  style="border-radius: 5px;height: 250px;width: 95%;" class="img-fluid" width="65px" height="auto" src="<?=base_url('upload/img_agent/phavatar.png')?>">
                  <?php
                }else  if($proprietaire['TYPE_PROPRIETAIRE_ID']==1 && empty($proprietaire['LOGO']))
                {?>

                  <span width="200px" height="auto" class="bi bi-bank"></span>

                <?php }elseif ($proprietaire['TYPE_PROPRIETAIRE_ID']==1 && !empty($proprietaire['LOGO'])) 
                {?>
                  <img  style="border-radius: 5px;height: 250px;width: 95%;"   onclick="popup_logo();" src="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['LOGO'])?>">
                <?php }
                ?>

              </div>

              <div class="col-md-6"> <h5 class="card-title">NIF</h5>
               <!--    <div class="col-lg-9 col-md-8"><?=$proprietaire['NOM_PROPRIETAIRE'].' '. $proprietaire['PRENOM_PROPRIETAIRE']?></div> -->
               <?php
               if($proprietaire['TYPE_PROPRIETAIRE_ID']==1 && empty($proprietaire['FILE_NIF']))
                {?>

                  <span width="200px" height="auto" class="bi bi-bank"></span>

                <?php }elseif ($proprietaire['TYPE_PROPRIETAIRE_ID']==1 && !empty($proprietaire['FILE_NIF'])) 
                {?>
                  <embed  style="border-radius: 5px;height: 100px;width: 50%;" onclick="popup_nif();" src="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['FILE_NIF'])?>" >
                  <?php }
                  ?>
                </div>
                <div class="col-md-6"> <h5 class="card-title">RC</h5>
                 <!--    <div class="col-lg-9 col-md-8"><?=$proprietaire['NOM_PROPRIETAIRE'].' '. $proprietaire['PRENOM_PROPRIETAIRE']?></div> -->
                 <?php
                 if($proprietaire['TYPE_PROPRIETAIRE_ID']==1 && empty($proprietaire['FILE_RC']))
                  {?>

                    <span width="200px" height="auto" class="bi bi-bank"></span>

                  <?php }elseif ($proprietaire['TYPE_PROPRIETAIRE_ID']==1 && !empty($proprietaire['FILE_RC'])) 
                  {?>
                    <embed  style="border-radius: 5px;height: 100px;width: 50%;"  onclick="popup_rc();" src="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['FILE_RC'])?>">
                    <?php }
                    ?>
                  </div>
                </div>



              </div>




              <div class="tab-pane fade <?php if($VEHICULE_PRO != " "){echo " show active";}else{echo "";}?> pt-3" id="voitures">

                <!-- <h5 class="card-title">A propos</h5> -->
                <input type="hidden" name="PROPRIETAIRE_ID" id="PROPRIETAIRE_ID" value="<?=$PROPRIETAIRE_ID?>">

                <div class="table-responsive">

                  <table id="mytable" class="table table-hover text-dark" style="width:100%">
                    <thead class="text-dark" style="background-color: rgba(0, 0, 0, 0.075);">
                      <tr>
                        <th class="text-dark">#</th>
                        <th class="text-dark">MARQUE</th>
                        <th class="text-dark">MODELE</th>
                        <th class="text-dark">COULEUR</th>
                        <th class="text-dark">PLAQUE</th>
                        <th class="text-dark">PHOTO</th>
                        <th class="text-dark">CHAUFFEUR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="text-dark">LOCALISATION</th>

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

    <!--      debut modal nif -->
    <div class="modal fade" id="Modal_nif" tabindex="-1" >
      <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
          <div class='modal-header' style='background:cadetblue;color:white;'>      
            <h5 class="modal-title">NIF  </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <embed  style="border-radius: 5px;height: 250px;width: 95%;" onclick="popup_nif();" src="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['FILE_NIF'])?>" >

            </div>

          </div>
        </div>
      </div>
    </div>
    <!-- fin modal nif -->
    <!--      debut modal rc -->
    <div class="modal fade" id="Modal_rc" tabindex="-1" >
      <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
          <div class='modal-header' style='background:cadetblue;color:white;'>      
            <h5 class="modal-title">RC  </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <embed  style="border-radius: 5px;height: 250px;width: 95%;"  onclick="popup_rc();" src="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['FILE_RC'])?>"></embed>

            

          </div>
        </div>
      </div>
    </div>
    <!-- fin modal rc -->
    <!--      debut modal logo -->
    <div class="modal fade" id="Modal_logo" tabindex="-1" >
      <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
          <div class='modal-header' style='background:cadetblue;color:white;'>      
            <h5 class="modal-title">Logo  </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <embed  style="border-radius: 5px;height: 250px;width: 95%;"  onclick="popup_logo();" src="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['LOGO'])?>"></embed>

            

          </div>
        </div>
      </div>
    </div>
    <!-- fin modal logo -->

  </section>

</main><!-- End #main -->

<?php include VIEWPATH . 'includes/footer.php'; ?>

</body>
<!------------------------ Modal detail proprietaire type physique' ------------------------>


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
   get_nbr_vehicule()
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

<script>
 function get_detail_chauffeur(CODE)
 {
   $("#myModal").modal("show");
   $.ajax({
    url: "<?= base_url() ?>proprietaire/Proprietaire/get_detail_chauffeur/" + CODE,
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

<script>
 function get_nbr_vehicule()
 {
  var PROPRIETAIRE_ID = $('#PROPRIETAIRE_ID').val();

  $.ajax({
    url: "<?= base_url() ?>proprietaire/Proprietaire/get_nbr_vehicule/" + PROPRIETAIRE_ID,
    type: "POST",
    dataType: "JSON",
    success: function(data) {
     $('.nbr_vehicule').text(data);
   },

 });
}
</script>

</html>