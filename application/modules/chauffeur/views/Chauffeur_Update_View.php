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
                   <form enctype="multipart/form-data" name="myform" id="myform" method="post" class="form-horizontal" action="<?= base_url('chauffeur/Chauffeur/update'); ?>" >
                  <input type="hidden" class="form-control" name="CHAUFFEUR_ID" id="CHAUFFEUR_ID" value="<?php echo $membre['CHAUFFEUR_ID'];?>">

                  <div class="row">
                    <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Nom <font color="red">*</font></label>
                      <input type="text" name="NOM" autocomplete="off" id="NOM" value="<?= $membre['NOM'] ?>"  class="form-control">
                      <font id="error_NOM" color="red"></font>
                      <?php echo form_error('NOM', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Prénom <font color="red">*</font></label>
                      <input type="text" name="PRENOM" autocomplete="off" id="PRENOM" value="<?= $membre['PRENOM'] ?>"  class="form-control">
                      <font id="error_PRENOM" color="red"></font>
                      <?php echo form_error('PRENOM', '<div class="text-danger">', '</div>'); ?>
                    </div>
                    <!--  <div class="col-md-4">
                      <label for="genre" class="text-dark" style="font-weight: 1000; color:#454545">Genre <font color="red">*</font></label>
                      <select class="form-control" name="GENRE_ID" id="GENRE_ID">

                       <option value="">---Sélectionner---</option>
                       <?php 
                      $genr=$this->Model->getOne('syst_genre',array('GENRE_ID' =>$membre['GENRE_ID']  ));
                       foreach ($type_genre as $value) 
                       {
                        if ($value['GENRE_ID'] ==$genr['GENRE_ID'])
                          {
                            ?>
                            <option value="<?=$value['GENRE_ID']?>" selected><?=$value['DESCR_GENRE']?></option>
                            <?php
                          }
                          else
                          {
                            ?>
                            <option value="<?=$value['GENRE_ID']?>"><?=$value['DESCR_GENRE']?></option>
                            <?php
                          }
                      }
                      ?>
                    </select>
                    <font id="error_genre" color="red"></font>
                    <?php echo form_error('GENRE_ID', '<div class="text-danger">', '</div>'); ?>
                  </div>  -->

                    <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Adresse/rue <font color="red">*</font></label>
                      <input type="text" name="ADRESSE_PHYSIQUE" autocomplete="off" id="ADRESSE_PHYSIQUE" value="<?=$membre['ADRESSE_PHYSIQUE'] ?>"  class="form-control">
                      <font id="error_ADRESSE_PHYSIQUE" color="red"></font>
                      <?php echo form_error('ADRESSE_PHYSIQUE', '<div class="text-danger">', '</div>'); ?> 
                    </div><br><br><br>

                    <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Numero de téléphone <font color="red">*</font></label>
                      <input type="phone" name="NUMERO_TELEPHONE" autocomplete="off" id="NUMERO_TELEPHONE" value="<?=$membre['NUMERO_TELEPHONE']?>"  class="form-control">
                      <font id="error_NUMERO_TELEPHONE" color="red"></font>
                      <?php echo form_error('NUMERO_TELEPHONE', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Adresse email</label>
                      <input type="text" name="ADRESSE_MAIL" autocomplete="off" id="ADRESSE_MAIL" value="<?=$membre['ADRESSE_MAIL']?>"  class="form-control">
                      <font id="error_ADRESSE_MAIL" color="red"></font>
                    </div>

                    <div class="col-md-4">
                      <label class="text-dark" style="font-weight: 1000; color:#454545">Confirmation du mail</label>
                      <input type="text" name="CONFIRMATION_EMAIL" id="CONFIRMATION_EMAIL" value="<?=$membre['ADRESSE_MAIL']?>"  class="form-control">
                      <font id="error_CONFIRMATION_EMAIL" color="red"></font>
                    </div><br><br><br>

                    <div class="col-md-4">
                      <label for="FName" class="text-dark"style="font-weight: 1000; color:#454545">Numéro carte d'identité <font color="red">*</font></label>
                      <input type="text" name="NUMERO_CARTE_IDENTITE" autocomplete="off" id="NUMERO_CARTE_IDENTITE" value="<?=$membre['NUMERO_CARTE_IDENTITE']?>"  class="form-control">
                      <font id="error_NUMERO_CARTE_IDENTITE" color="red"></font>
                      <?php echo form_error('NUMERO_CARTE_IDENTITE', '<div class="text-danger">', '</div>'); ?> 
                    </div>

                    <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Numéro de la personne de contact</label>
                      <input type="phone" name="PERSONNE_CONTACT_TELEPHONE" autocomplete="off" id="PERSONNE_CONTACT_TELEPHONE" value="<?=$membre['PERSONNE_CONTACT_TELEPHONE']?>"  class="form-control">
                      <font id="error_PERSONNE_CONTACT_TELEPHONE" color="red"></font>
                    </div>

                    <div class="col-md-4">
                      <label for="Ftype" class="text-dark" style="font-weight: 1000; color:#454545">Province <font color="red">*</font></label>
                      <select class="form-control" name="PROVINCE_ID" id="PROVINCE_ID" onchange="get_communes();">
                        <option selected value="">---Sélectionner---</option>
                        <?php 
                        $pro=$this->Model->getOne('provinces',array('PROVINCE_ID' =>$membre['PROVINCE_ID']  ));
                        foreach ($provinces as $value)
                        {
                          if ($value['PROVINCE_ID'] ==$pro['PROVINCE_ID'])
                          {
                            ?>
                            <option value="<?=$value['PROVINCE_ID']?>" selected><?=$value['PROVINCE_NAME']?></option>
                            <?php
                          }
                          else
                          {
                            ?>
                            <option value="<?=$value['PROVINCE_ID']?>"><?=$value['PROVINCE_NAME']?></option>
                            <?php
                          }
                        }
                        ?>
                      </select>
                      <font id="error_prov" color="red"></font>
                      <?php echo form_error('PROVINCE_ID', '<div class="text-danger">', '</div>'); ?>
                    </div><br><br><br>

                    <div class="col-md-4">
                      <label for="Ftype" class="text-dark" style="font-weight: 1000; color:#454545">Commune <font color="red">*</font></label>
                      <select class="form-control" name="COMMUNE_ID" id="COMMUNE_ID" onchange="get_zones();">
                        <option value="">---Sélectionner---</option>
                        <?php
                        foreach ($communes as $value)
                        {
                          if ($value['COMMUNE_ID']==$membre['COMMUNE_ID'])
                          {
                            ?>
                            <option value="<?= $value['COMMUNE_ID']?>" selected><?=$value['COMMUNE_NAME']?></option>
                            <?php
                          }
                          else
                          {
                            ?>
                            <option value="<?=$value['COMMUNE_ID']?>"><?=$value['COMMUNE_NAME']?></option>
                            <?php
                          }
                        }
                        ?>
                      </select>
                      <font id="error_com" color="red"></font>
                      <?php echo form_error('COMMUNE_ID', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="col-md-4">
                      <label for="Ftype" class="text-dark" style="font-weight: 1000; color:#454545">Zone <font color="red">*</font></label>
                      <select class="form-control" name="ZONE_ID" id="ZONE_ID" onchange="get_collines();">
                        <option value="">---Sélectionner---</option>
                        <?php 

                        foreach ($zones as $value)
                        {
                          if ($value['ZONE_ID']==$membre['ZONE_ID']) {
                            ?>
                            <option value="<?= $value['ZONE_ID']?>" selected><?=$value['ZONE_NAME']?></option>
                            <?php
                          }
                          else
                          {
                            ?>
                            <option value="<?=$value['ZONE_ID']?>"><?=$value['ZONE_NAME']?></option>
                            <?php
                          }
                        }
                        ?>
                      </select>
                      <font id="error_zon" color="red"></font>
                      <?php echo form_error('ZONE_ID', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="col-md-4">
                      <label for="Ftype" class="text-dark" style="font-weight: 1000; color:#454545">Colline <font color="red">*</font></label>
                      <select class="form-control" name="COLLINE_ID" id="COLLINE_ID">
                        <option value="">---Sélectionner---</option>
                        <?php 

                        foreach ($collines as $value)
                        {
                          if ($value['COLLINE_ID']==$membre['COLLINE_ID'])
                          {
                            ?>
                            <option value="<?= $value['COLLINE_ID']?>" selected><?=$value['COLLINE_NAME']?></option>
                            <?php
                          }
                          else
                          {
                            ?>
                            <option value="<?=$value['COLLINE_ID']?>"><?=$value['COLLINE_NAME']?></option>
                            <?php
                          }
                        }
                        ?>
                      </select>
                      <font id="error_col" color="red"></font>
                      <?php echo form_error('COLLINE_ID', '<div class="text-danger">', '</div>'); ?>
                    </div><br><br><br>

                    <div class="col-md-4">
                      <label for="description" class="text-dark" style="font-weight: 1000; color:#454545">Carte d'identité <font color="red">*</font></label>
                      <input type="hidden"  name="FILE_CARTE_IDENTITE_OLD" id="FILE_CARTE_IDENTITE_OLD" value="<?=$membre['FILE_CARTE_IDENTITE']?>">
                      <input type="file" class="form-control" id="FILE_CARTE_IDENTITE" name="FILE_CARTE_IDENTITE" value="<?=$membre['FILE_CARTE_IDENTITE']?>" title='Veuillez mettre une photo avec extension:  .png,.PNG,.jpg,.JPG,.JEPG,.jepg'>
                      <font id="fichier_identite_complete_error" color="red"></font>
                    </div>

                  <!--   <div class="col-md-4">
                      <label for="description" class="text-dark" style="font-weight: 1000; color:#454545">Casier judiciaire <font color="red">*</font></label>
                      <input type="hidden"  name="FILE_CASIER_JUDICIAIRE_OLD" id="FILE_CASIER_JUDICIAIRE_OLD" value="<?=$membre['FILE_CASIER_JUDICIAIRE']?>">
                      <input type="file" class="form-control" id="FILE_CASIER_JUDICIAIRE" name="FILE_CASIER_JUDICIAIRE" value="<?=$membre['FILE_CASIER_JUDICIAIRE']?>">
                      <font id="error_fichier_casier_judiciaire" color="red"></font>
                    </div> -->
                     <div class="col-md-4" class="text-dark">
                    <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Numéro permis <font color="red">*</font></label>
                    <input type="text" name="NUMERO_PERMIS" autocomplete="off" 
                    id="NUMERO_PERMIS" value="<?=$membre['NUMERO_PERMIS']?>"   class="form-control">
                    <font id="error_NUMERO_PERMIS" color="red"></font>
                    <?php echo form_error('NUMERO_PERMIS', '<div class="text-danger">', '</div>'); ?>
                  </div>
                   <div class="col-md-4">
                      <label for="description" class="text-dark" style="font-weight: 1000; color:#454545">Photo permis <font color="red">*</font></label>
                      <input type="hidden"  name="file_permis_OLD" id="file_permis_OLD" value="<?=$membre['file_permis']?>">

                      <input type="file" class="form-control" id="file_permis" name="file_permis" value="<?=$membre['file_permis']?>" title='Veuillez mettre une photo avec extension:  .png,.PNG,.jpg,.JPG,.JEPG,.jepg'>
                      <font id="file_permis_error" color="red"></font>
                    </div><br><br><br>

                    <div class="col-md-4">
                      <label for="FName" class="text-dark" style="font-weight: 1000; color:#454545">Photo passport <font color="red">*</font></label>
                      <input type="hidden"  name="PHOTO_PASSPORT_OLD" id="PHOTO_PASSPORT_OLD" value="<?=$membre['PHOTO_PASSPORT']?>">
                     <!--  <input type="hidden"  name="CODE_AGENT" id="CODE_AGENT" value="<?=$membre['CODE_AGENT']?>"> -->
                      <input type="file" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" name="PHOTO_PASSPORT" autocomplete="off" id="PHOTO_PASSPORT" value="<?=$membre['PHOTO_PASSPORT']?>"  class="form-control" title='Veuillez mettre une photo avec extension:  .png,.PNG,.jpg,.JPG,.JEPG,.jepg'>
                      <font id="error_photo_passport" color="red"></font>
                      <?php echo form_error('PHOTO_PASSPORT', '<div class="text-danger">', '</div>'); ?> 
                    </div>
                  </div>
                </form>

                <div class="col-md-12" style="margin-top:50px;">
                  <button style="float: right;" class="btn btn-outline-primary rounded-pill " onclick="submit_form();"><span class="fas "></span>Modifier</button>
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
        const photopassport = document.getElementById('PHOTO_PASSPORT');
        const fichier_carte_identite = document.getElementById('FILE_CARTE_IDENTITE');
        const fichier_casier_judiciaire = document.getElementById('FILE_CASIER_JUDICIAIRE');
        var mail = document.getElementById("ADRESSE_MAIL").value;
        var mail2 = document.getElementById("CONFIRMATION_EMAIL").value;
        var form = document.getElementById("myform");
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var statut=1;
        $('#error_com').html('');
        $('#error_zon').html('');
        $('#error_col').html('');
        $('#error_NOM').html('');
        $('#error_prov').html('');
        $('#error_PRENOM').html('');
        $('#error_ADRESSE_MAIL').html('');
        $('#error_ADRESSE_PHYSIQUE').html('');
        $('#error_NUMERO_TELEPHONE').html('');
        $('#error_CONFIRMATION_EMAIL').html('');
        $('#error_NUMERO_CARTE_IDENTITE').html('');
        $('#error_photo_passport').html('');
        $('#fichier_identite_complete_error').html('');
        $('#error_fichier_casier_judiciaire').html('');

        if($('#NOM').val()=='')
        {
          statut=2;
          $('#error_NOM').html('Le champ est obligatoire');
        }

        if($('#PRENOM').val()=='')
        {
          statut=2;
          $('#error_PRENOM').html('Le champ est obligatoire');
        }

        if($('#ADRESSE_PHYSIQUE').val()=='')
        {
          statut=2;
          $('#error_ADRESSE_PHYSIQUE').html('Le champ est obligatoire');
        }

        if($('#NUMERO_TELEPHONE').val()=='')
        {
          statut=2;
          $('#error_NUMERO_TELEPHONE').html('Le champ est obligatoire');
        }

        if($('#ADRESSE_MAIL').val()!='')
        {
          if(!emailReg.test($('#ADRESSE_MAIL').val()))
          {
            $('#error_ADRESSE_MAIL').html('Email invalide!');
            statut=2
          }

          if($('#CONFIRMATION_EMAIL').val()!=$('#ADRESSE_MAIL').val())
          {
            statut=2;
            $('#error_CONFIRMATION_EMAIL').html('Les emails ne corrospondent pas');
          }
        }

        if($('#NUMERO_CARTE_IDENTITE').val()=='')
        {
          statut=2;
          $('#error_NUMERO_CARTE_IDENTITE').html('Le champ est obligatoire');
        }

        if($('#PROVINCE_ID').val()=='')
        {
          statut=2;
          $('#error_prov').html('Le champ est obligatoire');
        }

        if($('#COMMUNE_ID').val()=='')
        {
          statut=2;
          $('#error_com').html('Le champ est obligatoire');
        }

        if($('#ZONE_ID').val()=='')
        {
          statut=2;
          $('#error_zon').html('Le champ est obligatoire');
        }

        if($('#COLLINE_ID').val()=='')
        {
          statut=2;
          $('#error_col').html('Le champ est obligatoire');
        }

        if(statut==1)
        {
          $('#myform').submit();
        }
      }

      function get_communes()
      {
        var PROVINCE_ID=$('#PROVINCE_ID').val();

        if(PROVINCE_ID==0)
        {
          $('#COMMUNE_ID').html('<option value="">---Sélectionner---</option>');
          $('#ZONE_ID').html('<option value="">---Sélectionner---</option>');
          $('#COLLINE_ID').html('<option value="">---Sélectionner---</option>');
        }
        else
        {
          $('#ZONE_ID').html('<option value="">---Sélectionner---</option>');
          $('#COLLINE_ID').html('<option value="">---Sélectionner---</option>');
          $.ajax(
          {
            url:"<?=base_url()?>chauffeur/Chauffeur/get_communes/"+PROVINCE_ID,
            type:"GET",
            dataType:"JSON",
            success: function(data)
            {
              $('#COMMUNE_ID').html(data);
              get_zones()
            }
          });
        }
      }

      function get_zones()
      {
        var COMMUNE_ID =$('#COMMUNE_ID').val();
        if(COMMUNE_ID=='')
        {
          $('#ZONE_ID').html('<option value="">---Sélectionner---</option>');
          $('#COLLINE_ID').html('<option value="">---Sélectionner---</option>');
        }
        else
        {
          $('#COLLINE_ID').html('<option value="">---Sélectionner---</option>');
          $.ajax(
          {
            url:"<?=base_url()?>chauffeur/Chauffeur/get_zones/"+COMMUNE_ID,
            type:"GET",
            dataType:"JSON",
            success: function(data)
            {
              $('#ZONE_ID').html(data);
              get_collines()
            }
          });
        }
      }

      function get_collines()
      {
        var ZONE_ID=$('#ZONE_ID').val();
        if(ZONE_ID=='')
        {
          $('#COLLINE_ID').html('<option value="">---Sélectionner---</option>');
        }
        else
        {
          $.ajax(
          {
            url:"<?=base_url()?>chauffeur/Chauffeur/get_collines/"+ZONE_ID,
            type:"GET",
            dataType:"JSON",
            success: function(data)
            {
              $('#COLLINE_ID').html(data);
            }
          });
        }
      }
  </script>

  <script>
      var province = document.getElementById('PROVINCE_ID');
      var province_error = document.getElementById('error_prov');
      var colline = document.getElementById('COLLINE_ID');
      var colline_error = document.getElementById('error_col');
      var zone = document.getElementById('ZONE_ID');
      var zone_error = document.getElementById('error_zon');
      var comm = document.getElementById('COMMUNE_ID');
      var commune_error = document.getElementById('error_com');
      $('#COMMUNE_ID').change(function(){
        commune_error.innerHTML ="";
      });

      $('#ZONE_ID').change(function(){
        zone_error.innerHTML ="";
      });

      $('#COLLINE_ID').change(function(){
        colline_error.innerHTML ="";
      });

      $('#PROVINCE_ID').change(function(){
        province_error.innerHTML ="";
      });


      $("#NOM,#PRENOM").on('input paste change keyup', function()
      {
        $(this).val($(this).val().replace(/[^a-z-\s]/gi, '').toUpperCase());
      });

      $("#NUMERO_CARTE_IDENTITE").on('input', function()
      {
        // $('#error_NUMERO_CARTE_IDENTITE').hide();
        $(this).val($(this).val().replace(/[^0-9/.]*$/gi, ''));
      });


      $("#ADRESSE_PHYSIQUE").keypress(function(event)
      {
        // $('#error_ADRESSE_PHYSIQUE').hide();
        var character = String.fromCharCode(event.keyCode);
        return isValid(character);     
      });

      function isValid(str)
      {
        return !/[~`!@#$%\^&*()+=\-\[\]\\';,/{}|\\":<>\?]/g.test(str);
      }

      $('#NUMERO_TELEPHONE').on('input change',function()
      {
        $(this).val($(this).val().replace(/[^0-9]*$/gi, ''));
        $(this).val($(this).val().replace(' ', ''));
        var subStr = this.value.substring(0,1);

        if (subStr != '+')
        {
          $('[name = "NUMERO_TELEPHONE"]').val('+257');
        }

        if(this.value.substring(0,4)=="+257")
        {
          if($(this).val().length == 12)
          {
            $('#errorNUMERO_TELEPHONE').text('');
          }
          else
          {
            $('#errorNUMERO_TELEPHONE').text('Numéro de téléphone est invalide ');
            if($(this).val().length > 12)
            {
              $(this).val(this.value.substring(0,12));
              $('#errorNUMERO_TELEPHONE').text('');
            }
          }
        }
        else
        {
          if ($(this).val().length > 12)
          {
            $('#errorNUMERO_TELEPHONE').text('');
          }
          else
          {
            $('#errorNUMERO_TELEPHONE').text('Invalide numéro de téléphone');
          }        
        } 
      });


    $('#PERSONNE_CONTACT_TELEPHONE').on('input change',function()
    {
      $('#error_PERSONNE_CONTACT_TELEPHONE').html();
      $(this).val($(this).val().replace(/[^0-9]*$/gi, ''));
      $(this).val($(this).val().replace(' ', ''));
      var subStr = this.value.substring(0,1);

      if (subStr != '+')
      {
        $('[name = "PERSONNE_CONTACT_TELEPHONE"]').val('+257');
      }

      if(this.value.substring(0,4)=="+257")
      {
        if($(this).val().length == 12)
        {
          $('#error_PERSONNE_CONTACT_TELEPHONE').text('');
        }
        else
        {
          $('#error_PERSONNE_CONTACT_TELEPHONE').text('Numéro de téléphone est invalide ');
          if($(this).val().length > 12)
          {
            $(this).val(this.value.substring(0,12));
            $('#error_PERSONNE_CONTACT_TELEPHONE').text('');
          }

        }
      }
      else
      {
        if ($(this).val().length > 12)
        {
          $('#error_PERSONNE_CONTACT_TELEPHONE').text('');
        }
        else
        {
          $('#error_PERSONNE_CONTACT_TELEPHONE').text('Invalide numéro de téléphone');
        }        
      }
    });
    </script>

   <script type="text/javascript">
    function verif_date()
    {
      var DATE_NAISSANCE = $('#date_naissance').val();
      $.ajax(
          {
            type: "POST",
            url:"<?=base_url()?>chauffeur/Chauffeur/verif_date/",
            dataType: 'JSON',
            data:{
              DATE_NAISSANCE:DATE_NAISSANCE
            },

            success: function(data){

              if(data < 18 )
          {
            $('#error_date_naissance').text("Seulement l'âge supérieur ou égal à 18 est autorisé !");
          }
          else
            {$('#error_date_naissance').text("");
        }

      

        
        }
      });

    }

  </script>


</html>