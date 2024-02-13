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
             <table>
              <tr>
                <td> 
                  <!-- <img src="<?= base_url()?>template/imagespopup/IconeMuyingajdfss-04.png" width="60px" height="60px" alt=""> -->
                </td>
                <td>  
                  <h4 class="text-dark">Véhicule</h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="#">Véhicule</a></li>
                      <li class="breadcrumb-item"><a href="#">Liste</a></li>
                      <!-- <li class="breadcrumb-item active" aria-current="page">Saving slides</li> -->
                    </ol>
                  </nav>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="col-md-2">

         <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('vehicule/Vehicule')?>" class="nav-link position-relative"><i class="bi bi-list"></i> Liste</a>

       </div>
     </div>


    <section class="section">
       <!-- <div class="container text-center"> -->
        <div class="row">
          <div class="text-left col-sm-12">
            <div class="card" style="border-radius: 20px;">
              <div class="card-header" style="border-radius: 20px;">
                <h4 class="card-title"> <small><?=$title?></small></h4>
              </div>

              <br>
              
              <div class="card-body">

                <?= $this->session->flashdata('message'); ?>

                <!-- <div class="row"> -->
                  <form id="add_form" enctype="multipart/form-data" method="post" action="<?=base_url('vehicule/Vehicule/save')?>">

                    <div class="row text-dark">

                      <div class="col-md-4">
                        <div class="form-group">
                          <label ><small> Code</small><span  style="color:red;">*</span></label>
                          <input type="hidden" name="VEHICULE_ID" id="VEHICULE_ID" value="<?=$vehicule['VEHICULE_ID']?>">

                          <input class="form-control" type='text' name="CODE" id="CODE" placeholder='code du vehicule' value="<?=$vehicule['CODE']?>"/>

                        </div>
                        <span id="errorCODE" class="text-danger"></span>
                        <?php echo form_error('CODE', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label ><small>Marque</small><span  style="color:red;">*</span></label>

                          <select class="form-control" name="ID_MARQUE" id="ID_MARQUE" onchange="get_modele();">
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
                          <label><small>Modèle</small><span  style="color:red;">*</span></label>

                          <select class="form-control" name="ID_MODELE" id="ID_MODELE">
                            <option value="" selected>-- Séléctionner --</option>

                            <?php
                            foreach ($modele as $modele)
                            {
                              ?>
                              <option value="<?=$modele['ID_MODELE']?>"<?php if($modele['ID_MODELE']==$vehicule['ID_MODELE']) echo " selected";?>><?=$modele['DESC_MODELE']?></option>
                              <?php
                            }
                            ?>

                          </select>
                        </div>
                        <span id="errorDESC_MODELE" class="text-danger"></span>
                        <?php echo form_error('ID_MODELE', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label ><small>Plaque</small><span  style="color:red;">*</span></label>

                          <input class="form-control" type='text' name="PLAQUE" id="PLAQUE" placeholder='plaque du vehicule' value="<?=$vehicule['PLAQUE']?>"/>

                        </div>
                        <span id="errorPLAQUE" class="text-danger"></span>
                        <?php echo form_error('PLAQUE', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label ><small>Couleur</small><span  style="color:red;">*</span></label>

                          <input class="form-control" type='text' name="COULEUR" id="COULEUR" placeholder='couleur du vehicule' value="<?=$vehicule['COULEUR']?>"/>

                        </div>
                        <span id="errorCOULEUR" class="text-danger"></span>
                        <?php echo form_error('COULEUR', '<div class="text-danger">', '</div>'); ?>
                      </div>

                      <div class="col-md-4">
                        <label> <small>Photo</small> </label>

                        <input class="form-control" type="hidden" name="PHOTO" id="PHOTO"  value="<?=$vehicule['PHOTO'];?>">

                        <input type="file" class="form-control" name="PHOTO_OUT" id="PHOTO_OUT" value="<?=set_value('PHOTO_OUT')?>" accept="image/png, image/jpeg">
                        <font color='red'><?php echo form_error('PHOTO_OUT'); ?></font>
                        <span id="errorPHOTO_OUT" class="text-danger"></span>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label><small>propriétaire</small><span  style="color:red;">*</span></label>

                          <select class="form-control" name="PROPRIETAIRE_ID" id="PROPRIETAIRE_ID">
                            <option value="" selected>-- Séléctionner --</option>
                            <?php
                            foreach ($proprio as $proprio)
                            {
                              ?>
                              <option value="<?=$proprio['PROPRIETAIRE_ID']?>"<?php if($proprio['PROPRIETAIRE_ID']==$vehicule['PROPRIETAIRE_ID']) echo " selected";?>><?=$proprio['proprio_desc']?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                        <span id="errorPROPRIETAIRE_ID" class="text-danger"></span>
                        <?php echo form_error('PROPRIETAIRE_ID', '<div class="text-danger">', '</div>'); ?>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12">
                        <button type="button" style="float: right;" class="btn btn-outline-primary rounded-pill " onclick="submit_form();"><i class="bi bi-check"> <?=$btn?></i></button>
                      </div>
                    </div>

                  </form>
                  <!-- </div> -->

                </div>
              </div>
            </div>
          </div>
        <!-- </div> -->
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
       $('#ID_MODELE').html('<option value="">-- Séléctionner --</option>');
     }  

   });
    function get_modele()
    {

      if($('#ID_MARQUE').val()!='')
      {
        $.ajax(
        {
          url:"<?=base_url('vehicule/Vehicule/get_modele/')?>"+$('#ID_MARQUE').val(),
          type: "GET",
          dataType:"JSON",
          success: function(data)
          {
            $('#ID_MODELE').html(data);
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
            alert('Erreur');
          }
        });
      }
      else
      {
        $('#ID_MODELE').html('<option value="">-- Séléctionner --</option>');
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

      if($('#ID_MODELE').val()=='')
      {
        statut=2;
        $('#errorID_MODELE').html('Le champ est obligatoire');
      }else{$('#errorID_MODELE').html('');}

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

      if($('#PROPRIETAIRE_ID').val()=='')
      {
        statut=2;
        $('#errorPROPRIETAIRE_ID').html('Le champ est obligatoire');
      }else{$('#errorPROPRIETAIRE_ID').html('');}


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