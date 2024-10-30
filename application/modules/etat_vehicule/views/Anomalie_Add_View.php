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
     <div class="row page-titles mx-0">
      <div class="col-sm-10 p-md-0">
        <div class="welcome-text">
          <center>
           <table>
            <tr>

              <td>  
                <!-- <h1 class=" text-center" style="margin-bottom: 1px;"><font class="fa fa-save" style="font-size:18px;"></font> <?=$title?></h1> -->
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

      <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('etat_vehicule/Sortie_Vehicule')?>" class="nav-link position-relative"><i class="bi bi-plus"></i> <?=lang('title_list')?></a>

    </div>
  </div>
</div>
<!-- End Page Title -->

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
                 <form enctype="multipart/form-data" name="myform" id="myform" method="POST" class="form-horizontal" action="<?= base_url('etat_vehicule/Sortie_Vehicule/add'); ?>" >

                  <fieldset class="border p-2">
                    <legend  class="float-none w-auto p-2"><?=$title?></legend>

                    <div class="row">

                      <div class="col-md-4">
                        <label for="genre" class="text-dark" style="font-weight: 1000; color:#454545">Véhicule <font color="red">*</font></label>
                        <select class="form-control" name="VEHICULE_ID" id="VEHICULE_ID">
                         <option value="">---<?=lang('selectionner')?>---</option>
                         <?php 
                         foreach ($vehiculee as $value) 
                         {
                           if ($value['VEHICULE_ID']==set_value('VEHICULE_ID')) 
                            {?>
                             <option value="<?=$value['VEHICULE_ID']?>" selected><?=$value['desc_vehicule']?></option>
                             <?php 
                           }else{?>
                            <option value="<?=$value['VEHICULE_ID']?>"><?=$value['desc_vehicule']?></option>
                            <?php
                          }
                        }
                        ?>
                      </select>
                      <font id="error_VEHICULE_ID" color="red"></font>
                      <?php echo form_error('VEHICULE_ID_error', '<div class="text-danger">', '</div>'); ?>
                    </div>


                    <div class="col-md-4">
                      <label for="genre" class="text-dark" style="font-weight: 1000; color:#454545">Type anomalie <font color="red">*</font></label>
                      <select class="form-control" name="ID_TYPE_ANOMALIE" id="ID_TYPE_ANOMALIE">
                       <option value="">---<?=lang('selectionner')?>---</option>
                       <?php 
                       foreach ($type_anomalie as $value) 
                       {
                         if ($value['ID_TYPE_ANOMALIE']==set_value('DESCRIPTION')) 
                          {?>
                           <option value="<?=$value['ID_TYPE_ANOMALIE']?>" selected=''><?=$value['DESCRIPTION']?></option>
                           <?php 
                         }else{?>
                          <option value="<?=$value['ID_TYPE_ANOMALIE']?>"><?=$value['DESCRIPTION']?></option>
                          <?php
                        }
                      }
                      ?>
                    </select>
                    <font id="error_ID_TYPE_ANOMALIE" color="red"></font>
                    <?php echo form_error('ID_TYPE_ANOMALIE', '<div class="text-danger">', '</div>'); ?>
                  </div>


                  <div class="col-md-4" id="info_supp_panne" style="display:none;">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Indication pannes <font color="red">*</font></label>

                      <textarea class="form-control" type="text" id="COMMENTAIRE_PANNE" name="COMMENTAIRE_PANNE" placeholder="Entrez votre commentaire ici"><?= set_value('COMMENTAIRE_PANNE') ?></textarea>
                      <font id="error_COMMENTAIRE_PANNE" color="red"></font>
                      <?php echo form_error('COMMENTAIRE_PANNE', '<div class="text-danger">', '</div>'); ?> 
                    </div>


                    <div class="col-md-4 info_supp_accident" id="" style="display:none;">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Circonstances accidents <font color="red">*</font></label>

                      <textarea class="form-control" type="text" id="CIRCONSTANNCES_ACCIDENT" name="CIRCONSTANNCES_ACCIDENT" placeholder="Entrez votre commentaire ici"><?= set_value('CIRCONSTANNCES_ACCIDENT') ?></textarea>
                      <font id="error_CIRCONSTANNCES_ACCIDENT" color="red"></font>
                      <?php echo form_error('CIRCONSTANNCES_ACCIDENT', '<div class="text-danger">', '</div>'); ?> 
                    </div>

                    <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Date d'anomalie <font color="red">*</font></label>
                      <input type="date" name="DATE_ANOMALIE" autocomplete="off" id="DATE_ANOMALIE" value="<?= set_value('DATE_ANOMALIE') ?>" max="<?=date('Y-m-d');?>" class="form-control" placeholder="Date course">
                      <font id="error_DATE_ANOMALIE" color="red"></font>
                      <?php echo form_error('DATE_ANOMALIE', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Lieu d'anomalie <font color="red">*</font></label>
                      <input type="text" name="LIEU_ANOMALIE" autocomplete="off" id="LIEU_ANOMALIE" value="<?= set_value('LIEU_ANOMALIE') ?>" class="form-control" placeholder="Lieu d'anomalie">
                      <font id="error_LIEU_ANOMALIE" color="red"></font>
                      <?php echo form_error('LIEU_ANOMALIE', '<div class="text-danger">', '</div>'); ?>
                    </div>

                </div>
              </fieldset>


              <fieldset class="border p-2 info_supp_accident"  style="display:none;">
                <legend  class="float-none w-auto p-2">Informations supplémentaires</legend>

                <div class="row">

                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Image avant <font color="red">*</font></label>
                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="photo_avant" autocomplete="off" id="photo_avant" value="<?= set_value('photo_avant') ?>"  class="form-control" title='<?=lang('title_file')?>'>
                    <font id="error_photo_avant" color="red"></font>
                    <?php echo form_error('photo_avant', '<div class="text-danger">', '</div>'); ?> 
                  </div>

                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Image arriere <font color="red">*</font></label>
                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="photo_arriere" autocomplete="off" id="photo_arriere" value="<?= set_value('photo_arriere') ?>"  class="form-control" title='<?=lang('title_file')?>'>
                    <font id="error_photo_arriere" color="red"></font>
                    <?php echo form_error('photo_arriere', '<div class="text-danger">', '</div>'); ?> 
                  </div>
                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Laterale gauche <font color="red">*</font></label>

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="photolateral_gauche" autocomplete="off" id="photolateral_gauche" value="<?= set_value('photolateral_gauche') ?>"  class="form-control"title='<?=lang('title_file')?>'>
                    <font id="error_photolateral_gauche" color="red"></font>
                    <?php echo form_error('photolateral_gauche', '<div class="text-danger">', '</div>'); ?> 
                    <br>
                  </div>

                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Laterale droite<font color="red">*</font></label>

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="photo_lat_droite" autocomplete="off" id="photo_lat_droite" value="<?= set_value('photo_lat_droite') ?>"  class="form-control" title='<?=lang('title_file')?>'>
                    <font id="error_photo_lat_droite" color="red"></font>
                    <?php echo form_error('photo_lat_droite', '<div class="text-danger">', '</div>'); ?> 
                  </div>

                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Tableau de bord <font color="red">*</font></label>

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="photo_tableau" autocomplete="off" id="photo_tableau" value="<?= set_value('photo_tableau') ?>"  class="form-control" title='<?=lang('title_file')?>'>
                    <font id="error_photo_tableau" color="red"></font>
                    <?php echo form_error('photo_tableau', '<div class="text-danger">', '</div>'); ?> 
                  </div>


                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Siege avant<font color="red">*</font></label>

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="siege_avant" autocomplete="off" id="siege_avant" value="<?= set_value('siege_avant') ?>"  class="form-control" title='<?=lang('title_file')?>'>
                    <font id="error_siege_avant" color="red"></font>
                    <?php echo form_error('siege_avant', '<div class="text-danger">', '</div>'); ?> 
                    <br>
                  </div>

                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Siege arriere <font color="red">*</font></label>

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="siege_arriere" autocomplete="off" id="siege_arriere" value="<?= set_value('siege_arriere') ?>"  class="form-control" title='<?=lang('title_file')?>'>
                    <font id="error_siege_arriere" color="red"></font>
                    <?php echo form_error('siege_arriere', '<div class="text-danger">', '</div>'); ?> 
                  </div>

                </div>

              </fieldset>

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

<script >
  $(document).ready(function(){

    $('#message').delay('slow').fadeOut(10000);
    // $('#info_supp').hide();
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
    const photo_kilometrage = document.getElementById('photo_kilometrage');
    const carburant = document.getElementById('carburant');
    var form = document.getElementById("myform");
    var statut=1;
    $('#error_VEHICULE_ID').html('');
    $('#error_CHAUFFEUR_ID').html('');
    $('#error_DESTINATION').html('');
    $('#error_HEURE_DEPART').html('');
    $('#error_ID_MOTIF_DEP').html('');
    $('#error_COMMENTAIRE').html('');
    $('#error_DATE_COURSE').html('');
    $('#err_photo_kilometrage').html('');
    $('#error_carburant').html('');
    $('#error_photo_avant').html('');
    $('#error_photo_arriere').html('');
    $('#error_photolateral_gauche').html('');
    $('#error_photo_lat_droite').html('');
    $('#error_photo_tableau').html('');
    $('#error_siege_avant').html('');
    $('#error_siege_arriere').html('');


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

    if(photo_kilometrage.files.length === 0)
    {
      statut=2;
      $('#err_photo_kilometrage').text("<?=lang('msg_validation')?>");
    }else{$('#err_photo_kilometrage').html('');}

    if(carburant.files.length === 0)
    {
      statut=2;
      $('#error_carburant').text("<?=lang('msg_validation')?>");
    }else{$('#error_carburant').html('');}


    var filephoto_kilometrage = photo_kilometrage.files[0];

      var fileSizephoto_kilometrage = filephoto_kilometrage.size; // Taille du fichier en octets

      if(fileSizephoto_kilometrage > maxSize)
      {
        statut=2;
        $('#err_photo_kilometrage').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#err_photo_kilometrage').html('');}

      var filecarburant = carburant.files[0];

      var fileSizecarburant = filecarburant.size; // Taille du fichier en octets

      if(fileSizecarburant > maxSize)
      {
        statut=2;
        $('#error_carburant').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_carburant').html('');}


    if ($('#oui').is(':checked'))
    {
      if(photo_avant.files.length === 0)
      {
        statut=2;
        $('#error_photo_avant').text("<?=lang('msg_validation')?>");
      }else{$('#error_photo_avant').text("");}

      if(photo_arriere.files.length === 0)
      {
        statut=2;
        $('#error_photo_arriere').text("<?=lang('msg_validation')?>");
      }else{$('#error_photo_arriere').text("");}

      if(photolateral_gauche.files.length === 0)
      {
        statut=2;
        $('#error_photolateral_gauche').text("<?=lang('msg_validation')?>");
      }else{$('#error_photolateral_gauche').text("");}

      if(photo_lat_droite.files.length === 0)
      {
        statut=2;
        $('#error_photo_lat_droite').text("<?=lang('msg_validation')?>");
      }else{$('#error_photo_lat_droite').text("");}

      if(photo_tableau.files.length === 0)
      {
        statut=2;
        $('#error_photo_tableau').text("<?=lang('msg_validation')?>");
      }else{$('#error_photo_tableau').text("");}

      if(siege_avant.files.length === 0)
      {
        statut=2;
        $('#error_siege_avant').text("<?=lang('msg_validation')?>");
      }else{$('#error_siege_avant').text("");}

      if(siege_arriere.files.length === 0)
      {
        statut=2;
        $('#error_siege_arriere').text("<?=lang('msg_validation')?>");
      }else{$('#error_siege_arriere').text("");}


      var filephoto_avant = photo_avant.files[0];
      var fileSizephoto_avant = filephoto_avant.size; // Taille du fichier en octets
      if(fileSizephoto_avant > maxSize)
      {
        statut=2;
        $('#error_photo_avant').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_photo_avant').html('');}


      var filephoto_arriere = photo_arriere.files[0];
      var fileSizephoto_arriere = filephoto_arriere.size; // Taille du fichier en octets
      if(fileSizephoto_arriere > maxSize)
      {
        statut=2;
        $('#error_photo_arriere').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_photo_arriere').html('');}


      var filephotolateral_gauche = photolateral_gauche.files[0];
      var fileSizephotolateral_gauche = filephotolateral_gauche.size; // Taille du fichier en octets
      if(fileSizephotolateral_gauche > maxSize)
      {
        statut=2;
        $('#error_photolateral_gauche').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_photolateral_gauche').html('');}



      var filephoto_lat_droite = photo_lat_droite.files[0];
      var fileSizephoto_lat_droite = filephoto_lat_droite.size; // Taille du fichier en octets
      if(fileSizephoto_lat_droite > maxSize)
      {
        statut=2;
        $('#error_photo_lat_droite').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_photo_lat_droite').html('');}


      var filephoto_tableau = photo_tableau.files[0];
      var fileSizephoto_tableau = filephoto_tableau.size; // Taille du fichier en octets
      if(fileSizephoto_tableau > maxSize)
      {
        statut=2;
        $('#error_photo_tableau').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_photo_tableau').html('');}

      var filesiege_avant = siege_avant.files[0];
      var fileSizesiege_avant = filesiege_avant.size; // Taille du fichier en octets
      if(fileSizesiege_avant > maxSize)
      {
        statut=2;
        $('#error_siege_avant').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_siege_avant').html('');}


      var filesiege_arriere = siege_arriere.files[0];
      var fileSizesiege_arriere = filesiege_arriere.size; // Taille du fichier en octets
      if(fileSizesiege_arriere > maxSize)
      {
        statut=2;
        $('#error_siege_arriere').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_siege_arriere').html('');}

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