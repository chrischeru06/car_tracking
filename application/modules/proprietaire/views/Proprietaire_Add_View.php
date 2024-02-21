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

    <div class="pagetitle">
      <h1>Propriétaire</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Enregistrement</a></li>
          <!-- <li class="breadcrumb-item active">Liste</li> -->
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"></h5>
          <div class="row">
            <!-- General Form Elements -->
            <form id="add_form" enctype="multipart/form-data" method="post" action="<?=base_url('proprietaire/Proprietaire/save')?>">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545" ><b>Type de proprietaire</b><span  style="color:red;">*</span></label>
                    <input type="hidden" name="PROPRIETAIRE_ID" id="PROPRIETAIRE_ID" value="<?=$proprietaire['PROPRIETAIRE_ID']?>">
                    <select class="form-control" name="TYPE_PROPRIETAIRE_ID" id="TYPE_PROPRIETAIRE_ID" onchange="get_type_proprietaire();">
                      <option value="">-- Séléctionner --</option>
                      <option value="1" <?php if($proprietaire['TYPE_PROPRIETAIRE_ID']==1) echo "selected";?>>Personne morale</option>
                      <option value="2" <?php if($proprietaire['TYPE_PROPRIETAIRE_ID']==2) echo "selected";?>>Personne physique</option>
                    </select>
                  </div>
                  <span id="errorTYPE_PROPRIETAIRE_ID" class="text-danger"></span>
                  <?php echo form_error('TYPE_PROPRIETAIRE_ID', '<div class="text-danger">', '</div>'); ?>
                </div>



                <div class="col-md-4">
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b>Nom</b><span  style="color:red;">*</span></label>
                    <input  class="form-control" name="NOM_PROPRIETAIRE"  id="NOM_PROPRIETAIRE" placeholder='Nom' value="<?=$proprietaire['NOM_PROPRIETAIRE']?>"/>
                  </div>
                  <span id="errorNOM_PROPRIETAIRE" class="text-danger"></span>
                  <?php echo form_error('NOM_PROPRIETAIRE', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4" id="div_PRENOM_PROPRIETAIRE"<?=$div_personne_physique?>>
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b>Prénom</b><span  style="color:red;">*</span></label>
                    <input class="form-control" name="PRENOM_PROPRIETAIRE" type="text" id="PRENOM_PROPRIETAIRE" placeholder='Prénom' value="<?=$proprietaire['PRENOM_PROPRIETAIRE']?>"/>
                  </div>
                  <span id="errorPRENOM_PROPRIETAIRE" class="text-danger"></span>
                  <?php echo form_error('PRENOM_PROPRIETAIRE', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label id="label_document" style="font-weight: 1000; color:#454545"><b><?=$label_document?></b><span  style="color:white;">*</span></label>
                    <input class="form-control" name="CNI_OU_NIF" id="CNI_OU_NIF"  type="text" placeholder='<?=$label_document?>' value="<?=$proprietaire['CNI_OU_NIF']?>"/>
                  </div>
                  <span id="errorCNI_OU_NIF" class="text-danger"></span>
                  <?php echo form_error('CNI_OU_NIF','<div class="text-danger">', '</div>'); ?>
                </div>


                <div class="col-md-4" id="div_rc"<?=$div_personne_moral?>>
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b>RC</b><span  style="color:white;">*</span></label>
                    <input class="form-control" name="RC" id="RC" type="text" value="<?=$proprietaire['RC']?>"/>
                  </div>
                  <span id="errorRC" class="text-danger"></span>
                  <?php echo form_error('RC','<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4" id="div_personne_reference"<?=$div_personne_moral?>>
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b>Personne de référence</b><span  style="color:white;">*</span></label>
                    <input class="form-control" name="PERSONNE_REFERENCE" id="PERSONNE_REFERENCE" value="<?=$proprietaire['PERSONNE_REFERENCE']?>"/>
                  </div>
                  <span id="errorPERSONNE_REFERENCE" class="text-danger"></span>
                  <?php echo form_error('PERSONNE_REFERENCE','<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b>Email</b><span  style="color:red;">*</span></label>
                    <input class="form-control" type='email' name="EMAIL" id="EMAIL" placeholder='e-mail' value="<?=$proprietaire['EMAIL']?>"/>
                  </div>
                  <span id="errorEMAIL" class="text-danger"></span>
                  <?php echo form_error('EMAIL','<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b>Confirmation du mail</b><span  style="color:red;">*</span></label>
                    <input class="form-control" type='email' name="CONFIRMATION_EMAIL" id="CONFIRMATION_EMAIL" placeholder='confirmer e-mail'/>
                  </div>
                  <span id="errorCONFIRMATION_EMAIL" class="text-danger"></span>
                  <?php echo form_error('EMAIL','<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b>Téléphone</b><span  style="color:red;">*</span></label>
                    <input class="form-control" type='text' name="TELEPHONE" id="TELEPHONE" placeholder='Téléphone' value="<?=$proprietaire['TELEPHONE']?>"/>
                  </div>
                  <span id="errorTELEPHONE" class="text-danger"></span>
                  <?php echo form_error('TELEPHONE','<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b>Province</b><span  style="color:red;">*</span></label>
                    <select class="form-control" name="PROVINCE_ID" id="PROVINCE_ID" onchange="change_province();">
                      <option value="" selected>-- Séléctionner --</option>
                      <?php
                      foreach ($provinces as $province)
                      {
                        ?>
                        <option value="<?=$province['PROVINCE_ID']?>"<?php if($proprietaire['PROVINCE_ID']==$province['PROVINCE_ID']) echo " selected";?>><?=$province['PROVINCE_NAME']?></option>
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
                    <label style="font-weight: 1000; color:#454545"><b>Commune</b><span  style="color:red;">*</span></label>
                    <select class="form-control" name="COMMUNE_ID" id="COMMUNE_ID" onchange="change_commune()">
                      <option value="">-- Séléctionner --</option>
                      <?php
                      if (!empty($communes))
                      {
                        foreach ($communes as $commune)
                        {
                          ?>
                          <option value="<?=$commune['COMMUNE_ID']?>"<?php if($proprietaire['COMMUNE_ID']==$commune['COMMUNE_ID']) echo " selected";?>><?=$commune['COMMUNE_NAME']?></option>
                          <?php
                        }
                      }
                      ?>
                    </select>
                  </div>
                  <span id="errorCOMMUNE_ID" class="text-danger"></span>
                  <?php echo form_error('COMMUNE_ID', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b>Zone</b><span  style="color:red;">*</span></label>
                    <select class="form-control" name="ZONE_ID" id="ZONE_ID" onchange="change_zone();">
                      <option value="">-- Séléctionner --</option>
                      <?php
                      if (!empty($zones))
                      {
                        foreach ($zones as $zone)
                        {
                          ?>
                          <option value="<?=$zone['ZONE_ID']?>"<?php if($proprietaire['ZONE_ID']==$zone['ZONE_ID']) echo " selected";?>><?=$zone['ZONE_NAME']?></option>
                          <?php
                        }
                      }
                      ?>
                    </select>
                  </div>
                  <span id="errorZONE_ID" class="text-danger"></span>
                  <?php echo form_error('ZONE_ID', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b>Colline</b><span  style="color:red;">*</span></label>
                    <select class="form-control" name="COLLINE_ID" id="COLLINE_ID">
                      <option value="">-- Séléctionner --</option>
                      <?php
                      if (!empty($collines))
                      {
                        foreach ($collines as $colline)
                        {
                          ?>
                          <option value="<?=$colline['COLLINE_ID']?>"<?php if($proprietaire['COLLINE_ID']==$colline['COLLINE_ID']) echo " selected";?>><?=$colline['COLLINE_NAME']?></option>
                          <?php
                        }
                      }
                      ?>
                    </select>
                  </div>
                  <span id="errorCOLLINE_ID" class="text-danger"></span>
                  <?php echo form_error('COLLINE_ID', '<div class="text-danger">', '</div>'); ?>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b>Adresse</b><span  style="color:red;">*</span></label>
                    <input class="form-control" name="ADRESSE" id="ADRESSE" placeholder='Adresse' value="<?=$proprietaire['ADRESSE']?>"/>
                  </div>
                  <span id="errorADRESSE" class="text-danger"></span>
                  <?php echo form_error('ADRESSE','<div class="text-danger">', '</div>'); ?>
                </div>
                <div class="col-md-4" id="div_photo">
                  <label for="FName" style="font-weight: 1000; color:#454545">Photo passport <font color="red">*</font></label>
                  <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="photo_passport" autocomplete="off" id="photo_passport" value="<?= $proprietaire['PHOTO_PASSPORT'] ?>"  class="form-control">
                  <input type="hidden"  name="photo_passport_old" id="photo_passport_old" value="<?=$proprietaire['PHOTO_PASSPORT']?>">
                  <font id="error_photo_passport" color="red"></font>
                  <?php echo form_error('photo_passport', '<div class="text-danger">', '</div>'); ?> 
                </div>
              </div>

            </form><!-- End General Form Elements -->

          </div>
          <br>
          <br>
          <div class="row">
            <div class="col-md-12">
              <button style="float: right;" class="btn btn-outline-primary" onclick="submit_form();"><i class="fa fa-save"> <?=$btn?></i></button>
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
  if($('#TYPE_PROPRIETAIRE_ID').val()=='')
  {
    $('#div_type_societe').prop('style','display:none;');
    $('#div_PRENOM_PROPRIETAIRE').prop('style','display:none;');
    $('#div_rc').prop('style','display:none;');
    $('#div_personne_reference').prop('style','display:none;');
    $('#div_photo').prop('style','display:none;');

    // $('#div_photo').hide();
    $('#label_document').html('<b>NIF/ CNI</b> <span  style="color:white;">*</span>');
  }
  else
  {

   if($('#TYPE_PROPRIETAIRE_ID').val()==1)
   {
    $('#label_document').html('<b>NIF</b> <span  style="color:red;"> </span>');
        // $('#div_type_societe').prop('style','');
    $('#div_PRENOM_PROPRIETAIRE').prop('style','display:none;');

    $('#div_rc').prop('style','');
    $('#div_personne_reference').prop('style','');
    $('#div_photo').hide();


  }
  else
  {
    $('#label_document').html('<b>CNI</b> <span  style="color:red;">*</span>');
       // $('#div_type_societe').prop('style','display:none;');
    $('#div_PRENOM_PROPRIETAIRE').prop('style','');

    $('#div_rc').prop('style','display:none;');
    $('#div_personne_reference').prop('style','display:none;');
    $('#div_photo').show();


  }
}

});
 function get_type_proprietaire()
 {
  if($('#TYPE_PROPRIETAIRE_ID').val()=='')
  {
    $('#div_type_societe').prop('style','display:none;');
    $('#div_PRENOM_PROPRIETAIRE').prop('style','display:none;');
    $('#div_rc').prop('style','display:none;');
    $('#div_personne_reference').prop('style','display:none;');
    $('#div_photo').hide();
    $('#label_document').html('<b>NIF/ CNI</b> <span  style="color:white;">*</span>');
  }
  else
  {
    if($('#TYPE_PROPRIETAIRE_ID').val()==1)
    {
      $('#label_document').html('<b>NIF</b> <span  style="color:red;"> </span>');
        // $('#div_type_societe').prop('style','');
      $('#div_PRENOM_PROPRIETAIRE').prop('style','display:none;');

      $('#div_rc').prop('style','');
      $('#div_personne_reference').prop('style','');
      $('#div_photo').hide();


    }
    else
    {
      $('#label_document').html('<b>CNI</b> <span  style="color:red;">*</span>');
       // $('#div_type_societe').prop('style','display:none;');
      $('#div_PRENOM_PROPRIETAIRE').prop('style','');
      
      $('#div_rc').prop('style','display:none;');
      $('#div_personne_reference').prop('style','display:none;');
      $('#div_photo').show();


    }
  }
}

function change_province()
{
  if($('#PROVINCE_ID').val()=='')
  {
    $('#COMMUNE_ID').html('<option value="">-- Séléctionner --</option>');
    $('#ZONE_ID').html('<option value="">-- Séléctionner --</option>');
    $('#COLLINE_ID').html('<option value="">-- Séléctionner --</option>');
  }
  else
  {
    $('#ZONE_ID').html('<option value="">-- Séléctionner --</option>');
    $('#COLLINE_ID').html('<option value="">-- Séléctionner --</option>');
    $.ajax(
    {
      url:"<?=base_url('proprietaire/Proprietaire/get_communes/')?>"+$('#PROVINCE_ID').val(),
      type: "GET",
      dataType:"JSON",
      success: function(data)
      {
        $('#COMMUNE_ID').html(data);
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        alert('Erreur');
      }
    });
  }
}

function change_commune()
{
  if($('#COMMUNE_ID').val()=='')
  {
    $('#ZONE_ID').html('<option value="">-- Séléctionner --</option>');
    $('#COLLINE_ID').html('<option value="">-- Séléctionner --</option>');
  }
  else
  {
    $('#COLLINE_ID').html('<option value="">-- Séléctionner --</option>');
    $.ajax(
    {
      url:"<?=base_url('proprietaire/Proprietaire/get_zones/')?>"+$('#COMMUNE_ID').val(),
      type:"GET",
      dataType:"JSON",
      success: function(data)
      {
        $('#ZONE_ID').html(data);
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        alert('Erreur');
      }
    });
  }
}

function change_zone()
{
  if($('#ZONE_ID').val()=='')
  {
    $('#COLLINE_ID').html('<option value="">-- Séléctionner --</option>');
  }
  else
  {
    $.ajax(
    {
      url:"<?=base_url('proprietaire/Proprietaire/get_collines/')?>"+$('#ZONE_ID').val(),
      type:"GET",
      dataType:"JSON",
      success: function(data)
      {
        $('#COLLINE_ID').html(data);
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        alert('Erreur');
      }
    });
  }
}

function submit_form()
{
  const photopassport = document.getElementById('photo_passport');
  var mail = document.getElementById("EMAIL").value;
  var mail2 = document.getElementById("CONFIRMATION_EMAIL").value;
  var form = document.getElementById("add_form");
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  var statut=1;

  if($('#TYPE_PROPRIETAIRE_ID').val()=='')
  {
    statut=2;
    $('#errorTYPE_PROPRIETAIRE_ID').html('Le champ est obligatoire');
  }else{$('#errorTYPE_PROPRIETAIRE_ID').html('');}

  if($('#NOM_PROPRIETAIRE').val()=='')
  {
    statut=2;
    $('#errorNOM_PROPRIETAIRE').html('Le champ est obligatoire');
  }else{$('#errorNOM_PROPRIETAIRE').html('');}


  if($('#TYPE_PROPRIETAIRE_ID').val()==1)
  {


  }

  if($('#TYPE_PROPRIETAIRE_ID').val()==2)
  {
    if($('#PRENOM_PROPRIETAIRE').val()=='')
    {
      statut=2;
      $('#errorPRENOM_PROPRIETAIRE').html('Le champ est obligatoire');
    }else{
      $('#errorPRENOM_PROPRIETAIRE').html('');
    }

    if(photopassport.files.length === 0 && $('#photo_passport_old').val()=='')
    {
      statut=2;
      $('#error_photo_passport').text("Le champ est obligatoire");
    }
  }




  if($('#EMAIL').val() !='')
  {
    if(!emailReg.test($('#EMAIL').val()))
    {
      $('#errorEMAIL').html('Email invalide!');
      statut=2
    }
    else{$('#errorEMAIL').html('');}



    if($('#CONFIRMATION_EMAIL').val()=='')
    {
      statut=2;
      $('#errorCONFIRMATION_EMAIL').html('Le champ est obligatoire');
    }
    else
    {
      $('#errorCONFIRMATION_EMAIL').html('');

      if($('#CONFIRMATION_EMAIL').val()!=$('#EMAIL').val())
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


    statut=2;
    $('#errorEMAIL').html('Le champ est obligatoire');
  }

  if($('#TELEPHONE').val()=='')
  {
    statut=2;
    $('#errorTELEPHONE').html('Le champ est obligatoire');
  }else{$('#errorTELEPHONE').html('');}

  if($('#PROVINCE_ID').val()=='')
  {
    statut=2;
    $('#errorPROVINCE_ID').html('Le champ est obligatoire');
  }else{$('#errorPROVINCE_ID').html('');}

  if($('#COMMUNE_ID').val()=='')
  {
    statut=2;
    $('#errorCOMMUNE_ID').html('Le champ est obligatoire');
  }else{$('#errorCOMMUNE_ID').html('');}

  if($('#ZONE_ID').val()=='')
  {
    statut=2;
    $('#errorZONE_ID').html('Le champ est obligatoire');
  }else{$('#errorZONE_ID').html('');}

  if($('#COLLINE_ID').val()=='')
  {
    statut=2;
    $('#errorCOLLINE_ID').html('Le champ est obligatoire');
  }else{$('#errorCOLLINE_ID').html('');}

  if($('#ADRESSE').val()=='')
  {
    statut=2;
    $('#errorADRESSE').html('Le champ est obligatoire');
  }else{$('#errorADRESSE').html('');}

      // alert(statut)

  if(statut==1)
  {
    $('#add_form').submit();
  }
}

</script>
<script type="text/javascript">
  document.getElementById('CONFIRMATION_EMAIL').onpaste = function()
  {
    return false;
  };

</script>


<script>

  $("#NOM_PROPRIETAIRE,#PRENOM_PROPRIETAIRE,#PERSONNE_REFERENCE").on('input', function()
  {
    $(this).val($(this).val().toUpperCase());
  });

  $("#RC,#CNI_OU_NIF").on('input', function()
  {
    var perso_physk=$('#TYPE_PROPRIETAIRE_ID').val();
    if (perso_physk==1) {

      $(this).val($(this).val().replace(/[^0-9]*$/gi, ''));
    }
  });

    // $("#ADRESSE").on('input', function()
    // {
    //   $(this).val($(this).val().replace(/[^0-9a-z]*$/gi, ''));
    // });

  $("#ADRESSE").keypress(function(event) {
    var character = String.fromCharCode(event.keyCode);
    return isValid(character);     
  });

  function isValid(str) {
    return !/[~`!@#$%\^&*()+=\-\[\]\\';,/{}|\\":<>\?]/g.test(str);
  }


  $('#TELEPHONE').on('input change',function()
  {
    $(this).val($(this).val().replace(/[^0-9]*$/gi, ''));
    $(this).val($(this).val().replace(' ', ''));
    var subStr = this.value.substring(0,1);

    if (subStr != '+') {
        //$('[name = "TELEPHONE"]').val('+257');
      $('[name = "TELEPHONE"]').val('+257');

    }
    if(this.value.substring(0,4)=="+257")
    {
      if($(this).val().length == 12)
      {
        $('#errorTELEPHONE').text('');
      }
      else
      {
        $('#errorTELEPHONE').text('Numéro de téléphone est invalide ');
        if($(this).val().length > 12)
        {
          $(this).val(this.value.substring(0,12));
          $('#errorTELEPHONE').text('');
        }

      }
    }
    else
    {
      if ($(this).val().length > 12)
      {
        $('#errorTELEPHONE').text('');
      }
      else
      {
        $('#errorTELEPHONE').text('Invalide numéro de téléphone');
      }        
    } 
  });
</script>

</html>