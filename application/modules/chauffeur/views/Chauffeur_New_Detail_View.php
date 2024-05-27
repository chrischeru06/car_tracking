<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>
  <style>
    .profile .profile-card img 
    {
      max-width: 290px;
    }
   
    #image-container{
      position: relative;
      left:10px;
      width: 770px; 
      height: 700px;
      overflow: hidden;
    }

    #image-containerPermisCarte{
      position: relative;
      left:10px;
      width: 770px; 
      height: 510px;
      overflow: hidden;
    }

    #image_pop2 {
      position: relative;
      cursor: grab;
      transition: transform 0.2s;
      border-radius: 50%;

      width: 103%;
      height: 100%;
      margin-left: -12px;
    }

    #image-container_proprio{
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

    .dash_card:hover {
      color: cadetblue;
      background-color: rgba(95, 158, 160,0.3);
      cursor: pointer;
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
          <li class="breadcrumb-item"><a><font class="fa fa-dashboard" style="font-size:18px;"></font>  <?=lang('titre_detail_chauffeur')?> <strong class="text-primary"><?=$chauff['NOM'].' '. $chauff['PRENOM']?></strong></a></li>
          <input type="hidden" id="CHAUFFEUR_ID" name="CHAUFFEUR_ID" value="<?=$chauff['CHAUFFEUR_ID']?>">
        </ol>
      </nav>
    </div>

    <!-- <div class="col-md-6">

      <div class="justify-content-sm-end d-flex">
        <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('chauffeur/Chauffeur')?>"><i class="bi bi-plus"></i> Liste</a>
      </div>
    </div> -->
    <!-- End Page Title -->
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
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info_generales"><?=lang('btn_info_gnl')?></button>
                  </li>

                  <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#voitures"><?=lang('info_veh_titre')?></button>
                  </li>

                  <li class="nav-item">
                    <button class="nav-link " data-bs-toggle="tab" data-bs-target="#doc_uploader"><?=lang('btn_doc')?></button>
                  </li>
                  <?php
                }elseif($chauff['STATUT_VEHICULE']==1)
                {
                  ?>
                  <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info_generales"><?=lang('btn_info_gnl')?></button>
                  </li>

                  <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#doc_uploader"><?=lang('btn_doc')?></button>
                  </li>


                  <?php
                }
                ?>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#historique"><?=lang('histo_titre')?></button>
                </li>

              </ul>
              <div class="tab-content pt-2">
                <div class="tab-pane fade show active profile-overview" id="info_generales">
                 <!-- <h5 class="card-title">A propos</h5> -->
                 
                 <div class="row">

                  <div class="col-xl-4">

                    <!-- <div class="card"> -->
                      <div class="card-body profile-card pt-2 d-flex flex-column align-items-center">

                        <?php
                        if(!empty($chauff['PHOTO_PASSPORT']))
                        {
                          ?>
                          <img style='border-radius: 50%;height:200px;width: 200px;cursor: pointer;' class="" onclick="show_imagechauff();" src='<?=base_url("upload/chauffeur/".$chauff['PHOTO_PASSPORT'])?>'>
                          <input type="hidden" id="image_pop" value="<?= base_url()?>/upload/chauffeur/<?= $chauff['PHOTO_PASSPORT']?>">
                          <?php
                        }
                        else if(empty($chauff['PHOTO_PASSPORT']))
                        {
                          ?>
                          <img style="border-radius: 50%;height: 200px;width: 200px; cursor:pointer;" class="img-fluid" width="65px" height="auto" src="<?=base_url('upload/img_agent/phavatar.png')?>">
                          <input type="hidden" id="image_pop" value="<?= base_url()?>/upload/img_agent/phavatar.png">
                          <?php
                        }
                        ?>
                        <strong class="text-muted"><?=$chauff['NOM'].' '. $chauff['PRENOM']?></strong>

                      </div>
                      <!-- </div> -->

                    </div>
                    <!-- <div class="col-xl-2">
                    </div> -->

                    <div class="col-xl-8 table-responsive" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">

                      <table class="table table-borderless">
                        <tr>
                          <td class="text-muted"><span class="fa fa-book"></span>&nbsp;&nbsp;<?=lang('mot_cni')?></td>
                          <td class="text-muted"><b><?=$chauff['NUMERO_CARTE_IDENTITE']?></b></td>
                        </tr>

                        <tr>
                          <td class="text-muted"><span class="fa fa-envelope-o"></span>&nbsp;&nbsp;<?=lang('input_email')?></td>
                          <td class="text-muted"><b><?=$chauff['ADRESSE_MAIL']?></b></td>
                        </tr>

                        <tr>
                          <td class="text-muted"><span class="fa fa-phone"></span>&nbsp;&nbsp;<?=lang('input_tlphone')?></td>
                          <td class="text-muted"><b><?=$chauff['NUMERO_TELEPHONE']?></b></td>
                        </tr>

                        <tr>
                          <td class="text-muted"><span class="fa fa-calendar"></span>&nbsp;&nbsp;<?=lang('date_naissance')?></td>
                          <td class="text-muted"><b><?=$chauff['DATE_NAISSANCE']?></b></td>
                        </tr>

                        <tr>
                          <td class="text-muted"><span class="fa fa-map-marker"></span>&nbsp;&nbsp;<?=lang('input_adresse')?></td>
                          <td class="text-muted"><b><?=$chauff['ADRESSE_PHYSIQUE']?></b></td>
                        </tr>

                        <tr>
                          <td class="text-muted"><span class="fa fa-map-marker"></span>&nbsp;&nbsp;<?=lang('td_localite')?></td>
                          <td class="text-muted"><b><?=$chauff['PROVINCE_NAME'].' / '.$chauff['COMMUNE_NAME'].' / '.$chauff['ZONE_NAME'].' / '.$chauff['COLLINE_NAME']?></b></td>
                        </tr>

                        
                      </table>

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
                            <td><span class="fa fa-bookmark-o"></span>&nbsp;<?=lang('label_marque')?></td>
                            <th><?=$info_vehicul['DESC_MARQUE']?></th>
                          </tr>
                          <tr>
                            <td><span class="fa fa-bolt"></span>&nbsp;<?=lang('label_modele')?></td>
                            <th><?=$info_vehicul['DESC_MODELE']?></th>
                          </tr> 
                          <tr>
                            <td><span class="fa fa-circle"></span>&nbsp;<?=lang('label_couleur')?></td>
                            <th><?=$info_vehicul['COULEUR']?></th>
                          </tr>
                          <tr>
                            <td><span class="fa fa-calendar"></span>&nbsp;<?=lang('label_plaque')?></td>
                            <th><?=$info_vehicul['PLAQUE']?></th>
                          </tr>
                          <tr>
                            <td><img src='<?=base_url("upload/proprietaire/photopassport/".$info_vehicul['PHOTO_PASSPORT'])?>' style="width: 15px;height: 15px;border-radius: 50%;cursor: pointer;" onclick='show_image_proprio();' title="Propriétaire">&nbsp;<?=lang('title_proprio_list')?></td>
                            <th><?=$info_vehicul['name']?></th>
                          </tr>
                        </table>
                      </div>
                    <?php }?>
                  </div>
                </div>

                <div class="tab-pane fade pt-3" id="doc_uploader">
                  <div class="col-md-4">


                    <table class="table table-borderless">
                     <tr>

                      <?php
                      if(!empty($chauff['FILE_PERMIS']))
                      {
                        $extension = pathinfo($chauff['FILE_PERMIS'], PATHINFO_EXTENSION);
                        ?>

                        <td class="text-center">

                          <input type="hidden" id="ext_permis" value="<?=$extension?>">

                          <font class="card dash_card" onclick="get_document(1,$('#ext_permis').val());">
                            <i class="small pt-2 ps-1 <?php if($extension == 'pdf'){echo "fa fa-file-pdf-o text-danger";}else{echo "fa fa-file-photo-o text-primary";}?>" style="font-size: 30px;margin-top: 5px;"></i><br>
                            <font class="text-muted small pt-2 ps-1 dash_v" style="margin-top: -20px;margin-bottom: 10px;"><?=lang('mot_permis_conduire')?></font>
                          </font>

                        </td>

                        <?php


                      }

                      if(!empty($chauff['FILE_CARTE_IDENTITE']))
                      {
                        $extension = pathinfo($chauff['FILE_CARTE_IDENTITE'], PATHINFO_EXTENSION);
                        ?>
                        <td class="text-center">

                          <input type="hidden" id="ext_carte_id" value="<?=$extension?>">

                          <font class="card dash_card" onclick="get_document(2,$('#ext_carte_id').val());">
                            <i class="small pt-2 ps-1 <?php if($extension == 'pdf'){echo "fa fa-file-pdf-o text-danger";}else{echo "fa fa-file-photo-o text-primary";}?>" style="font-size: 30px;margin-top: 5px;"></i><br>
                            <font class="text-muted small pt-2 ps-1 dash_v" style="margin-top: -20px;margin-bottom: 10px;"><?=lang('mot_ident_carte')?></font>
                          </font>
                        </td>

                        <?php
                      }

                      ?>
                    </tr>
                  </table>


                </div>
              </div>

              <div class="tab-pane fade pt-3" id="historique">
               <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-12">
                  <div class="row">


                    <!-- Reports -->
                    <div class="col-12">
                      <div class="card">


                        <div class="card-body">

                          <div class="table-responsive" style="padding-top: 20px;">

                            <table id="mytable" class="table table-hover" style="padding-top: 20px;width:100%;">
                             <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                              <tr>

                                <th class="text-dark">#</th>
                                <th class="text-dark"><?=lang('th_plaque')?></th>
                                <th class="text-dark"><?=lang('th_marque')?>/<?=lang('th_modele')?></th>
                                <th class="text-dark"><?=lang('th_proprio')?></th>
                                <th class="text-dark"><?=lang('dbut_affectation')?></th>
                                <th class="text-dark"><?=lang('fin_affectation')?></th>
                                <th class="text-dark"><?=lang('resum_parcours')?></th>

                              </tr>
                            </thead>

                            <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                            </tbody>

                          </table>
                        </div>

                      </div>

                    </div>
                  </div>



                </div>
              </div>



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
            <img src="" id="image_pop2" alt="Description de l'image">
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
        <h5 class="modal-title"><?=lang('btn_detail')?></h5>
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
                  <td><span class="fa fa-user"></span> &nbsp;&nbsp; <?=lang('td_nom_prenom')?></td>
                  <td><a id="NOM"></a></td>
                </tr>

                <tr>
                  <td><span class="fa fa-user-plus"></span> &nbsp;&nbsp; <?=lang('date_naissance')?></td>
                  <td><a id="DATE_NAISSANCE"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-newspaper-o"></span> &nbsp;&nbsp; <?=lang('input_tlphone')?></td>
                  <td><a id="NUMERO_TELEPHONE"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-phone"></span> &nbsp;&nbsp; <?=lang('input_email')?></td>
                  <td><a id="ADRESSE_MAIL"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-envelope-o"></span> &nbsp;&nbsp; <?=lang('input_cni_passeport')?></td>
                  <td><a id="NUMERO_CARTE_IDENTITE"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-bank"></span> &nbsp;&nbsp; <?=lang('input_adresse')?></td>
                  <td><a id="ADRESSE_PHYSIQUE"></a></td>
                </tr>


              </table>
            </div>
          </div>



        </div>

      </div>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div> -->
    </div>
  </div>
</div><!-- End Modal-->


<!-- </div>
</div>
</div> 

</div>
</div>
</div> --> 
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
            <center><img  id="imageproprio" style="border-radius: 5px;height: 700px;cursor: pointer;" alt="<?=lang('descr_img')?>" src='<?=base_url("upload/proprietaire/photopassport/".$info_vehicul['PHOTO_PASSPORT'])?>'></center>
          </div>

        </div>

      </div>
      <!-- footer here -->
    </div>
  </div>
</div>
<!-- fin-->


<!-- Modal permis doc-->

<div class="modal fade" id="Modal_permis_doc">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style='background:cadetblue;color:white;'>
        <h6 class="modal-title"></h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
      </div>
      <div class="modal-body">


        <div class="row text-center" style="background-color:rgba(230,230,200,0.3);margin-top:-10px;border-radius:50%;" id="div_op_image_permis">

          <div class="col-md-4">

          </div>

          <div class="col-md-1">
            <i onclick="zoomInPermis()" class="fa fa-plus-circle text-muted"></i>

            <input type="hidden" id="rotationPermis" value="0">
          </div>

          <div class="col-md-1">
            <i onclick="zoomOutPermis()" class="fa fa-minus-circle text-muted"></i>
          </div>
          <div class="col-md-1">
            <i onclick="rotate_opPermis()" class="fa fa-rotate-right text-muted"></i>
          </div>


        </div>


        <div class="row text-center" style="background-color:rgba(230,230,200,0.3);margin-top:-10px;border-radius:50%;" id="div_op_image_carte">

          <div class="col-md-4">

          </div>

          <div class="col-md-1">
            <i onclick="zoomInCarte()" class="fa fa-plus-circle text-muted"></i>

            <input type="hidden" id="rotationCarte" value="0">
          </div>

          <div class="col-md-1">
            <i onclick="zoomOutCarte()" class="fa fa-minus-circle text-muted"></i>
          </div>
          <div class="col-md-1">
            <i onclick="rotate_opCarte()" class="fa fa-rotate-right text-muted"></i>
          </div>


        </div>

        <div class="row">

          <div class="col-md-12" id="image-containerPermisCarte">


            <div id="div_permis">
              <input type="hidden" id="file_permis" value="<?=base_url("upload/chauffeur/".$chauff['FILE_PERMIS'])?>">

              <embed id="file_permis2" src="" #toolbar=0 scrolling="auto" height="500px" width="100%" frameborder="0"></embed>
            </div>

            <div id="div_carte_id">
              <input type="hidden" id="file_carte_id" value="<?=base_url("upload/chauffeur/".$chauff['FILE_CARTE_IDENTITE'])?>">

              <embed id="file_carte_id2" src="" #toolbar=0 scrolling="auto" height="500px" width="100%" frameborder="0"></embed>
            </div>

          </div>


        </div>

      </div>
      <!-- footer here -->
    </div>
  </div>
</div>

<script >
  $(document).ready(function ()
  {
   liste();
 });

  function liste()
  {

    var CHAUFFEUR_ID = $('#CHAUFFEUR_ID').val();

    // alert(CHAUFFEUR_ID)
    var row_count = 10000;
    $('#message').delay('slow').fadeOut(3000);
    $("#mytable").DataTable(
    {
      "destroy": true,
      "processing": true,
      "serverSide": true,
      "oreder": [],
      "ajax":
      {
        url: "<?php echo base_url('chauffeur/Chauffeur_New/hist_chauff'); ?>",
        type: "POST",
        data: {CHAUFFEUR_ID:CHAUFFEUR_ID},
        beforeSend: function()
        {
        }
      },
      // lengthMenu:
      // [
      //   [10, 50, 100, row_count],
      //   [10, 50, 100, "All"]
      //   ],
      pageLength: 10,
      "columnDefs": [
      {
        "targets": [],
        "orderable": false
      }],
      dom: 'Bfrtlip',
      buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
      language:
      {
        "sProcessing": "Traitement en cours...",
        "sSearch": "Recherche&nbsp;:",
        "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
        "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
        "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
        "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        "sInfoPostFix": "",
        "sLoadingRecords": "Chargement en cours...",
        "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
        "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
        "oPaginate":
        {
          "sFirst": "Premier",
          "sPrevious": "Pr&eacute;c&eacute;dent",
          "sNext": "Suivant",
          "sLast": "Dernier"
        },
        "oAria":
        {
          "sSortAscending": ": activer pour trier la colonne par ordre croissant",
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
  function show_imagechauff()
  {
    var image_pop = $('#image_pop').val();
    var imgElement = document.getElementById("image_pop2");
    imgElement.src = image_pop;
    $('#Modal_photo_chof').modal('show');
  }
</script>


<script type="">
  function get_document(id,extention)
  {
    // var ext_permis = $('#ext_permis').val();
    // var ext_carte_id = $('#ext_carte_id').val();


    if(id == 1)
    {
      $('#div_permis').show();
      $('#div_carte_id').hide();

      var file_permis = $('#file_permis').val();
      var imgElement = document.getElementById("file_permis2");
      imgElement.src = file_permis;

      if(extention == 'pdf')
      {
        $('#div_op_image_permis').hide();
        $('#div_op_image_carte').hide();
      }
      else{
        $('#div_op_image_permis').show();
        $('#div_op_image_carte').hide();
      }

      //alert(extention)
    }
    else if(id == 2)
    {
      $('#div_permis').hide();
      $('#div_carte_id').show();

      var file_carte_id = $('#file_carte_id').val();
      var imgElement2 = document.getElementById("file_carte_id2");
      imgElement2.src = file_carte_id;

      if(extention == 'pdf')
      {
        $('#div_op_image_permis').hide();
        $('#div_op_image_carte').hide();
      }
      else{
        $('#div_op_image_permis').hide();
        $('#div_op_image_carte').show();
      }

      //alert(extention)
    }


    $('#Modal_permis_doc').modal('show');
  }
</script>


<script>
             //Operations photo avec les boutons

        var scale = 1; // Facteur de zoom initial
        var translateX = 0; // Décalage horizontal initial
        var translateY = 0; // Décalage vertical initial

        var photo = document.getElementById('image_pop2');

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
          const image = document.getElementById('image_pop2');
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

        let image = document.getElementById('image_pop2');
        let lastX, lastY;
        let isDragging = false;
        let rotationAngle = 0;

    // Zoomer/dézoomer sur double clic
        document.getElementById('image_pop2').addEventListener('dblclick', function() {
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
        document.addEventListener('wheel', function(event) {
          if (event.deltaY < 0) {
            rotationAngle += 10;
          } else {
            rotationAngle -= 10;
          }
          image.style.transform = `rotate(${rotationAngle}deg)`;
        });

      </script>



      <script>


       //Operations photo permis avec les boutons

        var scalePermis = 1; // Facteur de zoom initial
        var translateXPermis = 0; // Décalage horizontal initial
        var translateYPermis = 0; // Décalage vertical initial

        var photoPermis = document.getElementById('file_permis2');

        // Fonction pour zoomer la photo
        function zoomInPermis() {
          scalePermis += 0.1;
          updateTransformPermis();

        }

        // Fonction pour dézoomer la photo
        function zoomOutPermis() {
          scalePermis -= 0.1;
          updateTransformPermis();
        }

        // Fonction pour déplacer la photo horizontalement
        function moveXPermis(direction) {
          translateXPermis += direction * 50; // Changer la valeur de décalage
          updateTransformPermis();
        }

        // Fonction pour déplacer la photo verticalement
        function moveYPermis(direction) {
          translateYPermis += direction * 50; // Changer la valeur de décalage
          updateTransformPermis();
        }

        // Fonction pour mettre à jour la transformation CSS de la photo
        function updateTransformPermis() {
          photoPermis.style.transform = `scale(${scalePermis}) translate(${translateXPermis}px, ${translateYPermis}px)`;
        }

      //Rotation de l'image

        function rotate_opPermis()
        {
          const image = document.getElementById('file_permis2');
      // const rotateBtn = document.getElementById('rotate-btn');
          let rotation = Number($('#rotationPermis').val());

      //rotateBtn.addEventListener('click', () => {
          rotation += 90;
          image.style.transform = `rotate(${rotation}deg)`;
          $('#rotationPermis').val(rotation)
      //});
        }

      </script>



      <script>
             //Operations photo permis avec la sourie

        let image2 = document.getElementById('file_permis2');
        let lastX2, lastY2;
        let isDragging2 = false;
        let rotationAngle2 = 0;

    // Zoomer/dézoomer sur double clic
        document.getElementById('file_permis2').addEventListener('dblclick', function() {
          if (this.style.transform === "scale(2)") {
            this.style.transform = "scale(1)";
          } else {
            this.style.transform = "scale(2)";
          }
        });

    // Déplacer en maintenant le clic gauche
        image2.addEventListener('mousedown', function(event) {
          if (event.button === 0) {
            isDragging2 = true;
            lastX2 = event.clientX;
            lastY2 = event.clientY;
            image2.style.cursor = 'grabbing';
          }
        });

        document.addEventListener('mousemove', function(event) {
          if (isDragging2) {
            let deltaX2 = event.clientX - lastX2;
            let deltaY2 = event.clientY - lastY2;
            let newX2 = image2.offsetLeft + deltaX2;
            let newY2 = image2.offsetTop + deltaY2;
            image2.style.left = newX2 + 'px';
            image2.style.top = newY2 + 'px';
            lastX2 = event.clientX;
            lastY2 = event.clientY;
          }
        });

        document.addEventListener('mouseup', function(event) {
          if (event.button === 0) {
            isDragging2 = false;
            image2.style.cursor = 'grab';
          }
        });

    // Pivoter avec la molette de la souris
        document.addEventListener('wheel', function(event) {
          if (event.deltaY2 < 0) {
            rotationAngle2 += 10;
          } else {
            rotationAngle2 -= 10;
          }
          image2.style.transform = `rotate(${rotationAngle2}deg)`;
        });

      </script>


      <script>


       //Operations photo carte d'identite avec les boutons

        var scaleCarte = 1; // Facteur de zoom initial
        var translateXCarte = 0; // Décalage horizontal initial
        var translateYCarte = 0; // Décalage vertical initial

        var photoCarte = document.getElementById('file_carte_id2');

        // Fonction pour zoomer la photo
        function zoomInCarte() {
          scaleCarte += 0.1;
          updateTransformCarte();

        }

        // Fonction pour dézoomer la photo
        function zoomOutCarte() {
          scaleCarte -= 0.1;
          updateTransformCarte();
        }

        // Fonction pour déplacer la photo horizontalement
        function moveXCarte(direction) {
          translateXPermis += direction * 50; // Changer la valeur de décalage
          updateTransformCarte();
        }

        // Fonction pour déplacer la photo verticalement
        function moveYCarte(direction) {
          translateYCarte += direction * 50; // Changer la valeur de décalage
          updateTransformCarte();
        }

        // Fonction pour mettre à jour la transformation CSS de la photo
        function updateTransformCarte() {
          photoCarte.style.transform = `scale(${scaleCarte}) translate(${translateXCarte}px, ${translateYCarte}px)`;
        }

      //Rotation de l'image

        function rotate_opCarte()
        {
          const image = document.getElementById('file_carte_id2');
      // const rotateBtn = document.getElementById('rotate-btn');
          let rotation = Number($('#rotationCarte').val());

      //rotateBtn.addEventListener('click', () => {
          rotation += 90;
          image.style.transform = `rotate(${rotation}deg)`;
          $('#rotationCarte').val(rotation)
      //});
        }

      </script>



      <script>
             //Operations photo carte d'identite avec la sourie

        let image3 = document.getElementById('file_carte_id2');
        let lastX3, lastY3;
        let isDragging3 = false;
        let rotationAngle3 = 0;

    // Zoomer/dézoomer sur double clic
        document.getElementById('file_carte_id2').addEventListener('dblclick', function() {
          if (this.style.transform === "scale(2)") {
            this.style.transform = "scale(1)";
          } else {
            this.style.transform = "scale(2)";
          }
        });

    // Déplacer en maintenant le clic gauche
        image3.addEventListener('mousedown', function(event) {
          if (event.button === 0) {
            isDragging3 = true;
            lastX3 = event.clientX;
            lastY3 = event.clientY;
            image3.style.cursor = 'grabbing';
          }
        });

        document.addEventListener('mousemove', function(event) {
          if (isDragging3) {
            let deltaX3 = event.clientX - lastX3;
            let deltaY3 = event.clientY - lastY3;
            let newX3 = image3.offsetLeft + deltaX3;
            let newY3 = image3.offsetTop + deltaY3;
            image3.style.left = newX3 + 'px';
            image3.style.top = newY3 + 'px';
            lastX3 = event.clientX;
            lastY3 = event.clientY;
          }
        });

        document.addEventListener('mouseup', function(event) {
          if (event.button === 0) {
            isDragging3 = false;
            image3.style.cursor = 'grab';
          }
        });

    // Pivoter avec la molette de la souris
        document.addEventListener('wheel', function(event) {
          if (event.deltaY3 < 0) {
            rotationAngle3 += 10;
          } else {
            rotationAngle3 -= 10;
          }
          image3.style.transform = `rotate(${rotationAngle3}deg)`;
        });

      </script>

      </html>