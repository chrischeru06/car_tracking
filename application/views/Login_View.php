<!DOCTYPE html>
<html lang="en">
<style>
  .card{
    -webkit-backdrop-filter:blur(15px);

  backdrop-filter:blur(60px);  
  

}

a:hover {
  background-color: rgba(255,255,255,0.5);
}
  </style>

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>
</head>

<body >

  <main>
    <!-- <div class="container-fluid login_bg" > -->
    <div class="container-fluid " style="background-color: cadetblue;">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
               <div class="d-flex justify-content-center py-4">
             <img src="<?= base_url()?>/upload/Car_tracking_png-03.png" height="60">
                <b  class="logo d-flex align-items-center w-auto" >

                  <span class="d-none d-lg-block"><label style="color: white;">MEDIATRACKING</label></span>
                </b>
              </div><!-- End Logo -->

              <div class="card login-area mb-4" style="background-color: cadetblue; border-radius: 10%;">


                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4" style="color: white;">Connexion</h5>
                    <!-- <p class="text-center small">Enter your username & password to login</p> -->
                  </div>

                  <div id="message_login" style="color:#012970;"></div> 

                  <form action="<?= base_url('Login/do_login')?>" id='form_login' method="post" class="row g-3 needs-validation" novalidate>

                    <div class="col-12">
                      <label for="yourUsername" class="form-label" style="color:white;"><span class="fa fa-user" ></span> Nom d'utilisateur</label>
                      <div class="input-group has-validation">
                        <input type="email" class="form-control"  id="email" name="email" style="border-radius:15px;" value="" placeholder="Nom d'utilisateur" autofocus>
                        <div class="invalid-feedback">Veuillez entrer votre nom d'utilisateur.</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <label for="yourPassword" class="form-label" style="color:white;"><span class="fa fa-lock" ></span> Mot de passe</label>
                      <input type="password" class="form-control" placeholder="Mot de passe"  id="Passworde" name="Passworde" style="border-radius:15px;">
                      <label class="fa fa-eye text-muted" id="eye_ico" onclick="show_password()"style="position: relative;top: -33%;left: 90%;"></label>
                      <div class="invalid-feedback">Veuillez entrer le mot de passe!</div>
                    </div>

                    <!-- <div class="col-12">
                      <div class="form-check ml-2">
                        <input class="form-check-input" type="checkbox" id="basic_checkbox_1" onclick="show_password()" style="border-radius:5px;">
                        <label class="form-check-label" for="basic_checkbox_1" style="color:white;">Afficher le mot de passe</label>
                      </div>
                    </div> -->
                    <div class="text-center">
                      <!-- <button type="button" id="connexion" onclick="login()" class="btn login-btn btn-block">Se connecter</button> -->

                      <button type="button" id="connexion" onclick="login()" class="btn btn-outline-light rounded-pill"><?=lang('msg_connexion')?></button>
                    </div>
                    <div class="form-group text-center">
                      <a class="login-link text-light small pt-2 ps-1" href="<?=base_url('Login/forgotten_pwd')?> " style="border-radius: 15px;padding: 5px;"> Mot de passe oublié ?</a>
                    </div>
                  </form>

                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>

  </main><!-- End #main -->
  <?php include VIEWPATH . 'includes/footer.php'; ?>

</body>






<script>

  $(document).ready(function()
 {
  $('#email').val()='';
  $('#Passworde').val()='';

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


<script>
  function login()
  {
    // alert('test')
    $('#connexion').text('Connexion'); 
    $('#connexion').attr('disabled',true);
    $('#message_login').html('')
    
    var formData = $('#form_login').serialize();
    $.ajax(
    {
      url : "<?=base_url()?>index.php/Login/check_login",
      type: "POST",
      data: formData,
      dataType: "JSON",
      success: function(data)
      {
        if(data.status)
        {
          $('#message_login').html("<center><span style='color: white;'>"+data.message+"</span></center>");
          $('#connexion').attr('disabled',true);
          
          setTimeout(function(){ 
            $('#form_login').submit();

          }, 2000);
        }
        else
        {
          $('#message_login').html("<span class='text text-danger'>"+data.message+"</span>");
        }
        $('#connexion').text('Connexion...'); 
        $('#connexion').attr('disabled',false); 
      }
    });
  }
</script>

</html>