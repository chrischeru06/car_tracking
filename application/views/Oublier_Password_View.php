<!DOCTYPE html>
<html lang="en">
<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>
</head>
<body class="h-100">
  <div class="authincation h-100">
    <div class="container-fluid h-100">
      <div class="row justify-content-center h-100 align-items-center">
        <div class="col-md-6">
          <div class="authincation-content">
            <div class="row no-gutters">
              <div class="col-xl-12">
                <div class="auth-form">           
                  <div id="message_login"></div> 
                  <form action="" id='form_mot_passe_oublie' method="post">
                    <h2>Mot de passe oublié</h2>
                    <div class="text-center">
                      <!-- <img src="<?=base_url('assets/images/logo_trans.png')?>" class="img-circle avatar" alt="Avatar"> -->
                    </div>
                    <div id="message_login"></div>         
                    <div class="form-group">
                      <input type="text" id="EMAIL_CONFIRMATION" name="EMAIL_CONFIRMATION" class="form-control" placeholder="Email">
                      <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                      <button type="button" class="btn btn-primary btn-block" id="btnSave" onclick="pwd_oublie()">Envoyer</button>
                    </div>

                    <p class="text-center"><a href="<?=base_url('Login')?>">Se connecter</a></p>

                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--********************************** Scripts start ***********************************-->
  <?php include VIEWPATH . 'includes/footer_scripts.php'; ?>
  <!--********************************** Scripts End ***********************************-->
</body>
</html>


<script>

 $(document).ready(function(){


 $('input').keyup(function(){
 $(this).parent().parent().removeClass('has-error');
 $(this).next().empty();
 });
 }); 

 function pwd_oublie()
 {

  $('#btnSave').text('Envoyer');
  $('.help-block').empty();

  var url;

  url = "<?php echo base_url('agent/Login_Sbg/forget_pwd')?>";


  var formData = new FormData($('#form_mot_passe_oublie')[0]);

  $.ajax({
    url:url,
    type:"POST",
    data:formData,
    contentType:false,
    processData:false,
    dataType:"JSON",
    success:function(data)
    {
      if(data.status)
      {
       $('#form_mot_passe_oublie')[0].reset();
       $('#message_login').css('color','green');               
       $('#message_login').html('<center>Le nouveau mot de passe a été envoyé.Vérifier votre boîte email</center>');               

       setTimeout(function(){ 

         $('#form_mot_passe_oublie').submit();

       },2000);

     }
     else
     {
      for (var i = 0; i < data.input_error.length; i++) 
      {
        $('[name="'+data.input_error[i]+'"]').parent().parent().addClass('has-error');
        $('[name="'+data.input_error[i]+'"]').next().text(data.error_string[i]);

      }
    }
    $('#btnSave').text('Envoyer');

  },
  error: function (jqXHR, textStatus, errorThrown)
  {
    alert('Error');
    $('#btnSave').text('Envoyer');


  }
})
}


</script>                                                     