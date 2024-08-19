<section class="section">
    <div class="row align-items-top">
      <div class="col-md-12">

        <div class="card">
          <div class="card-body">
            <center><h5 class="card-title"><?=lang('btn_info_gnl')?></h5></center>
            <div class="row">
              <div class="col-lg-6"style="width:44%">
                <div class="row" style="padding:0px 10px 20px;">
                 <div class="col-lg-12"style="width:250px">
                  <div class="card" style="">

                    <div class="card-body p-0"style="height:110px">
                     <div class="row profil-info">
                      <div class="col-md-4 profil-img">

                        <?php
                        if(!empty($get_chauffeur['PHOTO_PASSPORT']))
                        {
                          ?>
                          <img class="img" style="border-radius: 10%;background-color: white;" class="img-fluid" src="<?=base_url('/upload/chauffeur/'.$get_chauffeur['PHOTO_PASSPORT'])?>">


                          <?php
                        }
                        else if(empty($get_chauffeur['PHOTO_PASSPORT']))
                        {
                          ?>
                          <img class="img" style="background-color: #829b35;border-radius: 10%" class="img-fluid" src="<?=base_url('upload/phavatar.png')?>">
                          <?php
                        }?>
                      </div>
                      <div class="col-md-8 profil-text" style="padding-top:10px">
                       <?php
                       if(!empty($get_chauffeur)){?>

                        <p class="profil-name" title="<?=$get_chauffeur['NOM'].'&nbsp;'. $get_chauffeur['PRENOM']?>"><?=$get_chauffeur['NOM'].'&nbsp;'. $get_chauffeur['PRENOM']?></p>
                        <p class="profil-phone" title="<?=$get_chauffeur['NUMERO_TELEPHONE']?>"> <span class="bi bi-phone"></span>&nbsp;<?=$get_chauffeur['NUMERO_TELEPHONE']?></p>
                        <p class="profil-phone" title="<?=$get_chauffeur['ADRESSE_MAIL']?>"><i class="bi bi-envelope"></i>&nbsp;<?=$get_chauffeur['ADRESSE_MAIL']?></p>
                        <p class="profil-phone" title="<?=$get_chauffeur['ADRESSE_PHYSIQUE']?>"><i class="bi bi-geo-fill"></i>&nbsp;<?=$get_chauffeur['ADRESSE_PHYSIQUE']?></p>


                        <?php
                      }else{?>
                        <p class="profil-name" style="color: red;" title="<?=lang('chauf_non_affect')?>"> <?=lang('chauf_non_affect')?>
                      </p>

                      <?php
                    }
                    ?>
                  </div>
                </div>
                <div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- <br> -->
        <div class="row"style="margin-left: 250px;margin-top: -130px;width: 250px;">
          <div class="col-lg-12">
            <div class="card" style="height: 110px;margin-top: -30px;" >
              <div class="card-body p-0">
                <div class="row profil-info">
                  <div class="col-md-4 profil-img">

                    <?php
                    if(!empty($get_vehicule['PHOTO']))
                    {
                      ?>
                      <img class="img"  style="background-color: white;border-radius: 10%;" class="img-fluid" src="<?=base_url('/upload/photo_vehicule/'.$get_vehicule['PHOTO'])?>">
                      <?php
                    }
                    else if(empty($get_vehicule['PHOTO']))
                    {
                      ?>
                      <img class="img" style="border-radius: 10%;" class="img-fluid"  src="<?=base_url('upload/car.png')?>">

                      <?php
                    }?>

                  </div>
                  <div class="col-md-8 profil-text"style="padding-top:10px">

                    <p class="profil-name" title="<?=$get_vehicule['DESC_MARQUE'].' / '. $get_vehicule['DESC_MODELE']?>"><?=$get_vehicule['DESC_MARQUE'].' / '. $get_vehicule['DESC_MODELE']?></p>
                    <p class="profil-phone" title="<?=$get_vehicule['PLAQUE']?>"> <i class="bi bi-textarea-resize"></i><?=$get_vehicule['PLAQUE']?></p>
                    <p class="profil-phone" title="<?php if(empty($get_vehicule['COULEUR'])){?> N/A <?php } ?>
                    <?php if(!empty($get_vehicule['COULEUR'])){?>  <?= $get_vehicule['COULEUR']?> <?php } ?>"> <i class="bi bi-palette"></i><?php if(empty($get_vehicule['COULEUR'])){?> N/A <?php } ?>
                    <?php if(!empty($get_vehicule['COULEUR'])){?>  <?= $get_vehicule['COULEUR']?> <?php } ?></p>
                    <p class="profil-phone" title="<?php if(empty($get_vehicule['KILOMETRAGE'])){?> N/A <?php } ?>
                    <?php if(!empty($get_vehicule['KILOMETRAGE'])){?>  <?= $get_vehicule['KILOMETRAGE']?> litres / Km <?php } ?>"><i class="bi bi-vector-pen"></i> <?php if(empty($get_vehicule['KILOMETRAGE'])){?> N/A <?php } ?>
                    <?php if(!empty($get_vehicule['KILOMETRAGE'])){?>  <?= $get_vehicule['KILOMETRAGE']?> litres / Km <?php } ?></p>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6"style="width:56%">
        <div class="row">
         <div class="col-md-6"style=" width: auto;">
          <div class="card" style="">
            <div class="card-body">
              <h5 class="card-title" style="font-size:.6rem;width:100px"><?=lang('dist_parcourue')?> <span style="font-size:.5rem;">| Km</span></h5>

              <div class="d-flex align-items-center"style="width:100px">
                <div class="card-icon rounded-circle" >
                  <img style="background-color: #829b35;border-radius: 10%" class="img-fluid" width="30px" height="auto" src="<?=base_url('/upload/distance.jpg')?>">
                </div>
                <div class="ps-3">
                  <h6><span class="text-success small pt-1 fw-boldd"style="font-size:.5rem"><a id="distance_finale"></a> Km</span></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6"style=" width: auto;">


          <div class="card" style="border-radius: 10%">
            <div class="card-body">
              <h5 class="card-title" style="font-size:.6rem;width:100px"><?=lang('carburant_mot')?> <span style="font-size:.5rem;">| <?=lang('consomme_mot')?></span></h5>

              <div class="d-flex align-items-center"style="width:100px">
                <div class="card-icon rounded-circle">
                  <img style="background-color: #829b35;" class="img-fluid" width="30px" height="auto" src="<?=base_url('/upload/carburant_color.jfif')?>">
                </div>
                <div class="ps-3">
                  <h6><span class="text-success small pt-1 fw-boldd"style="font-size:.5rem"> <a id="carburant"></a> <?=lang('litre_mot')?></span></h6>


                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row"style="margin-left: 300px;margin-top: -143px;">


        <div class="col-md-6"style=" width: auto;">


          <div class="card" style="border-radius: 10%;">
            <div class="card-body">
              <h5 class="card-title" style="font-size:.6rem;width:100px"><?=lang('vitesse_max')?> <span style="font-size:.5rem;">| Max</span></h5>

              <div class="d-flex align-items-center"style="width:100px">
                <div class="card-icon rounded-circle">
                  <img style="background-color: #829b35;border-radius: 50%" class="img-fluid" width="30px" height="auto" src="<?=base_url('/upload/vitesse.png')?>">
                </div>
                <div class="ps-3">
                  <h6><span class="text-success small pt-1 fw-boldd"style="font-size:.5rem"> <a id="vitesse_max"></a> Km/h</span></h6>


                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6"style=" width: auto;">


          <div class="card" style="border-radius: 10%;">
            <div class="card-body"style="height: 100px;">
              <h5 class="card-titlee" style="font-size: .6rem;padding-top: 15px;">Score <span style="font-size:.5rem;">| 20</span></h5>

              <div class="d-flex align-items-center"style="width:100px">
                <div class="card-icon rounded-circle">
                  <img style="background-color: #829b35;" class="img-fluid" width="30px" height="auto" src="<?=base_url('/upload/score.png')?>">
                </div>
                <div class="ps-3">
                  <h6><span class="text-success small pt-1 fw-boldd"style="font-size:.5rem"> <a id="score"></a> Points</span></h6>
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

<div class="row align-items-top">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <center><h6 class="card-title"><?=lang('trajet_parcouru')?></h6></center>
        <div id="map_filtre" ></div>

      </div>
    </div>
  </div>



  <input type="hidden" id="ignition">




</div>
</section>
