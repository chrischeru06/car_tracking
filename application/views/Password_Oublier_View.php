<!DOCTYPE html>
<html lang="en">

<head>
  <style>
    .card{
      -webkit-backdrop-filter:blur(15px);

      backdrop-filter:blur(60px);  


    }
    a:hover {
      background-color: rgba(255,255,255,0.5);
    }
  </style>
  <?php include VIEWPATH . 'includes/header.php'; ?>

</head>

<body id="main" class="main" style="background-size: cover; background-repeat: no-repeat;background-image: url('<?php echo base_url().'upload/trackingbackground.png'; ?>')" >
  <main>
    <div class="card mb-3 bg-transparent border-success" style="border-radius: 20px;">


      <div class="card-body">
        
        <section class="section">

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
                            <div class="text-center">
                              <!-- <img src="<?=base_url('upload/vehicule_icon_tracking.png')?>" class="img-circle avatar" height="60" alt="Avatar"> -->
                              <h2 style="color:white;">Mot de passe oublié</h2>

                            </div>
                            <div id="message_login"></div>         
                            <div class="form-group">
                              <input type="text" id="EMAIL_CONFIRMATION" name="EMAIL_CONFIRMATION" class="form-control" placeholder="Email">
                              <span class="help-block"></span>
                            </div>
                            <br>
                            <div class="row">
                              <div class="col-md-12">

                                <button type="button" class="btn btn-primary btn-block" id="btnSave"  style=" float: right;background-color:#012970; border-radius: 20px; " onclick="pwd_oublie()">Envoyer</button>
                              </div>
                            </div>

                            <p class="text-center"  style="float: left;"><a href="<?=base_url('Login')?>" style="color: #012970;">Se connecter</a></p>

                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </section>
      </div>
    </div>
  </main><!-- End #main -->


</body>
<footer>
  <?php include VIEWPATH . 'includes/footer.php'; ?>

</footer>


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

  url = "<?php echo base_url('Login/forget_pwd')?>";


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