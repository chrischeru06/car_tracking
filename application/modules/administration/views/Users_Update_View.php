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
      <h1>Utilisateur</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Modification</a></li>
          <!-- <li class="breadcrumb-item active">Liste</li> -->
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"></h5>       
          <div class="row">
            <div class="col-xl-12 col-xxl-12 text-dark">
             <form  name="myform" method="post" class="form-horizontal" action="<?= base_url('administration/Users/modifier/'); ?>">
              <div class="row"> 
                <input type="hidden" name="id" id="id" value="<?=$geted['USER_ID']?>">
                <div class="col-md-6 " >
                  <label style="font-weight: 900; color:#454545"><b>IDENTIFICATION</b><span  style="color:red;">*</span></label><br>
                  <input class="form-control" type="text" name="IDENTIFICATION" id="IDENTIFICATION" placeholder="Nom"  value="<?php echo $geted['IDENTIFICATION']?>">
                  <?php echo form_error('IDENTIFICATION', '<div class="text-danger">', '</div>'); ?> 
                </div> 
                <div class="col-md-6 " >
                  <label style="font-weight: 900; color:#454545"><b>E-MAIL</b><span  style="color:red;">*</span></label><br>
                  <input class="form-control" type="email" name="E-MAIL" id="E-MAIL" placeholder="e-mail" value="<?php echo $geted['USER_NAME']?>">
                  <?php echo form_error('E-MAIL', '<div class="text-danger">', '</div>'); ?> 
                </div>

                <div class="col-md-6">
                  <label for="FName" style="font-weight: 1000; color:#454545">TELEPHONE<font color="red">*</font></label>
                  <input type="text" name="numero_telephone" autocomplete="off" id="numero_telephone"placeholder="+257 99999999" value="<?php echo $geted['TELEPHONE']?>"  class="form-control">
                  <?php echo form_error('numero_telephone', '<div id="error_numero_telephone" class="text-danger">', '</div>'); ?> 
                </div>
                <div class="col-md-6 " >
                  <label style="font-weight: 900; color:#454545"><b>PROFIL</b><span  style="color:red;">*</span></label><br>

                  <select class="form-control"  name="PROFIL" id="PROFIL">
                    <option selected value="">--selectionner--</option> 

                    <?php

                    foreach ($profiles as $value)

                    {

                      if ($value['PROFIL_ID'] ==$geted['PROFIL_ID'])
                      {
                        echo "<option value='".$value['PROFIL_ID']."' selected>".$value['DESCRIPTION_PROFIL']."</option>";
                      }
                      else
                      {
                        echo "<option value='".$value['PROFIL_ID']."'>".$value['DESCRIPTION_PROFIL']."</option>";
                      }
                    }
                    ?>

                  </select> 
                  <?php echo form_error('PROFIL', '<div class="text-danger">', '</div>'); ?>
                </div>
                <div class="col-md-6">
                  <label style="font-weight: 900; color:#454545"><b> MOT DE PASSE</b><span  style="color:red;">*</span></label><br>
                  
                  <input type="password" class="form-control" placeholder="Mot de passe"  id="Passworde" name="Passworde" value="">
                  <label class="fa fa-eye text-muted" id="eye_ico" onclick="show_password()"style="position: relative;top: -35%;left: 95%;"></label>
                  <div class="invalid-feedback">Veuillez entrer le mot de passe!</div>
                </div>

                <div class="col-lg-12" style="margin-top:31px;">
                  <button type="submit" style="float: right;" class="btn btn-primary"><span class="fa fa-edit"></span> Modifier</button>
                </div>
              </div>
            </form>
          </div>
        </div>




      </div>
    </div>
  </section>

</main><!-- End #main -->

<?php include VIEWPATH . 'includes/footer.php'; ?>

</body>


<script type="text/javascript">
  window.onload = function() {
    document.getElementById("Passworde").value == "";
};
 //  $(document).ready(function()
 // {
 //  document.getElementById("Passworde").value = "";

 // });
  $('#numero_telephone').on('input change',function()
  {

    $(this).val($(this).val().replace(/[^0-9]*$/gi, ''));
    $(this).val($(this).val().replace(' ', ''));
    var subStr = this.value.substring(0,1);

    if (subStr != '+') {
      $('[name = "numero_telephone"]').val('+257');
    }
    if(this.value.substring(0,4)=="+257")
    {
      if($(this).val().length == 12)
      {
        $('#error_numero_telephone').text('');
      }
      else
      {
        $('#error_numero_telephone').text('Numéro invalide ');
        if($(this).val().length > 12)
        {
          $(this).val(this.value.substring(0,12));
          $('#error_numero_telephone').text('');
        }

      }
    }
    else
    {
      if ($(this).val().length > 12)
      {
        $('#error_numero_telephone').text('');
      }
      else
      {
        $('#error_numero_telephone').text('Invalide numéro de téléphone');
      }        
    } 
  });

  function show_password() 
  {
    var x = document.getElementById("Passworde");
    var eye_ico = document.getElementById('eye_ico');
    if (x.type === "password") {
      x.type = "text";

      eye_ico.classList.remove('fa-eye');
      eye_ico.classList.add('fa-eye-slash');

    } else {
      x.type = "password";

      eye_ico.classList.add('fa-eye');
      eye_ico.classList.remove('fa-eye-slash');
    }
  }
</script>


</html>