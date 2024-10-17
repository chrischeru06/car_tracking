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

      <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('etat_vehicule/Retour_Vehicule')?>" class="nav-link position-relative"><i class="bi bi-plus"></i> <?=lang('title_list')?></a>

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
                   <form enctype="multipart/form-data" name="myform" id="myform" method="POST" class="form-horizontal" action="<?= base_url('etat_vehicule/Retour_Vehicule/add'); ?>" >

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
                      <select class="form-control" name="CHAUFFEUR_ID" id="VEHICULE_ID">
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
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Heure retour <font color="red">*</font></label>
                      <input type="text" name="HEURE_RETOUR" autocomplete="off" id="prenom" value="<?= set_value('HEURE_RETOUR') ?>"  class="form-control" placeholder="Heure retour">
                      <font id="error_HEURE_RETOUR" color="red"></font>
                      <?php echo form_error('HEURE_RETOUR', '<div class="text-danger">', '</div>'); ?>
                    </div>
                

                   <div class="col-md-4" class="text-dark">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Kilometrage retour (.png,.jpg,.jepg)<font color="red">*</font></label>
                    <input type="file" name="kilometrage_retour" autocomplete="off" id="kilometrage_retour" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" value="<?= set_value('kilometrage_retour') ?>"  class="form-control" title='<?=lang('title_file')?>'>
                    <font id="err_kilometrage_retour" color="red"></font>
                    <?php echo form_error('kilometrage_retour', '<div class="text-danger">', '</div>'); ?> 
                  </div><br><br><br>

                    <div class="col-md-4">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Carburant (.png,.jpg,.jepg)<font color="red">*</font></label>

                    <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="carburant_retour" autocomplete="off" id="carburant_retour" value="<?= set_value('carburant_retour') ?>"  class="form-control" title='carburant retour'>
                    <font id="error_carburant_retour" color="red"></font>
                    <?php echo form_error('carburant_retour', '<div class="text-danger">', '</div>'); ?> 
                  </div>

                   <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Commentaire <font color="red">*</font></label>
                      <input type="text" name="COMMENTAIRE_ANOMALIE" autocomplete="off" id="COMMENTAIRE_ANOMALIE" value="<?= set_value('COMMENTAIRE_ANOMALIE') ?>"  class="form-control" placeholder="Commentaire anomalie">
                      <font id="error_COMMENTAIRE_ANOMALIE" color="red"></font>
                      <?php echo form_error('COMMENTAIRE_ANOMALIE', '<div class="text-danger">', '</div>'); ?>
                    </div>

                   <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Commentaire validation <font color="red">*</font></label>
                      <input type="text" name="COMMENTAIRE_VALIDATION" autocomplete="off" id="COMMENTAIRE_VALIDATION" value="<?= set_value('COMMENTAIRE_VALIDATION') ?>"  class="form-control" placeholder="Commentaire validation">
                      <font id="error_COMMENTAIRE_VALIDATION" color="red"></font>
                      <?php echo form_error('COMMENTAIRE_VALIDATION', '<div class="text-danger">', '</div>'); ?>
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
      const kilometrage_retour = document.getElementById('kilometrage_retour');
      const carburant_retour = document.getElementById('carburant_retour');
      var form = document.getElementById("myform");
      var statut=1;
      $('#error_VEHICULE_ID').html('');
      $('#error_CHAUFFEUR_ID').html('');
      $('#error_HEURE_RETOUR').html('');
      $('#err_kilometrage_retour').html('');
      $('#error_carburant_retour').html('');
      $('#error_COMMENTAIRE_ANOMALIE').html('');
      $('#error_COMMENTAIRE_VALIDATION').html('');

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
       if($('#COMMENTAIRE_VALIDATION').val()=='')
      {
        statut=2;
        $('#error_COMMENTAIRE_VALIDATION').html('<?=lang('msg_validation')?>');
      }
      
      if(kilometrage_retour.files.length === 0)
      {
        statut=2;
        $('#err_kilometrage_retour').text("<?=lang('msg_validation')?>");
      }

      if(carburant_retour.files.length === 0)
      {
        statut=2;
        $('#error_carburant_retour').text("<?=lang('msg_validation')?>");
      }

      // if($('#PHOTO_KILOMETRAGE_RETOUR').val()=='')
      // {
      //   statut=2;
      //   $('#err_PHOTO_KILOMETRAGE_RETOUR').html('<?=lang('msg_validation')?>');
      // }
      //  if($('#PHOTO_CARBURANT_RETOUR').val()=='')
      // {
      //   statut=2;
      //   $('#error_PHOTO_CARBURANT_RETOUR').html('<?=lang('msg_validation')?>');
      // }

     

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