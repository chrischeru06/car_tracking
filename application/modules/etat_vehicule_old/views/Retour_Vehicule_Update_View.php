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
                 <form enctype="multipart/form-data" name="myform" id="myform" method="post" class="form-horizontal" action="<?= base_url('etat_vehicule/Retour_Vehicule/update'); ?>" >
                  <input type="hidden" class="form-control" name="ID_RETOUR" id="ID_RETOUR" value="<?php echo $membre['ID_RETOUR'];?>">

                  <div class="row">

                  <div class="col-md-4">
                    <label for="Ftype" class="text-dark" style="font-weight: 1000; color:#454545">Véhicule<font color="red">*</font></label>
                    <select class="form-control" name="VEHICULE_ID" id="VEHICULE_ID" >
                      <option selected value="">---<?=lang('selectionner')?>---</option>
                      <?php 
                      $veh=$this->Model->getOne('vehicule',array('VEHICULE_ID' =>$membre['VEHICULE_ID']));
                      foreach ($vehiculee as $value)
                      {
                        if ($value['VEHICULE_ID']==$veh['VEHICULE_ID'])
                        {
                          ?>
                          <option value="<?=$value['VEHICULE_ID']?>" selected><?=$value['CODE']?></option>
                          <?php
                        }
                        else
                        {
                          ?>
                          <option value="<?=$value['VEHICULE_ID']?>"><?=$value['CODE']?></option>
                          <?php
                        }
                      }
                      ?>
                    </select>
                    <font id="error_VEHICULE_ID" color="red"></font>
                    <?php echo form_error('VEHICULE_ID', '<div class="text-danger">', '</div>'); ?>
                  </div><br><br><br>

                  
                  <div class="col-md-4">
                    <label for="Ftype" class="text-dark" style="font-weight: 1000; color:#454545">Chauffeur<font color="red">*</font></label>
                    <select class="form-control" name="CHAUFFEUR_ID" id="CHAUFFEUR_ID" onchange="get_communes();">
                      <option selected value="">---<?=lang('selectionner')?>---</option>
                      <?php 
                      $chauff=$this->Model->getOne('chauffeur',array('CHAUFFEUR_ID' =>$membre['CHAUFFEUR_ID']));
                      foreach ($chauffeuri as $value)
                      {
                        if ($value['CHAUFFEUR_ID']==$chauff['CHAUFFEUR_ID'])
                        {
                          ?>
                          <option value="<?=$value['CHAUFFEUR_ID']?>" selected><?=$value['chauffeur_desc']?></option>
                          <?php
                        }
                        else
                        {
                          ?>
                          <option value="<?=$value['CHAUFFEUR_ID']?>"><?=$value['chauffeur_desc']?></option>
                          <?php
                        }
                      }
                      ?>
                    </select>
                    <font id="error_CHAUFFEUR_ID" color="red"></font>
                    <?php echo form_error('CHAUFFEUR_ID', '<div class="text-danger">', '</div>'); ?>
                  </div><br><br><br>

                  <div class="col-md-4">
                    <label for="FName" class="text-dark"style="font-weight: 1000; color:#454545">Heure retour <font color="red">*</font></label>
                    <input type="text" name="HEURE_RETOUR" autocomplete="off" id="HEURE_RETOUR" placeholder="<?=lang('n_identite')?>" value="<?=$membre['HEURE_RETOUR']?>"  class="form-control">
                    <font id="error_HEURE_RETOUR" color="red"></font>
                    <?php echo form_error('HEURE_RETOUR', '<div class="text-danger">', '</div>'); ?> 
                  </div>
                    <div class="col-md-4">
                    <label for="FName" class="text-dark"style="font-weight: 1000; color:#454545">Commentaire <font color="red">*</font></label>
                    <input type="text" name="COMMENTAIRE_ANOMALIE" autocomplete="off" id="COMMENTAIRE_ANOMALIE" placeholder="<?=lang('n_identite')?>" value="<?=$membre['COMMENTAIRE_ANOMALIE']?>"  class="form-control">
                    <font id="error_COMMENTAIRE_ANOMALIE" color="red"></font>
                    <?php echo form_error('COMMENTAIRE_ANOMALIE', '<div class="text-danger">', '</div>'); ?> 
                  </div>  
                  <div class="col-md-4">
                    <label for="FName" class="text-dark"style="font-weight: 1000; color:#454545">Commentaire validaton <font color="red">*</font></label>
                    <input type="text" name="COMMENTAIRE_VALIDATION" autocomplete="off" id="COMMENTAIRE_VALIDATION" placeholder="<?=lang('n_identite')?>" value="<?=$membre['COMMENTAIRE_VALIDATION']?>"  class="form-control">
                    <font id="error_COMMENTAIRE_VALIDATION" color="red"></font>
                    <?php echo form_error('COMMENTAIRE_VALIDATION', '<div class="text-danger">', '</div>'); ?> 
                  </div>

                  <div class="col-md-4">
                    <label for="description" class="text-dark" style="font-weight: 1000; color:#454545">Kilometrage retour <font color="red">*</font></label>
                    <input type="hidden"  name="PHOTO_KILOMETRAGE_RETOUR_OLD" id="PHOTO_KILOMETRAGE_RETOUR_OLD" value="<?=$membre['PHOTO_KILOMETRAGE_RETOUR']?>">
                    <input type="file" class="form-control" id="PHOTO_KILOMETRAGE_RETOUR" name="PHOTO_KILOMETRAGE_RETOUR" value="<?=$membre['PHOTO_KILOMETRAGE_RETOUR']?>" title='Veuillez mettre une photo avec extension:  .png,.PNG,.jpg,.JPG,.JEPG,.jepg'>
                    <font id="PHOTO_KILOMETRAGE_RETOUR_error" color="red"></font>
                  </div>

                    <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Carburant <font color="red">*</font></label>
                      <input type="hidden"  name="PHOTO_CARBURANT_RETOUR_OLD" id="PHOTO_CARBURANT_RETOUR_OLD" value="<?=$membre['PHOTO_CARBURANT_RETOUR']?>">
                      <!--  <input type="hidden"  name="CODE_AGENT" id="CODE_AGENT" value="<?=$membre['CODE_AGENT']?>"> -->
                      <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="PHOTO_CARBURANT_RETOUR" autocomplete="off" id="PHOTO_CARBURANT_RETOUR" value="<?=$membre['PHOTO_CARBURANT_RETOUR']?>"  class="form-control" title='<?=lang('title_file')?>'>
                      <font id="error_PHOTO_CARBURANT_RETOUR" color="red"></font>
                      <?php echo form_error('PHOTO_CARBURANT_RETOUR', '<div class="text-danger">', '</div>'); ?> 
                    </div>

                  </div>
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

  <script>
   function submit_form()
   {
    const kilometrage_retour = document.getElementById('PHOTO_KILOMETRAGE_RETOUR');
    const carburant_retour = document.getElementById('PHOTO_CARBURANT_RETOUR');
  

    var form = document.getElementById("myform");
 
    var statut=1;
  
    $('#PHOTO_KILOMETRAGE_RETOUR_error').html('');
    $('#error_PHOTO_CARBURANT_RETOUR').html('');
    $('#error_HEURE_RETOUR').html('');
    $('#error_COMMENTAIRE_ANOMALIE').html('');
  

    if($('#HEURE_RETOUR').val()=='')
    {
      statut=2;
      $('#error_HEURE_RETOUR').html('<?=lang('msg_validation')?>');
    }

    if($('#COMMENTAIRE_ANOMALIE').val()=='')
    {
      statut=2;
      $('#error_COMMENTAIRE_ANOMALIE').html('<?=lang('msg_validation')?>');
    }

   
  var maxSize = 2 * 1024 * 1024; // Taille maximale en octets (2 Mo)

      var filekilometrage_retour = kilometrage_retour.files[0];

      var fileSizekilometrage_retour = filekilometrage_retour.size; // Taille du fichier en octets

      if(fileSizekilometrage_retour > maxSize)
      {
        statut=2;
        $('#err_kilometrage_retour').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#err_kilometrage_retour').html('');}



      var filecarburant_retour = carburant_retour.files[0];

      var fileSizecarburant_retour = filecarburant_retour.size; // Taille du fichier en octets

      if(fileSizecarburant_retour > maxSize)
      {
        statut=2;
        $('#error_carburant_retour').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_carburant_retour').html('');}




    if(statut==1)
    {
      $('#myform').submit();
    }
  }



</script>
  <script>

   
    var phile1 = document.getElementById('kilometrage_retour');
    var phile2  = document.getElementById('carburant_retour');
   

    $('#kilometrage_retour,#carburant_retour').change(function()
    {
      if(phile1.files.length !==0)
      {
        err_kilometrage_retour.innerHTML ="";
      }

      if(phile2.files.length !==0)
      {
        error_carburant_retour.innerHTML ="";
      }
    });
       //pour les caracteres seuelement
    $("#COMMENTAIRE_ANOMALIE,#COMMENTAIRE_VALIDATION").on('input paste change keyup', function()      
    {
      $('#error_COMMENTAIRE_ANOMALIE,#error_COMMENTAIRE_VALIDATION').hide();
      $(this).val($(this).val().replace(/[^a-z-\s]/gi, '').toUpperCase());
    });
     //pour les chifrres
    // $("#NUMERO_CARTE_IDENTITE").on('input paste change keyup', function()
    // {
    //   $('#error_NUMERO_CARTE_IDENTITE').hide();
    //   $(this).val($(this).val().replace(/[^0-9/.]*$/gi, ''));
    // });

    $("#HEURE_RETOUR").keypress(function(event)
    {
      $('#error_HEURE_RETOUR').hide();
      var character = String.fromCharCode(event.keyCode);
      return isValid(character);     
    });
   
  </script>






</html>