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
    <div class="col-sm-10">
      <div class="welcome-text">
       <center>

        <table>
          <tr>
            <td> 
            </td>
            <td>  
              <h4 class="text-dark text-center" style="margin-bottom: 1px; margin-top: 3px;"><?=$title?></h4>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"></a></li>
                </ol>
              </nav>
            </td>
          </tr>
        </table>
      </center>
    </div>
  </div>
  <div class="col-md-2">

   <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('sim_management/Sim_management')?>" class="nav-link position-relative"><i class="bi bi-list"></i> Liste</a>

 </div>
</div>


<section class="section">
 <!-- <div class="container text-center"> -->
  <div class="row">
    <div class="text-left col-sm-12">
      <div class="card" style="border-radius: 20px;">

        <br>

        <div class="card-body">

          <?= $this->session->flashdata('message'); ?>
          <!-- <div class="row"> -->
            <form id="add_form" enctype="multipart/form-data" method="post" action="<?=base_url('sim_management/Sim_management/save')?>">

              <div class="row">

                <div class="col-md-4">
                  <div class="form-group">
                    <label ><small> Code (device uid)</small><span  style="color:red;">*</span></label>

                    <input type="hidden" name="DEVICE_ID" id="DEVICE_ID" value="<?=$device_data['DEVICE_ID']?>">

                    <input type="hidden" id="existe_code">

                    <input class="form-control" type='text' name="CODE" id="CODE" placeholder='' value="<?=$device_data['CODE']?>"/>

                  </div>
                  <span id="errorCODE" class="text-danger"></span>
                  <?php echo form_error('CODE', '<div class="text-danger">', '</div>'); ?>
                  <br>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label ><small>Véhicule</small><span  style="color:red;">*</span></label>

                    <select class="form-control" name="VEHICULE_ID" id="VEHICULE_ID" onchange="get_proprietaire();">
                      <option value="0" selected>-- Séléctionner --</option>
                      <?php
                      foreach ($vehicule as $vehicule)
                      {
                        ?>
                        <option value="<?=$vehicule['VEHICULE_ID']?>"<?php if($vehicule['VEHICULE_ID']==$device_data['VEHICULE_ID']) echo " selected";?>><?=$vehicule['DESC_MARQUE'].' - '.$vehicule['DESC_MODELE'].' - '.$vehicule['PLAQUE']?></option>
                        <?php
                      }
                      ?>
                    </select>
                  </div>
                  <span id="errorVEHICULE_ID" class="text-danger"></span>
                  <?php echo form_error('VEHICULE_ID', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label ><small> Propriétaire</small><span  style="color:red;"></span></label>
                    <input class="form-control" type='text' name="PROPRIETAIRE_ID" id="PROPRIETAIRE_ID" value="<?=$device_data['proprio_desc']?>" placeholder='' readonly/>

                  </div>
                  <span id="errorPROPRIETAIRE_ID" class="text-danger"></span>
                  <?php echo form_error('PROPRIETAIRE_ID', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label ><small> Date d'installation</small><span  style="color:red;">*</span></label>

                    <input class="form-control" type='date' name="DATE_INSTALL" id="DATE_INSTALL" placeholder='' value="<?=$device_data['DATE_INSTALL']?>" max="<?= date('Y-m-d')?>"/>

                  </div>
                  <span id="errorDATE_INSTALL" class="text-danger"></span>
                  <?php echo form_error('DATE_INSTALL', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label ><small>Carte sim</small><span  style="color:red;">*</span></label>

                    <select class="form-control" name="OPERATEUR_ID" id="OPERATEUR_ID">
                      <option value="0" selected>-- Séléctionner --</option>
                      <?php
                      foreach ($operateur as $operateur)
                      {
                        ?>
                        <option value="<?=$operateur['OPERATEUR_ID']?>"<?php if($operateur['OPERATEUR_ID'] == $device_data['OPERATEUR_ID'] || $operateur['OPERATEUR_ID'] == 1) echo " selected";?>><?=$operateur['DESC_OPERATEUR']?></option>
                        <?php
                      }
                      ?>
                    </select>
                  </div>
                  <span id="errorOPERATEUR_ID" class="text-danger"></span>
                  <?php echo form_error('OPERATEUR_ID', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label ><small> Numéro carte sim</small><span  style="color:red;">*</span></label>

                    <input type="hidden" id="existe_numero">
                    <input class="form-control" type='text' name="NUMERO" id="NUMERO" placeholder='' value="<?=isset($device_data['NUMERO'])?$device_data['NUMERO']:'+257'?>"/>
                  </div>
                  <span id="errorNUMERO" class="text-danger"></span>
                  <?php echo form_error('NUMERO', '<div class="text-danger">', '</div>'); ?>
                  <br>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label ><small> Date activation forfait</small><span  style="color:red;">*</span></label>

                    <input class="form-control" type='date' name="DATE_ACTIVE_MEGA" id="DATE_ACTIVE_MEGA" placeholder='' value="<?=$device_data['DATE_ACTIVE_MEGA']?>" max="<?= date('Y-m-d')?>" onchange="get_date_expire();"/>

                  </div>
                  <span id="errorDATE_ACTIVE_MEGA" class="text-danger"></span>
                  <?php echo form_error('DATE_ACTIVE_MEGA', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label ><small> Date expiration forfait</small><span  style="color:red;">*</span></label>

                    <input class="form-control" type='date' name="DATE_EXPIRE_MEGA" id="DATE_EXPIRE_MEGA" placeholder='' value="<?=$device_data['DATE_EXPIRE_MEGA']?>" readonly/>

                  </div>
                  <span id="errorDATE_EXPIRE_MEGA" class="text-danger"></span>
                  <?php echo form_error('DATE_EXPIRE_MEGA', '<div class="text-danger">', '</div>'); ?>
                </div>

              </div>
              <br>
              <div class="row">

                <div class="col-md-12">
                  <button type="button" style="float: right;" class="btn btn-outline-primary rounded-pill " onclick="submit_form();"><i class="bi bi-check"></i><?=$btn?></button>
                </div>
              </div>

            </form>  
          </div>

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


<script>

  // Fonction pour le chargement de donnees par defaut
  $(document).ready( function ()
  {
    $('#message').delay('slow').fadeOut(6000);  

  });

</script>

<script>
  //Fonction pour recuperer le proprietaire
  function get_proprietaire(){

    $.ajax(
    {
      url:"<?=base_url('sim_management/Sim_management/get_proprietaire/')?>"+$('#VEHICULE_ID').val(),
      type: "GET",
      dataType:"JSON",
      success: function(data)
      {
        $('#PROPRIETAIRE_ID').val(data);
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        alert('Erreur');
      }
    });
  }
</script>

<script>
        //Require phone

  $('#NUMERO').on('input change',function()
  {
    $(this).val($(this).val().replace(/[^0-9]*$/gi, ''));
    $(this).val($(this).val().replace(' ', ''));
    var subStr = this.value.substring(0,1);

    if(subStr != '+')
    {
      $('[name = "NUMERO"]').val('+257');
    }

    if(this.value.substring(0,4)=="+257")
    {
      if($(this).val().length == 12)
      {
        $('#errorNUMERO').text('');
      }
      else
      {
        $('#errorNUMERO').text('Numéro de téléphone est invalide ');
        if($(this).val().length > 12)
        {
          $(this).val(this.value.substring(0,12));
          $('#errorNUMERO').text('');
        }
      }
    }
    else
    {
      if ($(this).val().length > 12)
      {
        $('#errorNUMERO').text('');
      }
      else
      {
        $('#errorNUMERO').text('Invalide numéro de téléphone');
      }        
    }
  });
</script>

<script type="text/javascript">
  function get_date_expire()
  {
    var DATE_ACTIVE_MEGA = $('#DATE_ACTIVE_MEGA').val();

    $.ajax(
    {
      url:"<?=base_url('sim_management/Sim_management/get_date_expire/')?>",
      type : "POST",
      dataType: "JSON",
      cache:false,
      data: {
        DATE_ACTIVE_MEGA:DATE_ACTIVE_MEGA,
      },
      success: function(data)
      {
        $("#DATE_EXPIRE_MEGA").val(data);
        $("#DATE_EXPIRE_MEGA").prop('min',data);
        $("#DATE_EXPIRE_MEGA").prop('max',data);
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        alert('Erreur');
      }
    });
    
  }
</script>

<script>

  function submit_form()
  {
    var statut = 1;

    var CODE = $('#CODE').val();
    var NUMERO = $('#NUMERO').val();
    var DEVICE_ID = $('#DEVICE_ID').val();

    if(CODE == '')
    {
      statut = 2;
      $('#errorCODE').html('Le champ est obligatoire');
    }
    else
    {
      $.ajax(
      {
        url:"<?=base_url('sim_management/Sim_management/check_code/')?>",
        type : "POST",
        dataType: "JSON",
        cache:false,
        data: {
          CODE:CODE,
          NUMERO:NUMERO,
          DEVICE_ID:DEVICE_ID,
        },
        success: function(data)
        {
          $('#existe_code').val(data.existe_code);
          $('#existe_numero').val(data.existe_numero);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert('Erreur');
        }
      });

      if($('#existe_code').val() == 1)
      {
        statut = 2;
        $('#errorCODE').html('Le code existe déjà');
      }else{$('#errorCODE').html('');}

    }

    if($('#VEHICULE_ID').val() == 0)
    {
      statut = 2;
      $('#errorVEHICULE_ID').html('Le champ est obligatoire');
    }else{$('#errorVEHICULE_ID').html('');}

    if($('#DATE_INSTALL').val() == '')
    {
      statut = 2;
      $('#errorDATE_INSTALL').html('Le champ est obligatoire');
    }else{$('#errorDATE_INSTALL').html('');}

    if($('#OPERATEUR_ID').val() == '0')
    {
      statut = 2;
      $('#errorOPERATEUR_ID').html('Le champ est obligatoire');
    }else{$('#errorOPERATEUR_ID').html('');}

    if($('#NUMERO').val() == '')
    {
      statut = 2;
      $('#errorNUMERO').html('Le champ est obligatoire');
    }
    else if($('#NUMERO').val().length != 12)
    {
      $('#errorNUMERO').text('Numéro de téléphone est invalide ');
      statut = 2;
    }
    else if($('#existe_numero').val() == '')
    {
      statut = 2;
    }
    else if($('#existe_numero').val() == 1)
    {
      statut = 2;
      $('#errorNUMERO').html('Le numéro existe déjà');
    }
    else{$('#errorNUMERO').html('');}

    if($('#DATE_ACTIVE_MEGA').val() == '')
    {
      statut = 2;
      $('#errorDATE_ACTIVE_MEGA').html('Le champ est obligatoire');
    }
    else{$('#errorDATE_ACTIVE_MEGA').html('');}

    if($('#DATE_EXPIRE_MEGA').val() == '')
    {
      statut = 2;
      $('#errorDATE_EXPIRE_MEGA').html('Le champ est obligatoire');
    }else{$('#errorDATE_EXPIRE_MEGA').html('');}

    if(statut == 1)
    {
      $('#add_form').submit();
    }

  }
</script>

</html>