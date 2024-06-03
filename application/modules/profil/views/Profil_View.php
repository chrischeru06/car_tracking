<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>
  <style>
    .profile .profile-card img {
      max-width: 280px;
    }

    .scroller {
      height: 400px;
      overflow-y: scroll;
      border-radius: 10px;
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

    <!-- <div class="pagetitle">
      <h1>Profil</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Profil</a></li>
        </ol>
      </nav>
    </div> --><!-- End Page Title -->

    <!-- <section class="section dashboard">
      
    </section> -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">



          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
              <?php

              if (!empty($utilisateur['PHOTO_PASSPORT'])) {?>
                <img   src="<?=base_url('/upload/proprietaire/photopassport/'.$utilisateur['PHOTO_PASSPORT'])?>" alt="Profile" style="border-radius: 10px;height: 290px;width: 200%;">
                <?php
              }elseif (!empty($utilisateur['LOGO'])) {?>

                <img src="<?=base_url('/upload/proprietaire/photopassport/'.$utilisateur['LOGO'])?>" alt="Profile"  style="border-radius: 10px;height: 290px;width: 200%;">
              <?php }
              else{?>

                <img   src="<?= base_url()?>/upload/phavatar.png" alt="Profile"  style="border-radius: 10px;height: 290px;width: 200%;">
                <?php
              }
              ?>



              <h2><?=$utilisateur['IDENTIFICATION']?></h2>
              <h3><?=$utilisateur['profile']?></h3>
              <div class="social-links mt-2">
               <!--  <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a> -->
              </div>
            </div>
          </div>

        </div>

        <div class="col-xl-8">
          <?=  $this->session->flashdata('message');?>

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview"><?=lang('profil_min')?></button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit"><?=lang('btn_modifier')?></button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password"><?=lang('btn_modif_mot_de_passe')?></button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">


                  <h5 class="card-title"><?=lang('mot_a_propos')?></h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label "><?=lang('td_nom_prenom')?></div>
                    <div class="col-lg-9 col-md-8"><?=$utilisateur['IDENTIFICATION']?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label"><?=lang('input_email')?></div>
                    <div class="col-lg-9 col-md-8"><?=$utilisateur['USER_NAME']?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label"><?=lang('input_tlphone')?></div>
                    <div class="col-lg-9 col-md-8"><?=$utilisateur['TELEPHONE']?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label"><?=lang('input_pays')?></div>
                    <div class="col-lg-9 col-md-8"><?=$utilisateur['CommonName']?></div>
                  </div>

                  <?php
                  if ($utilisateur['COUNTRY_ID']==28) {?>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label"><?=lang('td_localite')?></div>
                      <div class="col-lg-9 col-md-8"><?=$utilisateur['PROVINCE_NAME'].' / '.$utilisateur['COMMUNE_NAME'].' / '.$utilisateur['ZONE_NAME'].' / '.$utilisateur['COLLINE_NAME']?></div>
                    </div>
                    
                    <?php
                  }

                  ?>
                  <?php
                  if (!empty($utilisateur['PERSONNE_REFERENCE'])) {?>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label"><?=lang('input_pers_reference')?></div>
                      <div class="col-lg-9 col-md-8"><?=$utilisateur['PERSONNE_REFERENCE']?></div>
                    </div>

                    <?php
                  }
                  ?>
                  
                  <?php
                  if ($utilisateur['TYPE_PROPRIETAIRE_ID']==1) {?>
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label"><?=lang('input_nif')?></div>
                      <div class="col-lg-9 col-md-8"><?=$utilisateur['CNI_OU_NIF']?></div>
                    </div>
                    <?php
                  }else{?>
                    <div class="row">
                      <div class="col-lg-3 col-md-4 label"><?=lang('mot_cni')?></div>
                      <div class="col-lg-9 col-md-8"><?=$utilisateur['CNI_OU_NIF']?></div>
                    </div>
                    <?php
                  }?>
                  


                  <div class="row">
                    <div class="col-lg-3 col-md-4 label"><?=lang('btn_doc')?></div>
                    <div class="col-lg-9 col-md-8"><button onclick="modal_doc();" class="btn-outline rounded-pill" style="background-color:cadetblue;"><i class="bi bi-eye"></i></button></div>
                  </div>


                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  
                  <form id="form_info" enctype="multipart/form-data" method="post" action="<?=base_url('profil/Profil/edit_info')?>">

                   <input type="hidden" name="PROPRIETAIRE_ID" id="PROPRIETAIRE_ID" value="<?=$proprietaire['PROPRIETAIRE_ID']?>">
                   <input type="hidden" name="TYPE_PROPRIETAIRE_ID" id="TYPE_PROPRIETAIRE_ID" value="<?=$proprietaire['TYPE_PROPRIETAIRE_ID']?>">
                   <input type="hidden" name="USER_ID" id="USER_ID" value="<?=$utilisateur['USER_ID']?>">
                   <!-- <div class="row mb-3">
                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                    <div class="col-md-8 col-lg-9">
                      <?php

                      if (!empty($utilisateur['PHOTO_PASSPORT'])) {?>
                        <img   src="<?=base_url('/upload/proprietaire/photopassport/'.$utilisateur['PHOTO_PASSPORT'])?>" alt="Profile" >
                        <?php
                      }else{?>

                        <img   src="<?= base_url()?>/upload/phavatar.png" alt="Profile">
                        <?php
                      }
                      ?>
                      <div class="pt-2">
                        
                      </div>
                    </div>
                  </div> -->

                  <div class="row mb-3">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label"><?=lang('input_nom')?></label>
                    <div class="col-md-8 col-lg-9">
                      <input name="NOM_PROPRIETAIRE" type="text" class="form-control" id="NOM_PROPRIETAIRE" value="<?=$proprietaire['NOM_PROPRIETAIRE']?>">
                    </div>
                    <center><font id="error_NOM_PROPRIETAIRE" color="red"></font></center>

                  </div>
                  <div class="row mb-3" id="div_prenom">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label"><?=lang('input_prenom')?></label>
                    <div class="col-md-8 col-lg-9">
                      <input name="PRENOM_PROPRIETAIRE" type="text" class="form-control" id="PRENOM_PROPRIETAIRE" value="<?=$proprietaire['PRENOM_PROPRIETAIRE']?>">
                    </div>

                    <center><font id="error_PRENOM_PROPRIETAIRE" color="red"></font></center>

                  </div>

                  <div class="row mb-3">
                    <label for="company" class="col-md-4 col-lg-3 col-form-label"><?=lang('input_email')?></label>
                    <div class="col-md-8 col-lg-9">
                      <input name="EMAIL" type="text" class="form-control" id="EMAIL" value="<?=$proprietaire['EMAIL']?>">
                    </div>
                    <center><font id="error_EMAIL" color="red"></font></center>

                  </div>

                  <div class="row mb-3">
                    <label for="Facebook" class="col-md-4 col-lg-3 col-form-label"><?=lang('input_tlphone')?></label>
                    <div class="col-md-8 col-lg-9">
                      <input class="form-control bg-light" type='tel' name="TELEPHONE" id="TELEPHONE" value="<?=$proprietaire['TELEPHONE']?>" pattern="^[0-9-+\s()]*$"/>
                    </div>

                    <center><font id="error_TELEPHONE" color="red"></font></center>

                  </div>

                  <div class="row mb-3">
                    <label for="Job" class="col-md-4 col-lg-3 col-form-label"><?=lang('input_pers_reference')?></label>
                    <div class="col-md-8 col-lg-9">
                      <input name="PERSONNE_REFERENCE" type="text" class="form-control" id="PERSONNE_REFERENCE" value="<?=$proprietaire['PERSONNE_REFERENCE']?>">
                    </div>
                    <center><font id="error_PERSONNE_REFERENCE" color="red"></font></center>

                  </div>

                  <div class="row mb-3">
                    <label for="Country" class="col-md-4 col-lg-3 col-form-label"><?=lang('input_pays')?></label>
                    <div class="col-md-8 col-lg-9">
                      <select class="form-control" id="COUNTRY_ID" name="COUNTRY_ID">
                        <?=$html1?>
                      </select>
                    </div>
                    <center><font id="error_COUNTRY_ID" color="red"></font></center>

                  </div>

                  <div class="row mb-3" id="div_prov">
                    <label for="Address" class="col-md-4 col-lg-3 col-form-label"><?=lang('input_province')?></label>
                    <div class="col-md-8 col-lg-9">
                      <select class="form-control" id="PROVINCE_ID" name="PROVINCE_ID">
                        <?=$html2?>
                      </select>
                    </div>
                    <center><font id="error_PROVINCE_ID" color="red"></font></center>
                  </div>

                  <div class="row mb-3" id="div_com">
                    <label for="Phone" class="col-md-4 col-lg-3 col-form-label"><?=lang('input_commune')?></label>
                    <div class="col-md-8 col-lg-9">
                      <select class="form-control" id="COMMUNE_ID" name="COMMUNE_ID">
                        <?=$html3?>
                      </select>
                    </div>
                    <center><font id="error_COMMUNE_ID" color="red"></font></center>

                  </div>

                  <div class="row mb-3" id="div_zon">
                    <label for="Email" class="col-md-4 col-lg-3 col-form-label"><?=lang('input_zone')?></label>
                    <div class="col-md-8 col-lg-9">
                      <select class="form-control" id="ZONE_ID" name="ZONE_ID">
                        <?=$html4?>
                      </select>
                    </div>
                    <center><font id="error_ZONE_ID" color="red"></font></center>

                  </div>

                  <div class="row mb-3" id="div_col">
                    <label for="Twitter" class="col-md-4 col-lg-3 col-form-label"><?=lang('input_colline')?></label>
                    <div class="col-md-8 col-lg-9">
                      <select class="form-control" id="COLLINE_ID" name="COLLINE_ID">
                        <?=$html5?>
                      </select>
                    </div>
                    <center><font id="error_COLLINE_ID" color="red"></font></center>

                  </div>

                  <div class="row mb-3">
                    <label for="Facebook" class="col-md-4 col-lg-3 col-form-label"><?=lang('input_adresse')?></label>
                    <div class="col-md-8 col-lg-9">
                     <input class="form-control" name="ADRESSE" id="ADRESSE" value="<?=$proprietaire['ADRESSE']?>"/>
                   </div>
                   <center><font id="error_ADRESSE" color="red"></font></center>

                 </div>

                 <div class="row mb-3">
                  <label for="Instagram" class="col-md-4 col-lg-3 col-form-label"><a id="CNI_NIF"></a></label>
                  <div class="col-md-8 col-lg-9">
                    <input name="CNI_OU_NIF" type="text" class="form-control" id="CNI_OU_NIF" value="<?=$proprietaire['CNI_OU_NIF']?>">
                  </div>
                  <center><font id="error_CNI_OU_NIF" color="red"></font></center>

                </div>              
              </form>
              <div class="row">
                <div class="col-md-12">
                  <button onclick="submit_form_form();" style="float:right;" class="btn btn-outline-primary rounded-pill"><?=lang('btn_modifier')?></button>
                </div>

              </div><!-- End Profile Edit Form -->

            </div>

            <div class="tab-pane fade pt-3" id="profile-change-password">
              <!-- Change Password Form -->
              <form id="form_password" enctype="multipart/form-data" method="post" action="<?=base_url('profil/Profil/edit_pwd')?>">
                <input type="hidden" name="PASSWORD_OLD" id="PASSWORD_OLD" value="<?=$utilisateur['PASSWORD']?>">
                <div class="row mb-3">
                  <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label"><?=lang('passw_actuel')?></label>
                  <div class="col-md-8 col-lg-9">
                    <input name="PASSWORD" type="password" class="form-control" id="PASSWORD" onchange="check_pwd();">
                  </div>
                  <center><span id="errorPASSWORD" class="text-danger"></span></center>

                </div>

                <div class="row mb-3">
                  <div class="form-check ml-2">
                    <input class="form-check-input" type="checkbox" id="basic_checkbox_1" onclick="show_password()" style="border-radius:2px; float: right;">
                    <label class="form-check-label" for="basic_checkbox_1" style="color:white;"><?=lang('affich_password')?></label>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="newPassword" class="col-md-4 col-lg-3 col-form-label"><?=lang('nveau_mot_passe')?></label>
                  <div class="col-md-8 col-lg-9">
                    <input name="NEW_PASSWORD" type="password" class="form-control" id="NEW_PASSWORD">
                  </div>
                  <center><span id="errorNEW_PASSWORD" class="text-danger"></span></center>
                </div>


                <div class="row mb-3">
                  <div class="form-check ml-2">
                    <input class="form-check-input" type="checkbox" id="basic_checkbox_1" onclick="show_password1()" style="border-radius:2px; float: right;">
                    <label class="form-check-label" for="basic_checkbox_1" style="color:white;"><?=lang('affich_password')?></label>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label"><?=lang('confirmation_mot_passe')?></label>
                  <div class="col-md-8 col-lg-9">
                    <input name="NEW_NEW_PASSWORD" type="password" class="form-control" id="NEW_NEW_PASSWORD">
                  </div>
                  <center><span id="errorNEW_NEW_PASSWORD" class="text-danger"></span></center>
                </div>


                <div class="row mb-3">

                  <div class="form-check ml-2">
                    <input class="form-check-input" type="checkbox" id="basic_checkbox_1" onclick="show_password2()" style="border-radius:2px; float: right;">
                    <label class="form-check-label" for="basic_checkbox_1" style="color:white;"><?=lang('affich_password')?></label>
                  </div>
                </div>


              </form><!-- End Change Password Form -->
              <div class="row">
                <div class="col-md-12">
                  <button onclick="submit_form();" style="float:right;" class="btn btn-outline-primary rounded-pill"><?=lang('btn_modifier')?></button>
                </div>

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
<!------------------------ Modal pour l'affichage des documents ------------------------>


<div class="modal fade" id="myModal_doc" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'>            
        <h5 class="modal-title"><?=lang('btn_detail')?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="scroller">

          <div class="table-responsive">
            <div class="row">

              <div id="div_info">
              </div>

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
<script>
  $(document).ready(function()
  {
    $('#message').delay('slow').fadeOut(3000);

    if($('#COUNTRY_ID').val()==28)
    {

      $('#div_prov').attr('hidden',false); 
      $('#div_com').attr('hidden',false);  
      $('#div_zon').attr('hidden',false);  
      $('#div_col').attr('hidden',false); 
    }
    else
    {


      $('#div_prov').attr('hidden',true); 
      $('#div_com').attr('hidden',true);  
      $('#div_zon').attr('hidden',true);  
      $('#div_col').attr('hidden',true); 


    }


    if($('#TYPE_PROPRIETAIRE_ID').val()==1)
    {

      $('#CNI_NIF').html('NIF');
      $('#div_prenom').attr('hidden',true); 



    }
    else
    {
      $('#CNI_NIF').html('CNI');
      $('#div_prenom').attr('hidden',false); 


    }

  });
</script>
<script>

  $("#NOM_PROPRIETAIRE,#PRENOM_PROPRIETAIRE,#PERSONNE_REFERENCE").on('input', function()
  {
    $(this).val($(this).val().toUpperCase());
  });
  function show_password() 
  {
    var x = document.getElementById("PASSWORD");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }

  function show_password1() 
  {
    var x = document.getElementById("NEW_PASSWORD");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }

  function show_password2() 
  {
    var x = document.getElementById("NEW_NEW_PASSWORD");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }


  function submit_form()
  {
    const photopassport = document.getElementById('photo_passport');
    var form = document.getElementById("form_password");
    
    var statut=1;

    if($('#PASSWORD').val()=='')
    {
      statut=2;
      $('#errorPASSWORD').html('<?=lang('msg_validation')?>');
    }else{
      $('#errorPASSWORD').html('');
    }


    if($('#NEW_PASSWORD').val()=='')
    {
      statut=2;
      $('#errorNEW_PASSWORD').html('<?=lang('msg_validation')?>');
    }else{
      $('#errorNEW_PASSWORD').html('');
    }

    if($('#NEW_NEW_PASSWORD').val()=='')
    {
      statut=2;
      $('#errorNEW_NEW_PASSWORD').html('<?=lang('msg_validation')?>');
    }else{
      $('#errorNEW_NEW_PASSWORD').html('');

      if($('#NEW_NEW_PASSWORD').val()!=$('#NEW_PASSWORD').val())
      {
        statut=2;

        $('#errorNEW_NEW_PASSWORD').html('mot_passe_ps_correspondant');
      }
      else
      {
        $('#errorNEW_NEW_PASSWORD').html('');
      }

    }

      // alert(statut)

    if(statut==1)
    {
      $('#form_password').submit();

    }
  }



  function check_pwd()
  {

    $.ajax(
    {
      url:"<?=base_url('profil/Profil/check_pwd/')?>"+$('#PASSWORD').val(),
      type: "GET",
      dataType:"JSON",
      success: function(data)
      {
        $('#errorPASSWORD').html(data);
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        alert('<?=lang('msg_erreur')?>');
      }
    });
  }

  function submit_form_form(){

    var form = document.getElementById("form_info");
    
    var statut=1;

    if($('#NOM_PROPRIETAIRE').val()=='')
    {
      statut=2;
      $('#error_NOM_PROPRIETAIRE').html('<?=lang('msg_validation')?>');
    }else{
      $('#error_NOM_PROPRIETAIRE').html('');
    }

    if($('#PRENOM_PROPRIETAIRE').val()=='')
    {
      statut=2;
      $('#error_PRENOM_PROPRIETAIRE').html('<?=lang('msg_validation')?>');
    }else{
      $('#error_PRENOM_PROPRIETAIRE').html('');
    }

    if($('#EMAIL').val()=='')
    {
      statut=2;
      $('#error_EMAIL').html('<?=lang('msg_validation')?>');
    }else{
      $('#error_EMAIL').html('');
    }

    if($('#TELEPHONE').val()=='')
    {
      statut=2;
      $('#error_TELEPHONE').html('<?=lang('msg_validation')?>');
    }else{
      $('#error_TELEPHONE').html('');
    }

    if($('#COUNTRY_ID').val()=='')
    {
      statut=2;
      $('#error_COUNTRY_ID').html('<?=lang('msg_validation')?>');
    }else{
      $('#error_COUNTRY_ID').html('');
    }

    if($('#COUNTRY_ID').val()== 28 )
    {
     if($('#PROVINCE_ID').val()=='')
     {
      statut=2;
      $('#error_PROVINCE_ID').html('<?=lang('msg_validation')?>');
    }else{
      $('#error_PROVINCE_ID').html('');
    }

    if($('#COMMUNE_ID').val()=='')
    {
      statut=2;
      $('#error_COMMUNE_ID').html('<?=lang('msg_validation')?>');
    }else{
      $('#error_COMMUNE_ID').html('');
    }

    if($('#ZONE_ID').val()=='')
    {
      statut=2;
      $('#error_ZONE_ID').html('<?=lang('msg_validation')?>');
    }else{
      $('#error_ZONE_ID').html('');
    }


  }

  if($('#ADRESSE').val()=='')
  {
    statut=2;
    $('#error_ADRESSE').html('<?=lang('msg_validation')?>');
  }else{
    $('#error_ADRESSE').html('');
  }


  if($('#CNI_OU_NIF').val()=='')
  {
    statut=2;
    $('#error_CNI_OU_NIF').html('<?=lang('msg_validation')?>');
  }else{
    $('#error_CNI_OU_NIF').html('');
  }



  if(statut==1)
  {
    $('#form_info').submit();

  }

}
function modal_doc(){

 $("#myModal_doc").modal("show");
 var PROPRIETAIRE_ID = $('#PROPRIETAIRE_ID').val();


 $.ajax({
  url: "<?= base_url() ?>profil/Profil/get_modal_documents/" + PROPRIETAIRE_ID,
  type: "POST",
  dataType: "JSON",
  success: function(data) {


    $('#div_info').html(data.div_info);



  },

});

}
</script>

</html>