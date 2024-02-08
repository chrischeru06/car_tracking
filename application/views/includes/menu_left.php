<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="<?=base_url()?>Dashboard">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Administration</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?=base_url()?>administration/Profils">
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
            <a href="<?=base_url()?>proprietaire/Proprietaire">
              <i class="bi bi-circle"></i><span>Propriétaires</span>
            </a>
          </li>
          <li>
            <a href="<?=base_url()?>vehicule/Vehicule">
              <i class="bi bi-circle"></i><span>Vehicules</span>
            </a>
          </li>
          <li>
            <a href="<?=base_url()?>agent/Agent">
              <i class="bi bi-circle"></i><span>Chauffeurs</span>
            </a>
          </li>
          
        </ul>
      </li><!-- End Forms Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Tracking voitures</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tables-general.html">
              <i class="bi bi-circle"></i><span>Voiture en temps réel</span>
            </a>
          </li>
          
        </ul>
      </li><!-- End Tables Nav -->


    </ul>

  </aside>