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

          <input type="hidden" name="TYPE_PROPRIETAIRE_ID" id="TYPE_PROPRIETAIRE_ID" value="<?=$proprietaire['TYPE_PROPRIETAIRE_ID']?>">
          <input type="hidden" name="USER_ID" id="USER_ID" value="<?=$utilisateur['USER_ID']?>">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
              <?php

              if (!empty($utilisateur['PHOTO_PASSPORT'])) {?>
                <img   src="<?=base_url('/upload/proprietaire/photopassport/'.$utilisateur['PHOTO_PASSPORT'])?>" alt="Profile" class="rounded-circle">
                <?php
              }else{?>

                <img   src="<?= base_url()?>/upload/phavatar.png" alt="Profile" class="rounded-circle">
                <?php
              }
              ?>
              
              <h2><?=$utilisateur['IDENTIFICATION']?></h2>
              <h3><?=$utilisateur['profile']?></h3>
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
          <?=  $this->session->flashdata('message');?>

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Profil</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Modifier</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Modifier le mot de passe</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">


                  <h5 class="card-title">A propos</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Nom & Prénom</div>
                    <div class="col-lg-9 col-md-8"><?=$utilisateur['IDENTIFICATION']?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">E-mail</div>
                    <div class="col-lg-9 col-md-8"><?=$utilisateur['USER_NAME']?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Téléphone</div>
                    <div class="col-lg-9 col-md-8"><?=$utilisateur['TELEPHONE']?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Pays</div>
                    <div class="col-lg-9 col-md-8"><?=$utilisateur['CommonName']?></div>
                  </div>

                  <?php
                  if ($utilisateur['COUNTRY_ID']==28) {?>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Localité</div>
                      <div class="col-lg-9 col-md-8"><?=$utilisateur['PROVINCE_NAME'].' / '.$utilisateur['COMMUNE_NAME'].' / '.$utilisateur['ZONE_NAME'].' / '.$utilisateur['COLLINE_NAME']?></div>
                    </div>
                    
                    <?php
                  }

                  ?>
                  <?php
                  if (!empty($utilisateur['PERSONNE_REFERENCE'])) {?>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Personne de référence</div>
                      <div class="col-lg-9 col-md-8"><?=$utilisateur['PERSONNE_REFERENCE']?></div>
                    </div>

                    <?php
                  }
                  ?>
                  

                  

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">CNI/NIF</div>
                    <div class="col-lg-9 col-md-8"><?=$utilisateur['CNI_OU_NIF']?></div>
                  </div>

                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form>
                    <div class="row mb-3">
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
                          <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                          <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nom & Prénom</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="fullName" type="text" class="form-control" id="fullName" value="<?=$proprietaire['NOM_PROPRIETAIRE'].' '.$proprietaire['PRENOM_PROPRIETAIRE']?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="company" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="company" type="text" class="form-control" id="company" value="<?=$proprietaire['EMAIL']?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Téléphone</label>
                      <div class="col-md-8 col-lg-9">
                        <input class="form-control bg-light" type='tel' name="TELEPHONE" id="TELEPHONE" value="<?=$proprietaire['TELEPHONE']?>" pattern="^[0-9-+\s()]*$"/>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Job" class="col-md-4 col-lg-3 col-form-label">Personne de référence</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="job" type="text" class="form-control" id="Job" value="<?=$proprietaire['PERSONNE_REFERENCE']?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Country" class="col-md-4 col-lg-3 col-form-label">Country</label>
                      <div class="col-md-8 col-lg-9">
                        <select class="form-control" id="COUNTRY_ID" name="COUNTRY_ID">
                          <?=$html1?>
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3" id="div_prov">
                      <label for="Address" class="col-md-4 col-lg-3 col-form-label">Province</label>
                      <div class="col-md-8 col-lg-9">
                        <select class="form-control" id="PROVINCE_ID" name="PROVINCE_ID">
                          <?=$html2?>
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3" id="div_com">
                      <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Commune</label>
                      <div class="col-md-8 col-lg-9">
                        <select class="form-control" id="COMMUNE_ID" name="COMMUNE_ID">
                          <?=$html3?>
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3" id="div_zon">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">zone</label>
                      <div class="col-md-8 col-lg-9">
                        <select class="form-control" id="ZONE_ID" name="ZONE_ID">
                          <?=$html4?>
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3" id="div_col">
                      <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Colline</label>
                      <div class="col-md-8 col-lg-9">
                        <select class="form-control" id="COLLINE_ID" name="COLLINE_ID">
                          <?=$html5?>
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Adresse</label>
                      <div class="col-md-8 col-lg-9">
                       <input class="form-control" name="ADRESSE" id="ADRESSE" placeholder='Adresse' value="<?=$proprietaire['ADRESSE']?>"/>

                     </div>
                   </div>

                   <div class="row mb-3">
                    <label for="Instagram" class="col-md-4 col-lg-3 col-form-label"><a id="CNI_NIF"></a></label>
                    <div class="col-md-8 col-lg-9">
                      <input name="CNI_OU_NIF" type="text" class="form-control" id="CNI_OU_NIF" value="<?=$proprietaire['CNI_OU_NIF']?>">
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">Modifier</button>
                  </div>
                </form><!-- End Profile Edit Form -->

              </div>

              <div class="tab-pane fade pt-3" id="profile-change-password">
                <!-- Change Password Form -->
                <form id="form_password" enctype="multipart/form-data" method="post" action="<?=base_url('profil/Profil/edit_pwd')?>">
                  <input type="hidden" name="PASSWORD_OLD" id="PASSWORD_OLD" value="<?=$utilisateur['PASSWORD']?>">
                  <div class="row mb-3">
                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Mot de passe actuel</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="PASSWORD" type="password" class="form-control" id="PASSWORD" onchange="check_pwd();">
                    </div>
                    <center><span id="errorPASSWORD" class="text-danger"></span></center>


                  </div>

                  <div class="row mb-3">
                    <div class="form-check ml-2">
                      <input class="form-check-input" type="checkbox" id="basic_checkbox_1" onclick="show_password()" style="border-radius:2px; float: right;">
                      <label class="form-check-label" for="basic_checkbox_1" style="color:white;">Afficher mot de passe</label>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Nouveau mot de passe</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="NEW_PASSWORD" type="password" class="form-control" id="NEW_PASSWORD">
                    </div>
                    <center><span id="errorNEW_PASSWORD" class="text-danger"></span></center>
                  </div>
                  

                  <div class="row mb-3">
                    <div class="form-check ml-2">
                      <input class="form-check-input" type="checkbox" id="basic_checkbox_1" onclick="show_password1()" style="border-radius:2px; float: right;">
                      <label class="form-check-label" for="basic_checkbox_1" style="color:white;">Afficher mot de passe</label>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Confirmation du nouveau mot de passe</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="NEW_NEW_PASSWORD" type="password" class="form-control" id="NEW_NEW_PASSWORD">
                    </div>
                    <center><span id="errorNEW_NEW_PASSWORD" class="text-danger"></span></center>
                  </div>
                  

                  <div class="row mb-3">

                    <div class="form-check ml-2">
                      <input class="form-check-input" type="checkbox" id="basic_checkbox_1" onclick="show_password2()" style="border-radius:2px; float: right;">
                      <label class="form-check-label" for="basic_checkbox_1" style="color:white;">Afficher mot de passe</label>
                    </div>
                  </div>

                  
                </form><!-- End Change Password Form -->
                <div class="text-center">
                  <button onclick="submit_form();" class="btn btn-outline-primary">Modifier</button>
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


    }
    else
    {
      $('#CNI_NIF').html('CNI');

    }

  });
</script>
<script>
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
      $('#errorPASSWORD').html('Le champ est obligatoire');
    }else{
      $('#errorPASSWORD').html('');
    }


    if($('#NEW_PASSWORD').val()=='')
    {
      statut=2;
      $('#errorNEW_PASSWORD').html('Le champ est obligatoire');
    }else{
      $('#errorNEW_PASSWORD').html('');
    }

    if($('#NEW_NEW_PASSWORD').val()=='')
    {
      statut=2;
      $('#errorNEW_NEW_PASSWORD').html('Le champ est obligatoire');
    }else{
      $('#errorNEW_NEW_PASSWORD').html('');

      if($('#NEW_NEW_PASSWORD').val()!=$('#NEW_PASSWORD').val())
      {
        statut=2;

        $('#errorNEW_NEW_PASSWORD').html('Les mots de passe ne correspondent pas');
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
        alert('Erreur');
      }
    });
  }
</script>

</html>