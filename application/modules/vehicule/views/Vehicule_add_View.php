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
      <h1>Véhicule</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Véhicule</a></li>
          <li class="breadcrumb-item active">Liste</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
       <div class="container text-center">
        <div class="row">
          <div class="text-left col-sm-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title lead"> <?=$title?></h4>
              </div>
              
              <div class="card-body text-left">

                <?= $this->session->flashdata('message'); ?>

                <!-- <div class="row"> -->
                  <form id="add_form" enctype="multipart/form-data" method="post" action="<?=base_url('vehicule/Vehicule/save')?>">

                    <div class="row text-dark">

                      <div class="col-md-4">
                        <div class="form-group">
                          <label style="font-weight: 1000; color:#454545"><b>Code</b><span  style="color:red;">*</span></label>
                          <input type="hidden" name="VEHICULE_ID" id="VEHICULE_ID" value="<?=$vehicule['VEHICULE_ID']?>">

                          <input class="form-control" type='text' name="CODE" id="CODE" placeholder='code du vehicule' value="<?=$vehicule['CODE']?>"/>

                        </div>
                        <span id="errorCODE" class="text-danger"></span>
                        <?php echo form_error('CODE', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label style="font-weight: 1000; color:#454545"><b>Marque</b><span  style="color:red;">*</span></label>

                          <select class="form-control" name="ID_MARQUE" id="ID_MARQUE" onchange="get_nom_vehicule();">
                            <option value="" selected>-- Séléctionner --</option>
                            <?php
                            foreach ($marque as $marque)
                            {
                              ?>
                              <option value="<?=$marque['ID_MARQUE']?>"<?php if($marque['ID_MARQUE']==$vehicule['ID_MARQUE']) echo " selected";?>><?=$marque['DESC_MARQUE']?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                        <span id="errorID_MARQUE" class="text-danger"></span>
                        <?php echo form_error('ID_MARQUE', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label style="font-weight: 1000; color:#454545"><b>Modèle</b><span  style="color:red;">*</span></label>

                          <select class="form-control" name="ID_NOM" id="ID_NOM">
                            <option value="" selected>-- Séléctionner --</option>

                            <?php
                            foreach ($nom_vehicule as $nom_vehicule)
                            {
                              ?>
                              <option value="<?=$nom_vehicule['ID_NOM']?>"<?php if($nom_vehicule['ID_NOM']==$vehicule['ID_NOM']) echo " selected";?>><?=$nom_vehicule['DESC_NOM']?></option>
                              <?php
                            }
                            ?>

                          </select>
                        </div>
                        <span id="errorID_NOM" class="text-danger"></span>
                        <?php echo form_error('ID_NOM', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label style="font-weight: 1000; color:#454545"><b>Plaque</b><span  style="color:red;">*</span></label>

                          <input class="form-control" type='text' name="PLAQUE" id="PLAQUE" placeholder='plaque du vehicule' value="<?=$vehicule['PLAQUE']?>"/>

                        </div>
                        <span id="errorPLAQUE" class="text-danger"></span>
                        <?php echo form_error('PLAQUE', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label style="font-weight: 1000; color:#454545"><b>Couleur</b><span  style="color:red;">*</span></label>

                          <input class="form-control" type='text' name="COULEUR" id="COULEUR" placeholder='couleur du vehicule' value="<?=$vehicule['COULEUR']?>"/>

                        </div>
                        <span id="errorCOULEUR" class="text-danger"></span>
                        <?php echo form_error('COULEUR', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <label>Photo </label>

                        <input class="form-control" type="hidden" name="PHOTO" id="PHOTO"  value="<?=$vehicule['PHOTO'];?>">

                        <input type="file" class="form-control" name="PHOTO_OUT" id="PHOTO_OUT" value="<?=set_value('PHOTO_OUT')?>" accept="image/png, image/jpeg">
                        <font color='red'><?php echo form_error('PHOTO_OUT'); ?></font>
                        <span id="errorPHOTO_OUT" class="text-danger"></span>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label style="font-weight: 1000; color:#454545"><b>propriétaire</b><span  style="color:red;">*</span></label>

                          <select class="form-control" name="CLIENT_ID" id="CLIENT_ID">
                            <option value="" selected>-- Séléctionner --</option>
                            <?php
                            foreach ($client as $client)
                            {
                              ?>
                              <option value="<?=$client['CLIENT_ID']?>"<?php if($client['CLIENT_ID']==$vehicule['CLIENT_ID']) echo " selected";?>><?=$client['client_desc']?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                        <span id="errorCLIENT_ID" class="text-danger"></span>
                        <?php echo form_error('CLIENT_ID', '<div class="text-danger">', '</div>'); ?>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12">
                        <button type="button" style="float: right;" class="btn btn-secondary" onclick="submit_form();"><i class="fa fa-save"> <?=$btn?></i></button>
                      </div>
                    </div>

                  </form>
                  <!-- </div> -->

                </div>
              </div>
            </div>
          </div>
        </div>
    </section>

  </main><!-- End #main -->

  <?php include VIEWPATH . 'includes/footer.php'; ?>

</body>


<script type="text/javascript">

    // Fonction pour le chargement de donnees par defaut
    $(document).ready( function ()
    {
      $('#message').delay('slow').fadeOut(6000);

      var VEHICULE_ID = $('#VEHICULE_ID').val();

      if(VEHICULE_ID == "")
      {
       $('#ID_NOM').html('<option value="">-- Séléctionner --</option>');
     }  

   });
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
        $('#ID_NOM').html('<option value="">-- Séléctionner --</option>');
      }
    }
  </script>

  <script>

    function submit_form()
    {
      var statut=1;

      if($('#CODE').val()=='')
      {
        statut=2;
        $('#errorCODE').html('Le champ est obligatoire');
      }else{$('#errorCODE').html('');}

      if($('#ID_MARQUE').val()=='')
      {
        statut=2;
        $('#errorID_MARQUE').html('Le champ est obligatoire');
      }else{$('#errorID_MARQUE').html('');}

      if($('#ID_NOM').val()=='')
      {
        statut=2;
        $('#errorID_NOM').html('Le champ est obligatoire');
      }else{$('#errorID_NOM').html('');}

      if($('#PLAQUE').val()=='')
      {
        statut=2;
        $('#errorPLAQUE').html('Le champ est obligatoire');
      }else{$('#errorPLAQUE').html('');}

      if($('#COULEUR').val()=='')
      {
        statut=2;
        $('#errorCOULEUR').html('Le champ est obligatoire');
      }else{$('#errorCOULEUR').html('');}

      if($('#VEHICULE_ID').val() =='')
      {
        if($('#PHOTO_OUT').val()=='')
        {
          statut=2;
          $('#errorPHOTO_OUT').html('Le champ est obligatoire');
        }else{$('#errorPHOTO_OUT').html('');}
      }

      if($('#CLIENT_ID').val()=='')
      {
        statut=2;
        $('#errorCLIENT_ID').html('Le champ est obligatoire');
      }else{$('#errorCLIENT_ID').html('');}


      var VEHICULE_ID = $('#VEHICULE_ID').val();
      var CODE = $('#CODE').val();
      var PLAQUE = $('#PLAQUE').val();

      // if(VEHICULE_ID != "")
      // {
      //   $.ajax(
      //   {
      //     // url:"<?=base_url('vehicule/Vehicule/check_existe/')?>"+VEHICULE_ID+'/'+CODE+'/'+PLAQUE,
      //     // type: "GET",
      //     // dataType:"JSON",

      //      url:"<?=base_url()?>vehicule/Vehicule/check_existe/"+VEHICULE_ID+'/'+CODE+'/'+PLAQUE,
      //     type:"GET",
      //     dataType:"JSON",
      //     success: function(data)
      //     {
      //       // $('#ID_NOM').html(data);
      //     },
      //     // error: function (jqXHR, textStatus, errorThrown)
      //     // {
      //     //   alert('Erreur');
      //     // }
      //   });

      // }

      // alert(statut)

      if(statut==1)
      {
        $('#add_form').submit();
      }
    }

  </script>

</html>