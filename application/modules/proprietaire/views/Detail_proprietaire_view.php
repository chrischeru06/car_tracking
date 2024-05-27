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

    #image-containerNifCarte{
      position: relative;
      left:10px;
      width: 770px; 
      height: 510px;
      overflow: hidden;
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
          <li class="breadcrumb-item"><i class="fa fa-dashboard"> </i> <a href="index.html"> <?= lang('btn_detail')?></a></li>

        </ol>
      </nav>
    </div>

    <div class="col-md-6">

      <div class="justify-content-sm-end d-flex">
        <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('proprietaire/Proprietaire/liste')?>"><i class="bi bi-plus"></i> <?=lang('title_list')?></a>
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
                  <button class="nav-link <?php if($VEHICULE_PRO == " "){echo "active";}else{echo "";}?>" data-bs-toggle="tab" data-bs-target="#info_generales"><?=lang('btn_info_gnl')?></button>
                </li>

                <li class="nav-item">
                  <button class="nav-link " data-bs-toggle="tab" data-bs-target="#doc_uploader"><?=lang('btn_doc')?></button>
                </li>

                <li class="nav-item">
                  <button class="nav-link <?php if($VEHICULE_PRO !=" "){echo "active";}else{echo "";}?>" data-bs-toggle="tab" data-bs-target="#voitures"><?=lang('btn_vehicules')?><span class="badge bg-primary rounded-pill nbr_vehicule" style="font-size:10px;position:relative;top:-10px;left:-2px;">4</span></button>
                </li>

              </ul>

              <div class="tab-content pt-2">

               <div class="tab-pane fade  <?php if($VEHICULE_PRO == " "){echo " show active";}else{echo "";}?> profile-overview" id="info_generales">

                <div class="row">

                  <div class="col-xl-4">

                    <!-- <div class="card"> -->
                      <div class="card-body profile-card pt-3 d-flex flex-column align-items-center">

                        <?php
                        if(!empty($proprietaire['PHOTO_PASSPORT']) && $proprietaire['TYPE_PROPRIETAIRE_ID']==2)
                        {
                          ?>
                          <img style='border-radius: 20px;height:200px;width: 200px;cursor: pointer;' src="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['PHOTO_PASSPORT'])?>" onclick="show_photo_profile();">

                          <input type="hidden" id="photo_profile" value="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['PHOTO_PASSPORT'])?>">
                          <?php
                        }
                        else if(empty($proprietaire['PHOTO_PASSPORT']) && $proprietaire['TYPE_PROPRIETAIRE_ID']==2)
                        {
                          ?>
                          <img  style='border-radius: 20px;height:200px;width: 200px;cursor: pointer;' class="img-fluid" width="65px" height="auto" src="<?=base_url('upload/img_agent/phavatar.png')?>" onclick="show_photo_profile();">

                          <input type="hidden" id="photo_profile" value="<?=base_url('upload/img_agent/phavatar.png')?>">
                          <?php
                        }else if($proprietaire['TYPE_PROPRIETAIRE_ID']==1 && empty($proprietaire['LOGO']))
                        {?>

                          <span style="font-size:110px;" class="bi bi-bank"></span>

                        <?php }elseif ($proprietaire['TYPE_PROPRIETAIRE_ID']==1 && !empty($proprietaire['LOGO'])) 
                        {?>
                          <img  style='border-radius: 20px;height:205px;width: 205px;cursor: pointer;'  src="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['LOGO'])?>" onclick="show_photo_profile();">

                          <input type="hidden" id="photo_profile" value="<?=base_url('/upload/proprietaire/photopassport/'.$proprietaire['LOGO'])?>">

                        <?php }
                        ?>
                        <!-- <h2><?=$proprietaire['NOM_PROPRIETAIRE'].' '. $proprietaire['PRENOM_PROPRIETAIRE']?></h2> -->

                        <!-- <strong class="text-muted"><?=$proprietaire['NOM_PROPRIETAIRE'].' '. $proprietaire['PRENOM_PROPRIETAIRE']?></strong> -->
                        <font class="bi bi-pencil" onclick="get_modif('PHOTO')"></font>

                      </div>
                      <!-- </div> -->

                    </div>


                    <div class="col-xl-8 table-responsive">

                      <tr>
                        <td>
                          <input type="hidden" name="TYPE_PRO" id="TYPE_PRO" value="<?=$proprietaire['TYPE_PROPRIETAIRE_ID']?>">

                          <input type="hidden" name="NOM" id="NOM" value="<?=$proprietaire['NOM_PROPRIETAIRE']?>">

                          <input type="hidden" name="PRENOM" id="PRENOM" value="<?=$proprietaire['PRENOM_PROPRIETAIRE']?>">
                        </td>
                      </tr>

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

                          <input type="hidden" name="CATEGORIE_ID" id="CATEGORIE_ID" value="<?=$proprietaire['CATEGORIE_ID']?>">
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
                          if ($proprietaire['TYPE_PROPRIETAIRE_ID'] == 1) //Personne morale
                          {
                            ?>
                            <tr>
                              <td class="text-muted"><span class="fa fa-user"></span>&nbsp;&nbsp;<?=lang('input_nom')?></td>
                              <td class="text-muted"><b>
                                <?=$proprietaire['NOM_PROPRIETAIRE']?></b>
                              </td>
                              <td class="text-muted"><font class="bi bi-pencil" onclick="get_modif('NOM')"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-envelope-o"></span>&nbsp;&nbsp;<?=lang('input_email')?></td>
                              <td class="text-muted"><b><?=$proprietaire['EMAIL']?></b></td>
                              <td class="text-muted"><font class="bi bi-pencil" onclick="get_modif('EMAIL')"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-phone"></span>&nbsp;&nbsp;<?=lang('input_tlphone')?></td>
                              <td class="text-muted"><b><?=$proprietaire['TELEPHONE']?></b></td>
                              <td class="text-muted"><font class="bi bi-pencil" onclick="get_modif('TELEPHONE')"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-book"></span>&nbsp;&nbsp;<?=$label_cni?></td>
                              <td class="text-muted"><b><?=$proprietaire['CNI_OU_NIF']?></b></td>
                              <td class="text-muted"><font class="bi bi-pencil" onclick="get_modif('CNI_OU_NIF')"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-newspaper-o"></span>&nbsp;&nbsp;<?=lang('input_rc')?></td>
                              <td class="text-muted"><b><?=$proprietaire['RC']?></b></td>
                              <td class="text-muted"><font class="bi bi-pencil" onclick="get_modif('RC')"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-newspaper-o"></span>&nbsp;&nbsp;<?=lang('input_categorie')?></td>
                              <td class="text-muted"><b><?=$proprietaire['DESC_CATEGORIE']?></b></td>
                              <td class="text-muted"><font class="bi bi-pencil" onclick="get_modif('CATEGORIE_ID')"></font></td>
                            </tr>
                            <?php
                          }
                          else //Personne physique
                          {
                            ?>

                            <tr>
                              <td class="text-muted"><span class="fa fa-user"></span>&nbsp;&nbsp;<?=lang('td_nom_prenom')?></td>
                              <td class="text-muted"><b>
                                <?=$proprietaire['NOM_PROPRIETAIRE'].' '.$proprietaire['PRENOM_PROPRIETAIRE']?></b>
                              </td>
                              <td class="text-muted"><font class="bi bi-pencil" onclick="get_modif('NOM')"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-envelope-o"></span>&nbsp;&nbsp;<?=lang('input_email')?></td>
                              <td class="text-muted"><b><?=$proprietaire['EMAIL']?></b></td>
                              <td class="text-muted"><font class="bi bi-pencil" onclick="get_modif('EMAIL')"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-phone"></span>&nbsp;&nbsp;<?=lang('input_tlphone')?></td>
                              <td class="text-muted"><b><?=$proprietaire['TELEPHONE']?></b></td>
                              <td class="text-muted"><font class="bi bi-pencil" onclick="get_modif('TELEPHONE')"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-book"></span>&nbsp;&nbsp;<?=$label_cni?></td>
                              <td class="text-muted"><b><?=$proprietaire['CNI_OU_NIF']?></b></td>
                              <td class="text-muted"><font class="bi bi-pencil" onclick="get_modif('CNI_OU_NIF')"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-map-marker"></span>&nbsp;&nbsp;<?=lang('input_adresse')?></td>
                              <td class="text-muted"><b><?=$proprietaire['ADRESSE']?></b></td>
                              <td class="text-muted"><font class="bi bi-pencil" onclick="get_modif('ADRESSE')"></font></td>
                            </tr>

                            <tr>
                              <td class="text-muted"><span class="fa fa-map-marker"></span> <?=lang('td_localite')?></td>
                              <td class="text-muted"><b><?=$proprietaire['PROVINCE_NAME'].' / '.$proprietaire['COMMUNE_NAME'].' / '.$proprietaire['ZONE_NAME'].' / '.$proprietaire['COLLINE_NAME']?></b></td>
                              <td class="text-muted"><font class="bi bi-pencil" onclick="get_modif('LOCALITE')"></font></td>
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
                      <a style="float:right;font-size: 12px;color: gray;" class=" " href="<?=base_url('proprietaire/Proprietaire/index/'.$this->uri->segment(4))?>"><font class="bi bi-pencil"></font> <?=lang('btn_modif_plus')?></a>
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

                              <input type="hidden" id="ext_file" value="<?=$extension?>">

                              <font class="card dash_card" onclick="get_document(2,$('#ext_file').val());">
                                <i class="small pt-2 ps-1 <?php if($extension == 'pdf'){echo "fa fa-file-pdf-o text-danger";}else{echo "fa fa-file-photo-o text-primary";}?>" style="font-size: 30px;margin-top: 5px;"></i><br>
                                <font class="text-muted small pt-2 ps-1 dash_v" style="margin-top: -20px;margin-bottom: 10px;"><?=lang('carte_identite')?></font>
                              </font>

                            </td>

                            <?php
                          }
                          else{
                            echo "<td class='text-center'><font class='text-danger'> ".lang('carte_identite_introuvable')."</font></td>";
                          }
                        } 
                        else
                        {
                          if(!empty($proprietaire['FILE_NIF']))
                          {
                            $extension = pathinfo($proprietaire['FILE_NIF'], PATHINFO_EXTENSION);
                            ?>

                            <td class="text-center">

                              <input type="hidden" id="ext_file2" value="<?=$extension?>">

                              <font class="card dash_card" onclick="get_document(1,$('#ext_file2').val());">
                                <i class="small pt-2 ps-1 <?php if($extension == 'pdf'){echo "fa fa-file-pdf-o text-danger";}else{echo "fa fa-file-photo-o text-primary";}?>" style="font-size: 30px;margin-top: 5px;"></i><br>
                                <font class="text-muted small pt-2 ps-1 dash_v" style="margin-top: -20px;margin-bottom: 10px;"><?=lang('input_nif')?></font>
                              </font>

                            </td>

                            <?php
                          }
                          else{
                            echo '<td class="text-center"><font class="text-danger">'.lang('nif_introuvable').'</font></td>';
                          }
                          if(!empty($proprietaire['FILE_RC']))
                          {
                            $extension = pathinfo($proprietaire['FILE_RC'], PATHINFO_EXTENSION);
                            ?>

                            <td class="text-center">

                              <input type="hidden" id="ext_file_3" value="<?=$extension?>">

                              <font class="card dash_card" onclick="get_document(3,$('#ext_file_3').val());">
                                <i class="small pt-2 ps-1 <?php if($extension == 'pdf'){echo "fa fa-file-pdf-o text-danger";}else{echo "fa fa-file-photo-o text-primary";}?>" style="font-size: 30px;margin-top: 5px;"></i><br>
                                <font class="text-muted small pt-2 ps-1 dash_v" style="margin-top: -20px;margin-bottom: 10px;">RC</font>
                              </font>

                            </td>

                            <?php
                          }
                          else{
                            echo '<td class="text-center"><font class="text-danger">'.lang('rc_introuvable').'</font></td>';
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
                        <th class="text-dark"><?=lang('th_marque')?></th>
                        <th class="text-dark"><?=lang('th_modele')?></th>
                        <th class="text-dark"><?=lang('th_couleur')?></th>
                        <th class="text-dark"><?=lang('th_plaque')?></th>
                        <th class="text-dark"><?=lang('th_photo')?></th>
                        <th class="text-dark"><?=lang('th_detail')?></th>
                        <th class="text-dark"><?=lang('th_chauffeur')?></th>
                        <!-- <th class="text-dark">LOCALISATION</th> -->

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
            <h5 class="modal-title"><?=lang('input_nif')?>  </h5>
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
            <h5 class="modal-title"><?=lang('input_rc')?>  </h5>
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
            <h5 class="modal-title"><?=lang('input_logo')?>  </h5>
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
<!-- end modal profil -->


<!--******** Debut Modal pour la modification d'element du proprietaire *********-->

<div class="modal fade" id="Modal_modif" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered  ">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'> 
        <!-- <h5 class="modal-title">Traiter la demande </h5> -->

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="modif_form" enctype="multipart/form-data" action="#" method="post">
          <div class="modal-body mb-1">
            <div class="row">
              <input type="hidden" name="PROPRIETAIRE_ID_modif" id="PROPRIETAIRE_ID_modif">
              <input type="hidden" name="champ" id="champ">
              <input type="hidden" name="TYPE_PRO_modif" id="TYPE_PRO_modif">

              <div id="div_modif_NOM_PRENOM">

                <div class="col-xl-12" id="div_modif_NOM">
                  <label for="description"><small><?=lang('input_nom')?></small><span  style="color:red;">*</span></label>
                  <input type="text" name="NOM_modif" id="NOM_modif" class="form-control">
                  <span id="errorNOM_modif" class="text-danger"></span>
                </div>

                <div class="col-xl-12" id="div_modif_PRENOM">
                  <label for="description"><small><?=lang('input_prenom')?></small><span  style="color:red;">*</span></label>
                  <input type="text" name="PRENOM_modif" id="PRENOM_modif" class="form-control">
                  <span id="errorPRENOM_modif" class="text-danger"></span>
                </div>

              </div>

              <div class="col-xl-12" id="div_modif_PHOTO">
                <label for="description"><small><?=lang('input_photo_passport')?> / <?=lang('input_logo')?></small><span  style="color:red;">*</span></label>
                <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="PHOTO_modif" autocomplete="off" id="PHOTO_modif"   class="form-control" title="<?=lang('title_file')?>">

                <span id="errorPHOTO_modif" class="text-danger"></span>
              </div>

              <div class="col-xl-12" id="div_modif_EMAIL">
                <label for="description"><small><?=lang('input_email')?></small><span  style="color:red;">*</span></label>
                <input type="text" name="EMAIL_modif" id="EMAIL_modif" class="form-control">
                <span id="errorEMAIL_modif" class="text-danger"></span>
              </div>


              <div class="col-xl-12" id="div_modif_TELEPHONE">
                <label for="description"><small><?=lang('input_tlphone')?></small><span  style="color:red;">*</span></label>
                <!-- <input type="text" name="TELEPHONE_modif" id="TELEPHONE_modif" class="form-control"> -->

                <input class="form-control bg-light" type='tel' name="TELEPHONE_modif" id="TELEPHONE_modif"  pattern="^[0-9-+\s()]*$"/>

                <span id="errorTELEPHONE_modif" class="text-danger"></span>
              </div>

              <div class="col-xl-12" id="div_modif_CNI_OU_NIF">
                <label for="description"><small><?=lang('input_nif_cni')?></small><span  style="color:red;">*</span></label>
                <input type="text" name="CNI_OU_NIF_modif" id="CNI_OU_NIF_modif" class="form-control">
                <span id="errorCNI_OU_NIF_modif" class="text-danger"></span>
              </div>

              <div class="col-xl-12" id="div_modif_RC">
                <label for="description"><small><?=lang('input_rc')?></small><span  style="color:red;">*</span></label>
                <input type="text" name="RC_modif" id="RC_modif" class="form-control">
                <span id="errorRC_modif" class="text-danger"></span>
              </div>

              <div class="col-xl-12" id="div_modif_CATEGORIE_ID">
                <label for="description"><small><?=lang('input_categorie')?></small><span  style="color:red;">*</span></label>
                <select name="CATEGORIE_ID_modif" id="CATEGORIE_ID_modif" class="form-control">
                </select>
                <span id="errorCATEGORIE_ID_modif" class="text-danger"></span>
              </div>


              <div class="col-xl-12" id="div_modif_ADRESSE">
                <label for="description"><small><?=lang('input_adresse')?></small><span  style="color:red;">*</span></label>
                <input type="text" name="ADRESSE_modif" id="ADRESSE_modif" class="form-control">
                <span id="errorADRESSE_modif" class="text-danger"></span>
              </div>

              <div id="div_modif_LOCALITE">

                <div class="col-xl-12">
                  <label for="description"><small><?=lang('input_province')?></small><span  style="color:red;">*</span></label>
                  <select name="PROVINCE_ID_modif" id="PROVINCE_ID_modif" class="form-control" onchange="get_communes();">
                    <option value="0">-- <?=lang('selectionner')?> --</option>
                  </select>
                  <span id="errorPROVINCE_ID_modif" class="text-danger"></span>
                </div>

                <div class="col-xl-12">
                  <label for="description"><small><?=lang('input_commune')?></small><span  style="color:red;">*</span></label>
                  <select name="COMMUNE_ID_modif" id="COMMUNE_ID_modif" class="form-control" onchange="get_zones();">
                    <option value="0">-- <?=lang('selectionner')?> --</option>
                  </select>
                  <span id="errorCOMMUNE_ID_modif" class="text-danger"></span>
                </div>

                <div class="col-xl-12">
                  <label for="description"><small><?=lang('input_zone')?></small><span  style="color:red;">*</span></label>
                  <select name="ZONE_ID_modif" id="ZONE_ID_modif" class="form-control" onchange="get_collines();">
                    <option value="0">-- <?=lang('selectionner')?> --</option>
                  </select>
                  <span id="errorZONE_ID_modif" class="text-danger"></span>
                </div>

                <div class="col-xl-12">
                  <label for="description"><small><?=lang('input_colline')?></small><span  style="color:red;">*</span></label>
                  <select name="COLLINE_ID_modif" id="COLLINE_ID_modif" class="form-control">
                    <option value="0">-- <?=lang('selectionner')?> --</option>
                  </select>
                  <span id="errorCOLLINE_ID_modif" class="text-danger"></span>
                </div>

              </div>


            </div>
          </div> 
          <div class="modal-footer">
            <input type="button"class="btn btn-outline-primary rounded-pill " type="button" id="btn_add" value="<?=lang('btn_modifier')?>" onclick="save();" />
            <!-- <input type="button" class="btn btn-light btn btn-outline-warning rounded-pill" data-bs-dismiss="modal" id="cancel" value="Annuler"/> -->

          </div>
        </form>
      </div>
    </div>
  </div>
</div><!-- End Modal modif-->


<!-- Modal permis doc-->

<div class="modal fade" id="Modal_file_doc">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style='background:cadetblue;color:white;'>
        <h6 class="modal-title"></h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
      </div>
      <div class="modal-body">


        <div class="row text-center" style="background-color:rgba(230,230,200,0.3);margin-top:-10px;border-radius:50%;" id="div_op_image_cni">

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


        <div class="row text-center" style="background-color:rgba(230,230,200,0.3);margin-top:-10px;border-radius:50%;" id="div_op_image_rc">

          <div class="col-md-4">

          </div>

          <div class="col-md-1">
            <i onclick="zoomInRC()" class="fa fa-plus-circle text-muted"></i>

            <input type="hidden" id="rotationRC" value="0">
          </div>

          <div class="col-md-1">
            <i onclick="zoomOutRC()" class="fa fa-minus-circle text-muted"></i>
          </div>
          <div class="col-md-1">
            <i onclick="rotate_opRC()" class="fa fa-rotate-right text-muted"></i>
          </div>


        </div>

        <div class="row">

          <div class="col-md-12" id="image-containerNifCarte">


            <div id="div_file_nif">
              <input type="hidden" id="file_nif" value="<?=base_url("upload/proprietaire/photopassport/".$proprietaire['FILE_NIF'])?>">

              <embed id="file_nif2" src="" #toolbar=0 scrolling="auto" height="500px" width="100%" frameborder="0"></embed>
            </div>

            <div id="div_carte_id">
              <input type="hidden" id="file_carte_id" value="<?=base_url("upload/proprietaire/piece_identite/".$proprietaire['FILE_CNI_PASSPORT'])?>">

              <embed id="file_carte_id2" src="" #toolbar=0 scrolling="auto" height="500px" width="100%" frameborder="0"></embed>
            </div>

            <div id="div_file_rc">
              <input type="hidden" id="file_rc" value="<?=base_url("upload/proprietaire/photopassport/".$proprietaire['FILE_RC'])?>">

              <embed id="file_rc2" src="" #toolbar=0 scrolling="auto" height="500px" width="100%" frameborder="0"></embed>
            </div>

          </div>


        </div>

      </div>
      <!-- footer here -->
    </div>
  </div>
</div>


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


      <script>
        //Fonction pour appel à la modification
        function get_modif(champ)
        {
          var PROPRIETAIRE_ID = $('#PROPRIETAIRE_ID').val();
          $('#PROPRIETAIRE_ID_modif').val(PROPRIETAIRE_ID);
          $('#champ').val(champ);

          var NOM = $('#NOM').val();
          var PRENOM = $('#PRENOM').val();
          var TYPE_PRO = $('#TYPE_PRO').val();
          var EMAIL = $('#EMAIL').val();
          var TELEPHONE = $('#TELEPHONE').val();
          var CNI_OU_NIF = $('#CNI_OU_NIF').val();
          var RC = $('#RC').val();
          var CATEGORIE_ID = $('#CATEGORIE_ID').val();
          var ADRESSE = $('#ADRESSE').val();

          var PROVINCE_ID = $('#PROVINCE_ID').val();
          var COMMUNE_ID = $('#COMMUNE_ID').val();
          var ZONE_ID = $('#ZONE_ID').val();
          var COLLINE_ID = $('#COLLINE_ID').val();

          $('#NOM_modif').val(NOM);
          $('#PRENOM_modif').val(PRENOM);
          $('#TYPE_PRO_modif').val(TYPE_PRO);
          $('#EMAIL_modif').val(EMAIL);
          $('#TELEPHONE_modif').val(TELEPHONE);
          $('#CNI_OU_NIF_modif').val(CNI_OU_NIF);
          $('#RC_modif').val(RC);
          $('#ADRESSE_modif').val(ADRESSE);

          $('#div_modif_EMAIL').hide();
          $('#div_modif_TELEPHONE').hide();
          $('#div_modif_CNI_OU_NIF').hide();
          $('#div_modif_RC').hide();
          $('#div_modif_CATEGORIE_ID').hide();
          $('#div_modif_ADRESSE').hide();
          $('#div_modif_LOCALITE').hide();
          $('#div_modif_NOM_PRENOM').hide();
          $('#div_modif_PRENOM').hide();
          $('#div_modif_PHOTO').hide();

          $.ajax(
          {
            url:"<?=base_url('proprietaire/Proprietaire/get_categorie_modif/')?>",
            type: "POST",
            data: {
              CATEGORIE_ID:CATEGORIE_ID,
              PROVINCE_ID:PROVINCE_ID,
              COMMUNE_ID:COMMUNE_ID,
              ZONE_ID:ZONE_ID,
              COLLINE_ID:COLLINE_ID,
            },
            dataType:"JSON",
            success: function(data)
            {
              $('#CATEGORIE_ID_modif').html(data.html_cat);
              $('#PROVINCE_ID_modif').html(data.html_prov);
              $('#COMMUNE_ID_modif').html(data.html_com);
              $('#ZONE_ID_modif').html(data.html_zon);
              $('#COLLINE_ID_modif').html(data.html_coll);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
              alert('Erreur');
            }
          });

          if(champ == 'NOM'){
            $('#div_modif_NOM_PRENOM').show();
            if(TYPE_PRO == 2){
              $('#div_modif_PRENOM').show();
            }
          }
          else if(champ == 'PHOTO'){
            $('#div_modif_PHOTO').show();
          }
          else if(champ == 'EMAIL'){
            $('#div_modif_EMAIL').show();
          }
          else if(champ == 'TELEPHONE'){
            $('#div_modif_TELEPHONE').show();
          }
          else if(champ == 'CNI_OU_NIF'){
            $('#div_modif_CNI_OU_NIF').show();
          }
          else if(champ == 'RC'){
            $('#div_modif_RC').show();
          }
          else if(champ == 'CATEGORIE_ID'){
            $('#div_modif_CATEGORIE_ID').show();
          }
          else if(champ == 'ADRESSE'){
            $('#div_modif_ADRESSE').show();
          }
          else if(champ == 'LOCALITE'){
            $('#div_modif_LOCALITE').show();
          }

          $('#Modal_modif').modal('show');

          //alert(PROPRIETAIRE_ID+' '+EMAIL)
        }
      </script>

      <script>
        //Fonction pour recuperer les communes selon la province
        function get_communes()
        {
          $('#COMMUNE_ID_modif').html('<option value="">-- Sélectionner --</option>');
          $('#ZONE_ID_modif').html('<option value="">-- Sélectionner --</option>');
          $('#COLLINE_ID_modif').html('<option value="">-- Sélectionner --</option>');

          $.ajax(
          {
            url:"<?=base_url('proprietaire/Proprietaire/get_communes/')?>"+$('#PROVINCE_ID_modif').val(),
            type: "GET",
            dataType:"JSON",
            success: function(data)
            {
              $('#COMMUNE_ID_modif').html(data);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
              alert('<?=lang('msg_erreur')?>');
            }
          });

        }
      </script>

      <script>
        // Fonction pour recuperer les zones selon la commune
        function get_zones()
        {
          $('#ZONE_ID_modif').html('<option value="">-- <?=lang('selectionner')?> --</option>');
          $('#COLLINE_ID_modif').html('<option value="">-- <?=lang('selectionner')?> --</option>');

          $.ajax(
          {
            url:"<?=base_url('proprietaire/Proprietaire/get_zones/')?>"+$('#COMMUNE_ID_modif').val(),
            type:"GET",
            dataType:"JSON",
            success: function(data)
            {
              $('#ZONE_ID_modif').html(data);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
              alert('<?=lang('msg_erreur')?>');
            }
          });

        }
      </script>


      <script>
        // Fonction pour recuperer les collines selon la zone
        function get_collines()
        {
          $('#COLLINE_ID_modif').html('<option value="">-- <?=lang('selectionner')?> --</option>');

          $.ajax(
          {
            url:"<?=base_url('proprietaire/Proprietaire/get_collines/')?>"+$('#ZONE_ID_modif').val(),
            type:"GET",
            dataType:"JSON",
            success: function(data)
            {
              $('#COLLINE_ID_modif').html(data);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
              alert('<?=lang('msg_erreur')?>');
            }
          });

        }
      </script>

      <script>
        //Require phone

        $('#TELEPHONE_modif').on('input change',function()
        {
          $(this).val($(this).val().replace(/[^0-9]*$/gi, ''));
          $(this).val($(this).val().replace(' ', ''));
          var subStr = this.value.substring(0,1);

          if(subStr != '+')
          {
            $('[name = "TELEPHONE_modif"]').val('+257');
          }

          if(this.value.substring(0,4)=="+257")
          {
            if($(this).val().length == 12)
            {
              $('#errorTELEPHONE_modif').text('');
            }
            else
            {
              $('#errorTELEPHONE_modif').text('<?=lang('tel_invalide')?>');
              if($(this).val().length > 12)
              {
                $(this).val(this.value.substring(0,12));
                $('#errorTELEPHONE_modif').text('');
              }
            }
          }
          else
          {
            if ($(this).val().length > 12)
            {
              $('#errorTELEPHONE_modif').text('');
            }
            else
            {
              $('#errorTELEPHONE_modif').text('<?=lang('tel_invalide')?>');
            }        
          }
        });
      </script>

      <script>
        //fonction pour l'enregistrement de la modification
        function save()
        {
          var champ = $('#champ').val();
          var NOM_modif = $('#NOM_modif').val();
          var PRENOM_modif = $('#PRENOM_modif').val();
          var EMAIL_modif = $('#EMAIL_modif').val();
          var TELEPHONE_modif = $('#TELEPHONE_modif').val();
          var CNI_OU_NIF_modif = $('#CNI_OU_NIF_modif').val();
          var RC_modif = $('#RC_modif').val();
          var CATEGORIE_ID_modif = $('#CATEGORIE_ID_modif').val();
          var ADRESSE_modif = $('#ADRESSE_modif').val();
          var PROVINCE_ID_modif = $('#PROVINCE_ID_modif').val();
          var COMMUNE_ID_modif = $('#COMMUNE_ID_modif').val();
          var ZONE_ID_modif = $('#ZONE_ID_modif').val();
          var COLLINE_ID_modif = $('#COLLINE_ID_modif').val();
          var PHOTO_modif = $('#PHOTO_modif').val();

          var TYPE_PRO_modif = $('#TYPE_PRO_modif').val();

          var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

          var statut = 1;

          if(champ == 'NOM' && TYPE_PRO_modif == 1)
          {
            if(NOM_modif == ''){
              $('#errorNOM_modif').text('<?=lang('msg_validation')?>');
              statut = 2;
            }
            else{$('#errorNOM_modif').text('');}
          }
          else if(champ == 'NOM' && TYPE_PRO_modif == 2)
          {
            if(NOM_modif == ''){
              $('#errorNOM_modif').text('<?=lang('msg_validation')?>');
              statut = 2;
            }
            else{$('#errorNOM_modif').text('');}

            if(PRENOM_modif == ''){
              $('#errorPRENOM_modif').text('<?=lang('msg_validation')?>');
              statut = 2;
            }
            else{$('#errorPRENOM_modif').text('');}
          }
          else if(champ == 'EMAIL')
          { 

            if(EMAIL_modif == ''){
              $('#errorEMAIL_modif').text('<?=lang('msg_validation')?>');
              statut = 2;
            }
            else if(!emailReg.test($('#EMAIL_modif').val()))
            {
              $('#errorEMAIL_modif').html('<?=lang('msg_validation_mail')?>');
              statut=2
            }
            else{$('#errorEMAIL_modif').html('');}
            
          }
          else if(champ == 'TELEPHONE'){
            if(TELEPHONE_modif == ''){
              $('#errorTELEPHONE_modif').text('<?=lang('msg_validation')?>');
              statut = 2;
            }
            else{$('#errorTELEPHONE_modif').text('');}
          }
          else if(champ == 'TELEPHONE'){
            if(TELEPHONE_modif == ''){
              $('#errorTELEPHONE_modif').text('<?=lang('msg_validation')?>');
              statut = 2;
            }
            else{$('#errorTELEPHONE_modif').text('');}
          }
          else if(champ == 'CNI_OU_NIF'){
            if(CNI_OU_NIF_modif == ''){
              $('#errorCNI_OU_NIF_modif').text('<?=lang('msg_validation')?>');
              statut = 2;
            }
            else{$('#errorCNI_OU_NIF_modif').text('');}
          }
          else if(champ == 'RC'){
            if(RC_modif == ''){
              $('#errorRC_modif').text('<?=lang('msg_validation')?>');
              statut = 2;
            }
            else{$('#errorRC_modif').text('');}
          }
          else if(champ == 'CATEGORIE_ID'){
            if(CATEGORIE_ID_modif == ''){
              $('#errorCATEGORIE_ID_modif').text('<?=lang('msg_validation')?>');
              statut = 2;
            }
            else{$('#errorCATEGORIE_ID_modif').text('');}
          }
          else if(champ == 'ADRESSE'){
            if(ADRESSE_modif == ''){
              $('#errorADRESSE_modif').text('<?=lang('msg_validation')?>');
              statut = 2;
            }
            else{$('#errorADRESSE_modif').text('');}
          }
          else if(champ == 'LOCALITE'){

            if(PROVINCE_ID_modif == 0){
              $('#errorPROVINCE_ID_modif').text('<?=lang('msg_validation')?>');
              statut = 2;
            }
            else{$('#errorPROVINCE_ID_modif').text('');}

            if(COMMUNE_ID_modif == 0){
              $('#errorCOMMUNE_ID_modif').text('<?=lang('msg_validation')?>');
              statut = 2;
            }
            else{$('#errorCOMMUNE_ID_modif').text('');}

            if(ZONE_ID_modif == 0){
              $('#errorZONE_ID_modif').text('<?=lang('msg_validation')?>');
              statut = 2;
            }
            else{$('#errorZONE_ID_modif').text('');}

            if(COLLINE_ID_modif == 0){
              $('#errorCOLLINE_ID_modif').text('<?=lang('msg_validation')?>');
              statut = 2;
            }
            else{$('#errorCOLLINE_ID_modif').text('');}

          }
          else if(champ == 'PHOTO'){
            if(PHOTO_modif == ''){
              $('#errorPHOTO_modif').text('<?=lang('msg_validation')?>');
              statut = 2;
            }
            else{$('#errorPHOTO_modif').text('');}
          }

          if (statut==1){  // si pas d'erreur

            var form_data = new FormData($("#modif_form")[0]);

            $.ajax(
            {
              url:"<?=base_url()?>proprietaire/Proprietaire/modif_pro_detail/",
              type: 'POST',
              dataType:'JSON',
              data: form_data ,
              contentType: false,
              cache: false,
              processData: false,
              success: function(data)
              {

               if(data.status == 1)
               {
                Swal.fire(
                {
                  icon: 'success',
                  title: 'Success',
                  text: '<?=lang('msg_success_modif')?>',
                  timer: 1500,
                }).then(() =>
                {
                 window.location.href='<?=base_url('')?>proprietaire/Proprietaire/liste/';
               });
              }
              else if(data.status == 0)
              {
                Swal.fire(
                {
                  icon: 'error',
                  title: 'Error',
                  text: '<?=lang('msg_error_modif')?>',
                  timer: 1500,
                }).then(() =>
                {
                 window.location.href='<?=base_url('')?>proprietaire/Proprietaire/liste/';
               });
              }
            }
          });
          }


        }
      </script>

      <script type="">
        function get_document(id,extention)
        {
          if(id == 1) // CNI
          {
            $('#div_file_nif').show();
            $('#div_carte_id').hide();
            $('#div_file_rc').hide();

            var file_nif = $('#file_nif').val();
            var imgElement = document.getElementById("file_nif2");
            imgElement.src = file_nif;

            if(extention == 'pdf')
            {
              $('#div_op_image_cni').hide();
              $('#div_op_image_carte').hide();
              $('#div_op_image_rc').hide();
            }
            else{
              $('#div_op_image_cni').show();
              $('#div_op_image_carte').hide();
              $('#div_op_image_rc').hide();
            }

      //alert(extention)
          }
          else if(id == 2)  // Carte ID ou passeport
          {
            $('#div_file_nif').hide();
            $('#div_carte_id').show();
            $('#div_file_rc').hide();

            var file_carte_id = $('#file_carte_id').val();
            var imgElement2 = document.getElementById("file_carte_id2");
            imgElement2.src = file_carte_id;

            if(extention == 'pdf')
            {
              $('#div_op_image_cni').hide();
              $('#div_op_image_carte').hide();
              $('#div_op_image_rc').hide();
            }
            else{
              $('#div_op_image_cni').hide();
              $('#div_op_image_carte').show();
              $('#div_op_image_rc').hide();
            }

      //alert(extention)
          }
          else if(id == 3)  // RC
          {
            $('#div_file_nif').hide();
            $('#div_carte_id').hide();
            $('#div_file_rc').show();

            var file_rc = $('#file_rc').val();
            var imgElement3 = document.getElementById("file_rc2");
            imgElement3.src = file_rc;

            if(extention == 'pdf')
            {
              $('#div_op_image_cni').hide();
              $('#div_op_image_carte').hide();
              $('#div_op_image_rc').hide();
            }
            else{
              $('#div_op_image_cni').hide();
              $('#div_op_image_carte').hide();
              $('#div_op_image_rc').show();
            }
          }


          $('#Modal_file_doc').modal('show');
        }
      </script>

      <script>


       //Operations photo NIF avec les boutons

        var scalePermis = 1; // Facteur de zoom initial
        var translateXPermis = 0; // Décalage horizontal initial
        var translateYPermis = 0; // Décalage vertical initial

        var photoPermis = document.getElementById('file_nif2');

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
          const image = document.getElementById('file_nif2');
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
             //Operations photo NIF avec la sourie

        let image2 = document.getElementById('file_nif2');
        let lastX2, lastY2;
        let isDragging2 = false;
        let rotationAngle2 = 0;

    // Zoomer/dézoomer sur double clic
        document.getElementById('file_nif2').addEventListener('dblclick', function() {
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


      <script>


       //Operations photo RC avec les boutons

        var scaleRC = 1; // Facteur de zoom initial
        var translateXRC = 0; // Décalage horizontal initial
        var translateYRC = 0; // Décalage vertical initial

        var photoRC = document.getElementById('file_rc2');

        // Fonction pour zoomer la photo
        function zoomInRC() {
          scaleRC += 0.1;
          updateTransformRC();

        }

        // Fonction pour dézoomer la photo
        function zoomOutRC() {
          scaleRC -= 0.1;
          updateTransformRC();
        }

        // Fonction pour déplacer la photo horizontalement
        function moveXRC(direction) {
          translateXRC += direction * 50; // Changer la valeur de décalage
          updateTransformRC();
        }

        // Fonction pour déplacer la photo verticalement
        function moveYRC(direction) {
          translateYRC += direction * 50; // Changer la valeur de décalage
          updateTransformCarte();
        }

        // Fonction pour mettre à jour la transformation CSS de la photo
        function updateTransformRC() {
          photoRC.style.transform = `scale(${scaleRC}) translate(${translateXRC}px, ${translateYRC}px)`;
        }

      //Rotation de l'image

        function rotate_opRC()
        {
          const image = document.getElementById('file_rc2');
      // const rotateBtn = document.getElementById('rotate-btn');
          let rotation = Number($('#rotationRC').val());

      //rotateBtn.addEventListener('click', () => {
          rotation += 90;
          image.style.transform = `rotate(${rotation}deg)`;
          $('#rotationRC').val(rotation)
      //});
        }

      </script>


      <script>
             //Operations photo RC avec la sourie

        let image4 = document.getElementById('file_rc2');
        let lastX4, lastY4;
        let isDragging4 = false;
        let rotationAngle4 = 0;

    // Zoomer/dézoomer sur double clic
        document.getElementById('file_rc2').addEventListener('dblclick', function() {
          if (this.style.transform === "scale(2)") {
            this.style.transform = "scale(1)";
          } else {
            this.style.transform = "scale(2)";
          }
        });

    // Déplacer en maintenant le clic gauche
        image4.addEventListener('mousedown', function(event) {
          if (event.button === 0) {
            isDragging4 = true;
            lastX4 = event.clientX;
            lastY4 = event.clientY;
            image4.style.cursor = 'grabbing';
          }
        });

        document.addEventListener('mousemove', function(event) {
          if (isDragging4) {
            let deltaX4 = event.clientX - lastX4;
            let deltaY4 = event.clientY - lastY4;
            let newX4 = image4.offsetLeft + deltaX4;
            let newY4 = image4.offsetTop + deltaY4;
            image4.style.left = newX4 + 'px';
            image4.style.top = newY4 + 'px';
            lastX4 = event.clientX;
            lastY4 = event.clientY;
          }
        });

        document.addEventListener('mouseup', function(event) {
          if (event.button === 0) {
            isDragging4 = false;
            image3.style.cursor = 'grab';
          }
        });

    // Pivoter avec la molette de la souris
        document.addEventListener('wheel', function(event) {
          if (event.deltaY4 < 0) {
            rotationAngle4 += 10;
          } else {
            rotationAngle4 -= 10;
          }
          image4.style.transform = `rotate(${rotationAngle4}deg)`;
        });

      </script>




      </html>