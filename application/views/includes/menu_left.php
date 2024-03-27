
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php 

        // # Codes profil
        // ADMIN : Admin systeme


if ($this->session->PROFIL_ID == 1) {
  ?>

  <aside id="sidebar" class="sidebar" style="background-color: cadetblue; background-size: cover; background-repeat: no-repeat;background-image: url('<?php echo base_url().'template/images/sidebar123-01s.png'; ?>')">

    <ul class="sidebar-nav" id="sidebar-nav" >

     <li class="nav-item">
      <a class="nav-link " href="<?=base_url()?>centre_situation/Centre_situation">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li> <!-- End Dashboard Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-menu-button-wide"></i><span>Administration</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="<?=base_url()?>administration/Profil">
            <i class="bi bi-circle"></i><span>Profils</span>
          </a>
        </li>
        <li>
          <a href="<?=base_url()?>administration/Users">
            <i class="bi bi-circle"></i><span>Utilisateurs</span>
          </a>
        </li>

      </ul>
    </li><!-- End Components Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-journal-text"></i><span>IHM</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="<?=base_url()?>proprietaire/Proprietaire/liste">
            <i class="bi bi-circle"></i><span>Propriétaires</span>
          </a>
        </li>
        <li>
          <a href="<?=base_url()?>vehicule/Vehicule">
            <i class="bi bi-circle"></i><span>Véhicules</span>
          </a>
        </li>
        <li>
          <a href="<?=base_url()?>chauffeur/Chauffeur">
            <i class="bi bi-circle"></i><span>Chauffeurs</span>
          </a>
        </li>

      </ul>
    </li><!-- End Forms Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-bar-chart"></i><span>Tableau de bord</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="<?=base_url()?>dashboard/Dashboard_General">
            <i class="bi bi-person"></i><span>Tableau de bord général</span>
          </a>
        </li>
        <!-- <li>
          <a href="<?=base_url()?>rapport/Dashboard_Chauffeur">
            <i class="bi bi-person"></i><span>Chauffeur</span>
          </a>
        </li> -->
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
        // # Codes profil
        // Proprietaire 

else if ($this->session->PROFIL_ID == 2) { 
  ?>


  <aside id="sidebar" class="sidebar" style="background-color: cadetblue; background-size: cover; background-repeat: no-repeat;background-image: url('<?php echo base_url().'template/images/sidebar123-01s.png'; ?>')">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="<?=base_url()?>centre_situation/Centre_situation">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <!-- <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Mes véhicules</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        
          <li>
            <a href="<?=base_url()?>proprietaire/Proprietaire_vehicule">
              <i class="bi bi-circle"></i><span>Véhicules</span>
            </a>
          </li>
        
          
        </ul>
      </li> --><!-- End Forms Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=base_url()?>proprietaire/Proprietaire_vehicule">
          <i class="bi bi-journal-text"></i>
          <span>Mes véhicules</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?=base_url()?>proprietaire/Proprietaire_chauffeur">
          <i class="bi bi-person"></i>
          <span>Chauffeurs</span>
        </a>
      </li>



    </ul>

  </aside>

  <?php 
}
else
{
  redirect(base_url('Login/logout'));
}
?>