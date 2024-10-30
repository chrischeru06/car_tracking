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
                <!-- <h1 class=" text-center" style="margin-bottom: 1px;"><font class="fa fa-plus" style="font-size:18px;"></font> <?=$title?></h1> -->
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

      <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('etat_vehicule/Retour_Vehicule')?>" class="nav-link position-relative"><i class="bi bi-list"></i> <?=lang('title_list')?></a>

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
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Heure retour <font color="red">*</font></label>
                      <input type="time" name="HEURE_RETOUR"  autocomplete="off" id="HEURE_RETOUR" value="<?= set_value('HEURE_RETOUR') ?>"  class="form-control" placeholder="Heure retour">
                      <font id="error_HEURE_RETOUR" color="red"></font>
                      <?php echo form_error('HEURE_RETOUR', '<div class="text-danger">', '</div>'); ?>
                    </div>


                    <div class="col-md-4" class="text-dark">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Kilometrage au retour<font color="red">*</font></label>
                      <input type="file" name="PHOTO_KILOMETRAGE_RETOUR" autocomplete="off" id="PHOTO_KILOMETRAGE_RETOUR" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" value="<?= set_value('PHOTO_KILOMETRAGE_RETOUR') ?>"  class="form-control" title='<?=lang('title_file')?>'>
                      <font id="err_PHOTO_KILOMETRAGE_RETOUR" color="red"></font>
                      <?php echo form_error('PHOTO_KILOMETRAGE_RETOUR', '<div class="text-danger">', '</div>'); ?>
                      <br>
                    </div>

                    <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Niveau carburant au retour<font color="red">*</font></label>

                      <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="PHOTO_CARBURANT_RETOUR" autocomplete="off" id="PHOTO_CARBURANT_RETOUR" value="<?= set_value('PHOTO_CARBURANT_RETOUR') ?>"  class="form-control" title='<?=lang('title_file')?>'>
                      <font id="error_PHOTO_CARBURANT_RETOUR" color="red"></font>
                      <?php echo form_error('PHOTO_CARBURANT_RETOUR', '<div class="text-danger">', '</div>'); ?> 
                    </div>

                    <div class="col-md-4" class="text-dark">
                      <font for="FName" class="text-dark" style="font-weight: 1000; color:#454545">État de la voiture  </font><br>
                      <small class="text-small text-muted"><b>La voiture est en bon état ?  </b></small>

                      <div class="row">

                        <div class="col-md-2">
                          <input type="radio" id="non" name="IS_EXCHANGE" value="1" checked onclick="reply_question();">
                          <label for="non">Non</label><br><br>
                        </div>

                        <div class="col-md-2">
                          <input type="radio" id="oui" name="IS_EXCHANGE" value="2" onclick="reply_question();">
                          <label for="oui">Oui</label><br>
                        </div> 

                      </div>

                    </div>

                    <div class="col-md-4" id="info_supp" style="display:none;">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Anomalies constatées <font color="red">*</font></label>

                      <textarea class="form-control" type="text" id="COMMENTAIRE_ANOMALIE" name="COMMENTAIRE_ANOMALIE" placeholder="Entrez votre commentaire ici"><?= set_value('COMMENTAIRE_ANOMALIE') ?></textarea>
                      <font id="error_COMMENTAIRE_ANOMALIE" color="red"></font>
                      <?php echo form_error('COMMENTAIRE_ANOMALIE', '<div class="text-danger">', '</div>'); ?> 
                    </div>

                  </div>
                </fieldset>


              </form>
              <div class="col-md-12" style="margin-top:10px;" id="submitButton">
                <button type="submit" style="float: right;" class="btn btn-outline-primary rounded-pill " onclick="submit_form();"><span class="fa fa-save "> </span> <?=lang('btn_enregistrer')?></button>
              </div>
            <!--   <div class="col-md-12" style="margin-top:10px;">
                <button style="float: right;" class="btn btn-outline-primary rounded-pill " onclick="submit_form();"><span class="fas "></span> <?=lang('btn_enregistrer')?></button>
              </div> -->
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

    const kilometrage_retour = document.getElementById('PHOTO_KILOMETRAGE_RETOUR');
    const carburant_retour = document.getElementById('PHOTO_CARBURANT_RETOUR');

    var form = document.getElementById("myform");

    var statut=1;

    $('#error_VEHICULE_ID').html('');
    $('#error_HEURE_RETOUR').html('');
    $('#err_PHOTO_KILOMETRAGE_RETOUR').html('');
    $('#error_PHOTO_CARBURANT_RETOUR').html('');
    $('#error_COMMENTAIRE_ANOMALIE').html('');

    if($('#VEHICULE_ID').val() == '')
    {
      statut=2;
      $('#error_VEHICULE_ID').html('<?=lang('msg_validation')?>');
    }

    if($('#HEURE_RETOUR').val() == '')
    {
      statut=2;
      $('#error_HEURE_RETOUR').html('<?=lang('msg_validation')?>');
    }

    if ($('#oui').is(':checked'))
    {
      var commentaire = $('#COMMENTAIRE_ANOMALIE').val().trim();

      if(commentaire.length === 0)
      {
        statut=2;
        $('#error_COMMENTAIRE_ANOMALIE').html('<?=lang('msg_validation')?>');
      }
    }

    if(kilometrage_retour.files.length === 0)
    {
      statut=2;
      $('#err_PHOTO_KILOMETRAGE_RETOUR').text("<?=lang('msg_validation')?>");
    }

    if(carburant_retour.files.length === 0)
    {
      statut=2;
      $('#error_PHOTO_CARBURANT_RETOUR').text("<?=lang('msg_validation')?>");
    }

     var maxSize = 2 * 1024 * 1024; // Taille maximale en octets (2 Mo)

     var filekilometrage_retour = kilometrage_retour.files[0];

      var fileSizekilometrage_retour = filekilometrage_retour.size; // Taille du fichier en octets

      if(fileSizekilometrage_retour > maxSize)
      {
        statut=2;
        $('#err_PHOTO_KILOMETRAGE_RETOUR').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#err_PHOTO_KILOMETRAGE_RETOUR').html('');}

      var filecarburant_retour = carburant_retour.files[0];

      var fileSizecarburant_retour = filecarburant_retour.size; // Taille du fichier en octets

      if(fileSizecarburant_retour > maxSize)
      {
        statut=2;
        $('#error_PHOTO_CARBURANT_RETOUR').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#error_PHOTO_CARBURANT_RETOUR').html('');}


      if(statut==1)
      {
        $('#myform').submit();
      }
    }

  </script>


  </html>