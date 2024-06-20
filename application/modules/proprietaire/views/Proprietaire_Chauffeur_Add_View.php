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
      <div class="row page-titles mx-0">
      <div class="col-sm-10 p-md-0">
        <div class="welcome-text">
        <center>
         <table>
          <tr>
          
            <td>  
              <h4 class="text-dark text-center" style="margin-bottom: 1px;"><?=$title?></h4>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
               
                </ol>
              </nav>
            </td>
          </tr>
        </table>
        </center>
      </div>
    </div>
    <div class="col-md-2">

      <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('proprietaire/Proprietaire_chauffeur')?>" class="nav-link position-relative"><i class="bi bi-plus"></i> Liste</a>

    </div>
  </div><!-- End Page Title -->

    <section class="section dashboard">
      <!--  <div class="container text-center"> -->
        <div class="row">
          <div class="text-left col-sm-12">
            <div class="card">
              <!-- <div class="card-header">
                <h4 class="card-title lead"> <?=$title?></h4>
              </div> -->
              
              <div class="card-body text-left">

                <?= $this->session->flashdata('message'); ?>

                <!-- <div class="row"> -->
                 <form enctype="multipart/form-data" name="myform" id="myform" method="POST" class="form-horizontal" action="<?= base_url('proprietaire/Proprietaire_chauffeur/add'); ?>" >

                  <div class="row">
                    <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('input_nom')?> <font color="red">*</font></label>
                      <input type="text" name="nom" autocomplete="off" id="nom" value="<?= set_value('nom') ?>"  class="form-control">
                      <font id="error_nom" color="red"></font>
                      <?php echo form_error('nom', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('input_prenom')?> <font color="red">*</font></label>
                      <input type="text" name="prenom" autocomplete="off" id="prenom" value="<?= set_value('prenom') ?>"  class="form-control">
                      <font id="error_prenom" color="red"></font>
                      <?php echo form_error('prenom', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="col-md-4">
                      <label for="date_naissance" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('date_naissance')?> <font color="red">*</font></label>
                      <input type="date" name="date_naissance" autocomplete="off" id="date_naissance" value="<?= set_value('date_naissance') ?>"  class="form-control" onchange="verif_date();" max="<?= date('Y-m-d')?>">

                      <font id="error_date_naissance" color="red"></font>
                      <?php echo form_error('date_naissance', '<div class="text-danger">', '</div>'); ?>
                    </div><br><br><br>

                    <div class="col-md-4">
                      <label for="genre" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('mot_genre')?> <font color="red">*</font></label>
                      <select class="form-control" name="GENRE_ID" id="GENRE_ID">
                       <option value="">---<?=lang('selectionner')?>---</option>
                       <?php 
                       foreach ($type_genre as $value) 
                       {
                         if ($value['GENRE_ID']==set_value('DESCR_GENRE')) 
                          {?>
                           <option value="<?=$value['GENRE_ID']?>" selected=''><?=$value['DESCR_GENRE']?></option>
                           <?php 
                         }else{?>
                          <option value="<?=$value['GENRE_ID']?>"><?=$value['DESCR_GENRE']?></option>
                          <?php
                        }
                      }
                      ?>
                    </select>
                    <font id="error_genre" color="red"></font>
                    <?php echo form_error('GENRE_ID', '<div class="text-danger">', '</div>'); ?>
                  </div>
                   <div class="col-md-4">
                      <label for="genre" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('title_proprio_list')?> <font color="red">*</font></label>
                      <select class="form-control" name="PROPRIETAIRE_ID" id="PROPRIETAIRE_ID">
                       <option value="">---<?=lang('selectionner')?>---</option>
                       <?php 
                       foreach ($proprio as $value) 
                       {
                         if ($value['PROPRIETAIRE_ID']==set_value('proprio_desc')) 
                          {?>
                           <option value="<?=$value['PROPRIETAIRE_ID']?>" selected=''><?=$value['proprio_desc']?></option>
                           <?php 
                         }else{?>
                          <option value="<?=$value['PROPRIETAIRE_ID']?>"><?=$value['proprio_desc']?></option>
                          <?php
                        }
                      }
                      ?>
                    </select>
                    <font id="error_PROPRIETAIRE_ID" color="red"></font>
                    <?php echo form_error('PROPRIETAIRE_ID', '<div class="text-danger">', '</div>'); ?>
                  </div>
                  
                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('input_adresse')?>/<?=lang('mot_rue')?> <font color="red">*</font></label>
                    <input type="text" name="adresse_physique" autocomplete="off" id="adresse_physique" value="<?= set_value('adresse_physique') ?>"  class="form-control">
                    <font id="error_adresse_physique" color="red"></font>
                    <?php echo form_error('adresse_physique', '<div class="text-danger">', '</div>'); ?>
                  </div><br><br><br>

                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('input_tlphone')?> <font color="red">*</font></label>
                    <input type="text" name="numero_telephone" autocomplete="off" id="numero_telephone" value="<?= set_value('numero_telephone') ?>"  class="form-control">
                    <font id="error_numero_telephone" color="red"></font>
                    <?php echo form_error('numero_telephone', '<div class="text-danger">', '</div>'); ?> 
                  </div>

                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('input_email')?><font color="red">*</font></label>
                    <input type="text" name="adresse_email" id="adresse_email" value="<?= set_value('adresse_email') ?>"  class="form-control">
                    <font id="error_adresse_email" color="red"></font>
                    <?php echo form_error('adresse_email', '<div class="text-danger">', '</div>'); ?>
                  </div>

                  <div class="col-md-4">
                    <label class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('input_conf_mail')?><font color="red">*</font></label>
                    <input type="text" name="CONFIRMATION_EMAIL" id="CONFIRMATION_EMAIL" value="<?=set_value('CONFIRMATION_EMAIL') ?>"  class="form-control" onpaste="return false;">
                    <font id="error_CONFIRMATION_EMAIL" color="red"></font>
                    <?php echo form_error('CONFIRMATION_EMAIL', '<div class="text-danger">', '</div>'); ?>
                  </div><br><br><br>

                  <div class="col-md-4" class="text-dark">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('n_identite')?> <font color="red">*</font></label>
                    <input type="text" name="NUMERO_CARTE_IDENTITE" autocomplete="off" 
                    id="NUMERO_CARTE_IDENTITE" value="<?= set_value('NUMERO_CARTE_IDENTITE') ?>"  class="form-control">
                    <font id="error_NUMERO_CARTE_IDENTITE" color="red"></font>
                    <?php echo form_error('NUMERO_CARTE_IDENTITE', '<div class="text-danger">', '</div>'); ?>
                  </div>

                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('n_prsonne_contact')?> <font color="red">*</font></label>
                    <input type="text" name="personne_contact_telephone" autocomplete="off" 
                    id="personne_contact_telephone" value="<?= set_value('personne_contact_telephone') ?>"  class="form-control">
                    <font id="error_personne_contact_telephone" color="red"></font>
                    <?php echo form_error('personne_contact_telephone', '<div class="text-danger">', '</div>'); ?>
                  </div>

                  <div class="col-md-4">
                    <label for="Ftype" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('input_province')?> <font color="red">*</font></label>
                    <select class="form-control" name="PROVINCE_ID" id="PROVINCE_ID" onchange="get_communes();">
                      <option selected value="">---<?=lang('selectionner')?>---</option>

                      <?php
                      foreach ($provinces as $value)
                      {
                        ?>
                        <option <?php if ($value['PROVINCE_ID']== set_value('PROVINCE_ID')): ?> selected <?php endif ?> value="<?=$value['PROVINCE_ID']?>"><?=$value['PROVINCE_NAME']?></option>
                        <?php
                      }
                      ?>
                    </select>
                    <font id="error_prov" color="red"></font>
                    <?php echo form_error('PROVINCE_ID', '<div class="text-danger">', '</div>'); ?>
                  </div><br><br><br>

                  <div class="col-md-4">
                    <label for="Ftype" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('input_commune')?> <font color="red">*</font></label>
                    <select class="form-control" name="COMMUNE_ID" id="COMMUNE_ID" onchange="get_zones();" > 
                      <option selected value="">--<?=lang('selectionner')?>--</option>
                      <?php
                      foreach($communes as $key)
                      { 
                        if ($key['COMMUNE_ID']==set_value('COMMUNE_ID'))
                        { 
                          echo "<option value='".$key['COMMUNE_ID']."' selected>".$key['COMMUNE_NAME']."</option>";
                        }
                        else
                        {
                          echo "<option value='".$key['COMMUNE_ID']."' >".$key['COMMUNE_NAME']."</option>";
                        }
                      }
                      ?> 
                    </select>
                    <font id="error_com" color="red"></font>
                    <?php echo form_error('COMMUNE_ID', '<div class="text-danger">', '</div>'); ?>
                  </div>

                  <div class="col-md-4">
                    <label for="Ftype" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('input_zone')?> <font color="red">*</font></label>
                    <select class="form-control" name="ZONE_ID" id="ZONE_ID" onchange="get_collines();">
                      <option value="<?= set_value('ZONE_ID') ?>">---<?=lang('selectionner')?>---</option>
                      <?php
                      foreach($zones as $key)
                      { 
                        if ($key['ZONE_ID']==set_value('ZONE_ID'))
                        { 
                          echo "<option value='".$key['ZONE_ID']."' selected>".$key['ZONE_NAME']."</option>";
                        }
                        else
                        {
                          echo "<option value='".$key['ZONE_ID']."' >".$key['ZONE_NAME']."</option>";
                        }
                      }
                      ?>
                    </select>
                    <font id="error_zon" color="red"></font>
                    <?php echo form_error('ZONE_ID', '<div class="text-danger">', '</div>'); ?>
                  </div>

                  <div class="col-md-4">
                    <label for="Ftype" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('input_colline')?> <font color="red">*</font></label>
                    <select class="form-control" name="COLLINE_ID" id="COLLINE_ID">
                      <option value="<?= set_value('COLLINE_ID') ?>">---<?=lang('selectionner')?>---</option>
                      <?php
                      foreach($collines as $key)
                      { 
                        if ($key['COLLINE_ID']==set_value('COLLINE_ID'))
                        {
                          echo "<option value='".$key['COLLINE_ID']."' selected>".$key['COLLINE_NAME']."</option>";
                        }
                        else
                        {
                          echo "<option value='".$key['COLLINE_ID']."' >".$key['COLLINE_NAME']."</option>";
                        }
                      }
                      ?>
                    </select>
                    <font id="error_col" color="red"></font>
                    <?php echo form_error('COLLINE_ID', '<div class="text-danger">', '</div>'); ?>
                  </div><br><br><br>

                  <div class="col-md-4" class="text-dark">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('carte_identite')?> <font color="red">*</font></label>
                    <input type="file" name="fichier_carte_identite" autocomplete="off" id="fichier_carte_identite" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" value="<?= set_value('fichier_carte_identite') ?>"  class="form-control" title='<?=lang('title_file')?>'>
                    <font id="error_fichier_carte_identite" color="red"></font>
                    <?php echo form_error('fichier_carte_identite', '<div class="text-danger">', '</div>'); ?> 
                  </div>
                  <div class="col-md-4" class="text-dark">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('n_permis_conduire')?> <font color="red">*</font></label>
                    <input type="text" name="NUMERO_PERMIS" autocomplete="off" 
                    id="NUMERO_PERMIS" value="<?= set_value('NUMERO_PERMIS') ?>"  class="form-control">
                    <font id="error_NUMERO_PERMIS" color="red"></font>
                    <?php echo form_error('NUMERO_PERMIS', '<div class="text-danger">', '</div>'); ?>
                  </div>

                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('phto_prmis')?> <font color="red">*</font></label>

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="file_permis" autocomplete="off" id="file_permis" value="<?= set_value('file_permis') ?>"  class="form-control"title='<?=lang('title_file')?>'>
                    <font id="error_file_permis" color="red"></font>
                    <?php echo form_error('file_permis', '<div class="text-danger">', '</div>'); ?> 
                  </div><br><br><br>
                  <div class="col-md-4">
                    <label for="date_expiration" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('date_exp_permis')?> <font color="red">*</font></label>
                    <input type="date" name="date_expiration" autocomplete="off" id="date_expiration" value="<?= set_value('date_expiration') ?>"  class="form-control" >

                    <font id="error_date_expiration" color="red"></font>
                    <?php echo form_error('date_expiration', '<div class="text-danger">', '</div>'); ?>
                  </div>


                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545"><?=lang('input_photo_passport')?> <font color="red">*</font></label>
                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="photo_passport" autocomplete="off" id="photo_passport" value="<?= set_value('photo_passport') ?>"  class="form-control" title='Veuillez mettre une photo avec extension:  .png,.PNG,.jpg,.JPG,.JEPG,.jepg'>
                    <font id="error_photo_passport" color="red"></font>
                    <?php echo form_error('photo_passport', '<div class="text-danger">', '</div>'); ?> 
                  </div>
                </div>
              </form>
              <div class="col-md-12" style="margin-top:10px;">
                <button style="float: right;" class="btn btn-outline-primary rounded-pill " onclick="submit_form();"><span class="fas "></span> <?=lang('btn_enregistrer')?></button>
              </div>
              <!-- </div> -->

            </div>
          </div>
        </div>
      </div>
      <!--   </div> -->
    </section>

  </main><!-- End #main -->

  <?php include VIEWPATH . 'includes/footer.php'; ?>

</body>


<script type="text/javascript">

   //  // Fonction pour le chargement de donnees par defaut
   //  $(document).ready( function ()
   //  {
   //    $('#message').delay('slow').fadeOut(6000);

   //    var VEHICULE_ID = $('#VEHICULE_ID').val();

   //    if(VEHICULE_ID == "")
   //    {
   //     $('#ID_NOM').html('<option value="">-- Séléctionner --</option>');
   //   }  

   // });
   function get_nom_vehicule()
   {

    if($('#ID_MARQUE').val()!='')
    {
      $.ajax(
      {
        url:"<?=base_url('vehicule/Vehicule/get_nom_vehicule/')?>"+$('#ID_MARQUE').val(),
        type: "GET",
        dataType:"JSON",
        success: function(data)
        {
          $('#ID_NOM').html(data);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert('Erreur');
        }
      });
    }
    else
    {
      $('#ID_NOM').html('<option value="">-- <?=lang('selectionner')?> --</option>');
    }
  }
</script>

<script>

  function submit_form()
  {
    const photopassport = document.getElementById('photo_passport');
    const file_permis = document.getElementById('file_permis');

    const fichier_carte_identite = document.getElementById('fichier_carte_identite');
    const fichier_casier_judiciaire = document.getElementById('fichier_casier_judiciaire');
    var mail = document.getElementById("adresse_email").value;
    var mail2 = document.getElementById("CONFIRMATION_EMAIL").value;
    var form = document.getElementById("myform");
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    var statut=1;
    $('#error_nom').html('');
    $('#error_prenom').html('');
    $('#error_adresse_physique').html('');
    $('#error_numero_telephone').html('');
    $('#error_CONFIRMATION_EMAIL').html('');
    $('#error_adresse_email').html('');
    $('#error_photo_passport').html('');
    $('#error_NUMERO_CARTE_IDENTITE').html('');
    $('#error_NUMERO_PERMIS').html('');
    $('#error_file_permis').html('');
    $('#error_prov').html('');
    $('#error_com').html('');
    $('#error_zon').html('');
    $('#error_col').html('');
    $('#error_fichier_carte_identite').html('');
    $('#error_fichier_casier_judiciaire').html('');

    if($('#nom').val()=='')
    {
      statut=2;
      $('#error_nom').html('<?=lang('msg_validation')?>');
    }

    if($('#prenom').val()=='')
    {
      statut=2;
      $('#error_prenom').html('<?=lang('msg_validation')?>');
    }
    if($('#personne_contact_telephone').val()=='')
    {
      statut=2;
      $('#error_personne_contact_telephone').html('<?=lang('msg_validation')?>');
    }



    if($('#CONFIRMATION_EMAIL').val()=='')
    {
      statut=2;
      $('#error_CONFIRMATION_EMAIL').html('<?=lang('msg_validation')?>');
    }


    if($('#numero_telephone').val()=='')
    {
      statut=2;
      $('#error_numero_telephone').html('<?=lang('msg_validation')?>');
    }

    if($('#adresse_email').val()=='')
    {
      statut=2;
      $('#error_adresse_email').html('<?=lang('msg_validation')?>');
    }
    if($('#adresse_email').val()!='')
    {
      if(!emailReg.test($('#adresse_email').val()))
      {
        $('#error_adresse_email').html('<?=lang('msg_validation_mail')?>');
        statut=2
      }

      if($('#CONFIRMATION_EMAIL').val()!=$('#adresse_email').val())
      {
        statut=2;
        $('#error_CONFIRMATION_EMAIL').html('<?=lang('msg_validation_mail_correspondance')?>');
      }
    }

    if($('#NUMERO_CARTE_IDENTITE').val()=='')
    {
      statut=2;
      $('#error_NUMERO_CARTE_IDENTITE').html('<?=lang('msg_validation')?>');
    }
    if($('#NUMERO_PERMIS').val()=='')
    {
      statut=2;
      $('#error_NUMERO_PERMIS').html('<?=lang('msg_validation')?>');
    }

    if($('#PROVINCE_ID').val()=='')
    {
      statut=2;
      $('#error_prov').html('<?=lang('msg_validation')?>');
    }

    if($('#COMMUNE_ID').val()=='')
    {
      statut=2;
      $('#error_com').html('<?=lang('msg_validation')?>');
    }

    if($('#ZONE_ID').val()=='')
    {
      statut=2;
      $('#error_zon').html('<?=lang('msg_validation')?>');
    }

    if($('#COLLINE_ID').val()=='')
    {
      statut=2;
      $('#error_col').html('<?=lang('msg_validation')?>');
    }

    if(fichier_carte_identite.files.length === 0)
    {
      statut=2;
      $('#error_fichier_carte_identite').text("<?=lang('msg_validation')?>");
    }

    if(file_permis.files.length === 0)
    {
      statut=2;
      $('#error_file_permis').text("<?=lang('msg_validation')?>");
    }

    if(photopassport.files.length === 0)
    {
      statut=2;
      $('#error_photo_passport').text("<?=lang('msg_validation')?>");
    }

    if($('#date_naissance').val()==''){
      statut=2;
      $('#error_date_naissance').text("<?=lang('msg_validation')?>");
    }


    if($('#date_expiration').val()==''){
      statut=2;
      $('#error_date_expiration').text("<?=lang('msg_validation')?>");
    }


    if($('#GENRE_ID').val()==''){
      statut=2;
      $('#error_genre').text("<?=lang('msg_validation')?>");
    }


    if($('#PROPRIETAIRE_ID').val()==''){
      statut=2;
      $('#error_PROPRIETAIRE_ID').text("<?=lang('msg_validation')?>");
    }



    if($('#ETHNIE_ID').val()==''){
      statut=2;
      $('#error_ethnie').text("<?=lang('msg_validation')?>");
    }
    if(statut==1)
    {
      $('#myform').submit();
    }
  }

  function get_communes()
  {
    var PROVINCE_ID=$('#PROVINCE_ID').val();
    if(PROVINCE_ID=='')
    {
      $('#COMMUNE_ID').html('<option value="">---<?=lang('selectionner')?>---</option>');
      $('#ZONE_ID').html('<option value="">---<?=lang('selectionner')?>---</option>');
      $('#COLLINE_ID').html('<option value="">---<?=lang('selectionner')?>---</option>');
    }
    else
    {
      $('#ZONE_ID').html('<option value="">---<?=lang('selectionner')?>---</option>');
      $('#COLLINE_ID').html('<option value="">---<?=lang('selectionner')?>---</option>');
      $.ajax(
      {
        url:"<?=base_url()?>proprietaire/Proprietaire_chauffeur/get_communes/"+PROVINCE_ID,
        type:"GET",
        dataType:"JSON",
        success: function(data)
        {
          $('#COMMUNE_ID').html(data);
        }
      });

    }
  }

  function get_zones()
  {
    var COMMUNE_ID=$('#COMMUNE_ID').val();
    if(COMMUNE_ID=='')
    {
      $('#ZONE_ID').html('<option value="">---<?=lang('selectionner')?>---</option>');
      $('#COLLINE_ID').html('<option value="">---<?=lang('selectionner')?>---</option>');
    }
    else
    {
      $('#COLLINE_ID').html('<option value="">---<?=lang('selectionner')?>---</option>');
      $.ajax(
      {
        url:"<?=base_url()?>proprietaire/Proprietaire_chauffeur/get_zones/"+COMMUNE_ID,
        type:"GET",
        dataType:"JSON",
        success: function(data)
        {
          $('#ZONE_ID').html(data);
        }
      });

    }
  }

  function get_collines()
  {
    var ZONE_ID=$('#ZONE_ID').val();
    if(ZONE_ID=='')
    {
      $('#COLLINE_ID').html('<option value="">---<?=lang('selectionner')?>---</option>');
    }
    else
    {
      $.ajax(
      {
        url:"<?=base_url()?>proprietaire/Proprietaire_chauffeur/get_collines/"+ZONE_ID,
        type:"GET",
        dataType:"JSON",
        success: function(data)
        {
          $('#COLLINE_ID').html(data);
        }
      });

    }
  }
</script>

<script>

  var province = document.getElementById('PROVINCE_ID');
  var province_error = document.getElementById('error_prov');

  var colline = document.getElementById('COLLINE_ID');
  var colline_error = document.getElementById('error_col');

  var zone = document.getElementById('ZONE_ID');
  var zone_error = document.getElementById('error_zon');

  var comm = document.getElementById('COMMUNE_ID');
  var commune_error = document.getElementById('error_com');

  var file1 = document.getElementById('fichier_casier_judiciaire');
  var casier_error  = document.getElementById('error_fichier_casier_judiciaire');
  var file = document.getElementById('fichier_carte_identite');
  var photopassport = document.getElementById('photo_passport');
  var error_photo_passport  = document.getElementById('error_photo_passport');
  var cni_error = document.getElementById('error_fichier_carte_identite');

  $('#fichier_carte_identite,#fichier_casier_judiciaire,#photo_passport').change(function()
  {
    if(file.files.length !==0)
    {
      cni_error.innerHTML ="";
    }

    if(file1.files.length !==0)
    {
      casier_error.innerHTML ="";
    }

    if(photopassport.files.length !==0)
    {
      error_photo_passport.innerHTML ="";
    }
  });

  $('#COMMUNE_ID').change(function()
  {
    commune_error.innerHTML ="";
  });

  $('#ZONE_ID').change(function()
  {
    zone_error.innerHTML ="";
  });

  $('#COLLINE_ID').change(function()
  {
    colline_error.innerHTML ="";
  });

  $('#PROVINCE_ID').change(function()
  {
    province_error.innerHTML ="";
  });

  $("#nom,#prenom").on('input paste change keyup', function()      
  {
    $('#error_nom,#error_prenom').hide();
    $(this).val($(this).val().replace(/[^a-z-\s]/gi, '').toUpperCase());
  });

  $("#NUMERO_CARTE_IDENTITE").on('input paste change keyup', function()
  {
    $('#error_NUMERO_CARTE_IDENTITE').hide();
    $(this).val($(this).val().replace(/[^0-9/.]*$/gi, ''));
  });

  $("#adresse_physique").keypress(function(event)
  {
    $('#error_adresse_physique').hide();
    var character = String.fromCharCode(event.keyCode);
    return isValid(character);     
  });

  $('#numero_telephone').on('input change',function()
  {
    $(this).val($(this).val().replace(/[^0-9]*$/gi, ''));
    $(this).val($(this).val().replace(' ', ''));
    var subStr = this.value.substring(0,1);

    if(subStr != '+')
    {
      $('[name = "numero_telephone"]').val('+257');
    }

    if(this.value.substring(0,4)=="+257")
    {
      if($(this).val().length == 12)
      {
        $('#error_numero_telephone').text('');
      }
      else
      {
        $('#error_numero_telephone').text('<?=lang('tel_invalide')?> ');
        if($(this).val().length > 12)
        {
          $(this).val(this.value.substring(0,12));
          $('#error_numero_telephone').text('');
        }
      }
    }
    else
    {
      if ($(this).val().length > 12)
      {
        $('#error_numero_telephone').text('');
      }
      else
      {
        $('#error_numero_telephone').text('<?=lang('tel_invalide')?>');
      }        
    }
  });

  $('#personne_contact_telephone').on('input change',function()
  {
    $('#error_personne_contact_telephone').html();
    $(this).val($(this).val().replace(/[^0-9]*$/gi, ''));
    $(this).val($(this).val().replace(' ', ''));
    var subStr = this.value.substring(0,1);

    if (subStr != '+')
    {
      $('[name = "personne_contact_telephone"]').val('+257');
    }

    if(this.value.substring(0,4)=="+257")
    {
      if($(this).val().length == 12)
      {
        $('#error_personne_contact_telephone').text('');
      }
      else
      {
        $('#error_personne_contact_telephone').text('<?=lang('tel_invalide')?> ');
        if($(this).val().length > 12)
        {
          $(this).val(this.value.substring(0,12));
          $('#error_personne_contact_telephone').text('');
        }

      }
    }
    else
    {
      if ($(this).val().length > 12)
      {
        $('#error_personne_contact_telephone').text('');
      }
      else
      {
        $('#error_personne_contact_telephone').text('<?=lang('tel_invalide')?>');
      }        
    }
  });
</script>

<script type="text/javascript">
  function verif_date()
  {
    var DATE_NAISSANCE = $('#date_naissance').val();
    $.ajax(
    {
      type: "POST",
      url:"<?=base_url()?>proprietaire/Proprietaire_chauffeur/verif_date/",
      dataType: 'JSON',
      data:{
        DATE_NAISSANCE:DATE_NAISSANCE
      },

      success: function(data){

        if(data < 18 )
        {
          $('#error_date_naissance').text("<?=lang('validation_age')?> !");
        }
        else
          {$('#error_date_naissance').text("");
      }

      


    }
  });

  }

</script>


</html>