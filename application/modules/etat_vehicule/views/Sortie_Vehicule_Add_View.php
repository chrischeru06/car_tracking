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
              <h1 class=" text-center" style="margin-bottom: 1px;"><font class="fa fa-plus" style="font-size:18px;"></font> <?=$title?></h1>
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

                  <div class="row">
                   
                    <div class="col-md-4">
                      <label for="genre" class="text-dark" style="font-weight: 1000; color:#454545">Vehicule <font color="red">*</font></label>
                      <select class="form-control" name="VEHICULE_ID" id="VEHICULE_ID">
                       <option value="">---<?=lang('selectionner')?>---</option>
                       <?php 
                       foreach ($vehiculee as $value) 
                       {
                         if ($value['VEHICULE_ID']==set_value('CODE')) 
                          {?>
                           <option value="<?=$value['VEHICULE_ID']?>" selected=''><?=$value['CODE']?></option>
                           <?php 
                         }else{?>
                          <option value="<?=$value['VEHICULE_ID']?>"><?=$value['CODE']?></option>
                          <?php
                        }
                      }
                      ?>
                    </select>
                    <font id="error_VEHICULE_ID" color="red"></font>
                    <?php echo form_error('VEHICULE_ID_error', '<div class="text-danger">', '</div>'); ?>
                  </div>
                   <div class="col-md-4">
                      <label for="genre" class="text-dark" style="font-weight: 1000; color:#454545">Chauffeur <font color="red">*</font></label>
                      <select class="form-control" name="CHAUFFEUR_ID" id="CHAUFFEUR_ID">
                       <option value="">---<?=lang('selectionner')?>---</option>
                       <?php 
                       foreach ($chauffeuri as $value) 
                       {
                         if ($value['CHAUFFEUR_ID']==set_value('chauffeur_desc')) 
                          {?>
                           <option value="<?=$value['CHAUFFEUR_ID']?>" selected=''><?=$value['chauffeur_desc']?></option>
                           <?php 
                         }else{?>
                          <option value="<?=$value['CHAUFFEUR_ID']?>"><?=$value['chauffeur_desc']?></option>
                          <?php
                        }
                      }
                      ?>
                    </select>
                    <font id="error_CHAUFFEUR_ID" color="red"></font>
                    <?php echo form_error('CHAUFFEUR_ID', '<div class="text-danger">', '</div>'); ?>
                  </div>

                   <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Destination <font color="red">*</font></label>
                      <input type="text" name="DESTINATION" autocomplete="off" id="DESTINATION" value="<?= set_value('DESTINATION') ?>"  class="form-control" placeholder="Destination">
                      <font id="error_DESTINATION" color="red"></font>
                      <?php echo form_error('DESTINATION', '<div class="text-danger">', '</div>'); ?>
                    </div>
                     <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Heure départ <font color="red">*</font></label>
                      <input type="text" name="HEURE_DEPART" autocomplete="off" id="HEURE_DEPART" value="<?= set_value('HEURE_DEPART') ?>"  class="form-control" placeholder="Heure départ">
                      <font id="error_HEURE_DEPART" color="red"></font>
                      <?php echo form_error('HEURE_DEPART', '<div class="text-danger">', '</div>'); ?>
                    </div>
                

                    <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Heure retour <font color="red">*</font></label>
                      <input type="text" name="HEURE_ESTIMATIVE_RETOUR" autocomplete="off" id="HEURE_ESTIMATIVE_RETOUR" value="<?= set_value('HEURE_ESTIMATIVE_RETOUR') ?>"  class="form-control" placeholder="Heure retour">
                      <font id="err_HEURE_ESTIMATIVE_RETOUR" color="red"></font>
                      <?php echo form_error('HEURE_ESTIMATIVE_RETOUR', '<div class="text-danger">', '</div>'); ?>
                    </div><br><br><br>


                       <div class="col-md-4">
                      <label for="genre" class="text-dark" style="font-weight: 1000; color:#454545">Motif deplacement <font color="red">*</font></label>
                      <select class="form-control" name="ID_MOTIF_DEP" id="ID_MOTIF_DEP">
                       <option value="">---<?=lang('selectionner')?>---</option>
                       <?php 
                       foreach ($motif as $value) 
                       {
                         if ($value['ID_MOTIF_DEP']==set_value('DESC_MOTIF')) 
                          {?>
                           <option value="<?=$value['ID_MOTIF_DEP']?>" selected=''><?=$value['DESC_MOTIF']?></option>
                           <?php 
                         }else{?>
                          <option value="<?=$value['ID_MOTIF_DEP']?>"><?=$value['DESC_MOTIF']?></option>
                          <?php
                        }
                      }
                      ?>
                    </select>
                    <font id="error_ID_MOTIF_DEP" color="red"></font>
                    <?php echo form_error('ID_MOTIF_DEP', '<div class="text-danger">', '</div>'); ?>
                  </div>


                   <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Date course <font color="red">*</font></label>
                      <input type="date" name="DATE_COURSE" autocomplete="off" id="DATE_COURSE" value="<?= set_value('DATE_COURSE') ?>"  class="form-control" placeholder="Date course">
                      <font id="error_DATE_COURSE" color="red"></font>
                      <?php echo form_error('DATE_COURSE', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Commentaire  <font color="red">*</font></label>
                      <input type="text" name="COMMENTAIRE" autocomplete="off" id="COMMENTAIRE" value="<?= set_value('COMMENTAIRE') ?>"  class="form-control" placeholder="Commentaire ">
                      <font id="error_COMMENTAIRE" color="red"></font>
                      <?php echo form_error('COMMENTAIRE', '<div class="text-danger">', '</div>'); ?>
                    </div>



                    <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Carburant (.png,.jpg,.jepg)<font color="red">*</font></label>

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="carburant" autocomplete="off" id="carburant" value="<?= set_value('carburant') ?>"  class="form-control" title='carburant retour'>
                    <font id="error_carburant" color="red"></font>
                    <?php echo form_error('carburant', '<div class="text-danger">', '</div>'); ?> 
                  </div>


                   <div class="col-md-4" class="text-dark">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Kilometrage départ (.png,.jpg,.jepg)<font color="red">*</font></label>
                    <input type="file" name="photo_kilometrage" autocomplete="off" id="photo_kilometrage" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" value="<?= set_value('photo_kilometrage') ?>"  class="form-control" title='<?=lang('title_file')?>'>
                    <font id="err_photo_kilometrage" color="red"></font>
                    <?php echo form_error('photo_kilometrage', '<div class="text-danger">', '</div>'); ?> 
                  </div><br><br><br>
                    <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Image avant (.png,.jpg,.jepg)<font color="red">*</font></label>
                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="photo_avant" autocomplete="off" id="photo_avant" value="<?= set_value('photo_avant') ?>"  class="form-control" title='photo_avant '>
                    <font id="error_photo_avant" color="red"></font>
                    <?php echo form_error('photo_avant', '<div class="text-danger">', '</div>'); ?> 
                  </div>

                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Image arriere (.png,.jpg,.jepg)<font color="red">*</font></label>
                   <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="photo_arriere" autocomplete="off" id="photo_arriere" value="<?= set_value('photo_arriere') ?>"  class="form-control" title='photo_arriere '>
                    <font id="error_photo_arriere" color="red"></font>
                    <?php echo form_error('photo_arriere', '<div class="text-danger">', '</div>'); ?> 
                  </div>
                   <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Laterale gauche (.png,.jpg,.jepg)<font color="red">*</font></label>

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="photolateral_gauche" autocomplete="off" id="photolateral_gauche" value="<?= set_value('photolateral_gauche') ?>"  class="form-control" title='photolateral_gauche '>
                    <font id="error_photolateral_gauche" color="red"></font>
                    <?php echo form_error('photolateral_gauche', '<div class="text-danger">', '</div>'); ?> 
                  </div>

                  <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Laterale droite (.png,.jpg,.jepg)<font color="red">*</font></label>

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="photo_lat_droite" autocomplete="off" id="photo_lat_droite" value="<?= set_value('photo_lat_droite') ?>"  class="form-control" title='photo_lat_droite '>
                    <font id="error_photo_lat_droite" color="red"></font>
                    <?php echo form_error('photo_lat_droite', '<div class="text-danger">', '</div>'); ?> 
                  </div>

                    <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Tableau de bord (.png,.jpg,.jepg)<font color="red">*</font></label>

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="photo_tableau" autocomplete="off" id="photo_tableau" value="<?= set_value('photo_tableau') ?>"  class="form-control" title='photo_tableau '>
                    <font id="error_photo_tableau" color="red"></font>
                    <?php echo form_error('photo_tableau', '<div class="text-danger">', '</div>'); ?> 
                  </div>


                    <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Siege avant (.png,.jpg,.jepg)<font color="red">*</font></label>

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="siege_avant" autocomplete="off" id="siege_avant" value="<?= set_value('siege_avant') ?>"  class="form-control" title='siege_avant'>
                    <font id="error_siege_avant" color="red"></font>
                    <?php echo form_error('siege_avant', '<div class="text-danger">', '</div>'); ?> 
                  </div>

                    <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Siege arriere (.png,.jpg,.jepg)<font color="red">*</font></label>

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="siege_arriere" autocomplete="off" id="siege_arriere" value="<?= set_value('siege_arriere') ?>"  class="form-control" title='siege_arriere'>
                    <font id="error_siege_arriere" color="red"></font>
                    <?php echo form_error('siege_arriere', '<div class="text-danger">', '</div>'); ?> 
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


      if($('#VEHICULE_ID').val()=='')
      {
        statut=2;
        $('#error_VEHICULE_ID').html('<?=lang('msg_validation')?>');
      }
      if($('#CHAUFFEUR_ID').val()=='')
      {
        statut=2;
        $('#error_CHAUFFEUR_ID').html('<?=lang('msg_validation')?>');
      }
      if($('#DESTINATION').val()=='')
      {
        statut=2;
        $('#error_DESTINATION').html('<?=lang('msg_validation')?>');
      }


       if($('#HEURE_DEPART').val()=='')
      {
        statut=2;
        $('#error_HEURE_DEPART').html('<?=lang('msg_validation')?>');
      }
            if($('#HEURE_ESTIMATIVE_RETOUR').val()=='')
      {
        statut=2;
        $('#err_HEURE_ESTIMATIVE_RETOUR').html('<?=lang('msg_validation')?>');
      }
       if($('#ID_MOTIF_DEP').val()=='')
      {
        statut=2;
        $('#error_ID_MOTIF_DEP').html('<?=lang('msg_validation')?>');
      }

      if($('#DATE_COURSE').val()=='')
      {
        statut=2;
        $('#error_DATE_COURSE').html('<?=lang('msg_validation')?>');
      }  
      if($('#COMMENTAIRE').val()=='')
      {
        statut=2;
        $('#error_COMMENTAIRE').html('<?=lang('msg_validation')?>');
      }
      
      if(photo_kilometrage.files.length === 0)
      {
        statut=2;
        $('#err_photo_kilometrage').text("<?=lang('msg_validation')?>");
      }

      if(carburant.files.length === 0)
      {
        statut=2;
        $('#error_carburant').text("<?=lang('msg_validation')?>");
      }
       if(photo_avant.files.length === 0)
      {
        statut=2;
        $('#error_photo_avant').text("<?=lang('msg_validation')?>");
      }
        if(photo_arriere.files.length === 0)
      {
        statut=2;
        $('#error_photo_arriere').text("<?=lang('msg_validation')?>");
      }
      if(photolateral_gauche.files.length === 0)
      {
        statut=2;
        $('#error_photolateral_gauche').text("<?=lang('msg_validation')?>");
      }
       if(photo_lat_droite.files.length === 0)
      {
        statut=2;
        $('#error_photo_lat_droite').text("<?=lang('msg_validation')?>");
      }
       if(photo_tableau.files.length === 0)
      {
        statut=2;
        $('#error_photo_tableau').text("<?=lang('msg_validation')?>");
      }
      if(siege_avant.files.length === 0)
      {
        statut=2;
        $('#error_siege_avant').text("<?=lang('msg_validation')?>");
      }
      if(siege_arriere.files.length === 0)
      {
        statut=2;
        $('#error_siege_arriere').text("<?=lang('msg_validation')?>");
      }
          
       

     var maxSize = 2 * 1024 * 1024; // Taille maximale en octets (2 Mo)

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