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
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Destination <font color="red">*</font></label>
                      <input type="text" name="DESTINATION" autocomplete="off" id="DESTINATION" value="<?=$membre['DESTINATION']?>"  class="form-control" placeholder="Destination">
                      <font id="error_DESTINATION" color="red"></font>
                      <?php echo form_error('DESTINATION', '<div class="text-danger">', '</div>'); ?>
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
                    </div><br><br><br>
                         <div class="col-md-4">
                      <label for="genre" class="text-dark" style="font-weight: 1000; color:#454545">Motif deplacement <font color="red">*</font></label>
                      <select class="form-control" name="ID_MOTIF_DEP" id="ID_MOTIF_DEP">
                       <option value="">---<?=lang('selectionner')?>---</option>
                       <?php 
                        $moti=$this->Model->getOne('motif_deplacement',array('ID_MOTIF_DEP' =>$membre['ID_MOTIF_DEP']));
                       foreach ($motif as $value) 
                       {

                        if ($value['ID_MOTIF_DEP']==$moti['ID_MOTIF_DEP'])
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
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Date course <font color="red">*</font></label>
                      <input type="date" name="DATE_COURSE" autocomplete="off" id="DATE_COURSE" value="<?=$membre['DATE_COURSE']?>"   class="form-control" placeholder="Date course">
                      <font id="error_DATE_COURSE" color="red"></font>
                      <?php echo form_error('DATE_COURSE', '<div class="text-danger">', '</div>'); ?>
                     </div>

                     <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Commentaire  <font color="red">*</font></label>
                      <input type="text" name="COMMENTAIRE" autocomplete="off" id="COMMENTAIRE" value="<?=$membre['COMMENTAIRE']?>"  class="form-control" placeholder="Commentaire ">
                      <font id="error_COMMENTAIRE" color="red"></font>
                      <?php echo form_error('COMMENTAIRE', '<div class="text-danger">', '</div>'); ?>
                    </div> 
                    <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Carburant (.png,.jpg,.jepg)<font color="red">*</font></label>
                    <input type="hidden"  name="PHOTO_CARBURANT_OLD" id="PHOTO_CARBURANT_OLD" value="<?=$membre['PHOTO_CARBURANT']?>">

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="PHOTO_CARBURANT" autocomplete="off" id="PHOTO_CARBURANT" value="<?= set_value('PHOTO_CARBURANT') ?>"  class="form-control" title='PHOTO_CARBURANT'>
                    <font id="error_PHOTO_CARBURANT" color="red"></font>
                    <?php echo form_error('PHOTO_CARBURANT', '<div class="text-danger">', '</div>'); ?> 
                  </div>

                  <div class="col-md-4">
                    <label for="description" class="text-dark" style="font-weight: 1000; color:#454545">Kilometrage départ <font color="red">*</font></label>
                    <input type="hidden"  name="PHOTO_KILOMETAGE_OLD" id="PHOTO_KILOMETAGE_OLD" value="<?=$membre['PHOTO_KILOMETAGE']?>">
                    <input type="file" class="form-control" id="PHOTO_KILOMETAGE" name="PHOTO_KILOMETAGE" value="<?=$membre['PHOTO_KILOMETAGE']?>" title='Veuillez mettre une photo avec extension:  .png,.PNG,.jpg,.JPG,.JEPG,.jepg'>
                    <font id="PHOTO_KILOMETAGE_error" color="red"></font>
                  </div>

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
                    </div>

                     <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Laterale gauche<font color="red">*</font></label>
                      <input type="hidden"  name="IMAGE_LATERALE_GAUCHE_OLD" id="IMAGE_LATERALE_GAUCHE_OLD" value="<?=$membre['IMAGE_LATERALE_GAUCHE']?>">
                      <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="IMAGE_LATERALE_GAUCHE" autocomplete="off" id="IMAGE_LATERALE_GAUCHE" value="<?=$membre['IMAGE_LATERALE_GAUCHE']?>"  class="form-control" title='<?=lang('title_file')?>'>
                      <font id="error_IMAGE_LATERALE_GAUCHE" color="red"></font>
                      <?php echo form_error('IMAGE_LATERALE_GAUCHE', '<div class="text-danger">', '</div>'); ?> 
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
    const photo_kilometrage = document.getElementById('PHOTO_KILOMETAGE');
    const carburant = document.getElementById('PHOTO_CARBURANT');
    const photo_avant = document.getElementById('IMAGE_AVANT');
    const photo_arriere = document.getElementById('IMAGE_ARRIERE'); 
    const photolateral_gauche = document.getElementById('IMAGE_LATERALE_GAUCHE');
    const photo_lat_droite = document.getElementById('IMAGE_LATERALE_DROITE');
    const photo_tableau = document.getElementById('IMAGE_TABLEAU_DE_BORD');
    const siege_avant = document.getElementById('IMAGE_SIEGE_AVANT');
    const siege_arriere = document.getElementById('IMAGE_SIEGE_ARRIERE');
  
            
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

    $('#error_COMMENTAIRE').html('');
    $('#error_IMAGE_LATERALE_DROITE').html('');
    $('#error_DATE_COURSE').html('');


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
    if($('#COMMENTAIRE').val()=='')
    {
      statut=2;
      $('#error_COMMENTAIRE').html('<?=lang('msg_validation')?>');
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