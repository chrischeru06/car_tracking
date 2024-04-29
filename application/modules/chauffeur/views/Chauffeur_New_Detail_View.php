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
    #image-container{
      position: relative;
      left:10px;
      width: 670px; 
      height: 600px;
      overflow: hidden;
    }
    #image-container_proprio{
      position: relative;
      left:10px;
      width: 670px; 
      height: 600px;
      overflow: hidden;
    }
     #image-container_chof{
      position: relative;
      left:10px;
      width: 670px; 
      height: 600px;
      overflow: hidden;
    }
    #image-container2{
      position: relative;
      left:10px;
      width: 670px; 
      height: 600px;
      overflow: hidden;
    }

    #phot_v {
      position: relative;
      cursor: grab;
      transition: transform 0.2s;
      border-radius: 10px;

      width: 105%; 
      height: 100%;
      margin-left: -12px;
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
          <li class="breadcrumb-item"><a href="index.html">Détail du chauffeur <strong><?=$chauff['NOM'].' '. $chauff['PRENOM']?></strong></a></li>

        </ol>
      </nav>
    </div>

    <div class="col-md-6">

      <div class="justify-content-sm-end d-flex">
        <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('chauffeur/Chauffeur')?>"><i class="bi bi-plus"></i> Liste</a>
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
               if($chauff['STATUT_VEHICULE']==2) 
                 {?>
                   <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info_generales">Informations générales</button>
                  </li>

                  <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#voitures">Information véhicules</button>
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
                 <!-- <h5 class="card-title">A propos</h5> -->
                 <br>
                 <div class="row">
                  <div class="col-xl-4">

                    <!-- <div class="card"> -->
                      <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <!-- <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle"> -->


                        <?php
                        if(!empty($chauff['PHOTO_PASSPORT']))
                        {
                          ?>
                          <img style='border-radius: 10px;height: 320px;width: 200%;' class="" onclick="show_imagechauff();" src='<?=base_url("upload/chauffeur/".$chauff['PHOTO_PASSPORT'])?>'>
                          <input type="hidden" id="phot_chof2" value="<?= base_url()?>/upload/chauffeur/<?= $chauff['PHOTO_PASSPORT']?>">
                          <?php
                        }
                        else if(empty($chauff['PHOTO_PASSPORT']))
                        {
                          ?>
                          <img style="border-radius: 10px;height: 290px;width: 200%;" class="img-fluid" width="65px" height="auto" src="<?=base_url('upload/img_agent/phavatar.png')?>">
                           <input type="hidden" id="phot_chof2" value="<?= base_url()?>/upload/img_agent/phavatar.png">
                          <?php
                        }
                        ?>
                        <h2><?=$chauff['NOM'].' '. $chauff['PRENOM']?></h2>

                      </div>
                      <!-- </div> -->

                    </div>
                    <div class="col-xl-2">
                    </div>
                    <div class="col-xl-6">
                      <br>
                      <div class="row">
                        <div class="col-lg-3 col-md-4 label "><span class="fa fa-book"></span>&nbsp;&nbsp;CNI</div>
                        <div class="col-lg-9 col-md-8"><?=$chauff['NUMERO_CARTE_IDENTITE']?></div>
                      </div>

                      <div class="row">
                        <div class="col-lg-3 col-md-4 label"> <span class="fa fa-envelope-o"></span>&nbsp;&nbsp;E-mail</div>
                        <div class="col-lg-9 col-md-8"><?=$chauff['ADRESSE_MAIL']?></div>
                      </div>

                      <div class="row">
                        <div class="col-lg-3 col-md-4 label"><span class="fa fa-phone"></span>&nbsp;&nbsp;Téléphone</div>
                        <div class="col-lg-9 col-md-8"><?=$chauff['NUMERO_TELEPHONE']?></div>
                      </div>

                      <div class="row">
                        <div class="col-lg-3 col-md-4 label"><span class="fa fa-calendar"></span>&nbsp;&nbsp;Date&nbsp;naissance</div>
                        <div class="col-lg-9 col-md-8"><?=$chauff['DATE_NAISSANCE']?></div>
                      </div>

                      <div class="row">
                        <div class="col-lg-3 col-md-4 label"><span class="fa fa-map-marker"></span>&nbsp;&nbsp;Adresse</div>
                        <div class="col-lg-9 col-md-8"><?=$chauff['ADRESSE_PHYSIQUE']?></div>
                      </div>
                      <div class="row">
                        <div class="col-lg-3 col-md-4 label"><span class="fa fa-map-marker"></span>&nbsp;&nbsp;Localité</div>
                        <div class="col-lg-9 col-md-8"><?=$chauff['PROVINCE_NAME'].' / '.$chauff['COMMUNE_NAME'].' / '.$chauff['ZONE_NAME'].' / '.$chauff['COLLINE_NAME']?></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade pt-3" id="voitures">
                  <div class="row">
                    <div class="col-xl-4">
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
                    <div class="col-xl-2" >
                    </div>
                    <?php
                    if(!empty($info_vehicul))
                    {
                      ?>
                      <div class="col-xl-6" >
                        <table class='table table-borderless  text-dark'>
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
                          <tr>
                            <td><img src='<?=base_url("upload/proprietaire/photopassport/".$info_vehicul['PHOTO_PASSPORT'])?>' style="width: 15px;height: 15px;border-radius: 50%;cursor: pointer;" onclick='show_image_proprio();' title="Propriétaire">&nbsp;Propriétaire</td>
                            <th><?=$info_vehicul['name']?></th>
                          </tr>
                        </table>
                      </div>
                    <?php }?>
                  </div>
                </div>

                <div class="tab-pane fade pt-3" id="doc_uploader">
                  <div class="col-md-4">
                  <table class='table table-borderless  text-dark'>
                    <tr>

                      <?php
                      if(!empty($chauff['FILE_PERMIS']))
                      {
                        ?>
                        <th> <img style='border-radius: 5px;height: 50px;cursor: pointer;' src='<?=base_url("upload/chauffeur/".$chauff['FILE_PERMIS'])?>' onclick='show_image();'></th>

                        <?php
                      }
                      else if(empty($chauff['FILE_PERMIS']))
                      {
                        ?>
                        <th> <img style="border-radius: 5px;height: 50px;cursor: pointer;" class="img-fluid" width="65px" height="auto" src="<?=base_url('upload/img_agent/phavatar.png')?>"></th>
                        <?php
                      }
                      ?>
                      


                      

                      <?php
                      if(!empty($chauff['FILE_CARTE_IDENTITE']))
                      {
                        ?>
                        <th><img style='border-radius: 5px;height: 50px;cursor: pointer;' onclick='show_image_id();' src='<?=base_url("upload/chauffeur/".$chauff['FILE_CARTE_IDENTITE'])?>'></th>

                        <?php
                      }
                      else if(empty($chauff['FILE_CARTE_IDENTITE']))
                      {
                        ?>
                        <th><img  style="border-radius: 5px;height: 50px;cursor: pointer;" class="img-fluid" width="65px" height="auto" src="<?=base_url('upload/img_agent/phavatar.png')?>"></th>
                        <?php
                      }
                      ?>
                    </tr>
                    <tr>
                      <td>Permis de conduire</td>  
                      <td>Carte d'identité</td>
                    </tr>
                  </table>
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


<!-- Modal photo du chauffeur-->

<div class="modal fade" id="Modal_photo_chof">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style='background:cadetblue;color:white;'>
        <h6 class="modal-title"></h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
      </div>
      <div class="modal-body">

        <div class="row text-center" style="background-color:rgba(230,230,200,0.3);margin-top:-10px;border-radius:50%;">

          <div class="col-md-4">

          </div>

          <div class="col-md-1">
            <i onclick="zoomIn_chof()" class="fa fa-plus-circle text-muted"></i>

            <input type="hidden" id="rotation" value="0">
          </div>

          <div class="col-md-1">
            <i onclick="zoomOut_chof()" class="fa fa-minus-circle text-muted"></i>
          </div>

                <div class="col-md-1">
                  <i onclick="rotate_chof()" class="fa fa-rotate-right text-muted"></i>
                </div>


              </div>

              <div class="row">

                <div class="col-md-12" id="image-container_chof">
                  <img src="" id="phot_chof" alt="Description de l'image">
                </div>


              </div>

            </div>
            <!-- footer here -->
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

<!-- Modal photo du permis-->

<div class="modal fade" id="Modal_photo_permis">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style='background:cadetblue;color:white;'>
        <h6 class="modal-title"></h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
      </div>
      <div class="modal-body">

        <div class="row text-center" style="background-color:rgba(230,230,200,0.3);margin-top:-10px;border-radius:50%;">

          <div class="col-md-4">

          </div>

          <div class="col-md-1">
            <i onclick="zoomIn()" class="fa fa-plus-circle text-muted"></i>

            <input type="hidden" id="rotation" value="0">
          </div>

          <div class="col-md-1">
            <i onclick="zoomOut()" class="fa fa-minus-circle text-muted"></i>
          </div>
          <div class="col-md-1">
            <i onclick="rotate_op()" class="fa fa-rotate-right text-muted"></i>
          </div>


        </div>

        <div class="row">

          <div class="col-md-12" id="image-container">
            <center><img  id="phot_v"  alt="Description de l'image" src='<?=base_url("upload/chauffeur/".$chauff['FILE_PERMIS'])?>'></center>
          </div>

        </div>

      </div>
      <!-- footer here -->
    </div>
  </div>
</div>
<!-- Modal fin-->
<!-- Modal identite-->
<div class="modal fade" id="Modal_photo_identite">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style='background:cadetblue;color:white;'>
        <h6 class="modal-title"></h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
      </div>
      <div class="modal-body">

        <div class="row text-center" style="background-color:rgba(230,230,200,0.3);margin-top:-10px;border-radius:50%;">

          <div class="col-md-4">

          </div>

          <div class="col-md-1">
            <i onclick="zoomIn2()" class="fa fa-plus-circle text-muted"></i>

            <input type="hidden" id="rotation" value="0">
          </div>

          <div class="col-md-1">
            <i onclick="zoomOut2()" class="fa fa-minus-circle text-muted"></i>
          </div>
          <div class="col-md-1">
            <i onclick="rotate_op2()" class="fa fa-rotate-right text-muted"></i>
          </div>


        </div>

        <div class="row">

          <div class="col-md-12" id="image-container2">
            <center><img  id="phot_v2" style="border-radius: 5px;height: 700px;cursor: pointer;" alt="Description de l'image" src='<?=base_url("upload/chauffeur/".$chauff['FILE_CARTE_IDENTITE'])?>'></center>
          </div>

        </div>

      </div>
      <!-- footer here -->
    </div>
  </div>
</div>
<!-- fin-->
<!-- Modal photo du proprietaire-->

<div class="modal fade" id="Modal_proprio">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style='background:cadetblue;color:white;'>
        <h6 class="modal-title"></h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
      </div>
      <div class="modal-body">

        <div class="row text-center" style="background-color:rgba(230,230,200,0.3);margin-top:-10px;border-radius:50%;">

          <div class="col-md-4">

          </div>

          <div class="col-md-1">
            <i onclick="zoomIn_pro()" class="fa fa-plus-circle text-muted"></i>

            <input type="hidden" id="rotation" value="0">
          </div>

          <div class="col-md-1">
            <i onclick="zoomOut_pro()" class="fa fa-minus-circle text-muted"></i>
          </div>
          <div class="col-md-1">
            <i onclick="rotate_op_pro()" class="fa fa-rotate-right text-muted"></i>
          </div>


        </div>

        <div class="row">

          <div class="col-md-12" id="image-container_proprio">
            <center><img  id="imageproprio" style="border-radius: 5px;height: 700px;cursor: pointer;" alt="Description de l'image" src='<?=base_url("upload/proprietaire/photopassport/".$info_vehicul['PHOTO_PASSPORT'])?>'></center>
          </div>

        </div>

      </div>
      <!-- footer here -->
    </div>
  </div>
</div>
<!-- fin-->

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
  function show_image()
  {
    $('#Modal_photo_permis').modal('show');

  }
  function show_image_proprio()
  {
    $('#Modal_proprio').modal('show');

  }
  function show_image_id()
  {
    $('#Modal_photo_identite').modal('show');

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
<script>
    function show_image2()
    {
      var phot_v2 = $('#phot_v2').val();
      var imgElement = document.getElementById("phot_v");
      imgElement.src = phot_v2;
      $('#Modal_photo_chof').modal('show');
    }
  </script>

<script>
             //Operations photo avec les boutons

        var scale = 1; // Facteur de zoom initial
        var translateX = 0; // Décalage horizontal initial
        var translateY = 0; // Décalage vertical initial

        var photo = document.getElementById('phot_v');

        // Fonction pour zoomer la photo
        function zoomIn() {
          scale += 0.1;
          updateTransform();

        }

        // Fonction pour dézoomer la photo
        function zoomOut() {
          scale -= 0.1;
          updateTransform();
        }

        // Fonction pour déplacer la photo horizontalement
        function moveX(direction) {
          translateX += direction * 50; // Changer la valeur de décalage
          updateTransform();
        }

        // Fonction pour déplacer la photo verticalement
        function moveY(direction) {
          translateY += direction * 50; // Changer la valeur de décalage
          updateTransform();
        }

        // Fonction pour mettre à jour la transformation CSS de la photo
        function updateTransform() {
          photo.style.transform = `scale(${scale}) translate(${translateX}px, ${translateY}px)`;
        }

      //Rotation de l'image

        function rotate_op()
        {
          const image = document.getElementById('phot_v');
      // const rotateBtn = document.getElementById('rotate-btn');
          let rotation = Number($('#rotation').val());

      //rotateBtn.addEventListener('click', () => {
          rotation += 90;
          image.style.transform = `rotate(${rotation}deg)`;
          $('#rotation').val(rotation)
      //});
        }


      </script>
      <script>
             //Operations photo avec la sourie

        let container = document.getElementById('image-container');
        let image = document.getElementById('phot_v');
        let lastX, lastY;
        let isDragging = false;
        let rotationAngle = 0;

    // Zoomer/dézoomer sur double clic
        document.getElementById('phot_v').addEventListener('dblclick', function() {
          if (this.style.transform === "scale(2)") {
            this.style.transform = "scale(1)";
          } else {
            this.style.transform = "scale(2)";
          }
        });
    // Déplacer en maintenant le clic gauche
        image.addEventListener('mousedown', function(event) {
          if (event.button === 0) {
            isDragging = true;
            lastX = event.clientX;
            lastY = event.clientY;
            image.style.cursor = 'grabbing';
          }
        });

        document.addEventListener('mousemove', function(event) {
          if (isDragging) {
            let deltaX = event.clientX - lastX;
            let deltaY = event.clientY - lastY;
            let newX = image.offsetLeft + deltaX;
            let newY = image.offsetTop + deltaY;
            image.style.left = newX + 'px';
            image.style.top = newY + 'px';
            lastX = event.clientX;
            lastY = event.clientY;
          }
        });

        document.addEventListener('mouseup', function(event) {
          if (event.button === 0) {
            isDragging = false;
            image.style.cursor = 'grab';
          }
        });

    // Pivoter avec la molette de la souris
      // document.addEventListener('wheel', function(event) {
      //   if (event.deltaY < 0) {
      //     rotationAngle += 10;
      //   } else {
      //     rotationAngle -= 10;
      //   }
      //   image.style.transform = `rotate(${rotationAngle}deg)`;
      // });


             // Fonction pour mettre à jour la transformation CSS de la photo
        function updateTransform() {
          photo.style.transform = `scale(${scale}) translate(${translateX}px, ${translateY}px)`;
        }
      </script>


      <script>
             //Operations photo avec les boutons

        var scalee = 1; // Facteur de zoom initial
        var translateXX = 0; // Décalage horizontal initial
        var translateYY = 0; // Décalage vertical initial

        var photo2 = document.getElementById('phot_v2');

        // Fonction pour zoomer la photo
        function zoomIn2() {
          scalee += 0.1;
          updateTransform2();

        }

        // Fonction pour dézoomer la photo
        function zoomOut2() {
          scalee -= 0.1;
          updateTransform2();
        }


        // Fonction pour mettre à jour la transformation CSS de la photo
        function updateTransform2() {
          photo2.style.transform = `scale(${scalee}) translate(${translateXX}px, ${translateYY}px)`;
        }

      //Rotation de l'image

        function rotate_op2()
        {
          const image = document.getElementById('phot_v2');
      // const rotateBtn = document.getElementById('rotate-btn');
          let rotation = Number($('#rotation').val());

      //rotateBtn.addEventListener('click', () => {
          rotation += 90;
          image.style.transform = `rotate(${rotation}deg)`;
          $('#rotation').val(rotation)
      //});
        }


      </script>
      <script>
             //Operations photo avec la sourie

        let containerr = document.getElementById('image-container2');
        let imagee = document.getElementById('phot_v2');
        let lastXX, lastYY;
        let isDraggingG = false;
        let rotationAnglee = 0;

    // Zoomer/dézoomer sur double clic
        document.getElementById('phot_v2').addEventListener('dblclick', function() {
          if (this.style.transform === "scale(2)") {
            this.style.transform = "scale(1)";
          } else {
            this.style.transform = "scale(2)";
          }
        });
    // Déplacer en maintenant le clic gauche
        imagee.addEventListener('mousedown', function(event) {
          if (event.button === 0) {
            isDraggingG = true;
            lastXX = event.clientX;
            lastYY = event.clientY;
            imagee.style.cursor = 'grabbing';
          }
        });

        document.addEventListener('mousemove', function(event) {
          if (isDraggingG) {
            let deltaXX = event.clientX - lastXX;
            let deltaYY = event.clientY - lastYY;
            let newXX = imagee.offsetLeft + deltaXX;
            let newYY = imagee.offsetTop + deltaYY;
            imagee.style.left = newXX + 'px';
            imagee.style.top = newYY + 'px';
            lastXX = event.clientX;
            lastYY = event.clientY;
          }
        });

        document.addEventListener('mouseup', function(event) {
          if (event.button === 0) {
            isDraggingG = false;
            imagee.style.cursor = 'grab';
          }
        });

    // Pivoter avec la molette de la souris
      // document.addEventListener('wheel', function(event) {
      //   if (event.deltaYY < 0) {
      //     rotationAnglee += 10;
      //   } else {
      //     rotationAnglee -= 10;
      //   }
      //   imagee.style.transform = `rotate(${rotationAnglee}deg)`;
      // });


             // Fonction pour mettre à jour la transformation CSS de la photo
        function updateTransform2() {
          photo2.style.transform = `scale(${scalee}) translate(${translateXX}px, ${translateYY}px)`;
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
        //Proprietaire
             //Operations photo avec les boutons

        var scalePRO = 1; // Facteur de zoom initial
        var translateXPro = 0; // Décalage horizontal initial
        var translateYPro = 0; // Décalage vertical initial

        var photo_proprio = document.getElementById('imageproprio');

        // Fonction pour zoomer la photo
        function zoomIn_pro() {
          scalePRO += 0.1;
          updateTransformPRO();

        }

        // Fonction pour dézoomer la photo
        function zoomOut_pro() {
          scalePRO -= 0.1;
          updateTransformPRO();
        }

        // Fonction pour déplacer la photo horizontalement
        function moveX(direction) {
          translateXPro += direction * 50; // Changer la valeur de décalage
          updateTransformPRO();
        }

        // Fonction pour déplacer la photo verticalement
        function moveY(direction) {
          translateYPro += direction * 50; // Changer la valeur de décalage
          updateTransformPRO();
        }

        // Fonction pour mettre à jour la transformation CSS de la photo
        function updateTransformPRO() {
          photo_proprio.style.transform = `scale(${scalePRO}) translate(${translateXPro}px, ${translateYPro}px)`;
        }

      //Rotation de l'image

        function rotate_op_pro()
        {
          const image_pro = document.getElementById('imageproprio');
      // const rotateBtn = document.getElementById('rotate-btn');
          let rotationpro = Number($('#rotation').val());

      //rotateBtn.addEventListener('click', () => {
          rotationpro += 90;
          image_pro.style.transform = `rotate(${rotationpro}deg)`;
          $('#rotation').val(rotationpro)
      //});
        }


      </script>
      <script>
             //Operations photo avec la sourie

        let container_pro = document.getElementById('image-container_proprio');
        let image_pro = document.getElementById('imageproprio');
        let lastXPRO, lastYPRO;
        let isDraggingPRO = false;
        let rotationAnglePRO = 0;

    // Zoomer/dézoomer sur double clic
        document.getElementById('imageproprio').addEventListener('dblclick', function() {
          if (this.style.transform === "scale(2)") {
            this.style.transform = "scale(1)";
          } else {
            this.style.transform = "scale(2)";
          }
        });
    // Déplacer en maintenant le clic gauche
        image_pro.addEventListener('mousedown', function(event) {
          if (event.button === 0) {
            isDraggingPRO = true;
            lastXPRO = event.clientX;
            lastYPRO = event.clientY;
            image_pro.style.cursor = 'grabbing';
          }
        });

        document.addEventListener('mousemove', function(event) {
          if (isDraggingPRO) {
            let deltaX = event.clientX - lastXPRO;
            let deltaY = event.clientY - lastYPRO;
            let newX = image_pro.offsetLeft + deltaX;
            let newY = image_pro.offsetTop + deltaY;
            image_pro.style.left = newX + 'px';
            image_pro.style.top = newY + 'px';
            lastXPRO = event.clientX;
            lastYPRO = event.clientY;
          }
        });

        document.addEventListener('mouseup', function(event) {
          if (event.button === 0) {
            isDraggingPRO = false;
            image_pro.style.cursor = 'grab';
          }
        });

    


             // Fonction pour mettre à jour la transformation CSS de la photo
        function updateTransform() {
          photo_proprio.style.transform = `scale(${scalePRO}) translate(${translateXPro}px, ${translateYPro}px)`;
        }
      </script>
      <script>
    function show_imagechauff()
    {
      var phot_chof2 = $('#phot_chof2').val();
      var imgElement = document.getElementById("phot_chof");
      imgElement.src = phot_chof2;
      $('#Modal_photo_chof').modal('show');
    }
  </script>

      <script>
        //chauffeur
             //Operations photo avec les boutons

        var scalechof = 1; // Facteur de zoom initial
        var translateXchof = 0; // Décalage horizontal initial
        var translateYchof = 0; // Décalage vertical initial

        var image_choff = document.getElementById('phot_chof');

        // Fonction pour zoomer la photo
        function zoomIn_chof() {
          scalechof += 0.1;
          updateTransformchof();

        }

        // Fonction pour dézoomer la photo
        function zoomOut_chof() {
          scalechof -= 0.1;
          updateTransformchof();
        }

        // Fonction pour déplacer la photo horizontalement
        function moveX(direction) {
          translateXchof += direction * 50; // Changer la valeur de décalage
          updateTransformchof();
        }

        // Fonction pour déplacer la photo verticalement
        function moveY(direction) {
          translateYchof += direction * 50; // Changer la valeur de décalage
          updateTransformchof();
        }

        // Fonction pour mettre à jour la transformation CSS de la photo
        function updateTransformchof() {
          image_choff.style.transform = `scale(${scalechof}) translate(${translateXchof}px, ${translateYchof}px)`;
        }

      //Rotation de l'image

        function rotate_chof()
        {
          const image_choff = document.getElementById('phot_chof');
      // const rotateBtn = document.getElementById('rotate-btn');
          let rotationchof = Number($('#rotation').val());

      //rotateBtn.addEventListener('click', () => {
          rotationchof += 90;
          image_choff.style.transform = `rotate(${rotationchof}deg)`;
          $('#rotation').val(rotationchof)
      //});
        }
      </script>

      <script>
             //Operations photo avec la sourie

        let container = document.getElementById('image-container_chof');
        let image_choff = document.getElementById('phot_chof');
        let lastX, lastY;
        let isDragging = false;
        let rotationAngle = 0;

    // Zoomer/dézoomer sur double clic
        document.getElementById('phot_chof').addEventListener('dblclick', function() {
          if (this.style.transform === "scale(2)") {
            this.style.transform = "scale(1)";
          } else {
            this.style.transform = "scale(2)";
          }
        });
    // Déplacer en maintenant le clic gauche
        image_choff.addEventListener('mousedown', function(event) {
          if (event.button === 0) {
            isDragging = true;
            lastX = event.clientX;
            lastY = event.clientY;
            image_choff.style.cursor = 'grabbing';
          }
        });

        document.addEventListener('mousemove', function(event) {
          if (isDragging) {
            let deltaX = event.clientX - lastX;
            let deltaY = event.clientY - lastY;
            let newX = image.offsetLeft + deltaX;
            let newY = image.offsetTop + deltaY;
            image_choff.style.left = newX + 'px';
            image_choff.style.top = newY + 'px';
            lastX = event.clientX;
            lastY = event.clientY;
          }
        });

        document.addEventListener('mouseup', function(event) {
          if (event.button === 0) {
            isDragging = false;
            image_choff.style.cursor = 'grab';
          }
        });

    // Pivoter avec la molette de la souris
        document.addEventListener('wheel', function(event) {
          if (event.deltaY < 0) {
            rotationAngle += 10;
          } else {
            rotationAngle -= 10;
          }
          image_choff.style.transform = `rotate(${rotationAngle}deg)`;
        });


        // Fonction pour mettre à jour la transformation CSS de la photo
        function updateTransform() {
          image_choff.style.transform = `scale(${scale}) translate(${translateX}px, ${translateY}px)`;
        }
      </script>

      </html>