
<?php
function __construct()
{
  $this->out_application();
  $this->load->helper('email');
}
  //Fonction pour rediriger vers la page d'accueil,une fois la session perdue
function out_application()
{
  if(empty($this->session->userdata('USER_ID')))
  {
    redirect(base_url('Login/logout'));
  }
}
?><header id="header" class="header fixed-top d-flex align-items-center"  style="background-color: cadetblue">

  <div class="d-flex align-items-center justify-content-between">
    <a href="<?= base_url()?>/template/Dashboard.php" class="logo d-flex align-items-center">
      <img src="<?= base_url()?>upload/Car_tracking_png-01.png" alt="" >
      <span class="d-none d-lg-block">Mediatracking</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div><!-- End Logo -->

  <nav class="header-nav ms-auto" >
    <ul class="d-flex align-items-center">

      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle " href="#">
          <i class="bi bi-search"></i>
        </a>
      </li><!-- End Search Icon-->

      <li class="nav-item dropdown">

        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
          <i class="bi bi-bell"></i>
          <span class="badge bg-primary badge-number">4</span>
        </a><!-- End Notification Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
          <li class="dropdown-header">
            You have 4 new notifications
            <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li class="notification-item">
            <i class="bi bi-exclamation-circle text-warning"></i>
            <div>
              <h4>Lorem Ipsum</h4>
              <p>Quae dolorem earum veritatis oditseno</p>
              <p>30 min. ago</p>
            </div>
          </li>

          <li>
            <hr class="dropdown-divider">
          </li>

          <li class="notification-item">
            <i class="bi bi-x-circle text-danger"></i>
            <div>
              <h4>Atque rerum nesciunt</h4>
              <p>Quae dolorem earum veritatis oditseno</p>
              <p>1 hr. ago</p>
            </div>
          </li>

          <li>
            <hr class="dropdown-divider">
          </li>

          <li class="notification-item">
            <i class="bi bi-check-circle text-success"></i>
            <div>
              <h4>Sit rerum fuga</h4>
              <p>Quae dolorem earum veritatis oditseno</p>
              <p>2 hrs. ago</p>
            </div>
          </li>

          <li>
            <hr class="dropdown-divider">
          </li>

          <li class="notification-item">
            <i class="bi bi-info-circle text-primary"></i>
            <div>
              <h4>Dicta reprehenderit</h4>
              <p>Quae dolorem earum veritatis oditseno</p>
              <p>4 hrs. ago</p>
            </div>
          </li>

          <li>
            <hr class="dropdown-divider">
          </li>
          <li class="dropdown-footer">
            <a href="#">Show all notifications</a>
          </li>

        </ul><!-- End Notification Dropdown Items -->

      </li><!-- End Notification Nav -->

      <li class="nav-item dropdown">
       <?php
       $message=$this->Model->getRequeteOne('SELECT count(ID_MESSAGE_USER) as nbre_msg FROM message_utilisateurs WHERE PROFIL_ID_ENVOIE !=1 AND STATUT_ADMIN=1');

       $PROFIL_IDENTIFIANT = $this->session->userdata('PROFIL_ID');
       $USER_ID = $this->session->userdata('USER_ID');
       $message_users=$this->Model->getRequeteOne('SELECT count(ID_MESSAGE_USER) as nbre_msg FROM message_utilisateurs WHERE STATUT_UTILISATEUR=1 AND USER_ID_DESTINATAIRE='.$USER_ID);
       ?>
       <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-chat-left-text"></i>
        <?php if($PROFIL_IDENTIFIANT==1){?>

          <span class="badge bg-success badge-number"><?= $message['nbre_msg'];?></span>

        <?php } ?>
        <?php if($PROFIL_IDENTIFIANT==2){?>

          <span class="badge bg-success badge-number"><?= $message_users['nbre_msg'];?></span>

        <?php } ?>

      </a><!-- End Messages Icon -->

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">



        <?php
        if ($PROFIL_IDENTIFIANT==1) {?>
          <li class="dropdown-header">
            You have <?=$message['nbre_msg']?> new messages
            <a href="<?=base_url()?>administration/Message/message"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
          </li>
          <?php
        }else{?>

          <li class="dropdown-header">
            You have <?=$message_users['nbre_msg']?> new messages
            <a href="<?=base_url()?>administration/Message/message"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
          </li>


        <?php }



        ?>


        <li>
          <hr class="dropdown-divider">
        </li>
        <?php
        $message_details=$this->Model->getRequete('SELECT ID_MESSAGE_USER,MESSAGE,USER_ID_ENVOIE,users.IDENTIFICATION,DATE as date_enreg FROM message_utilisateurs JOIN users ON users.USER_ID=message_utilisateurs.USER_ID_ENVOIE WHERE message_utilisateurs.PROFIL_ID_ENVOIE !=1 AND message_utilisateurs.STATUT_ADMIN=1');

        $message_details_utilisateur=$this->Model->getRequete('SELECT ID_MESSAGE_USER,MESSAGE,USER_ID_ENVOIE,users.IDENTIFICATION,DATE as date_enreg,message_utilisateurs.USER_ID_DESTINATAIRE FROM message_utilisateurs JOIN users ON users.USER_ID=message_utilisateurs.USER_ID_ENVOIE WHERE message_utilisateurs.STATUT_UTILISATEUR=1 AND USER_ID_DESTINATAIRE='.$USER_ID);
        $now=date('Y-m-d H:i:s');
        if ($PROFIL_IDENTIFIANT==1) {
          foreach ($message_details as $key_message) {

            $heure=$this->notifications->ago($key_message['date_enreg'],$now);

            ?>
            <li class="message-item">
              <a href="<?=base_url('administration/Message/index/'.$key_message['USER_ID_ENVOIE'])?>">
                <!-- <img src="<?= base_url()?>/template/assets/img/messages-1.jpg" alt="" class="rounded-circle"> -->
                <div>
                  <h4><?=$key_message['IDENTIFICATION']?></h4>
                  <p><?=$key_message['MESSAGE']?></p>
                  <p><?=$heure?> ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <?php
          }
        }else{

          foreach ($message_details_utilisateur as $keymessageutilisateur) {
            $heure1=$this->notifications->ago($keymessageutilisateur['date_enreg'],$now);

            ?>
            <li class="message-item">
              <a href="<?=base_url('administration/Message/index/'.$keymessageutilisateur['USER_ID_DESTINATAIRE'])?>">
                <!-- <img src="<?= base_url()?>/template/assets/img/messages-1.jpg" alt="" class="rounded-circle"> -->
                <div>
                  <h4><?=$keymessageutilisateur['IDENTIFICATION']?></h4>
                  <p><?=$keymessageutilisateur['MESSAGE']?></p>
                  <p><?=$heure1?> ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <?php
          }
        }
        ?>



      </ul><!-- End Messages Dropdown Items -->

    </li><!-- End Messages Nav -->

    <li class="nav-item dropdown pe-3">

      <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
        <?php 
        $photo=$this->Model->getRequeteOne('SELECT PHOTO_PASSPORT,LOGO FROM proprietaire JOIN users ON users.PROPRIETAIRE_ID=proprietaire.PROPRIETAIRE_ID WHERE users.USER_ID= '.$this->session->userdata('USER_ID')); ?>
        <?php 
        if (!empty($photo['PHOTO_PASSPORT'])) 
          {?>
            <img src="<?=base_url('/upload/proprietaire/photopassport/'.$photo['PHOTO_PASSPORT'])?>" alt="Profile" class="rounded-circle">
          <?php }
          elseif(!empty($photo['LOGO'])){?>
            <img src="<?=base_url('/upload/proprietaire/photopassport/'.$photo['LOGO'])?>" alt="Profile" class="rounded-circle">

            <?php
          }
          else{?>

            <img src="<?= base_url()?>/upload/phavatar.png" alt="Profile" class="rounded-circle">

          <?php }
            // code...
          ?>

          <span class="d-none d-md-block dropdown-toggle ps-2"><?=$this->session->userdata('IDENTIFICATION')?></span>
        </a><!-- End Profile Iamge Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6><?=$this->session->userdata('USER_NAME')?></h6>
            <span><?=$this->session->userdata('CODE_PROFIL')?></span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <?php $CODE = $this->session->userdata('CODE_PROFIL');
          ?>
          <?php

          if($CODE!='ADMIN'){?>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?=base_url()?>profil/Profil">
                <i class="bi bi-gear"></i>
                <span>Paramètres</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?=base_url('administration/Message/index/'.$USER_ID)?>">
                <i class="bi bi-question-circle"></i>
                <span>Besoin d'aide?</span>
              </a>
            </li>

            <?php
          }
          ?>




          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="<?=base_url()?>Login/logout">
              <i class="bi bi-box-arrow-right"></i>
              <span>Se déconnecter</span>
            </a>
          </li>

        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->

</header>