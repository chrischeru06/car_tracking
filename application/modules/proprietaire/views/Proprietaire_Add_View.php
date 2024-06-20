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
    <center>
      <div class="pagetitle">
        <h1><?=$title?></h1>
        <nav>
          <ol class="breadcrumb">
            <!-- <li class="breadcrumb-item"><a href="index.html">Enregistrement</a></li> -->
            <!-- <li class="breadcrumb-item active">Liste</li> -->
          </ol>
        </nav>
      </div><!-- End Page Title -->
    </center>
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
                    <label style="font-weight: 1000; color:#454545" ><b><?=lang('input_type_proprio')?></b><span  style="color:red;">*</span></label>
                    <input type="hidden" name="PROPRIETAIRE_ID" id="PROPRIETAIRE_ID" value="<?=$proprietaire['PROPRIETAIRE_ID']?>">
                    <select class="form-control" name="TYPE_PROPRIETAIRE_ID" id="TYPE_PROPRIETAIRE_ID" onchange="get_type_proprietaire();">
                      <option value="">-- <?=lang('selectionner')?> --</option>
                      <option value="1" <?php if($proprietaire['TYPE_PROPRIETAIRE_ID']==1) echo "selected";?>><?=lang('select_pers_morale')?></option>
                      <option value="2" <?php if($proprietaire['TYPE_PROPRIETAIRE_ID']==2) echo "selected";?>><?=lang('select_pers_physique')?></option>
                    </select>
                  </div>
                  <span id="errorTYPE_PROPRIETAIRE_ID" class="text-danger"></span>
                  <?php echo form_error('TYPE_PROPRIETAIRE_ID', '<div class="text-danger">', '</div>'); ?>
                </div>



                <div class="col-md-4" >
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b><?=lang('input_nom')?></b><span  style="color:red;">*</span></label>
                    <input  class="form-control" name="NOM_PROPRIETAIRE"  id="NOM_PROPRIETAIRE" placeholder='<?=lang('input_nom')?>' value="<?=$proprietaire['NOM_PROPRIETAIRE']?>"/>
                  </div>
                  <span id="errorNOM_PROPRIETAIRE" class="text-danger"></span>

                  <?php echo form_error('NOM_PROPRIETAIRE', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4" id="div_categor">
                  <label for="genre" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('input_categorie')?> <font color="red">*</font></label>
                  <select class="form-control" name="CATEGORIE_ID" id="CATEGORIE_ID">
                    <option value="">---<?=lang('selectionner')?>---</option>
                    <?php 

                    foreach ($catego as $value)
                    {
                      if ($value['CATEGORIE_ID']==$proprietaire['CATEGORIE_ID'])
                      {
                        ?>
                        <option value="<?= $value['CATEGORIE_ID']?>" selected><?=$value['DESC_CATEGORIE']?></option>
                        <?php
                      }
                      else
                      {
                        ?>
                        <option value="<?=$value['CATEGORIE_ID']?>"><?=$value['DESC_CATEGORIE']?></option>
                        <?php
                      }
                    }
                    ?>
                  </select>
                  <font id="error_CATEGORIE_ID" color="red"></font>
                  <?php echo form_error('CATEGORIE_ID', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4" id="div_PRENOM_PROPRIETAIRE"<?=$div_personne_physique?>>
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b><?=lang('input_prenom')?></b><span  style="color:red;">*</span></label>
                    <input class="form-control" name="PRENOM_PROPRIETAIRE" type="text" id="PRENOM_PROPRIETAIRE" placeholder='<?=lang('input_prenom')?>' value="<?=$proprietaire['PRENOM_PROPRIETAIRE']?>"/>
                  </div>
                  <span id="errorPRENOM_PROPRIETAIRE" class="text-danger"></span>
                  <?php echo form_error('PRENOM_PROPRIETAIRE', '<div class="text-danger">', '</div>'); ?>
                </div>
                <br>
                <br>
                <br>

                <div class="col-md-4">
                  <div class="form-group">
                    <label id="label_document" style="font-weight: 1000; color:#454545"><b><?=$label_document?></b><span  style="color:red;">*</span></label>
                    <input class="form-control" name="CNI_OU_NIF" id="CNI_OU_NIF"  type="text" placeholder="<?=$label_document?>" value="<?=$proprietaire['CNI_OU_NIF']?>"/>
                  </div>
                  <span id="errorCNI_OU_NIF" class="text-danger"></span>
                  <?php echo form_error('CNI_OU_NIF','<div class="text-danger">', '</div>'); ?>
                </div>
                <br>
                <br>
                <br>


                <div class="col-md-4" id="div_rc"<?=$div_personne_moral?>>
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b><?=lang('input_rc')?></b><span  style="color:red;">*</span></label>
                    <input class="form-control" name="RC" id="RC" type="text" value="<?=$proprietaire['RC']?>"/>
                  </div>
                  <span id="errorRC" class="text-danger"></span>
                  <?php echo form_error('RC','<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4" id="div_personne_reference"<?=$div_personne_moral?>>
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b><?=lang('input_pers_reference')?></b><span  style="color:white;">*</span></label>
                    <input class="form-control" name="PERSONNE_REFERENCE" id="PERSONNE_REFERENCE" value="<?=$proprietaire['PERSONNE_REFERENCE']?>"/>
                  </div>
                  <span id="errorPERSONNE_REFERENCE" class="text-danger"></span>
                  <?php echo form_error('PERSONNE_REFERENCE','<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4" id="div_fich_cni">
                  <label for="FName" style="font-weight: 1000; color:#454545"><?=lang('input_cni_passeport')?> <font color="red">*</font></label>
                  <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF" title='<?=lang('title_file')?>' name="FILE_CNI_PASSPORT" autocomplete="off" id="FILE_CNI_PASSPORT" value="<?= $proprietaire['FILE_CNI_PASSPORT'] ?>"  class="form-control">
                  <input type="hidden"  name="FILE_CNI_PASSPORT_OLD" id="FILE_CNI_PASSPORT_OLD" value="<?=$proprietaire['FILE_CNI_PASSPORT']?>">
                  <font id="error_FILE_CNI_PASSPORT" color="red"></font>
                  <?php echo form_error('FILE_CNI_PASSPORT', '<div class="text-danger">', '</div>'); ?> 
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b><?=lang('input_email')?></b><span  style="color:red;">*</span></label>
                    <input class="form-control" type='email' name="EMAIL" id="EMAIL" placeholder='<?=lang('input_email')?>' value="<?=$proprietaire['EMAIL']?>"/>
                  </div>
                  <span id="errorEMAIL" class="text-danger"></span>
                  <?php echo form_error('EMAIL','<div class="text-danger">', '</div>'); ?>
                </div>

                <br>
                <br>
                <br>

                <div class="col-md-4">
                  <div class="form-group">
                    <label style="font-weight: 1000; color:#454545"><b><?=lang('input_conf_mail')?></b><span  style="color:red;">*</span></label>
                    <input class="form-control" type='email' name="CONFIRMATION_EMAIL" id="CONFIRMATION_EMAIL"  value="<?=$proprietaire['EMAIL']?>"placeholder='<?=lang('input_conf_mail')?>'/>
                  </div>
                  <span id="errorCONFIRMATION_EMAIL" class="text-danger"></span>
                  <?php echo form_error('EMAIL','<div class="text-danger">', '</div>'); ?>
                </div>
                <div class="col-md-4">
                  <label style="font-weight: 1000; color:#454545"><?=lang('input_pays')?> <span style="color:red;">*</span></label>
                  <div class="input-group has-validation">

                    <select onchange="localisation();" class="form-control" name="COUNTRY_ID" id="COUNTRY_ID">
                     <option value=""><?=lang('selectionner')?></option>

                     <?php
                     foreach($pays as $key) { 
                      if ($key['COUNTRY_ID']==$proprietaire['COUNTRY_ID']) { 
                       echo "<option value='".$key['COUNTRY_ID']."' selected>".$key['CommonName']."</option>";
                     }  else{
                       echo "<option value='".$key['COUNTRY_ID']."' >".$key['CommonName']."</option>"; 
                     } }?>
                   </select>

                   <div class="valid-feedback">
                   </div>
                 </div>
                 <span class="text-danger" id="errorcountry"></span>
                 <?php echo form_error('COUNTRY_ID','<div class="text-danger">', '</div>'); ?>


               </div>

               <br>
               <br>
               <br>
               <div class="col-md-4">
                <label style="font-weight: 1000; color:#454545"><?=lang('input_code_pays')?> <span style="color:red;">*</span></label>
                <select class="form-control selectize" data-live-search="true" id="ITU-T_Telephone_Code_1" name="ITU-T_Telephone_Code">
                  <option value="" class="text-dark"><?=lang('selectionner')?></option>
                  <?php
                  
                  foreach ($countries1 as $key) {
                    $selected='';
                    if($key['ITU-T_Telephone_Code']==set_value('ITU-T_Telephone_Code'))
                    {
                      $selected='selected';
                    }
                    echo '<option value="'.$key['ITU-T_Telephone_Code'].'" '.$selected.'>'.$key['CommonName'].'</option>';
                  }
                  ?>
                </select>
                <font color="red" id="errorpays"></font>
              </div>
              <br>
              <br>
              <br>
              <div class="col-md-4">
                <div class="form-group">
                  <label style="font-weight: 1000; color:#454545"><b><?=lang('input_tlphone')?></b><span  style="color:red;">*</span></label>
                  <input class="form-control bg-light" type='tel' name="TELEPHONE" id="TELEPHONE" value="<?=$proprietaire['TELEPHONE']?>" pattern="^[0-9-+\s()]*$"/>
                </div>

                <span id="errorTELEPHONE" class="text-danger"></span>
                <?php echo form_error('TELEPHONE','<div class="text-danger">', '</div>'); ?>
              </div>

              <div class="col-md-4" id="div_prov">
                <div class="form-group">
                  <label style="font-weight: 1000; color:#454545"><b><?=lang('input_province')?></b><span  style="color:red;">*</span></label>
                  <select class="form-control" name="PROVINCE_ID" id="PROVINCE_ID" onchange="change_province();">
                    <option value="" selected><?=lang('selectionner')?></option>
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
              <div class="col-md-4" id="div_com">
                <div class="form-group">
                  <label style="font-weight: 1000; color:#454545"><b><?=lang('input_commune')?></b><span  style="color:red;">*</span></label>
                  <select class="form-control" name="COMMUNE_ID" id="COMMUNE_ID" onchange="change_commune()">
                    <option value="">-- <?=lang('selectionner')?> --</option>
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

              <div class="col-md-4" id="div_zon">
                <div class="form-group">
                  <label style="font-weight: 1000; color:#454545"><b><?=lang('input_zone')?></b><span  style="color:red;">*</span></label>
                  <select class="form-control" name="ZONE_ID" id="ZONE_ID" onchange="change_zone();">
                    <option value="">-- <?=lang('selectionner')?> --</option>
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

              <div class="col-md-4" id="div_col">
                <div class="form-group">
                  <label style="font-weight: 1000; color:#454545"><b><?=lang('input_colline')?></b><span  style="color:red;">*</span></label>
                  <select class="form-control" name="COLLINE_ID" id="COLLINE_ID">
                    <option value="">-- <?=lang('selectionner')?> --</option>
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
                  <label style="font-weight: 1000; color:#454545"><b><?=lang('input_adresse')?></b><span  style="color:red;">*</span></label>
                  <input class="form-control" name="ADRESSE" id="ADRESSE" placeholder='<?=lang('input_adresse')?>' value="<?=$proprietaire['ADRESSE']?>"/>
                </div>
                <span id="errorADRESSE" class="text-danger"></span>
                <?php echo form_error('ADRESSE','<div class="text-danger">', '</div>'); ?>
              </div>
              <div class="col-md-4" id="div_photo">
                <label for="FName" style="font-weight: 1000; color:#454545"><?=lang('input_photo_passport')?> <font color="red">*</font></label>
                <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" title='<?=lang('title_file')?>' name="photo_passport" autocomplete="off" id="photo_passport" value="<?= $proprietaire['PHOTO_PASSPORT'] ?>"  class="form-control">
                <input type="hidden"  name="photo_passport_old" id="photo_passport_old" value="<?=$proprietaire['PHOTO_PASSPORT']?>">
                <font id="error_photo_passport" color="red"></font>
                <?php echo form_error('photo_passport', '<div class="text-danger">', '</div>'); ?> 
              </div>

              <div class="col-md-4" id="div_logo">
                <label for="FName" style="font-weight: 1000; color:#454545"><?=lang('input_logo')?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(.png,.jpg,.jepg) <font color="red">*</font></label>
                <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" class="form-control" title='<?=lang('title_file')?>'  name="LOGO" autocomplete="off" id="LOGO" value="<?= $proprietaire['LOGO'] ?>"  class="form-control">
                <input type="hidden"  name="LOGO_OLD" id="LOGO_OLD" value="<?=$proprietaire['LOGO']?>">
                <font id="error_LOGO" color="red"></font>
                <?php echo form_error('LOGO', '<div class="text-danger">', '</div>'); ?> 
              </div>
              <div class="col-md-4" id="div_doc_rc">
                <label for="FName" style="font-weight: 1000; color:#454545"><?=lang('input_doc_rc')?> (.png,.jpg,.jepg) <font color="red">*</font></label>
                <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg,.PDF,.pdf" class="form-control" title='<?=lang('title_file')?>' name="FILE_RC" autocomplete="off" id="FILE_RC" value="<?= $proprietaire['FILE_RC'] ?>"  class="form-control">
                <input type="hidden"  name="FILE_RC_OLD" id="FILE_RC_OLD" value="<?=$proprietaire['FILE_RC']?>">
                <font id="error_FICHIER_RC" color="red"></font>
                <?php echo form_error('FILE_RC', '<div class="text-danger">', '</div>'); ?> 
              </div>
              <div class="col-md-4" id="div_doc_nif">
                <label for="FName" style="font-weight: 1000; color:#454545"><?=lang('input_doc_nif')?> (.png,.jpg,.jepg) <font color="red">*</font></label>
                <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg,.PDF,.pdf" class="form-control" title='<?=lang('title_file')?>' name="FILE_NIF" autocomplete="off" id="FILE_NIF" value="<?= $proprietaire['FILE_NIF'] ?>"  class="form-control">
                <input type="hidden"  name="FILE_NIF_OLD" id="FILE_NIF_OLD" value="<?=$proprietaire['FILE_NIF']?>">
                <font id="error_FILE_NIF" color="red"></font>
                <?php echo form_error('FILE_NIF', '<div class="text-danger">', '</div>'); ?> 
              </div>

            </div>

          </form><!-- End General Form Elements -->

        </div>
        <br>
        <br>
        <div class="row">
          <div class="col-md-12">
            <button style="float: right;" class="btn btn-outline-primary rounded-pill" onclick="submit_form();"><i class="fa fa-save"></i> <?=$btn?></button>
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
    $('#div_nompro').prop('style','display:none;');

    $('#div_PRENOM_PROPRIETAIRE').prop('style','display:none;');
    $('#div_rc').prop('style','display:none;');
    $('#div_personne_reference').prop('style','display:none;');
    $('#div_photo').prop('style','display:none;');

    // $('#div_photo').hide();
    $('#label_document').html('<b><?=lang('input_nif_cni')?></b> <span  style="color:red;">*</span>');
    $('#div_prov').attr('hidden',true); 
    $('#div_com').attr('hidden',true);  
    $('#div_zon').attr('hidden',true);  
    $('#div_col').attr('hidden',true); 
    $('#div_logo').hide();
    $('#div_doc_rc').hide();
    $('#div_doc_nif').hide();
    $('#div_fich_cni').hide();
    $('#div_nomsoci').hide();
    $('#div_nompro').hide();
    $('#div_categor').hide();

  }
  else
  {

   if($('#TYPE_PROPRIETAIRE_ID').val()==1)
   {
    $('#label_document').html('<b><?=lang('input_nif')?></b> <span  style="color:red;">*</span>');
        // $('#div_type_societe').prop('style','');
    $('#div_PRENOM_PROPRIETAIRE').prop('style','display:none;');
    $('#div_nompro').prop('style','display:none;');


    $('#div_rc').prop('style','');
    $('#div_personne_reference').prop('style','');
    $('#div_photo').hide();
    $('#div_logo').show();
    $('#div_categor').show();
    $('#div_doc_rc').show();
    $('#div_doc_nif').show();
    $('#div_nomsoci').show();
    $('#div_nompro').hide();
    $('#div_fich_cni').hide();

  }
  else
  {
    $('#label_document').html('<b><?=lang('input_cni_numeropasseport')?></b> <span  style="color:red;">*</span>');
       // $('#div_type_societe').prop('style','display:none;');
    $('#div_PRENOM_PROPRIETAIRE').prop('style','');
    $('#div_nompro').prop('style','');
    $('#div_rc').prop('style','display:none;');
    $('#div_personne_reference').prop('style','display:none;');
    $('#div_photo').show();
    $('#div_logo').hide();
    $('#div_doc_rc').hide();
    $('#div_doc_nif').hide();
    $('#div_categor').hide();
    $('#div_nomsoci').hide();
    $('#div_fich_cni').show();

  }
}

});
 function get_type_proprietaire()
 {
  if($('#TYPE_PROPRIETAIRE_ID').val()=='')
  {
    $('#div_type_societe').prop('style','display:none;');
    $('#div_PRENOM_PROPRIETAIRE').prop('style','display:none;');
    // $('#div_nompro').prop('style','display:none;');
    // $('#div_rc').prop('style','display:none;');
    $('#div_personne_reference').prop('style','display:none;');
    $('#div_photo').hide();
    $('#label_document').html('<b><?=lang('input_nif_cni')?></b> <span  style="color:red;">*</span>');
    $('#div_logo').hide();
    $('#div_doc_rc').hide();
    $('#div_doc_nif').hide();
    $('#div_categor').hide();
    $('#div_nomsoci').hide();

    $('#div_fich_cni').hide();


  }
  else
  {
    if($('#TYPE_PROPRIETAIRE_ID').val()==1)
    {
      $('#label_document').html('<b>NIF</b> <span  style="color:red;">*</span>');
        // $('#div_type_societe').prop('style','');
      $('#div_PRENOM_PROPRIETAIRE').prop('style','display:none;');
      $('#div_nompro').prop('style','display:none;');

      $('#div_rc').prop('style','');
      $('#div_personne_reference').prop('style','');
      $('#div_photo').hide();
      $('#div_logo').show();
      $('#div_doc_rc').show();
      $('#div_doc_nif').show();
      $('#div_categor').show();
      $('#div_nomsoci').show();
      $('#div_fich_cni').hide();




    }
    else
    {
      $('#label_document').html('<b><?=lang('input_cni_numeropasseport')?></b> <span  style="color:red;">*</span>');
       // $('#div_type_societe').prop('style','display:none;');
      $('#div_nompro').prop('style','');
      $('#div_PRENOM_PROPRIETAIRE').prop('style','');

      
      $('#div_rc').prop('style','display:none;');
      $('#div_personne_reference').prop('style','display:none;');
      $('#div_photo').show();
      $('#div_logo').hide();
      $('#div_doc_rc').hide();
      $('#div_doc_nif').hide();
      $('#div_categor').hide();
      $('#div_nomsoci').hide();

      $('#div_fich_cni').show();




    }
  }
}

function change_province()
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
      url:"<?=base_url('proprietaire/Proprietaire/get_communes/')?>"+$('#PROVINCE_ID').val(),
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

function change_commune()
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
      url:"<?=base_url('proprietaire/Proprietaire/get_zones/')?>"+$('#COMMUNE_ID').val(),
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

function change_zone()
{
  if($('#ZONE_ID').val()=='')
  {
    $('#COLLINE_ID').html('<option value="">-- <?=lang('selectionner')?> --</option>');
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
        alert('<?=lang('msg_erreur')?>');
      }
    });
  }
}

function submit_form()
{
  const photopassport = document.getElementById('photo_passport');
  const logo = document.getElementById('LOGO');
  const nif = document.getElementById('FILE_NIF');
  const rc = document.getElementById('FILE_RC');

  const file_cni_passport = document.getElementById('FILE_CNI_PASSPORT');

  var mail = document.getElementById("EMAIL").value;
  var mail2 = document.getElementById("CONFIRMATION_EMAIL").value;
  var form = document.getElementById("add_form");
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  var statut=1;

  var maxSize = 2 * 1024 * 1024; // Taille maximale en octets (2 Mo)

  if ($('#ITU-T_Telephone_Code_1').val()=='') {
    $('#errorpays').text("<?=lang('msg_validation')?>");
    statut=2;
  }else{

    $('#errorpays').html('');

  }

  var COUNTRY_ID=$('#COUNTRY_ID').val() ;
//input commun
  if($('#COUNTRY_ID').val()=='')
  {
    statut=2;
    $('#errorcountry').html('<?=lang('msg_validation')?>');
  }else{$('#errorcountry').html('');}
  if($('#EMAIL').val() !='')
  {
    if(!emailReg.test($('#EMAIL').val()))
    {
      $('#errorEMAIL').html('<?=lang('msg_validation_mail')?>');
      statut=2
    }
    else{$('#errorEMAIL').html('');}



    if($('#CONFIRMATION_EMAIL').val()=='')
    {
      statut=2;
      $('#errorCONFIRMATION_EMAIL').html('<?=lang('msg_validation')?>');
    }
    else
    {
      $('#errorCONFIRMATION_EMAIL').html('');

      if($('#CONFIRMATION_EMAIL').val()!=$('#EMAIL').val())
      {
        statut=2;

        $('#errorCONFIRMATION_EMAIL').html('<?=lang('msg_validation_mail_correspondance')?>');
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
    $('#errorEMAIL').html('<?=lang('msg_validation')?>');
  }

  if($('#TELEPHONE').val()=='')
  {
    statut=2;
    $('#errorTELEPHONE').html('<?=lang('msg_validation')?>');
  }else{$('#errorTELEPHONE').html('');}

  if (COUNTRY_ID==28) {

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

  }

  if($('#ADRESSE').val()=='')
  {
    statut=2;
    $('#errorADRESSE').html('<?=lang('msg_validation')?>');
  }else{$('#errorADRESSE').html('');}


  if($('#TYPE_PROPRIETAIRE_ID').val()=='')
  {
    statut=2;
    $('#errorTYPE_PROPRIETAIRE_ID').html('<?=lang('msg_validation')?>');
  }else{$('#errorTYPE_PROPRIETAIRE_ID').html('');}

  if($('#NOM_PROPRIETAIRE').val()=='')
  {
    statut=2;
    $('#errorNOM_PROPRIETAIRE').html('<?=lang('msg_validation')?>');
  }else{$('#errorNOM_PROPRIETAIRE').html('');}

  
//input type_moral
  if($('#TYPE_PROPRIETAIRE_ID').val()==1)
  {

    if($('#RC').val()=='')
    {
      statut=2;
      $('#errorRC').html('<?=lang('msg_validation')?>');
    }else{
      $('#errorRC').html('');
    }
    if($('#CATEGORIE_ID').val()=='')
    {
      statut=2;
      $('#error_CATEGORIE_ID').html('<?=lang('msg_validation')?>');
    }else{$('#error_CATEGORIE_ID').html('');}


    if($('#CNI_OU_NIF').val()=='')
    {
      statut=2;
      $('#errorCNI_OU_NIF').html('Le champ est obligatoire');
    }else{
      $('#errorCNI_OU_NIF').html('');
    }

    if(logo.files.length === 0 && $('#LOGO_OLD').val()=='')
    {
      statut=2;
      $('#error_LOGO').text("<?=lang('msg_validation')?>");
    }else{
      $('#error_LOGO').text('');

    }

    if(rc.files.length === 0 && $('FILE_RC_OLD').val()=='')
    {
      statut=2;
      $('#error_FICHIER_RC').text("<?=lang('msg_validation')?>");
    }else{
      $('#error_FICHIER_RC').text('');
    }


    if(nif.files.length === 0 && $('#FILE_NIF_OLD').val()=='')
    {
      statut=2;
      $('#error_FILE_NIF').text("<?=lang('msg_validation')?>");
    }else{
      $('#error_FILE_NIF').text('');
    }

    var fileLogo = logo.files[0];

      var fileSizeLogo = fileLogo.size; // Taille du fichier en octets

      if(fileSizeLogo > maxSize)
      {
        statut=2;
        $('#error_LOGO').html('<?=lang('msg_taille_fich')?>');
      }else{$('#error_LOGO').html('');}

      var fileRC = rc.files[0];

      var fileSizeRC = fileRC.size; // Taille du fichier en octets

      if(fileSizeRC > maxSize)
      {
        statut=2;
        $('#error_FICHIER_RC').html('<?=lang('msg_taille_fich')?>');
      }else{$('#error_FICHIER_RC').html('');}


      var fileNIF = nif.files[0];

      var fileSizeNIF = fileNIF.size; // Taille du fichier en octets

      if(fileSizeNIF > maxSize)
      {
        statut=2;
        $('#error_FILE_NIF').html('<?=lang('msg_taille_fich')?>');
      }else{$('#error_FILE_NIF').html('');}


      if($('#PROPRIETAIRE_ID').val() != '') //Modification perso moral
      {


        if($('#LOGO').val() != '')
        {

          var fileLogo = logo.files[0];

          var fileSizeLogo = fileLogo.size; // Taille du fichier en octets

          if(fileSizeLogo > maxSize)
          {
            statut=2;
            $('#error_LOGO').html('<?=lang('msg_taille_fich')?>');
          }else{$('#error_LOGO').html('');}
        }


        if($('#FILE_RC').val() != '')
        {
          var fileRC = rc.files[0];

          var fileSizeRC = fileRC.size; // Taille du fichier en octets

          if(fileSizeRC > maxSize)
          {
            statut=2;
            $('#error_FICHIER_RC').html('<?=lang('msg_taille_fich')?>');
          }else{$('#error_FICHIER_RC').html('');}
        }


        if($('#FILE_NIF').val() != '')
        {
          var fileNIF = nif.files[0];

          var fileSizeNIF = fileNIF.size; // Taille du fichier en octets

          if(fileSizeNIF > maxSize)
          {
            statut=2;
            $('#error_FILE_NIF').html('<?=lang('msg_taille_fich')?>');
          }else{$('#error_FILE_NIF').html('');}
        }



      }


    }


//input type_physique
    if($('#TYPE_PROPRIETAIRE_ID').val()==2)
    {
      if($('#PRENOM_PROPRIETAIRE').val()=='')
      {
        statut=2;
        $('#errorPRENOM_PROPRIETAIRE').html('<?=lang('msg_validation')?>');
      }else{
        $('#errorPRENOM_PROPRIETAIRE').html('');
      }


      if(photopassport.files.length === 0 && $('#photo_passport_old').val()=='')
      {
        statut=2;
        $('#error_photo_passport').text("<?=lang('msg_validation')?>");
      }else{
        $('#error_photo_passport').html('');
      }

      if(file_cni_passport.files.length === 0 && $('#FILE_CNI_PASSPORT_OLD').val()=='')
      {
        statut=2;
        $('#error_FILE_CNI_PASSPORT').text("<?=lang('msg_validation')?>");
      }else{
        $('#error_FILE_CNI_PASSPORT').html('');
      }

      var filePhotopassport = photopassport.files[0];

      var fileSizePhotopassport = filePhotopassport.size; // Taille du fichier en octets

      if(fileSizePhotopassport > maxSize)
      {
        statut=2;
        $('#error_photo_passport').html('<?=lang('msg_taille_fich')?>');
      }else{$('#error_photo_passport').html('');}


      var fileCNI_passport = file_cni_passport.files[0];

      var fileSizeCNI_passport = fileCNI_passport.size; // Taille du fichier en octets

      if(fileSizeCNI_passport > maxSize)
      {
        statut=2;
        $('#error_FILE_CNI_PASSPORT').html('<?=lang('msg_taille_fich')?>');
      }else{$('#error_FILE_CNI_PASSPORT').html('');}



      if($('#PROPRIETAIRE_ID').val() != '') //Modification perso physique
      {

        if($('#photo_passport').val() != '')
        {
          var filePhotopassport = photopassport.files[0];

          var fileSizePhotopassport = filePhotopassport.size; // Taille du fichier en octets

          if(fileSizePhotopassport > maxSize)
          {
            statut=2;
            $('#error_photo_passport').html('<?=lang('msg_taille_fich')?>');
          }else{$('#error_photo_passport').html('');}
        }

        if($('#FILE_CNI_PASSPORT').val() != '')
        {
          var fileCNI_passport = file_cni_passport.files[0];

          var fileSizeCNI_passport = fileCNI_passport.size; // Taille du fichier en octets

          if(fileSizeCNI_passport > maxSize)
          {
            statut=2;
            $('#error_FILE_CNI_PASSPORT').html('<?=lang('msg_taille_fich')?>');
          }else{$('#error_FILE_CNI_PASSPORT').html('');}
        }


      }


    }



      // alert(statut)

    if(statut==1)
    {
      $('#add_form').submit();
    }
  }


  function localisation(){
    var COUNTRY_ID=$('#COUNTRY_ID').val() ;

    if (COUNTRY_ID==28) {
     $('#div_prov').attr('hidden',false);
     $('#div_com').attr('hidden',false);
     $('#div_zon').attr('hidden',false);
     $('#div_col').attr('hidden',false);

   }else{
    $('#div_prov').attr('hidden',true); 
    $('#div_com').attr('hidden',true);  
    $('#div_zon').attr('hidden',true);  
    $('#div_col').attr('hidden',true);  

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

  $("#CNI_OU_NIF").on('input', function()
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

  $("#ADRESSE,#RC").keypress(function(event) {
    var character = String.fromCharCode(event.keyCode);
    return isValid(character);     
  });

  function isValid(str) {
    return !/[~`!@#$%\^&*()+=\-\[\]\\';,/{}|\\":<>\?]/g.test(str);
  }



</script>

<script type="text/javascript">

  function DoPrevent(e)
  {
    e.preventDefault();
    e.stopPropagation();
  }




  $('#ITU-T_Telephone_Code_1').on('change',function()
  {
    $('[name = "TELEPHONE"]').val(this.value);
  });
  $('#TELEPHONE').on('input change keypress',function()
  {
    $(this).val($(this).val().replace(/[^0-9]*$/gi, ''));
    $(this).val($(this).val().replace(' ', ''));
    let tel_code = $('#ITU-T_Telephone_Code_1').val();
    let tel_code_ln = tel_code.length;
    var subStr = this.value.substring(0, tel_code_ln);
    if (subStr != tel_code)
    {
      $('[name = "TELEPHONE"]').val(tel_code);
    }
    if (tel_code == '+257')
    {
      if ($(this).val().length == 12)
      {
            // Bind:
        $('#TELEPHONE').on('keypress', DoPrevent);
        $('#TELEPHONE').text('')
        $('[name = "TELEPHONE"]').removeClass('is-invalid').addClass('is-valid');
      }
      else
      {

        $('#TELEPHONE').off('keypress', DoPrevent);
        $('#TELEPHONE').text('invalide');
        $('[name = "TELEPHONE"]').removeClass('is-valid').addClass('is-invalid');
      }
    }
    else
    {
      if ($(this).val().length >= 10)
      {
        $('#TELEPHONE').off('keypress', DoPrevent);
        $('#TELEPHONE').text('')
        $('[name = "TELEPHONE"]').removeClass('is-invalid').addClass('is-valid');
      }
      else
      {
        $('#TELEPHONE').off('keypress', DoPrevent);
        $('#TELEPHONE').text('<?=lang('tel_invalide')?>');
        $('[name = "TELEPHONE"]').removeClass('is-valid').addClass('is-invalid');
      }
    }
  });
</script>

</html>