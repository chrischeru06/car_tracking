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
                 <form enctype="multipart/form-data" name="myform" id="myform" method="post" class="form-horizontal" action="<?= base_url('etat_vehicule/Sortie_Vehicule/update'); ?>" >
                  <input type="hidden" class="form-control" name="ID_SORTIE" id="ID_SORTIE" value="<?php echo $membre['ID_SORTIE'];?>">

                  <fieldset class="border p-2">
                    <legend  class="float-none w-auto p-2"><?=$title?></legend>

                    <div class="row">

                      <div class="col-md-4">
                        <label for="Ftype" class="text-dark" style="font-weight: 1000; color:#454545">Véhicule<font color="red">*</font></label>
                        <select class="form-control" name="VEHICULE_ID" id="VEHICULE_ID" >
                          <option selected value="">---<?=lang('selectionner')?>---</option>
                          <?php 
                          foreach ($vehiculee as $value)
                          {
                            if ($value['VEHICULE_ID']==$membre['VEHICULE_ID'])
                            {
                              ?>
                              <option value="<?=$value['VEHICULE_ID']?>" selected><?=$value['desc_vehicule']?></option>
                              <?php
                            }
                            else
                            {
                              ?>
                              <option value="<?=$value['VEHICULE_ID']?>"><?=$value['desc_vehicule']?></option>
                              <?php
                            }
                          }
                          ?>
                        </select>
                        <font id="error_VEHICULE_ID" color="red"></font>
                        <?php echo form_error('VEHICULE_ID', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Auteur de la course <font color="red">*</font></label>
                        <input type="text" name="AUTEUR_COURSE" autocomplete="off" id="AUTEUR_COURSE" value="<?=$membre['AUTEUR_COURSE']?>"  class="form-control" placeholder="Auteur de la course">
                        <font id="error_AUTEUR_COURSE" color="red"></font>
                        <?php echo form_error('AUTEUR_COURSE', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Destination <font color="red">*</font></label>
                        <input type="text" name="DESTINATION" autocomplete="off" id="DESTINATION" value="<?=$membre['DESTINATION']?>"  class="form-control" placeholder="Destination">
                        <font id="error_DESTINATION" color="red"></font>
                        <?php echo form_error('DESTINATION', '<div class="text-danger">', '</div>'); ?>
                        <br>
                      </div>

                      <div class="col-md-4">
                        <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Date course <font color="red">*</font></label>
                        <input type="date" name="DATE_COURSE" autocomplete="off" id="DATE_COURSE" value="<?=$membre['DATE_COURSE']?>"   class="form-control" placeholder="Date course">
                        <font id="error_DATE_COURSE" color="red"></font>
                        <?php echo form_error('DATE_COURSE', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Heure départ <font color="red">*</font></label>
                        <input type="time" name="HEURE_DEPART" autocomplete="off" id="HEURE_DEPART" value="<?=$membre['HEURE_DEPART']?>"  class="form-control" placeholder="Heure départ">
                        <font id="error_HEURE_DEPART" color="red"></font>
                        <?php echo form_error('HEURE_DEPART', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Heure retour <font color="red">*</font></label>
                        <input type="time" name="HEURE_ESTIMATIVE_RETOUR" autocomplete="off" id="HEURE_ESTIMATIVE_RETOUR" value="<?=$membre['HEURE_ESTIMATIVE_RETOUR']?>"   class="form-control" placeholder="Heure retour">
                        <font id="err_HEURE_ESTIMATIVE_RETOUR" color="red"></font>
                        <?php echo form_error('HEURE_ESTIMATIVE_RETOUR', '<div class="text-danger">', '</div>'); ?>
                        <br>
                      </div>

                      <div class="col-md-4">
                        <label for="genre" class="text-dark" style="font-weight: 1000; color:#454545">Motif deplacement <font color="red">*</font></label>
                        <select class="form-control" name="ID_MOTIF_DEP" id="ID_MOTIF_DEP">
                         <option value="">---<?=lang('selectionner')?>---</option>
                         <?php 
                         foreach ($motif as $value) 
                         {

                          if ($value['ID_MOTIF_DEP']==$membre['ID_MOTIF_DEP'])
                          {
                            ?>
                            <option value="<?=$value['ID_MOTIF_DEP']?>" selected><?=$value['DESC_MOTIF']?></option>
                            <?php
                          }
                          else
                          {
                            ?>
                            <option value="<?=$value['CHAUFFEUR_ID']?>"><?=$value['DESC_MOTIF']?></option>
                            <?php
                          }

                        }
                        ?>
                      </select>
                      <font id="error_ID_MOTIF_DEP" color="red"></font>
                      <?php echo form_error('ID_MOTIF_DEP', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Niveau carburant au départ<font color="red">*</font></label>
                      <input type="hidden"  name="PHOTO_CARBURANT_OLD" id="PHOTO_CARBURANT_OLD" value="<?=$membre['PHOTO_CARBURANT']?>">

                      <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="PHOTO_CARBURANT" autocomplete="off" id="PHOTO_CARBURANT" value="<?= set_value('PHOTO_CARBURANT') ?>"  class="form-control" title='<?=lang('title_file')?>'>
                      <font id="error_PHOTO_CARBURANT" color="red"></font>
                      <?php echo form_error('PHOTO_CARBURANT', '<div class="text-danger">', '</div>'); ?> 
                    </div>

                    <div class="col-md-4">
                      <label for="description" class="text-dark" style="font-weight: 1000; color:#454545">Kilométrage au départ <font color="red">*</font></label>
                      <input type="hidden"  name="PHOTO_KILOMETAGE_OLD" id="PHOTO_KILOMETAGE_OLD" value="<?=$membre['PHOTO_KILOMETAGE']?>">
                      <input type="file" class="form-control" id="PHOTO_KILOMETAGE" name="PHOTO_KILOMETAGE" value="<?=$membre['PHOTO_KILOMETAGE']?>" title='<?=lang('title_file')?>'>
                      <font id="PHOTO_KILOMETAGE_error" color="red"></font>
                      <br>
                    </div>

                    <div class="col-md-12" class="text-dark">
                      <font for="FName" class="text-dark" style="font-weight: 1000; color:#454545">État de la voiture  </font><br>
                      <small class="text-small text-muted"><b>Y’a-t-il un changement sur l’état initial de la voiture ? </b></small>

                      <div class="row">

                        <input type="hidden" value="<?=$membre['IS_EXCHANGE']?>" id= "IS_EXCHANGE_OLD">

                        <div class="col-md-1">
                          <input type="radio" id="non" name="IS_EXCHANGE" value="1" checked onclick="reply_question();">
                          <label for="non">Non</label><br><br>
                        </div>

                        <div class="col-md-1">
                          <input type="radio" id="oui" name="IS_EXCHANGE" value="2" onclick="reply_question();">
                          <label for="oui">Oui</label><br>
                        </div> 

                      </div>

                    </div>

                  </div>
                </fieldset>

                <fieldset class="border p-2" id="info_supp" style="">
                  <legend  class="float-none w-auto p-2">Informations supplémentaires</legend>

                  <div class="row">

                   <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Image avant <font color="red">*</font></label>
                    <input type="hidden"  name="IMAGE_AVANT_OLD" id="IMAGE_AVANT_OLD" value="<?=$membre['IMAGE_AVANT']?>">
                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="IMAGE_AVANT" autocomplete="off" id="IMAGE_AVANT" value="<?=$membre['IMAGE_AVANT']?>"  class="form-control" title='<?=lang('title_file')?>'>
                    <font id="error_IMAGE_AVANT" color="red"></font>
                    <?php echo form_error('IMAGE_AVANT', '<div class="text-danger">', '</div>'); ?> 
                  </div>
                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Image arriere<font color="red">*</font></label>
                    <input type="hidden"  name="IMAGE_ARRIERE_OLD" id="IMAGE_ARRIERE_OLD" value="<?=$membre['IMAGE_ARRIERE']?>">
                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="IMAGE_ARRIERE" autocomplete="off" id="IMAGE_ARRIERE" value="<?=$membre['IMAGE_ARRIERE']?>"  class="form-control" title='<?=lang('title_file')?>'>
                    <font id="error_IMAGE_ARRIERE" color="red"></font>
                    <?php echo form_error('IMAGE_ARRIERE', '<div class="text-danger">', '</div>'); ?> 
                  </div>

                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Laterale gauche<font color="red">*</font></label>
                    <input type="hidden"  name="IMAGE_LATERALE_GAUCHE_OLD" id="IMAGE_LATERALE_GAUCHE_OLD" value="<?=$membre['IMAGE_LATERALE_GAUCHE']?>">

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="IMAGE_LATERALE_GAUCHE" autocomplete="off" id="IMAGE_LATERALE_GAUCHE" value="<?=$membre['IMAGE_LATERALE_GAUCHE']?>"  class="form-control" title='<?=lang('title_file')?>'>
                    <font id="error_IMAGE_LATERALE_GAUCHE" color="red"></font>
                    <?php echo form_error('IMAGE_LATERALE_GAUCHE', '<div class="text-danger">', '</div>'); ?> 
                    <br>
                  </div>

                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Laterale droite<font color="red">*</font></label>
                    <input type="hidden"  name="IMAGE_LATERALE_DROITE_OLD" id="IMAGE_LATERALE_DROITE_OLD" value="<?=$membre['IMAGE_LATERALE_DROITE']?>">

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="IMAGE_LATERALE_DROITE" autocomplete="off" id="IMAGE_LATERALE_DROITE" value="<?=$membre['IMAGE_LATERALE_DROITE']?>"  class="form-control" title='<?=lang('title_file')?>'>
                    <font id="error_IMAGE_LATERALE_DROITE" color="red"></font>
                    <?php echo form_error('IMAGE_LATERALE_DROITE', '<div class="text-danger">', '</div>'); ?> 
                  </div>
                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Tableau de bord<font color="red">*</font></label>
                    <input type="hidden"  name="IMAGE_TABLEAU_DE_BORD_OLD" id="IMAGE_TABLEAU_DE_BORD_OLD" value="<?=$membre['IMAGE_TABLEAU_DE_BORD']?>">

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="IMAGE_TABLEAU_DE_BORD" autocomplete="off" id="IMAGE_TABLEAU_DE_BORD" value="<?=$membre['IMAGE_TABLEAU_DE_BORD']?>"  class="form-control" title='<?=lang('title_file')?>'>
                    <font id="error_IMAGE_TABLEAU_DE_BORD" color="red"></font>
                    <?php echo form_error('IMAGE_TABLEAU_DE_BORD', '<div class="text-danger">', '</div>'); ?> 
                    <br>
                  </div>
                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Tableau de bordSiege avant<font color="red">*</font></label>
                    <input type="hidden"  name="IMAGE_SIEGE_AVANT_OLD" id="IMAGE_SIEGE_AVANT_OLD" value="<?=$membre['IMAGE_SIEGE_AVANT']?>">

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="IMAGE_SIEGE_AVANT" autocomplete="off" id="IMAGE_SIEGE_AVANT" value="<?=$membre['IMAGE_SIEGE_AVANT']?>"  class="form-control" title='<?=lang('title_file')?>'>
                    <font id="error_IMAGE_SIEGE_AVANT" color="red"></font>
                    <?php echo form_error('IMAGE_SIEGE_AVANT', '<div class="text-danger">', '</div>'); ?> 
                  </div>

                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Siege arriere<font color="red">*</font></label>
                    <input type="hidden"  name="IMAGE_SIEGE_ARRIERE_OLD" id="IMAGE_SIEGE_ARRIERE_OLD" value="<?=$membre['IMAGE_SIEGE_ARRIERE']?>">       
                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="IMAGE_SIEGE_ARRIERE" autocomplete="off" id="IMAGE_SIEGE_ARRIERE" value="<?=$membre['IMAGE_SIEGE_ARRIERE']?>"  class="form-control" title='<?=lang('title_file')?>'>
                    <font id="error_IMAGE_SIEGE_ARRIERE" color="red"></font>
                    <?php echo form_error('IMAGE_SIEGE_ARRIERE', '<div class="text-danger">', '</div>'); ?> 
                  </div>

                </div>

              </fieldset>
            </form>

            <div class="col-md-12" style="margin-top:50px;">
              <button style="float: right;" class="btn btn-outline-primary rounded-pill " onclick="submit_form();"><span class="fas "></span><?=lang('btn_modifier')?></button>
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

<script >
  $(document).ready(function(){

    $('#message').delay('slow').fadeOut(10000);

    var IS_EXCHANGE_OLD = $('#IS_EXCHANGE_OLD').val();

    if(IS_EXCHANGE_OLD == '1')
    {
      $('#info_supp').hide();
      $('#non').prop('checked', true);
      $('#oui').prop('checked', false);
    }
    else if(IS_EXCHANGE_OLD == '2')
    {
      $('#info_supp').show();
      $('#non').prop('checked', false);
      $('#oui').prop('checked', true);
    }
  })
</script>

<script>
  function reply_question() 
  {
    if ($('#non').is(':checked')) 
    {
      $('#info_supp').hide();
    } else if ($('#oui').is(':checked')) 
    {
      $('#info_supp').show();
    }
  }
</script>

<script>
 function submit_form()
 {
  const photo_kilometrage = document.getElementById('PHOTO_KILOMETAGE');
  const carburant = document.getElementById('PHOTO_CARBURANT');
  const photo_avant = document.getElementById('IMAGE_AVANT');
  const photo_arriere = document.getElementById('IMAGE_ARRIERE'); 
  const photolateral_gauche = document.getElementById('IMAGE_LATERALE_GAUCHE');
  const photo_lat_droite = document.getElementById('IMAGE_LATERALE_DROITE');
  const photo_tableau = document.getElementById('IMAGE_TABLEAU_DE_BORD');
  const siege_avant = document.getElementById('IMAGE_SIEGE_AVANT');
  const siege_arriere = document.getElementById('IMAGE_SIEGE_ARRIERE');

  const photo_kilometrage_old = document.getElementById('PHOTO_KILOMETAGE_OLD');
  const carburant_old = document.getElementById('PHOTO_CARBURANT_OLD');
  const photo_avant_old = document.getElementById('IMAGE_AVANT_OLD');
  const photo_arriere_old = document.getElementById('IMAGE_ARRIERE_OLD'); 
  const photolateral_gauche_old = document.getElementById('IMAGE_LATERALE_GAUCHE_OLD');
  const photo_lat_droite_old = document.getElementById('IMAGE_LATERALE_DROITE_OLD');
  const photo_tableau_old = document.getElementById('IMAGE_TABLEAU_DE_BORD_OLD');
  const siege_avant_old = document.getElementById('IMAGE_SIEGE_AVANT_OLD');
  const siege_arriere_old = document.getElementById('IMAGE_SIEGE_ARRIERE_OLD');
  

  var form = document.getElementById("myform");

  var statut=1;

  $('#error_IMAGE_SIEGE_ARRIERE').html('');
  $('#error_IMAGE_SIEGE_AVANT').html('');
  $('#error_IMAGE_TABLEAU_DE_BORD').html('');
  $('#error_IMAGE_LATERALE_DROITE').html('');
  $('#error_IMAGE_LATERALE_GAUCHE').html('');
  $('#error_IMAGE_ARRIERE').html('');
  $('#error_IMAGE_AVANT').html('');
  $('#PHOTO_KILOMETAGE_error').html('');
  $('#error_PHOTO_CARBURANT').html('');

  $('#error_AUTEUR_COURSE').html('');
  $('#error_IMAGE_LATERALE_DROITE').html('');
  $('#error_DATE_COURSE').html('');

var maxSize = 2 * 1024 * 1024; // Taille maximale en octets (2 Mo)


if($('#VEHICULE_ID').val()=='')
{
  statut=2;
  $('#error_VEHICULE_ID').html('<?=lang('msg_validation')?>');
}

if($('#AUTEUR_COURSE').val()=='')
{
  statut=2;
  $('#error_AUTEUR_COURSE').html('<?=lang('msg_validation')?>');
}else{$('#error_AUTEUR_COURSE').html('');} 

if($('#DESTINATION').val()=='')
{
  statut=2;
  $('#error_DESTINATION').html('<?=lang('msg_validation')?>');
}else{$('#error_DESTINATION').html('');} 

if($('#HEURE_DEPART').val()=='')
{
  statut=2;
  $('#error_HEURE_DEPART').html('<?=lang('msg_validation')?>');
}else{$('#error_HEURE_DEPART').html('');} 

if($('#HEURE_ESTIMATIVE_RETOUR').val()=='')
{
  statut=2;
  $('#err_HEURE_ESTIMATIVE_RETOUR').html('<?=lang('msg_validation')?>');
}else{$('#err_HEURE_ESTIMATIVE_RETOUR').html('');} 

if($('#ID_MOTIF_DEP').val()=='')
{
  statut=2;
  $('#error_ID_MOTIF_DEP').html('<?=lang('msg_validation')?>');
}else{$('#error_ID_MOTIF_DEP').html('');} 

if($('#DATE_COURSE').val()=='')
{
  statut=2;
  $('#error_DATE_COURSE').html('<?=lang('msg_validation')?>');
}else{$('#error_DATE_COURSE').html('');}  


if($('#PHOTO_KILOMETAGE').val() != '')
{
  var filephoto_kilometrage = photo_kilometrage.files[0];

      var fileSizephoto_kilometrage = filephoto_kilometrage.size; // Taille du fichier en octets

      if(fileSizephoto_kilometrage > maxSize)
      {
        statut=2;
        $('#err_photo_kilometrage').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#PHOTO_KILOMETAGE_error').html('');}
    }

    if($('#PHOTO_CARBURANT').val() != '')
    {
      var filecarburant = carburant.files[0];

      var fileSizecarburant = filecarburant.size; // Taille du fichier en octets

      if(fileSizecarburant > maxSize)
      {
        statut=2;
        $('#error_carburant').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_PHOTO_CARBURANT').html('');}
    }
    


    if ($('#oui').is(':checked'))
    {
      if(photo_avant.files.length === 0 && photo_avant_old == '')
      {
        statut=2;
        $('#error_IMAGE_AVANT').text("<?=lang('msg_validation')?>");
      }else{$('#error_IMAGE_AVANT').text("");}

      if(photo_arriere.files.length === 0 && photo_arriere_old == '')
      {
        statut=2;
        $('#error_IMAGE_ARRIERE').text("<?=lang('msg_validation')?>");
      }else{$('#error_IMAGE_ARRIERE').text("");}

      if(photolateral_gauche.files.length === 0 && photolateral_gauche_old == '')
      {
        statut=2;
        $('#error_IMAGE_LATERALE_GAUCHE').text("<?=lang('msg_validation')?>");
      }else{$('#error_IMAGE_LATERALE_GAUCHE').text("");}

      if(photo_lat_droite.files.length === 0 && photo_lat_droite_old == '')
      {
        statut=2;
        $('#error_IMAGE_LATERALE_DROITE').text("<?=lang('msg_validation')?>");
      }else{$('#error_IMAGE_LATERALE_DROITE').text("");}

      if(photo_tableau.files.length === 0 && photo_tableau_old == '')
      {
        statut=2;
        $('#error_IMAGE_TABLEAU_DE_BORD').text("<?=lang('msg_validation')?>");
      }else{$('#error_IMAGE_TABLEAU_DE_BORD').text("");}

      if(siege_avant.files.length === 0 && siege_avant_old == '')
      {
        statut=2;
        $('#error_IMAGE_SIEGE_AVANT').text("<?=lang('msg_validation')?>");
      }else{$('#error_IMAGE_SIEGE_AVANT').text("");}

      if(siege_arriere.files.length === 0 && siege_arriere_old == '')
      {
        statut=2;
        $('#error_IMAGE_SIEGE_ARRIERE').text("<?=lang('msg_validation')?>");
      }else{$('#error_IMAGE_SIEGE_ARRIERE').text("");}

      if($('#IMAGE_AVANT').val() != '')
      {
        var filephoto_avant = photo_avant.files[0];
      var fileSizephoto_avant = filephoto_avant.size; // Taille du fichier en octets
      if(fileSizephoto_avant > maxSize)
      {
        statut=2;
        $('#error_photo_avant').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_photo_avant').html('');}
    }


    if($('#IMAGE_ARRIERE').val() != '')
    {
      var filephoto_arriere = photo_arriere.files[0];
      var fileSizephoto_arriere = filephoto_arriere.size; // Taille du fichier en octets
      if(fileSizephoto_arriere > maxSize)
      {
        statut=2;
        $('#error_photo_arriere').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_photo_arriere').html('');}
    }
    

    if($('#IMAGE_LATERALE_GAUCHE').val() != '')
    {
      var filephotolateral_gauche = photolateral_gauche.files[0];
      var fileSizephotolateral_gauche = filephotolateral_gauche.size; // Taille du fichier en octets
      if(fileSizephotolateral_gauche > maxSize)
      {
        statut=2;
        $('#error_photolateral_gauche').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_photolateral_gauche').html('');}
    }
    

    if($('#IMAGE_LATERALE_DROITE').val() != '')
    {
      var filephoto_lat_droite = photo_lat_droite.files[0];
      var fileSizephoto_lat_droite = filephoto_lat_droite.size; // Taille du fichier en octets
      if(fileSizephoto_lat_droite > maxSize)
      {
        statut=2;
        $('#error_photo_lat_droite').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_photo_lat_droite').html('');}
    }

    if($('#IMAGE_TABLEAU_DE_BORD').val() != '')
    {
      var filephoto_tableau = photo_tableau.files[0];
      var fileSizephoto_tableau = filephoto_tableau.size; // Taille du fichier en octets
      if(fileSizephoto_tableau > maxSize)
      {
        statut=2;
        $('#error_photo_tableau').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_photo_tableau').html('');}
    }


    if($('#IMAGE_SIEGE_AVANT').val() != '')
    {
      var filesiege_avant = siege_avant.files[0];
      var fileSizesiege_avant = filesiege_avant.size; // Taille du fichier en octets
      if(fileSizesiege_avant > maxSize)
      {
        statut=2;
        $('#error_siege_avant').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_siege_avant').html('');}
    }
    

    if($('#IMAGE_SIEGE_ARRIERE').val() != '')
    {
      var filesiege_arriere = siege_arriere.files[0];
      var fileSizesiege_arriere = filesiege_arriere.size; // Taille du fichier en octets
      if(fileSizesiege_arriere > maxSize)
      {
        statut=2;
        $('#error_siege_arriere').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_siege_arriere').html('');}
    }


  }


  if(statut==1)
  {
    $('#myform').submit();
  }
}



</script>

<script>


  var phile1 = document.getElementById('photo_kilometrage');
  var phile2  = document.getElementById('carburant');
  var phile3  = document.getElementById('photo_avant');
  var phile4  = document.getElementById('photo_arriere');
  var phile5 = document.getElementById('photolateral_gauche');
  var phile6 = document.getElementById('photo_lat_droite');
  var phile7 = document.getElementById('photo_tableau');
  var phile8 = document.getElementById('siege_avant');
  var phile9 = document.getElementById('siege_arriere');

  $('#photo_kilometrage,#carburant,#photo_avant,#photo_arriere,#photolateral_gauche,#photo_lat_droite,#photo_tableau,#siege_avant,#siege_arriere').change(function()
  {
    if(phile1.files.length !==0)
    {
      err_photo_kilometrage.innerHTML ="";
    }

    if(phile2.files.length !==0)
    {
      error_carburant.innerHTML ="";
    }
    if(phile3.files.length !==0)
    {
      error_photo_avant.innerHTML ="";
    } 
    if(phile4.files.length !==0)
    {
      error_photo_arriere.innerHTML ="";
    }
    if(phile5.files.length !==0)
    {
      error_photolateral_gauche.innerHTML ="";
    }
    if(phile6.files.length !==0)
    {
      error_photo_lat_droite.innerHTML ="";
    }
    if(phile7.files.length !==0)
    {
      error_photo_tableau.innerHTML ="";
    }
    if(phile8.files.length !==0)
    {
      error_siege_avant.innerHTML ="";
    }
    if(phile9.files.length !==0)
    {
      error_siege_arriere.innerHTML ="";
    }


  });
    //pour les caracteres seuelement
  $("#COMMENTAIRE").on('input paste change keyup', function()      
  {
    $('#error_COMMENTAIRE').hide();
      // $(this).val($(this).val().replace(/[^a-z-\s]/gi, '').toUpperCase());
  });

  $("#DESTINATION,#HEURE_DEPART,#HEURE_ESTIMATIVE_RETOUR").keypress(function(event)
  {
    $('#error_DESTINATION,#error_HEURE_DEPART,#err_HEURE_ESTIMATIVE_RETOUR').hide();
    var character = String.fromCharCode(event.keyCode);
    return isValid(character);     
  });

       //pour les caracteres seuelement
    // $("#DESTINATION").on('input paste change keyup', function()      
    // {
    //   $('#error_DESTINATION').hide();
    //   $(this).val($(this).val().replace(/[^a-z-\s]/gi, '').toUpperCase());
    // });
     //pour les chifrres


    // $("#HEURE_RETOUR").keypress(function(event)
    // {
    //   $('#error_HEURE_RETOUR').hide();
    //   var character = String.fromCharCode(event.keyCode);
    //   return isValid(character);     
    // });

</script>






</html>