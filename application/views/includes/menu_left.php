<head>
</head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style type="text/css">

  .sou_menu:hover{
    background-color: rgba(246, 249, 255,0.3);
    border-radius: 5px;
  }

</style>

<?php 

        // # Codes profil
        // ADMIN : Admin systeme


if ($this->session->PROFIL_ID == 1) {
  ?>

  <!-- <aside id="sidebar" class="sidebar" style="background-color: cadetblue; background-size: cover; background-repeat: no-repeat;background-image: url('<?php //echo base_url().'template/images/sidebar123-01s.png'; ?>')"> -->

    <aside id="sidebar" class="sidebar" style="background-color: cadetblue;">

      <ul class="sidebar-nav" id="sidebar-nav" >

       <li class="nav-item menu">
        <a class="nav-link " href="<?=base_url()?>centre_situation/Centre_situation">
          <i class="bi bi-grid"></i>
          <span><?=lang('Centre_situation_lng')?></span>
        </a>
      </li> <!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="<?=base_url()?>administration/Profil">
          <i class="bi bi-menu-button-wide"></i><span><?=lang('Administration_admin')?></span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li class="sou_menu">
            <a href="<?=base_url()?>administration/Profil">
              <i class="bi bi-circle"></i><span><?=lang('Profils_lng')?></span>
            </a>
          </li>
          <li class="sou_menu">
            <a href="<?=base_url()?>administration/Users">
              <i class="bi bi-circle"></i><span><?=lang('Utilisateurs_lng')?></span>
            </a>
          </li>

        </ul>
      </li><!-- End Components Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="<?=base_url()?>proprietaire/Proprietaire/liste">
          <i class="bi bi-journal-text"></i><span><?=lang('IHM_lng')?></span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li class="sou_menu">
            <a href="<?=base_url()?>proprietaire/Proprietaire/liste">
              <i class="fa fa-user"></i><span><?=lang('title_proprio_list_plur')?></span>
            </a>
          </li>
          <li class="sou_menu">
            <a href="<?=base_url()?>vehicule/Vehicule">
              <i class="fa fa-car"></i><span><?=lang('btn_vehicules')?></span>
            </a>
          </li>
          <li class="sou_menu">
            <a href="<?=base_url()?>chauffeur/Chauffeur">
              <i class="fa fa-user-md"></i><span><?=lang('Chauffeurs_lng')?></span>
            </a>
          </li>

        </ul>
      </li>
      <!-- End Forms Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="<?=base_url()?>dashboard/Dashboard_General">
          <i class="bi bi-bar-chart"></i><span><?=lang('Tableau_bord_lng')?></span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li class="sou_menu">
            <a href="<?=base_url()?>dashboard/Dashboard_General">
              <i class="bi bi-person"></i><span> <?=lang('Gnrl_lng')?></span>
            </a>
          </li>
          <li class="sou_menu">
            <a href="<?=base_url()?>dashboard/Dashboard_Anomalies">
              <i class="bi bi-person"></i><span><?=lang('anomalies_lng')?></span>
            </a>
          </li>

          
         <!-- <li>
          <a href="<?=base_url()?>dashboard/Dashboard_Vehicule">
            <i class="bi bi-person"></i><span>Véhicule</span>
          </a>
        </li>
      -->
    </ul>
  </li><!-- End Tables Nav -->


  <li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#components-sim" data-bs-toggle="collapse" href="<?=base_url()?>administration/Profil">
      <i class="fa fa-cogs"></i><span><?=lang('Sim_management_lng')?></span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="components-sim" class="nav-content collapse " data-bs-parent="#sidebar-nav">
      <li class="sou_menu">
        <a href="<?=base_url()?>sim_management/Sim_management">
          <i class="fa fa-hdd-o" style="font-size:18px;"></i><span><?=lang('device_mot')?></span>
        </a>
      </li>
      <li class="sou_menu">
        <a href="<?=base_url()?>notification/Notification">
          <i class="bi bi-bell" style="font-size:18px;"></i><span>Notification</span>
        </a>
      </li>

    </ul>
  </li>

  <!-- <li class="nav-item">
    <a class="nav-link collapsed" href="<?=base_url()?>etat_vehicule/Liste_Etat_Vehicule">
      <i class="fa fa-car"></i>
      <span><?=lang('etat_vehicul')?></span>
    </a>
  </li> -->



</ul>

</aside>
<?php 
}
        // # Codes profil
        // Proprietaire 

else if ($this->session->PROFIL_ID == 2) { 
  ?>


  <!--<aside id="sidebar" class="sidebar" style="background-color: cadetblue; background-size: cover; background-repeat: no-repeat;background-image: url('<?php //echo base_url().'template/images/sidebar123-01s.png'; ?>')">-->

    <aside id="sidebar" class="sidebar" style="background-color: cadetblue;">

      <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
          <a class="nav-link " href="<?=base_url()?>centre_situation/Centre_situation">
            <i class="bi bi-grid"></i>
            <span><?=lang('Centre_situation_lng')?></span>
          </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
          <a class="nav-link collapsed" href="<?=base_url()?>vehicule/Vehicule">
            <i class="bi bi-journal-text"></i>
            <span><?=lang('ms_veh')?></span>
          </a>
        </li>
        <li class="nav-item">
          <!-- <a class="nav-link collapsed" href="<?=base_url()?>proprietaire/Proprietaire_chauffeur"> -->
            <a class="nav-link collapsed" href="<?=base_url()?>chauffeur/Chauffeur">
              <i class="bi bi-person"></i>
              <span><?=lang('Chauffeurs_lng')?></span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="<?=base_url()?>dashboard/Dashboard_General">
              <i class="bi bi-bar-chart"></i><span><?=lang('Tableau_bord_lng')?></span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              <li class="sou_menu">
                <a href="<?=base_url()?>dashboard/Dashboard_General">
                  <i class="bi bi-person"></i><span> <?=lang('Gnrl_lng')?></span>
                </a>
              </li>
        <!-- <li class="sou_menu">
          <a href="<?=base_url()?>dashboard/Dashboard_Anomalies">
            <i class="bi bi-person"></i><span><?=lang('anomalies_lng')?></span>
          </a>
        </li>
      -->

         <!-- <li>
          <a href="<?=base_url()?>dashboard/Dashboard_Vehicule">
            <i class="bi bi-person"></i><span>Véhicule</span>
          </a>
        </li>
      -->
    </ul>
  </li><!-- End Tables Nav -->

</ul>

</aside>

<?php 
}

else if ($this->session->PROFIL_ID == 3) //Si c'est un Chauffeur
{ 
  ?>

  <aside id="sidebar" class="sidebar" style="background-color: cadetblue;">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=base_url()?>vehicule/Vehicule">
          <i class="fa fa-car"></i>
          <span>Mes véhicules</span>
        </a>
      </li>

      <li class="nav-item">

        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="<?=base_url()?>dashboard/Dashboard_General">
          <i  class="fa fa-exchange"></i> <span>Entrée/Sortie</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">

          <li class="sou_menu">
            <a href="<?=base_url()?>etat_vehicule/Sortie_Vehicule">
              <i class="fa fa-long-arrow-left"></i>
              <span>Prise de véhicule</span>
            </a>
          </li>

          <li class="sou_menu">
            <a href="<?=base_url()?>etat_vehicule/Retour_Vehicule">
              <i class="fa fa-long-arrow-right"></i>
              <span>Remise du véhicule</span>
            </a>
          </li>

        </ul>

      </li>

      
    </ul>

  </aside>

  <?php 
}
elseif ($this->session->PROFIL_ID == 4) //Si c'est un gestionnaire
{ 
  ?>


  <aside id="sidebar" class="sidebar" style="background-color: cadetblue;">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=base_url()?>vehicule/Vehicule">
          <i class="fa fa-car"></i>
          <span>Mes véhicules</span>
        </a>
      </li>

      <li class="nav-item">

        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="<?=base_url()?>dashboard/Dashboard_General">
          <i  class="fa fa-cogs"></i> <span>Gestion véhicule</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">

          <li class="sou_menu">
            <a href="<?=base_url()?>gestionnaire/Sortie_Vehicule">
              <i class="bi bi-person"></i>
              <span>Prise de véhicule</span>
            </a>
          </li>

          <li class="sou_menu">
            <a href="<?=base_url()?>gestionnaire/Retour_Vehicule">
              <i class="fa fa-car"></i>
              <span>Remise du véhicule</span>
            </a>
          </li>

          

        </ul>
      </li>



      
    </ul>

  </aside>

  <?php 
}else 
{
  redirect(base_url('Login/logout'));
}
?>