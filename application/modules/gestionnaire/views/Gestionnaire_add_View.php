<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>

<body>

  <!-- ======= Header ======= -->
  <?php include VIEWPATH . 'includes/nav_bar.php'; ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php include VIEWPATH . 'includes/menu_left.php'; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

   <div class="row page-titles mx-0">
    <div class="col-sm-10">
      <div class="welcome-text">
       <center>

        <!-- <div class="col-md-4"> -->


          <!-- <div class="card" style="border-radius: 20px;margin-bottom: 10px;"> -->

            <table>
              <tr>
                <td> 
                  <!-- <img src="<?= base_url()?>template/imagespopup/IconeMuyingajdfss-04.png" width="60px" height="60px" alt=""> -->
                </td>
                <td>  
                  <h4 class="text-dark text-center" style="margin-bottom: 1px; margin-top: 3px;"><?=$title?></h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <!-- <li class="breadcrumb-item"><a href="#">Véhicule</a></li> -->
                      <li class="breadcrumb-item"><a href="#"></a></li>
                      <!-- <li class="breadcrumb-item active" aria-current="page">Saving slides</li> -->
                    </ol>
                  </nav>
                </td>
              </tr>
            </table>
            <!-- </div> -->
            <!-- </div> -->
          </center>
        </div>
      </div>
      <div class="col-md-2">

       <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('gestionnaire/Gestionnaire')?>" class="nav-link position-relative"><i class="bi bi-list"></i> <?=lang('title_list')?></a>

     </div>
   </div>

   <section class="section">
     <!-- <div class="container text-center"> -->
      <div class="row">
        <div class="text-left col-sm-12">
          <div class="card" style="border-radius: 20px;">

            <br>

            <div class="card-body">

              <?= $this->session->flashdata('message'); ?>
              <!-- <div class="row"> -->
                <form id="add_form" enctype="multipart/form-data" method="post" action="<?=base_url('gestionnaire/Gestionnaire/save')?>">
                  <fieldset class="border p-2">
                    <legend  class="float-none w-auto p-2"><?=lang('btn_info_gnl')?></legend>

                    <div  class="row text-dark">

                      <input type="hidden" name="ID_GESTIONNAIRE_VEHICULE" id="ID_GESTIONNAIRE_VEHICULE" value="<?=$gestionnaire['ID_GESTIONNAIRE_VEHICULE']?>">

                      <div class="col-md-4">
                        <div class="form-group">
                          <label ><small>Nom</small><span  style="color:red;">*</span></label>
                          <input class="form-control" type='text' name="NOM" id="NOM" placeholder='Nom' value="<?=$gestionnaire['NOM']?>"/>
                        </div>
                        <span id="errorNOM" class="text-danger"></span>
                        <?php echo form_error('NOM', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label ><small>Prénom </small><span  style="color:red;">*</span></label>
                          <input class="form-control" type='text' name="PRENOM" id="PRENOM" placeholder='Prénom' value="<?=$gestionnaire['PRENOM']?>"/>
                        </div>
                        <span id="errorPRENOM" class="text-danger"></span>
                        <?php echo form_error('PRENOM', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label ><small>Genre</small><span  style="color:red;">*</span></label>

                          <select class="form-control select2" name="GENRE_ID" id="GENRE_ID">
                            <option value="" selected>-- <?=lang('selectionner')?> --</option>
                            <?php
                            foreach ($genre as $genre)
                            {
                              ?>
                              <option value="<?=$genre['GENRE_ID']?>"<?php if($genre['GENRE_ID']==$gestionnaire['GENRE_ID']) echo " selected";?>><?=$genre['DESCR_GENRE']?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                        <span id="errorGENRE_ID" class="text-danger"></span>
                        <?php echo form_error('GENRE_ID', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label ><small>Email </small><span  style="color:red;">*</span></label>
                          <input class="form-control" type='email' name="ADRESSE_MAIL" id="ADRESSE_MAIL" placeholder='Email' value="<?=$gestionnaire['ADRESSE_MAIL']?>"/>
                        </div>
                        <span id="errorADRESSE_MAIL" class="text-danger"></span>
                        <?php echo form_error('ADRESSE_MAIL', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label style="font-weight: 1000; color:#454545"><b>Confirmation du mail</b><span  style="color:red;">*</span></label>
                          <input class="form-control" type='email' name="CONFIRMATION_EMAIL" id="CONFIRMATION_EMAIL" placeholder='confirmer e-mail' value="<?=$gestionnaire['ADRESSE_MAIL']?>"/>
                        </div>
                        <span id="errorCONFIRMATION_EMAIL" class="text-danger"></span>
                        <?php echo form_error('CONFIRMATION_EMAIL','<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label ><small>Téléphone </small><span  style="color:red;">*</span></label>
                          <input class="form-control" type='text' name="NUMERO_TELEPHONE" id="NUMERO_TELEPHONE" placeholder='Téléphone' value="<?=$gestionnaire['NUMERO_TELEPHONE']?>"/>
                        </div>
                        <span id="errorNUMERO_TELEPHONE" class="text-danger"></span>
                        <?php echo form_error('NUMERO_TELEPHONE', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label ><small><?=lang('input_province')?></small><span  style="color:red;">*</span></label>

                          <select class="form-control select2" name="PROVINCE_ID" id="PROVINCE_ID" onchange="get_communes();">
                            <option value="" selected>-- <?=lang('selectionner')?> --</option>
                            <?php
                            foreach ($province as $province)
                            {
                              ?>
                              <option value="<?=$province['PROVINCE_ID']?>"<?php if($province['PROVINCE_ID']==$gestionnaire['PROVINCE_ID']) echo " selected";?>><?=$province['PROVINCE_NAME']?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                        <span id="errorPROVINCE_ID" class="text-danger"></span>
                        <?php echo form_error('PROVINCE_ID', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label ><small><?=lang('input_commune')?></small><span  style="color:red;">*</span></label>

                          <select class="form-control select2" name="COMMUNE_ID" id="COMMUNE_ID" onchange="get_zones();">
                            <option value="" selected>-- <?=lang('selectionner')?> --</option>
                            <?php
                            foreach ($commune as $commune)
                            {
                              ?>
                              <option value="<?=$commune['COMMUNE_ID']?>"<?php if($commune['COMMUNE_ID']==$gestionnaire['COMMUNE_ID']) echo " selected";?>><?=$commune['COMMUNE_NAME']?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                        <span id="errorCOMMUNE_ID" class="text-danger"></span>
                        <?php echo form_error('COMMUNE_ID', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label ><small><?=lang('input_zone')?></small><span  style="color:red;">*</span></label>

                          <select class="form-control select2" name="ZONE_ID" id="ZONE_ID" onchange="get_collines();">
                            <option value="" selected>-- <?=lang('selectionner')?> --</option>
                            <?php
                            foreach ($zone as $zone)
                            {
                              ?>
                              <option value="<?=$zone['ZONE_ID']?>"<?php if($zone['ZONE_ID']==$gestionnaire['ZONE_ID']) echo " selected";?>><?=$zone['ZONE_NAME']?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                        <span id="errorZONE_ID" class="text-danger"></span>
                        <?php echo form_error('ZONE_ID', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label ><small><?=lang('input_colline')?></small><span  style="color:red;">*</span></label>

                          <select class="form-control select2" name="COLLINE_ID" id="COLLINE_ID">
                            <option value="" selected>-- <?=lang('selectionner')?> --</option>
                            <?php
                            foreach ($colline as $colline)
                            {
                              ?>
                              <option value="<?=$colline['COLLINE_ID']?>"<?php if($colline['COLLINE_ID']==$gestionnaire['COLLINE_ID']) echo " selected";?>><?=$colline['COLLINE_NAME']?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                        <span id="errorCOLLINE_ID" class="text-danger"></span>
                        <?php echo form_error('COLLINE_ID', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label ><small>Adresse physique </small><span  style="color:red;">*</span></label>
                          <input class="form-control" type='text' name="ADRESSE_PHYSIQUE" id="ADRESSE_PHYSIQUE" placeholder='Adresse physique' value="<?=$gestionnaire['ADRESSE_PHYSIQUE']?>"/>
                        </div>
                        <span id="errorADRESSE_PHYSIQUE" class="text-danger"></span>
                        <?php echo form_error('ADRESSE_PHYSIQUE', '<div class="text-danger">', '</div>'); ?>
                      </div>


                      <div class="col-md-4">
                        <div class="form-group">
                          <label ><small><?=lang('title_proprio_list')?></small><span  style="color:red;">*</span></label>

                          <select class="form-control select2" name="PROPRIETAIRE_ID" id="PROPRIETAIRE_ID">
                            <option value="" selected>-- <?=lang('selectionner')?> --</option>
                            <?php
                            foreach ($proprio as $proprio)
                            {
                              ?>
                              <option value="<?=$proprio['PROPRIETAIRE_ID']?>"<?php if($proprio['PROPRIETAIRE_ID']==$gestionnaire['PROPRIETAIRE_ID']) echo " selected";?>><?=$proprio['proprio_desc']?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                        <span id="errorPROPRIETAIRE_ID" class="text-danger"></span>
                        <?php echo form_error('PROPRIETAIRE_ID', '<div class="text-danger">', '</div>'); ?>
                      </div>

                    </div>

                  </fieldset>

                  <br>
                  <fieldset class="border p-2">
                    <legend  class="float-none w-auto p-2"><?=lang('btn_doc')?></legend>
                    <div  class="row">

                      <div class="col-md-4">
                        <label> <small>Photo</small><span  style="color:red;">*</span></label>

                        <input class="form-control" type="hidden" name="PHOTO_PROFIL" id="PHOTO_PROFIL"  value="<?=$gestionnaire['PHOTO_PROFIL'];?>">

                        <input type="file" class="form-control" name="PHOTO_PROFIL_NEW" id="PHOTO_PROFIL_NEW" value="<?=set_value('PHOTO_PROFIL_NEW')?>" accept=".png,.PNG,.jpg,.JPG,.JPEG,.jpeg" class="form-control" title='<?=lang('title_file')?>'>
                        <font color='red'><?php echo form_error('PHOTO_PROFIL_NEW'); ?></font>
                        <span id="errorPHOTO_PROFIL_NEW" class="text-danger"></span>
                      </div>

                      <div class="col-md-4" class="text-dark">
                        <label for="FName" class="text-dark" ><small><?=lang('carte_identite')?></small> <span  style="color:red;">*</span></label>

                        <input class="form-control" type="hidden" name="CARTE_ID" id="CARTE_ID"  value="<?=$gestionnaire['CARTE_ID'];?>">

                        <input type="file" class="form-control" name="CARTE_ID_NEW" id="CARTE_ID_NEW" value="<?=set_value('CARTE_ID_NEW')?>" accept=".png,.PNG,.jpg,.JPG,.JPEG,.jpeg" class="form-control" title='<?=lang('title_file')?>'>
                        <font color='red'><?php echo form_error('CARTE_ID_NEW'); ?></font>
                        <span id="errorCARTE_ID_NEW" class="text-danger"></span> 
                      </div>

                    </div>
                    <br>
                  </fieldset>

                  <br><br>
                  <div class="row">
                    <div class="col-md-12">
                      <button type="button" style="float: right;" class="btn btn-outline-primary rounded-pill " onclick="submit_form();"><i class="bi bi-check"></i><?=$btn?></button>
                    </div>
                  </div>

                </form>  
              </div>

              <!-- </div> -->

            </div>
          </div>
        </div>
      </div>
      <!-- </div> -->
    </section>

  </main><!-- End #main -->

  <?php include VIEWPATH . 'includes/footer.php'; ?>

</body>


<script type="text/javascript">

    // Fonction pour le chargement de donnees par defaut
  $(document).ready( function ()
  {
    $('#message').delay('slow').fadeOut(6000);  

  });

</script>


<script>
  function get_communes()
  {
    if($('#PROVINCE_ID').val()=='')
    {
      $('#COMMUNE_ID').html('<option value="">-- <?=lang('selectionner')?> --</option>');
      $('#ZONE_ID').html('<option value="">-- <?=lang('selectionner')?> --</option>');
      $('#COLLINE_ID').html('<option value="">-- <?=lang('selectionner')?> --</option>');
    }
    else
    {
      $('#ZONE_ID').html('<option value="">-- <?=lang('selectionner')?> --</option>');
      $('#COLLINE_ID').html('<option value="">-- <?=lang('selectionner')?> --</option>');
      $.ajax(
      {
        url:"<?=base_url('gestionnaire/Gestionnaire/get_communes/')?>"+$('#PROVINCE_ID').val(),
        type: "GET",
        dataType:"JSON",
        success: function(data)
        {
          $('#COMMUNE_ID').html(data);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert('<?=lang('msg_erreur')?>');
        }
      });
    }
  }
</script>


<script>
  function get_zones()
  {
    if($('#COMMUNE_ID').val()=='')
    {
      $('#ZONE_ID').html('<option value="">-- <?=lang('selectionner')?> --</option>');
      $('#COLLINE_ID').html('<option value="">-- <?=lang('selectionner')?> --</option>');
    }
    else
    {
      $('#COLLINE_ID').html('<option value="">-- <?=lang('selectionner')?> --</option>');
      $.ajax(
      {
        url:"<?=base_url('gestionnaire/Gestionnaire/get_zones/')?>"+$('#COMMUNE_ID').val(),
        type:"GET",
        dataType:"JSON",
        success: function(data)
        {
          $('#ZONE_ID').html(data);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert('<?=lang('msg_erreur')?>');
        }
      });
    }
  }
</script>


<script>
  function get_collines()
  {
    if($('#ZONE_ID').val()=='')
    {
      $('#COLLINE_ID').html('<option value="">-- <?=lang('selectionner')?> --</option>');
    }
    else
    {
      $.ajax(
      {
        url:"<?=base_url('gestionnaire/Gestionnaire/get_collines/')?>"+$('#ZONE_ID').val(),
        type:"GET",
        dataType:"JSON",
        success: function(data)
        {
          $('#COLLINE_ID').html(data);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert('<?=lang('msg_erreur')?>');
        }
      });
    }
  }
</script>




<script>

  function submit_form()
  {
    var mail = document.getElementById("ADRESSE_MAIL").value;
    var mail2 = document.getElementById("CONFIRMATION_EMAIL").value;
    var form = document.getElementById("add_form");
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

    var statut=1;

    if($('#NOM').val()=='')
    {
      statut=2;
      $('#errorNOM').html('<?=lang('msg_validation')?>');
    }else{$('#errorNOM').html('');}

    if($('#PRENOM').val()=='')
    {
      statut=2;
      $('#errorPRENOM').html('<?=lang('msg_validation')?>');
    }else{$('#errorPRENOM').html('');}

    if($('#ADRESSE_PHYSIQUE').val()=='')
    {
      statut=2;
      $('#errorADRESSE_PHYSIQUE').html('<?=lang('msg_validation')?>');
    }else{$('#errorADRESSE_PHYSIQUE').html('');}

    if($('#NUMERO_TELEPHONE').val()=='')
    {
      statut=2;
      $('#errorNUMERO_TELEPHONE').html('<?=lang('msg_validation')?>');
    }else{$('#errorNUMERO_TELEPHONE').html('');}

    if($('#GENRE_ID').val()=='')
    {
      statut=2;
      $('#errorGENRE_ID').html('<?=lang('msg_validation')?>');
    }else{$('#errorGENRE_ID').html('');}

    if($('#ADRESSE_MAIL').val() !='')
    {
      if(!emailReg.test($('#ADRESSE_MAIL').val()))
      {
        $('#errorADRESSE_MAIL').html('Email invalide!');
        statut=2
      }
      else{$('#errorADRESSE_MAIL').html('');}

      if($('#CONFIRMATION_EMAIL').val()=='')
      {
        statut=2;
        $('#errorCONFIRMATION_EMAIL').html('<?=lang('msg_validation')?>');
      }
      else
      {
        $('#errorCONFIRMATION_EMAIL').html('');

        if($('#CONFIRMATION_EMAIL').val()!=$('#ADRESSE_MAIL').val())
        {
          statut=2;
          $('#errorCONFIRMATION_EMAIL').html('Les emails ne correspondent pas');
        }
        else
        {
          $('#errorCONFIRMATION_EMAIL').html('');
        }
      }

    }
    else
    {
      $('#errorADRESSE_MAIL').html('<?=lang('msg_validation')?>');
    }

    if($('#PROVINCE_ID').val()=='')
    {
      statut=2;
      $('#errorPROVINCE_ID').html('<?=lang('msg_validation')?>');
    }else{$('#errorPROVINCE_ID').html('');}

    if($('#COMMUNE_ID').val()=='')
    {
      statut=2;
      $('#errorCOMMUNE_ID').html('<?=lang('msg_validation')?>');
    }else{$('#errorCOMMUNE_ID').html('');}

    if($('#ZONE_ID').val()=='')
    {
      statut=2;
      $('#errorZONE_ID').html('<?=lang('msg_validation')?>');
    }else{$('#errorZONE_ID').html('');}

    if($('#COLLINE_ID').val()=='')
    {
      statut=2;
      $('#errorCOLLINE_ID').html('<?=lang('msg_validation')?>');
    }else{$('#errorCOLLINE_ID').html('');}

    if($('#PROPRIETAIRE_ID').val()=='')
    {
      statut=2;
      $('#errorPROPRIETAIRE_ID').html('<?=lang('msg_validation')?>');
    }else{$('#errorPROPRIETAIRE_ID').html('');}

    if($('#PHOTO_PROFIL').val() =='')
    {
      if($('#PHOTO_PROFIL_NEW').val()=='')
      {
        statut=2;
        $('#errorPHOTO_PROFIL_NEW').html('<?=lang('msg_validation')?>');
      }else{$('#errorPHOTO_PROFIL_NEW').html('');}

    }
    else{$('#errorPHOTO_PROFIL_NEW').html('');}


    if($('#CARTE_ID').val() =='')
    {
      if($('#CARTE_ID_NEW').val()=='')
      {
        statut=2;
        $('#errorCARTE_ID_NEW').html('<?=lang('msg_validation')?>');
      }else{$('#errorCARTE_ID_NEW').html('');}

    }
    else{$('#errorCARTE_ID_NEW').html('');}


    if(statut==1)
    {
      $('#add_form').submit();
    }
  }

</script>


<script>

  $("#NOM").on('input', function()
  {
    $(this).val($(this).val().replace(/[^a-z-\s]/gi, '').toUpperCase());
  });

  $("#ADRESSE_PHYSIQUE").keypress(function(event) {
    var character = String.fromCharCode(event.keyCode);
    return isValid(character);     
  });

  function isValid(str) {
    return !/[~`!@#$%\^&*()+=\-\[\]\\';,/{}|\\":<>\?]/g.test(str);
  }


  $('#NUMERO_TELEPHONE').on('input change',function()
  {
    $(this).val($(this).val().replace(/[^0-9]*$/gi, ''));
    $(this).val($(this).val().replace(' ', ''));
    var subStr = this.value.substring(0,1);

    if (subStr != '+') {

      $('[name = "NUMERO_TELEPHONE"]').val('+257');

    }
    if(this.value.substring(0,4)=="+257")
    {
      if($(this).val().length == 12)
      {
        $('#errorNUMERO_TELEPHONE').text('');
      }
      else
      {
        $('#errorNUMERO_TELEPHONE').text('Numéro de téléphone est invalide ');
        if($(this).val().length > 12)
        {
          $(this).val(this.value.substring(0,12));
          $('#errorNUMERO_TELEPHONE').text('');
        }

      }
    }
    else
    {
      if ($(this).val().length > 12)
      {
        $('#errorNUMERO_TELEPHONE').text('');
      }
      else
      {
        $('#errorNUMERO_TELEPHONE').text('Invalide numéro de téléphone');
      }        
    } 
  });
</script>

<script type="text/javascript">
  document.getElementById('CONFIRMATION_EMAIL').onpaste = function()
  {
    return false;
  };
</script>
</html>