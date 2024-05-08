<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>
  <style>
    .profile .profile-card img {
      max-width: 290px;
    }

    #image-container{
      position: relative;
      left:10px;
      width: 770px; 
      height: 700px;
      overflow: hidden;
    }

    #photo_profile2 {
      position: relative;
      cursor: grab;
      transition: transform 0.2s;
      border-radius: 50%;

      width: 103%;
      height: 100%;
      margin-left: -12px;
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

                <li class="nav-item">
                  <button class="nav-link <?php if($VEHICULE_PRO == " "){echo "active";}else{echo "";}?>" data-bs-toggle="tab" data-bs-target="#info_generales">Informations générales</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link " data-bs-toggle="tab" data-bs-target="#doc_uploader">Documents</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link <?php if($VEHICULE_PRO !=" "){echo "active";}else{echo "";}?>" data-bs-toggle="tab" data-bs-target="#voitures">Véhicules<span class="badge bg-primary rounded-pill nbr_vehicule" style="font-size:10px;position:relative;top:-10px;left:-2px;">4</span></button>
                </li>

              </ul>

              <div class="tab-content pt-2">

               <div class="tab-pane fade  <?php if($VEHICULE_PRO == " "){echo " show active";}else{echo "";}?> profile-overview" id="info_generales">

                <div class="row">

                  <div class="col-xl-4">

                    <!-- <div class="card"> -->
                      <div class="card-body profile-card pt-2 d-flex flex-column align-items-center">

                        <?php
                        if(!empty($proprietaire['PHOTO_PASSPORT']) && $proprietaire['TYPE_PROPRIETAIRE_ID']==2)
                        {
                          ?>
                          <img style='border-radius: 50%;height:165px;width: 165px;cursor: pointer;' src="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['PHOTO_PASSPORT'])?>" onclick="show_photo_profile();">

                          <input type="hidden" id="photo_profile" value="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['PHOTO_PASSPORT'])?>">
                          <?php
                        }
                        else if(empty($proprietaire['PHOTO_PASSPORT']) && $proprietaire['TYPE_PROPRIETAIRE_ID']==2)
                        {
                          ?>
                          <img  style='border-radius: 50%;height:165px;width: 165px;cursor: pointer;' class="img-fluid" width="65px" height="auto" src="<?=base_url('upload/img_agent/phavatar.png')?>" onclick="show_photo_profile();">

                          <input type="hidden" id="photo_profile" value="<?=base_url('upload/img_agent/phavatar.png')?>">
                          <?php
                        }else if($proprietaire['TYPE_PROPRIETAIRE_ID']==1 && empty($proprietaire['LOGO']))
                        {?>

                          <span style="font-size:109px;" class="bi bi-bank"></span>

                        <?php }elseif ($proprietaire['TYPE_PROPRIETAIRE_ID']==1 && !empty($proprietaire['LOGO'])) 
                        {?>
                          <img  style='border-radius: 50%;height:170px;width: 170px;cursor: pointer;'  src="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['LOGO'])?>" onclick="show_photo_profile();">

                          <input type="hidden" id="photo_profile" value="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['LOGO'])?>">

                        <?php }
                        ?>
                        <!-- <h2><?=$proprietaire['NOM_PROPRIETAIRE'].' '. $proprietaire['PRENOM_PROPRIETAIRE']?></h2> -->

                        <strong class="text-muted"><?=$proprietaire['NOM_PROPRIETAIRE'].' '. $proprietaire['PRENOM_PROPRIETAIRE']?></strong>

                      </div>
                      <!-- </div> -->

                    </div>


                    <div class="col-xl-8">

                      <tr>
                          <td>
                          <input type="hidden" name="PROPRIETAIRE_ID" id="PROPRIETAIRE_ID" value="<?=$proprietaire['proprietaire_ID']?>">

                          <input type="hidden" name="EMAIL" id="EMAIL" value="<?=$proprietaire['EMAIL']?>">

                          <input type="hidden" name="TELEPHONE" id="TELEPHONE" value="<?=$proprietaire['TELEPHONE']?>">
                        </td>
                        </tr>

                        <tr>
                          <td>
                          <input type="hidden" name="CNI_OU_NIF" id="CNI_OU_NIF" value="<?=$proprietaire['CNI_OU_NIF']?>">
                          
                          <input type="hidden" name="RC" id="RC" value="<?=$proprietaire['RC']?>">

                          <input type="hidden" name="DESC_CATEGORIE" id="DESC_CATEGORIE" value="<?=$proprietaire['DESC_CATEGORIE']?>">
                        </td>
                        </tr>

                        <tr>
                          <td>
                          <input type="hidden" name="ADRESSE" id="ADRESSE" value="<?=$proprietaire['ADRESSE']?>">
                          
                          <input type="hidden" name="PROVINCE_ID" id="PROVINCE_ID" value="<?=$proprietaire['PROVINCE_ID']?>">
                          
                          <input type="hidden" name="COMMUNE_ID" id="COMMUNE_ID" value="<?=$proprietaire['COMMUNE_ID']?>">
                        </td>
                        </tr>

                        <tr>
                          <td>
                          <input type="hidden" name="ZONE_ID" id="ZONE_ID" value="<?=$proprietaire['ZONE_ID']?>">
                          
                          <input type="hidden" name="COLLINE_ID" id="COLLINE_ID" value="<?=$proprietaire['COLLINE_ID']?>">
                        </td>
                        </tr>

                      <table class="table table-borderless">

                        <tbody style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                          <?php 
                          if ($proprietaire['TYPE_PROPRIETAIRE_ID'] == 1)
                          {
                            ?>
                            <tr>
                              <td class="text-muted"><span class="fa fa-envelope-o"></span>&nbsp;&nbsp;E-mail</td>
                              <td class="text-muted"><b><?=$proprietaire['EMAIL']?></b></td>
                              <td class="text-muted"><font class="btn btn-outline-primary rounded-pill fa fa-edit" ></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-phone"></span>&nbsp;&nbsp;Téléphone</td>
                              <td class="text-muted"><b><?=$proprietaire['TELEPHONE']?></b></td>
                              <td class="text-muted"><font class="btn btn-outline-primary rounded-pill fa fa-edit" style="font-size:10px;"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-book"></span>&nbsp;&nbsp;<?=$label_cni?></td>
                              <td class="text-muted"><b><?=$proprietaire['CNI_OU_NIF']?></b></td>
                              <td class="text-muted"><font class="btn btn-outline-primary rounded-pill fa fa-edit" style="font-size:10px;"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-newspaper-o"></span>&nbsp;&nbsp;RC</td>
                              <td class="text-muted"><b><?=$proprietaire['RC']?></b></td>
                              <td class="text-muted"><font class="btn btn-outline-primary rounded-pill fa fa-edit" style="font-size:10px;"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-newspaper-o"></span>&nbsp;&nbsp;Catégorie</td>
                              <td class="text-muted"><b><?=$proprietaire['DESC_CATEGORIE']?></b></td>
                              <td class="text-muted"><font class="btn btn-outline-primary rounded-pill fa fa-edit" style="font-size:10px;"></font></td>
                            </tr>
                            <?php
                          }
                          else
                          {
                            ?>

                            <tr>
                              <td class="text-muted"><span class="fa fa-envelope-o"></span>&nbsp;&nbsp;E-mail</td>
                              <td class="text-muted"><b><?=$proprietaire['EMAIL']?></b></td>
                              <td class="text-muted"><font class="btn btn-outline-primary rounded-pill fa fa-edit" style="font-size:10px;"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-phone"></span>&nbsp;&nbsp;Téléphone</td>
                              <td class="text-muted"><b><?=$proprietaire['TELEPHONE']?></b></td>
                              <td class="text-muted"><font class="btn btn-outline-primary rounded-pill fa fa-edit" style="font-size:10px;"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-book"></span>&nbsp;&nbsp;<?=$label_cni?></td>
                              <td class="text-muted"><b><?=$proprietaire['CNI_OU_NIF']?></b></td>
                              <td class="text-muted"><font class="btn btn-outline-primary rounded-pill fa fa-edit" style="font-size:10px;"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-map-marker"></span>&nbsp;&nbsp;Addresse</td>
                              <td class="text-muted"><b><?=$proprietaire['ADRESSE']?></b></td>
                              <td class="text-muted"><font class="btn btn-outline-primary rounded-pill fa fa-edit" style="font-size:10px;"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-map-marker"></span> Localité</td>
                              <td class="text-muted"><b><?=$proprietaire['PROVINCE_NAME'].' / '.$proprietaire['COMMUNE_NAME'].' / '.$proprietaire['ZONE_NAME'].' / '.$proprietaire['COLLINE_NAME']?></b></td>
                              <td class="text-muted"><font class="btn btn-outline-primary rounded-pill fa fa-edit" style="font-size:10px;"></font></td>
                            </tr>
                            <?php
                          }
                          ?>
                          
                        </tbody>

                        


                        
                      </table>

                    </div>




                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <a style="float:right;font-size: 10px;" class="btn btn-outline-primary rounded-pill" href="<?=base_url('administration/Users/getOne/'.$this->uri->segment(4))?>"><font class="fa fa-edit"></font> Modifier plusieurs</a>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade pt-3  profile-overview" id="doc_uploader">

                  <div class="row">
                    <div class="col-xl-4">

                      <table class="table table-borderless">
                       <tr>

                        <?php
                        if ($proprietaire['TYPE_PROPRIETAIRE_ID'] == 2)
                        {
                          if(!empty($proprietaire['FILE_CNI_PASSPORT']))
                          {
                            $extension = pathinfo($proprietaire['FILE_CNI_PASSPORT'], PATHINFO_EXTENSION);
                            ?>

                            <td class="text-center">

                              <input type="hidden" id="ext_permis" value="<?=$extension?>">

                              <font class="card dash_card" onclick="get_document(1,$('#ext_permis').val());">
                                <i class="small pt-2 ps-1 <?php if($extension == 'pdf'){echo "fa fa-file-pdf-o text-danger";}else{echo "fa fa-file-photo-o text-primary";}?>" style="font-size: 30px;margin-top: 5px;"></i><br>
                                <font class="text-muted small pt-2 ps-1 dash_v" style="margin-top: -20px;margin-bottom: 10px;">Carte d'identité</font>
                              </font>

                            </td>

                            <?php
                          }
                          else{
                            echo '<td class="text-center"><font class="text-danger">Document carte d\'identité introuvable !</font></td>';
                          }
                        }
                        else
                        {
                          if(!empty($proprietaire['FILE_NIF']))
                          {
                            $extension = pathinfo($proprietaire['FILE_NIF'], PATHINFO_EXTENSION);
                            ?>

                            <td class="text-center">

                              <input type="hidden" id="ext_permis" value="<?=$extension?>">

                              <font class="card dash_card" onclick="get_document(1,$('#ext_permis').val());">
                                <i class="small pt-2 ps-1 <?php if($extension == 'pdf'){echo "fa fa-file-pdf-o text-danger";}else{echo "fa fa-file-photo-o text-primary";}?>" style="font-size: 30px;margin-top: 5px;"></i><br>
                                <font class="text-muted small pt-2 ps-1 dash_v" style="margin-top: -20px;margin-bottom: 10px;">NIF</font>
                              </font>

                            </td>

                            <?php
                          }
                          else{
                            echo '<td class="text-center"><font class="text-danger">Document NIF introuvable !</font></td>';
                          }
                          if(!empty($proprietaire['FILE_RC']))
                          {
                            $extension = pathinfo($proprietaire['FILE_RC'], PATHINFO_EXTENSION);
                            ?>

                            <td class="text-center">

                              <input type="hidden" id="ext_permis" value="<?=$extension?>">

                              <font class="card dash_card" onclick="get_document(1,$('#ext_permis').val());">
                                <i class="small pt-2 ps-1 <?php if($extension == 'pdf'){echo "fa fa-file-pdf-o text-danger";}else{echo "fa fa-file-photo-o text-primary";}?>" style="font-size: 30px;margin-top: 5px;"></i><br>
                                <font class="text-muted small pt-2 ps-1 dash_v" style="margin-top: -20px;margin-bottom: 10px;">RC</font>
                              </font>

                            </td>

                            <?php
                          }
                          else{
                            echo '<td class="text-center"><font class="text-danger">Document RC introuvable !</font></td>';
                          }
                        }

                        ?>
                      </tr>
                    </table>


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
                        <th class="text-dark">DETAIL</th>
                        <th class="text-dark">CHAUFFEUR</th>
                        <th class="text-dark">LOCALISATION</th>

                        <!-- <th class="text-dark"></th> -->



                      </tr>
                    </thead>
                    <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
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


<!-- Modal photo du profil ou logo proprietaire -->

<div class="modal fade" id="Modal_photo_profile">
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
            <img src="" id="photo_profile2" alt="Description de l'image">
          </div>


        </div>

      </div>
      <!-- footer here -->
    </div>
  </div>
</div>
<!-- end modal -->
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

<script>
  //Affichage du popup pour la photo du profil ou logo
  function show_photo_profile()
  {
    var image_pop = $('#photo_profile').val();
    var imgElement = document.getElementById("photo_profile2");
    imgElement.src = image_pop;
    $('#Modal_photo_profile').modal('show');
  }
</script>

<script>
             //Operations photo profil/logo avec les boutons

        var scale = 1; // Facteur de zoom initial
        var translateX = 0; // Décalage horizontal initial
        var translateY = 0; // Décalage vertical initial

        var photo = document.getElementById('photo_profile2');

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
          const image = document.getElementById('photo_profile2');
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
             //Operations photo profil/logo avec la sourie

        let image = document.getElementById('photo_profile2');
        let lastX, lastY;
        let isDragging = false;
        let rotationAngle = 0;

    // Zoomer/dézoomer sur double clic
        document.getElementById('photo_profile2').addEventListener('dblclick', function() {
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

      </html>