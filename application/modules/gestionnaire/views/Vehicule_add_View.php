<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

        <!-- <div class="col-md-4"> -->


          <!-- <div class="card" style="border-radius: 20px;margin-bottom: 10px;"> -->

            <table>
              <tr>
                <td> 
                  <!-- <img src="<?= base_url()?>template/imagespopup/IconeMuyingajdfss-04.png" width="60px" height="60px" alt=""> -->
                </td>
                <td>  
                  <h4 class="text-dark text-center" style="margin-bottom: 1px; margin-top: 3px;"><?=$title?></h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <!-- <li class="breadcrumb-item"><a href="#">Véhicule</a></li> -->
                      <li class="breadcrumb-item"><a href="#"></a></li>
                      <!-- <li class="breadcrumb-item active" aria-current="page">Saving slides</li> -->
                    </ol>
                  </nav>
                </td>
              </tr>
            </table>
            <!-- </div> -->
            <!-- </div> -->
          </center>
        </div>
      </div>
      <div class="col-md-2">

       <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('vehicule/Vehicule')?>" class="nav-link position-relative"><i class="bi bi-list"></i> <?=lang('title_list')?></a>

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
                <form id="add_form" enctype="multipart/form-data" method="post" action="<?=base_url('vehicule/Vehicule/save')?>">
                  <fieldset class="border p-2">
                    <legend  class="float-none w-auto p-2"><?=lang('btn_info_gnl')?></legend>
                    <div  class="row text-dark">
                      <input type="hidden" name="VEHICULE_ID" id="VEHICULE_ID" value="<?=$vehicule_data['VEHICULE_ID']?>">
                    <!-- <div class="col-md-4">
                      <div class="form-group">
                        <label ><small> Code (device uid)</small><span  style="color:red;">*</span></label>
                        

                        <input class="form-control" type='text' name="CODE" id="CODE" placeholder='' value="<?=$vehicule_data['CODE']?>"/>

                      </div>
                      <span id="errorCODE" class="text-danger"></span>
                      <?php echo form_error('CODE', '<div class="text-danger">', '</div>'); ?>
                    </div> -->

                    <div class="col-md-4">
                      <div class="form-group">
                        <label ><small><?=lang('label_marque')?></small><span  style="color:red;">*</span></label>

                        <select class="form-control select2" name="ID_MARQUE" id="ID_MARQUE" onchange="get_modele();">
                          <option value="" selected>-- <?=lang('selectionner')?> --</option>
                          <?php
                          foreach ($marque as $marque)
                          {
                            ?>
                            <option value="<?=$marque['ID_MARQUE']?>"<?php if($marque['ID_MARQUE']==$vehicule_data['ID_MARQUE']) echo " selected";?>><?=$marque['DESC_MARQUE']?></option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>
                      <span id="errorID_MARQUE" class="text-danger"></span>
                      <?php echo form_error('ID_MARQUE', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    <!-- <div class="col-md-4">
                    </div> -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <label><small><?=lang('label_modele')?></small><span  style="color:red;">*</span></label>

                        <select class="form-control" name="ID_MODELE" id="ID_MODELE">
                          <option value="" selected>-- <?=lang('selectionner')?> --</option>

                          <?php
                          foreach ($modele as $modele)
                          {
                            ?>
                            <option value="<?=$modele['ID_MODELE']?>"<?php if($modele['ID_MODELE']==$vehicule_data['ID_MODELE']) echo " selected";?>><?=$modele['DESC_MODELE']?></option>
                            <?php
                          }
                          ?>

                        </select>
                      </div>
                      <span id="errorID_MODELE" class="text-danger"></span>
                      <?php echo form_error('ID_MODELE', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label ><small><?=lang('label_plaque')?></small><span  style="color:red;">*</span></label>
                        <input class="form-control" type='text' name="PLAQUE" id="PLAQUE" placeholder='<?=lang('label_plaque')?>' value="<?=$vehicule_data['PLAQUE']?>"/>
                      </div>
                      <span id="errorPLAQUE" class="text-danger"></span>
                      <?php echo form_error('PLAQUE', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>
                  <br>
                  <div class="row text-dark">

                    <div class="col-md-4">
                      <div class="form-group">
                        <label ><small><?=lang('label_numero_chassis')?></small><span  style="color:red;">*</span></label>

                        <input class="form-control" type='text' name="NUMERO_CHASSIS" id="NUMERO_CHASSIS" placeholder='<?=lang('label_numero_chassis')?>' value="<?=$vehicule_data['NUMERO_CHASSIS']?>"/>

                      </div>
                      <span id="errorNUMERO_CHASSIS" class="text-danger"></span>
                      <?php echo form_error('NUMERO_CHASSIS', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label ><small><?=lang('label_couleur')?></small><span  style="color:red;">*</span></label>

                        <input class="form-control" type='text' name="COULEUR" id="COULEUR" placeholder='<?=lang('label_couleur')?>' value="<?=$vehicule_data['COULEUR']?>"/>

                      </div>
                      <span id="errorCOULEUR" class="text-danger"></span>
                      <?php echo form_error('COULEUR', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="col-md-4">
                      <label ><small><?=lang('label_date_fabrication')?></small><span  style="color:red;">*</span></label>
                      <input type="date" name="ANNEE_FABRICATION" autocomplete="off" id="ANNEE_FABRICATION" value="<?=$vehicule_data['ANNEE_FABRICATION']?>"  class="form-control" onchange="verif_date();" max="<?= date('Y-m-d')?>">

                      <font id="error_ANNEE_FABRICATION" color="red"></font>
                      <?php echo form_error('ANNEE_FABRICATION', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>
                  <br>
                  <div class="row text-dark">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label ><small><?=lang('label_consommation_l_kg')?></small><span  style="color:red;">*</span></label>

                        <input class="form-control" type='text' name="KILOMETRAGE" id="KILOMETRAGE" placeholder='<?=lang('label_consommation_l_kg')?>' value="<?=$vehicule_data['KILOMETRAGE']?>"/>

                      </div>
                      <span id="errorKILOMETRAGE" class="text-danger"></span>
                      <?php echo form_error('KILOMETRAGE', '<div class="text-danger">', '</div>'); ?>
                    </div>



                    <div class="col-md-4">
                      <div class="form-group">
                        <label><small><?=lang('title_proprio_list')?></small><span  style="color:red;">*</span></label>

                        <select class="form-control" name="PROPRIETAIRE_ID" id="PROPRIETAIRE_ID">
                          <option value="" selected>-- <?=lang('selectionner')?> --</option>
                          <?php
                          foreach ($proprio as $proprio)
                          {
                            ?>
                            <option value="<?=$proprio['PROPRIETAIRE_ID']?>"<?php if($proprio['PROPRIETAIRE_ID']==$vehicule_data['PROPRIETAIRE_ID']) echo " selected";?>><?=$proprio['proprio_desc']?></option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>
                      <span id="errorPROPRIETAIRE_ID" class="text-danger"></span>
                      <?php echo form_error('PROPRIETAIRE_ID', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label><small><?=lang('label_usage_vehicule')?></small><span  style="color:red;">*</span></label>

                        <select class="form-control" name="USAGE_ID" id="USAGE_ID">
                          <option value="" selected>-- <?=lang('selectionner')?> --</option>
                          <?php
                          foreach ($usage as $key)
                          {
                            ?>
                            <option value="<?=$key['USAGE_ID']?>"<?php if($key['USAGE_ID']==$vehicule_data['USAGE_ID']) echo " selected";?>><?=$key['USAGE_DESC']?></option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>
                      <span id="errorUSAGE_ID" class="text-danger"></span>
                      <?php echo form_error('USAGE_ID', '<div class="text-danger">', '</div>'); ?>
                    </div>
                  </div>
                  <br>
                  <div class="row text-dark">
                    <div class="col-md-4" id="assureur">
                      <label ><small><?=lang('label_assureur')?></small><span  style="color:red;">*</span></label>
                      <select class="form-control" name="ID_ASSUREUR"  id="ID_ASSUREUR">
                       <option value="" selected>-- <?=lang('selectionner')?> --</option>
                       <?php
                       foreach ($assureur as $assureur)
                       {
                        ?>
                        <option value="<?=$assureur['ID_ASSUREUR']?>"<?php if($assureur['ID_ASSUREUR']==$vehicule_data['ID_ASSUREUR']) echo " selected";?>><?=$assureur['ASSURANCE']?></option>
                        <?php
                      }
                      ?>

                    </select>
                    <font id="error_ID_ASSUREUR" color="red"></font>
                  </div>

                  <div class="col-md-4">
                    <label ><small><?=lang('label_date_deb_assurance')?></small><span  style="color:red;">*</span></label>
                    <input type="date" name="DATE_DEBUT_ASSURANCE" autocomplete="off" id="DATE_DEBUT_ASSURANCE" value="<?=$vehicule_data['DATE_DEBUT_ASSURANCE']?>"  class="form-control" onchange="verif_date();" max="<?= date('Y-m-d')?>">

                    <font id="error_DATE_DEBUT_ASSURANCE" color="red"></font>
                    <?php echo form_error('DATE_DEBUT_ASSURANCE', '<div class="text-danger">', '</div>'); ?><br>
                  </div>
                  <div class="col-md-4">
                    <label ><small><?=lang('label_date_fin_assurance')?></small><span  style="color:red;">*</span></label>
                    <input type="date" name="DATE_FIN_ASSURANCE" autocomplete="off" id="DATE_FIN_ASSURANCE" value="<?=$vehicule_data['DATE_FIN_ASSURANCE']?>"  class="form-control" onchange="verif_date();" min="<?= date('Y-m-d')?>">

                    <font id="error_DATE_FIN_ASSURANCE" color="red"></font>
                    <?php echo form_error('DATE_FIN_ASSURANCE', '<div class="text-danger">', '</div>'); ?>
                  </div>

                  <div class="col-md-4">
                    <label ><small><?=lang('label_date_deb_ctrl_technique')?></small><span  style="color:red;">*</span></label>
                    <input type="date" name="DATE_DEBUT_CONTROTECHNIK" autocomplete="off" id="DATE_DEBUT_CONTROTECHNIK" value="<?=$vehicule_data['DATE_DEBUT_CONTROTECHNIK']?>"  class="form-control" onchange="verif_date();" max="<?= date('Y-m-d')?>">

                    <font id="error_DATE_DEBUT_CONTROTECHNIK" color="red"></font>
                    <?php echo form_error('DATE_DEBUT_CONTROTECHNIK', '<div class="text-danger">', '</div>'); ?>
                  </div>

                  <div class="col-md-4">
                    <label ><small><?=lang('label_date_fin_ctrl_technique')?></small><span  style="color:red;">*</span></label>
                    <input type="date" name="DATE_FIN_CONTROTECHNIK" autocomplete="off" id="DATE_FIN_CONTROTECHNIK" value="<?=$vehicule_data['DATE_FIN_CONTROTECHNIK']?>"  class="form-control" onchange="verif_date();" min="<?= date('Y-m-d')?>">

                    <font id="error_DATE_FIN_CONTROTECHNIK" color="red"></font>
                    <?php echo form_error('DATE_FIN_CONTROTECHNIK', '<div class="text-danger">', '</div>'); ?>
                  </div>

                  <div class="col-md-4" id="assureur">
                      <label ><small>Heures de service</small><span  style="color:red;"></span></label>
                      <select class="form-control" name="SHIFT_ID"  id="SHIFT_ID">
                       <option value="" selected>-- <?=lang('selectionner')?> --</option>
                       <?php
                       foreach ($shift as $key)
                       {
                        ?>
                        <option value="<?=$key['SHIFT_ID']?>"<?php if($key['SHIFT_ID']==$vehicule_data['SHIFT_ID']) echo " selected";?>><?=$key['HEURE_DEBUT'].' à '.$key['HEURE_FIN']?></option>
                        <?php
                      }
                      ?>

                    </select>
                    <font id="error_SHIFT_ID" color="red"></font>
                  </div>

                </div>
                

              </fieldset>
              <br>
              <fieldset class="border p-2">
                <legend  class="float-none w-auto p-2"><?=lang('btn_doc')?></legend>
                <div  class="row">

                  <div class="col-md-4">
                    <label> <small><?=lang('label_photo_veh')?></small><span  style="color:red;">*</span></label>

                    <input class="form-control" type="hidden" name="PHOTO" id="PHOTO"  value="<?=$vehicule_data['PHOTO'];?>">

                    <input type="file" class="form-control" name="PHOTO_OUT" id="PHOTO_OUT" value="<?=set_value('PHOTO_OUT')?>" accept=".png,.PNG,.jpg,.JPG,.JPEG,.jpeg" class="form-control" title='<?=lang('title_file')?>'>
                    <font color='red'><?php echo form_error('PHOTO_OUT'); ?></font>
                    <span id="errorPHOTO_OUT" class="text-danger"></span>
                  </div>

                  <div class="col-md-4">
                    <label> <small><?=lang('label_photo_assurance')?></small><span  style="color:red;">*</span></label>
                    <input class="form-control" type="hidden" name="FILE_ASSURANCE_OLD" id="FILE_ASSURANCE_OLD"  value="<?=$vehicule_data['FILE_ASSURANCE'];?>">
                    <input type="file" class="form-control" name="FILE_ASSURANCE" id="FILE_ASSURANCE" value="<?=set_value('FILE_ASSURANCE')?>" accept=".png,.PNG,.jpg,.JPG,.JPEG,.jpeg,.pdf,.PDF" class="form-control" title='<?=lang('title_file')?>'>
                    <font color='red'><?php echo form_error('FILE_ASSURANCE'); ?></font>
                    <span id="errorFILE_ASSURANCE" class="text-danger"></span>
                  </div>

                  <div class="col-md-4">
                    <label> <small><?=lang('label_photo_ctrl_technique')?></small><span  style="color:red;">*</span></label>
                    <input class="form-control" type="hidden" name="FILE_CONTRO_TECHNIQUE_OLD" id="FILE_CONTRO_TECHNIQUE_OLD"  value="<?=$vehicule_data['FILE_CONTRO_TECHNIQUE'];?>">
                    <input type="file" class="form-control" name="FILE_CONTRO_TECHNIQUE" id="FILE_CONTRO_TECHNIQUE" value="<?=set_value('FILE_CONTRO_TECHNIQUE')?>" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF" class="form-control" title='Veuillez mettre une photo avec extension:  .png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF'>
                    <font color='red'><?php echo form_error('FILE_CONTRO_TECHNIQUE'); ?></font>
                    <span id="errorFILE_CONTRO_TECHNIQUE" class="text-danger"></span>
                  </div>

                  <input type="hidden" name="USER_ID" id="USER_ID" value="<?=$this->session->userdata('USER_ID')?>">

                  <div class="col-md-4">

                    <label> <small>Image avant</small><span  style="color:red;">*</span></label>

                    <input class="form-control" type="hidden" name="IMAGE_AVANT_OLD" id="IMAGE_AVANT_OLD"  value="<?=$vehicule_data['IMAGE_AVANT'];?>">

                    <input type="file" class="form-control" name="IMAGE_AVANT" id="IMAGE_AVANT" value="<?=set_value('IMAGE_AVANT')?>" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF" class="form-control" title='Veuillez mettre une photo avec extension:  .png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF'>

                    <font color='red'><?php echo form_error('IMAGE_AVANT'); ?></font>
                    <span id="errorIMAGE_AVANT" class="text-danger"></span>

                  </div>

                  <div class="col-md-4">

                    <label> <small>Image arrière</small><span  style="color:red;">*</span></label>

                    <input class="form-control" type="hidden" name="IMAGE_ARRIERE_OLD" id="IMAGE_ARRIERE_OLD"  value="<?=$vehicule_data['IMAGE_ARRIERE'];?>">

                    <input type="file" class="form-control" name="IMAGE_ARRIERE" id="IMAGE_ARRIERE" value="<?=set_value('IMAGE_ARRIERE')?>" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF" class="form-control" title='Veuillez mettre une photo avec extension:  .png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF'>

                    <font color='red'><?php echo form_error('IMAGE_ARRIERE'); ?></font>
                    <span id="errorIMAGE_ARRIERE" class="text-danger"></span>

                  </div>

                  <div class="col-md-4">

                    <label> <small>Image latérale gauche </small><span  style="color:red;">*</span></label>

                    <input class="form-control" type="hidden" name="IMAGE_LATERALE_GAUCHE_OLD" id="IMAGE_LATERALE_GAUCHE_OLD"  value="<?=$vehicule_data['IMAGE_LATERALE_GAUCHE'];?>">

                    <input type="file" class="form-control" name="IMAGE_LATERALE_GAUCHE" id="IMAGE_LATERALE_GAUCHE" value="<?=set_value('IMAGE_LATERALE_GAUCHE')?>" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF" class="form-control" title='Veuillez mettre une photo avec extension:  .png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF'>

                    <font color='red'><?php echo form_error('IMAGE_LATERALE_GAUCHE'); ?></font>
                    <span id="errorIMAGE_LATERALE_GAUCHE" class="text-danger"></span>

                  </div>

                  <div class="col-md-4">

                    <label> <small>Image latérale droite </small><span  style="color:red;">*</span></label>

                    <input class="form-control" type="hidden" name="IMAGE_LATERALE_DROITE_OLD" id="IMAGE_LATERALE_DROITE_OLD"  value="<?=$vehicule_data['IMAGE_LATERALE_DROITE'];?>">

                    <input type="file" class="form-control" name="IMAGE_LATERALE_DROITE" id="IMAGE_LATERALE_DROITE" value="<?=set_value('IMAGE_LATERALE_DROITE')?>" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF" class="form-control" title='Veuillez mettre une photo avec extension:  .png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF'>

                    <font color='red'><?php echo form_error('IMAGE_LATERALE_DROITE'); ?></font>
                    <span id="errorIMAGE_LATERALE_DROITE" class="text-danger"></span>

                  </div>

                  <div class="col-md-4">

                    <label> <small>Image tableau de bord </small><span  style="color:red;">*</span></label>

                    <input class="form-control" type="hidden" name="IMAGE_TABLEAU_DE_BORD_OLD" id="IMAGE_TABLEAU_DE_BORD_OLD"  value="<?=$vehicule_data['IMAGE_TABLEAU_DE_BORD'];?>">

                    <input type="file" class="form-control" name="IMAGE_TABLEAU_DE_BORD" id="IMAGE_TABLEAU_DE_BORD" value="<?=set_value('IMAGE_TABLEAU_DE_BORD')?>" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF" class="form-control" title='Veuillez mettre une photo avec extension:  .png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF'>

                    <font color='red'><?php echo form_error('IMAGE_TABLEAU_DE_BORD'); ?></font>
                    <span id="errorIMAGE_TABLEAU_DE_BORD" class="text-danger"></span>

                  </div>

                  <div class="col-md-4">

                    <label> <small>Image siège avant </small><span  style="color:red;">*</span></label>

                    <input class="form-control" type="hidden" name="IMAGE_SIEGE_AVANT_OLD" id="IMAGE_SIEGE_AVANT_OLD"  value="<?=$vehicule_data['IMAGE_SIEGE_AVANT'];?>">

                    <input type="file" class="form-control" name="IMAGE_SIEGE_AVANT" id="IMAGE_SIEGE_AVANT" value="<?=set_value('IMAGE_SIEGE_AVANT')?>" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF" class="form-control" title='Veuillez mettre une photo avec extension:  .png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF'>

                    <font color='red'><?php echo form_error('IMAGE_SIEGE_AVANT'); ?></font>
                    <span id="errorIMAGE_SIEGE_AVANT" class="text-danger"></span>

                  </div>

                  <div class="col-md-4">

                    <label> <small>Image siège arrière </small><span  style="color:red;">*</span></label>

                    <input class="form-control" type="hidden" name="IMAGE_SIEGE_ARRIERE_OLD" id="IMAGE_SIEGE_ARRIERE_OLD"  value="<?=$vehicule_data['IMAGE_SIEGE_ARRIERE'];?>">

                    <input type="file" class="form-control" name="IMAGE_SIEGE_ARRIERE" id="IMAGE_SIEGE_ARRIERE" value="<?=set_value('IMAGE_SIEGE_ARRIERE')?>" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF" class="form-control" title='Veuillez mettre une photo avec extension:  .png,.PNG,.jpg,.JPG,.JEPG,.jepg,.pdf,.PDF'>

                    <font color='red'><?php echo form_error('IMAGE_SIEGE_ARRIERE'); ?></font>
                    <span id="errorIMAGE_SIEGE_ARRIERE" class="text-danger"></span>

                  </div>

                </div>
                <br>
              </fieldset><br><br>
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


<script type="text/javascript">

    // Fonction pour le chargement de donnees par defaut
  $(document).ready( function ()
  {
    $('#message').delay('slow').fadeOut(6000);

    var VEHICULE_ID = $('#VEHICULE_ID').val();

    if(VEHICULE_ID == "")
    {
     $('#ID_MODELE').html('<option value="">-- <?=lang('selectionner')?> --</option>');
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
          alert('<?=lang('msg_erreur')?>');
        }
      });
    }
    else
    {
      $('#ID_MODELE').html('<option value="">-- <?=lang('selectionner')?> --</option>');
    }
  }
</script>

<script>

  function submit_form()
  {
    var statut=1;

    if($('#ID_ASSUREUR').val()=='')
    {
      statut=2;
      $('#error_ID_ASSUREUR').html('<?=lang('msg_validation')?>');
    }else{$('#error_ID_ASSUREUR').html('');}

    if($('#NUMERO_CHASSIS').val()=='')
    {
      statut=2;
      $('#errorNUMERO_CHASSIS').html('<?=lang('msg_validation')?>');
    }else{$('#errorNUMERO_CHASSIS').html('');}

    if($('#ANNEE_FABRICATION').val()=='')
    {
      statut=2;
      $('#error_ANNEE_FABRICATION').html('<?=lang('msg_validation')?>');
    }else{$('#error_ANNEE_FABRICATION').html('');}

    if($('#USAGE_ID').val()=='')
    {
      statut=2;
      $('#errorUSAGE_ID').html('<?=lang('msg_validation')?>');
    }else{$('#errorUSAGE_ID').html('');}

    if($('#ID_MARQUE').val()=='')
    {
      statut=2;
      $('#errorID_MARQUE').html('<?=lang('msg_validation')?>');
    }else{$('#errorID_MARQUE').html('');}

    if($('#ID_MODELE').val() == '')
    {
      statut=2;
      $('#errorID_MODELE').html('<?=lang('msg_validation')?>');
    }else{$('#errorID_MODELE').html('');}

    if($('#PLAQUE').val()=='')
    {
      statut=2;
      $('#errorPLAQUE').html('<?=lang('msg_validation')?>');
    }else{$('#errorPLAQUE').html('');}

    if($('#COULEUR').val()=='')
    {
      statut=2;
      $('#errorCOULEUR').html('<?=lang('msg_validation')?>');
    }else{$('#errorCOULEUR').html('');}

    if($('#KILOMETRAGE').val()=='')
    {
      statut=2;
      $('#errorKILOMETRAGE').html('<?=lang('msg_validation')?>');
    }else{$('#errorKILOMETRAGE').html('');}

    if($('#VEHICULE_ID').val() =='')
    {
      if($('#PHOTO_OUT').val()=='')
      {
        statut=2;
        $('#errorPHOTO_OUT').html('<?=lang('msg_validation')?>');
      }else{$('#errorPHOTO_OUT').html('');}


      var maxSize = 2 * 1024 * 1024; // Taille maximale en octets (2 Mo)

      var fileInputPHOTO_OUT = document.getElementById('PHOTO_OUT');
      var filePHOTO_OUT = fileInputPHOTO_OUT.files[0];

      var fileSizePHOTO_OUT = filePHOTO_OUT.size; // Taille du fichier en octets

      if(fileSizePHOTO_OUT > maxSize)
      {
        statut=2;
        $('#errorPHOTO_OUT').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#errorPHOTO_OUT').html('');}

      if($('#FILE_ASSURANCE').val()=='')
      {
        statut=2;
        $('#errorFILE_ASSURANCE').html('<?=lang('msg_validation')?>');
      }else{$('#errorFILE_ASSURANCE').html('');}


      var fileInputFILE_ASSURANCE = document.getElementById('FILE_ASSURANCE');
      var fileFILE_ASSURANCE = fileInputFILE_ASSURANCE.files[0];

      var fileSizeFILE_ASSURANCE = fileFILE_ASSURANCE.size; // Taille du fichier en octets

      if(fileSizeFILE_ASSURANCE > maxSize)
      {
        statut=2;
        $('#errorFILE_ASSURANCE').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#errorFILE_ASSURANCE').html('');}

      if($('#FILE_CONTRO_TECHNIQUE').val()=='')
      {
        statut=2;
        $('#errorFILE_CONTRO_TECHNIQUE').html('<?=lang('msg_validation')?>');
      }else{$('#errorFILE_CONTRO_TECHNIQUE').html('');}

      var fileInputFILE_CONTRO_TECHNIQUE = document.getElementById('FILE_CONTRO_TECHNIQUE');
      var fileFILE_CONTRO_TECHNIQUE = fileInputFILE_CONTRO_TECHNIQUE.files[0];

      var fileSizeFILE_CONTRO_TECHNIQUE = fileFILE_CONTRO_TECHNIQUE.size; // Taille du fichier en octets

      if(fileSizeFILE_CONTRO_TECHNIQUE > maxSize)
      {
        statut=2;
        $('#errorFILE_CONTRO_TECHNIQUE').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#errorFILE_CONTRO_TECHNIQUE').html('');}


      if($('#IMAGE_AVANT').val()=='')
      {
        statut=2;
        $('#errorIMAGE_AVANT').html('<?=lang('msg_validation')?>');
      }else{$('#errorIMAGE_AVANT').html('');}

      var fileInputIMAGE_AVANT = document.getElementById('IMAGE_AVANT');
      var fileIMAGE_AVANT = fileInputIMAGE_AVANT.files[0];

      var fileSizeIMAGE_AVANT = fileIMAGE_AVANT.size; // Taille du fichier en octets

      if(fileSizeIMAGE_AVANT > maxSize)
      {
        statut=2;
        $('#errorIMAGE_AVANT').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#errorIMAGE_AVANT').html('');}


      if($('#IMAGE_ARRIERE').val()=='')
      {
        statut=2;
        $('#errorIMAGE_ARRIERE').html('<?=lang('msg_validation')?>');
      }else{$('#errorIMAGE_ARRIERE').html('');}

      var fileInputIMAGE_ARRIERE = document.getElementById('IMAGE_ARRIERE');
      var fileIMAGE_ARRIERE = fileInputIMAGE_ARRIERE.files[0];

      var fileSizeIMAGE_ARRIERE = fileIMAGE_ARRIERE.size; // Taille du fichier en octets

      if(fileSizeIMAGE_ARRIERE > maxSize)
      {
        statut=2;
        $('#errorIMAGE_ARRIERE').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#errorIMAGE_ARRIERE').html('');}


      if($('#IMAGE_LATERALE_GAUCHE').val()=='')
      {
        statut=2;
        $('#errorIMAGE_LATERALE_GAUCHE').html('<?=lang('msg_validation')?>');
      }else{$('#errorIMAGE_LATERALE_GAUCHE').html('');}

      var fileInputIMAGE_LATERALE_GAUCHE = document.getElementById('IMAGE_LATERALE_GAUCHE');
      var fileIMAGE_LATERALE_GAUCHE = fileInputIMAGE_LATERALE_GAUCHE.files[0];

      var fileSizeIMAGE_LATERALE_GAUCHE = fileIMAGE_LATERALE_GAUCHE.size; // Taille du fichier en octets

      if(fileSizeIMAGE_LATERALE_GAUCHE > maxSize)
      {
        statut=2;
        $('#errorIMAGE_LATERALE_GAUCHE').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#errorIMAGE_LATERALE_GAUCHE').html('');}


      if($('#IMAGE_LATERALE_DROITE').val()=='')
      {
        statut=2;
        $('#errorIMAGE_LATERALE_DROITE').html('<?=lang('msg_validation')?>');
      }else{$('#errorIMAGE_LATERALE_DROITE').html('');}

      var fileInputIMAGE_LATERALE_DROITE = document.getElementById('IMAGE_LATERALE_DROITE');
      var fileIMAGE_LATERALE_DROITE = fileInputIMAGE_LATERALE_DROITE.files[0];

      var fileSizeIMAGE_LATERALE_DROITE = fileIMAGE_LATERALE_DROITE.size; // Taille du fichier en octets

      if(fileSizeIMAGE_LATERALE_DROITE > maxSize)
      {
        statut=2;
        $('#errorIMAGE_LATERALE_DROITE').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#errorIMAGE_LATERALE_DROITE').html('');}


      if($('#IMAGE_TABLEAU_DE_BORD').val()=='')
      {
        statut=2;
        $('#errorIMAGE_TABLEAU_DE_BORD').html('<?=lang('msg_validation')?>');
      }else{$('#errorIMAGE_TABLEAU_DE_BORD').html('');}

      var fileInputIMAGE_TABLEAU_DE_BORD = document.getElementById('IMAGE_TABLEAU_DE_BORD');
      var fileIMAGE_TABLEAU_DE_BORD = fileInputIMAGE_TABLEAU_DE_BORD.files[0];

      var fileSizeIMAGE_TABLEAU_DE_BORD = fileIMAGE_TABLEAU_DE_BORD.size; // Taille du fichier en octets

      if(fileSizeIMAGE_TABLEAU_DE_BORD > maxSize)
      {
        statut=2;
        $('#errorIMAGE_TABLEAU_DE_BORD').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#errorIMAGE_TABLEAU_DE_BORD').html('');}


      if($('#IMAGE_SIEGE_AVANT').val()=='')
      {
        statut=2;
        $('#errorIMAGE_SIEGE_AVANT').html('<?=lang('msg_validation')?>');
      }else{$('#errorIMAGE_SIEGE_AVANT').html('');}

      var fileInputIMAGE_SIEGE_AVANT = document.getElementById('IMAGE_SIEGE_AVANT');
      var fileIMAGE_SIEGE_AVANT = fileInputIMAGE_SIEGE_AVANT.files[0];

      var fileSizeIMAGE_SIEGE_AVANT = fileIMAGE_SIEGE_AVANT.size; // Taille du fichier en octets

      if(fileSizeIMAGE_SIEGE_AVANT > maxSize)
      {
        statut=2;
        $('#errorIMAGE_SIEGE_AVANT').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#errorIMAGE_SIEGE_AVANT').html('');}


      if($('#IMAGE_SIEGE_ARRIERE').val()=='')
      {
        statut=2;
        $('#errorIMAGE_SIEGE_ARRIERE').html('<?=lang('msg_validation')?>');
      }else{$('#errorIMAGE_SIEGE_ARRIERE').html('');}

      var fileInputIMAGE_SIEGE_ARRIERE = document.getElementById('IMAGE_SIEGE_ARRIERE');
      var fileIMAGE_SIEGE_ARRIERE = fileInputIMAGE_SIEGE_ARRIERE.files[0];

      var fileSizeIMAGE_SIEGE_ARRIERE = fileIMAGE_SIEGE_ARRIERE.size; // Taille du fichier en octets

      if(fileSizeIMAGE_SIEGE_ARRIERE > maxSize)
      {
        statut=2;
        $('#errorIMAGE_SIEGE_ARRIERE').html('La taille du fichier ne doit pas dépasser 2 Mo');
      }else{$('#errorIMAGE_SIEGE_ARRIERE').html('');}


    }
    else if($('#VEHICULE_ID').val() !='')  // Modification
    {
      var maxSize = 2 * 1024 * 1024; // Taille maximale en octets (2 Mo)

      if($('#PHOTO_OUT').val() != '')
      {
        var fileInputPHOTO_OUT = document.getElementById('PHOTO_OUT');
        var filePHOTO_OUT = fileInputPHOTO_OUT.files[0];

        var fileSizePHOTO_OUT = filePHOTO_OUT.size; // Taille du fichier en octets

        if(fileSizePHOTO_OUT > maxSize)
        {
          statut=2;
          $('#errorPHOTO_OUT').html('La taille du fichier ne doit pas dépasser 2 Mo');
        }else{$('#errorPHOTO_OUT').html('');}
      }


      if($('#FILE_ASSURANCE').val() != '')
      {
        var fileInputFILE_ASSURANCE = document.getElementById('FILE_ASSURANCE');
        var fileFILE_ASSURANCE = fileInputFILE_ASSURANCE.files[0];

        var fileSizeFILE_ASSURANCE = fileFILE_ASSURANCE.size; // Taille du fichier en octets

        if(fileSizeFILE_ASSURANCE > maxSize)
        {
          statut=2;
          $('#errorFILE_ASSURANCE').html('La taille du fichier ne doit pas dépasser 2 Mo');
        }else{$('#errorFILE_ASSURANCE').html('');}
      }


      if($('#FILE_CONTRO_TECHNIQUE').val() != '')
      {
        var fileInputFILE_CONTRO_TECHNIQUE = document.getElementById('FILE_CONTRO_TECHNIQUE');
        var fileFILE_CONTRO_TECHNIQUE = fileInputFILE_CONTRO_TECHNIQUE.files[0];

        var fileSizeFILE_CONTRO_TECHNIQUE = fileFILE_CONTRO_TECHNIQUE.size; // Taille du fichier en octets

        if(fileSizeFILE_CONTRO_TECHNIQUE > maxSize)
        {
          statut=2;
          $('#errorFILE_CONTRO_TECHNIQUE').html('La taille du fichier ne doit pas dépasser 2 Mo');
        }else{$('#errorFILE_CONTRO_TECHNIQUE').html('');}
      }


      if($('#IMAGE_AVANT').val() != '')
      {
        var fileInputIMAGE_AVANT = document.getElementById('IMAGE_AVANT');
        var fileIMAGE_AVANT = fileInputIMAGE_AVANT.files[0];

        var fileSizeIMAGE_AVANT = fileIMAGE_AVANT.size; // Taille du fichier en octets

        if(fileSizeIMAGE_AVANT > maxSize)
        {
          statut=2;
          $('#errorIMAGE_AVANT').html('La taille du fichier ne doit pas dépasser 2 Mo');
        }else{$('#errorIMAGE_AVANT').html('');}
      }

      if($('#IMAGE_ARRIERE').val() != '')
      {
        var fileInputIMAGE_ARRIERE = document.getElementById('IMAGE_ARRIERE');
        var fileIMAGE_ARRIERE = fileInputIMAGE_ARRIERE.files[0];

        var fileSizeIMAGE_ARRIERE = fileIMAGE_ARRIERE.size; // Taille du fichier en octets

        if(fileSizeIMAGE_ARRIERE > maxSize)
        {
          statut=2;
          $('#errorIMAGE_ARRIERE').html('La taille du fichier ne doit pas dépasser 2 Mo');
        }else{$('#errorIMAGE_ARRIERE').html('');}
      }

      if($('#IMAGE_LATERALE_GAUCHE').val() != '')
      {
        var fileInputIMAGE_LATERALE_GAUCHE = document.getElementById('IMAGE_LATERALE_GAUCHE');
        var fileIMAGE_LATERALE_GAUCHE = fileInputIMAGE_LATERALE_GAUCHE.files[0];

        var fileSizeIMAGE_LATERALE_GAUCHE = fileIMAGE_LATERALE_GAUCHE.size; // Taille du fichier en octets

        if(fileSizeIMAGE_LATERALE_GAUCHE > maxSize)
        {
          statut=2;
          $('#errorIMAGE_LATERALE_GAUCHE').html('La taille du fichier ne doit pas dépasser 2 Mo');
        }else{$('#errorIMAGE_LATERALE_GAUCHE').html('');}
      }

      if($('#IMAGE_LATERALE_DROITE').val() != '')
      {
        var fileInputIMAGE_LATERALE_DROITE = document.getElementById('IMAGE_LATERALE_DROITE');
        var fileIMAGE_LATERALE_DROITE = fileInputIMAGE_LATERALE_DROITE.files[0];

        var fileSizeIMAGE_LATERALE_DROITE = fileIMAGE_LATERALE_DROITE.size; // Taille du fichier en octets

        if(fileSizeIMAGE_LATERALE_DROITE > maxSize)
        {
          statut=2;
          $('#errorIMAGE_LATERALE_DROITE').html('La taille du fichier ne doit pas dépasser 2 Mo');
        }else{$('#errorIMAGE_LATERALE_DROITE').html('');}
      }

      if($('#IMAGE_TABLEAU_DE_BORD').val() != '')
      {
        var fileInputIMAGE_TABLEAU_DE_BORD = document.getElementById('IMAGE_TABLEAU_DE_BORD');
        var fileIMAGE_TABLEAU_DE_BORD = fileInputIMAGE_TABLEAU_DE_BORD.files[0];

        var fileSizeIMAGE_TABLEAU_DE_BORD = fileIMAGE_TABLEAU_DE_BORD.size; // Taille du fichier en octets

        if(fileSizeIMAGE_TABLEAU_DE_BORD > maxSize)
        {
          statut=2;
          $('#errorIMAGE_TABLEAU_DE_BORD').html('La taille du fichier ne doit pas dépasser 2 Mo');
        }else{$('#errorIMAGE_TABLEAU_DE_BORD').html('');}
      }

      if($('#IMAGE_SIEGE_AVANT').val() != '')
      {
        var fileInputIMAGE_SIEGE_AVANT = document.getElementById('IMAGE_SIEGE_AVANT');
        var fileIMAGE_SIEGE_AVANT = fileInputIMAGE_SIEGE_AVANT.files[0];

        var fileSizeIMAGE_SIEGE_AVANT = fileIMAGE_SIEGE_AVANT.size; // Taille du fichier en octets

        if(fileSizeIMAGE_SIEGE_AVANT > maxSize)
        {
          statut=2;
          $('#errorIMAGE_SIEGE_AVANT').html('La taille du fichier ne doit pas dépasser 2 Mo');
        }else{$('#errorIMAGE_SIEGE_AVANT').html('');}
      }

      if($('#IMAGE_SIEGE_ARRIERE').val() != '')
      {
        var fileInputIMAGE_SIEGE_ARRIERE = document.getElementById('IMAGE_SIEGE_ARRIERE');
        var fileIMAGE_SIEGE_ARRIERE = fileInputIMAGE_SIEGE_ARRIERE.files[0];

        var fileSizeIMAGE_SIEGE_ARRIERE = fileIMAGE_SIEGE_ARRIERE.size; // Taille du fichier en octets

        if(fileSizeIMAGE_SIEGE_ARRIERE > maxSize)
        {
          statut=2;
          $('#errorIMAGE_SIEGE_ARRIERE').html('La taille du fichier ne doit pas dépasser 2 Mo');
        }else{$('#errorIMAGE_SIEGE_ARRIERE').html('');}
      }


  }



  if($('#PROPRIETAIRE_ID').val()=='')
  {
    statut=2;
    $('#errorPROPRIETAIRE_ID').html('<?=lang('msg_validation')?>');
  }else{$('#errorPROPRIETAIRE_ID').html('');}


  if($('#DATE_DEBUT_ASSURANCE').val()=='')
  {
    statut=2;
    $('#error_DATE_DEBUT_ASSURANCE').html('<?=lang('msg_validation')?>');
  }else{$('#error_DATE_DEBUT_ASSURANCE').html('');}


  if($('#DATE_FIN_ASSURANCE').val()=='')
  {
    statut=2;
    $('#error_DATE_FIN_ASSURANCE').html('<?=lang('msg_validation')?>');
  }else{$('#error_DATE_FIN_ASSURANCE').html('');}

  if($('#DATE_DEBUT_CONTROTECHNIK').val()=='')
  {
    statut=2;
    $('#error_DATE_DEBUT_CONTROTECHNIK').html('<?=lang('msg_validation')?>');
  }else{$('#error_DATE_DEBUT_CONTROTECHNIK').html('');}

  if($('#DATE_FIN_CONTROTECHNIK').val()=='')
  {
    statut=2;
    $('#error_DATE_FIN_CONTROTECHNIK').html('<?=lang('msg_validation')?>');
  }else{$('#error_DATE_FIN_CONTROTECHNIK').html('');}

  var VEHICULE_ID = $('#VEHICULE_ID').val();
// var CODE = $('#CODE').val();
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