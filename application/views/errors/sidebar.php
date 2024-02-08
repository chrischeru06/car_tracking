
<style type="text/css">
  .bordersolid{
    border-style: solid;
    border-color: gray;
    border-width: 1px;
  }
  .tooltipp {
    display:inline-block;
    position:relative;
    border-bottom:1px dotted #666;
    text-align:left;
  }

  .tooltipp .topp {
    min-width:200px; 
    top:-20px;
    left:50%;
    transform:translate(-50%, -100%);
    padding:10px 20px;
    color:#111111;
    background-color:#EEEEEE;
    font-weight:normal;
    font-size:13px;
    border-radius:8px;
    position:absolute;
    z-index:99999999;
    box-sizing:border-box;
    box-shadow:0 1px 8px #FFFFFF;
    display:none;
  }

  .tooltipp:hover .topp {
    display:block;
  }

  .tooltipp .topp i {
    position:absolute;
    top:100%;
    left:50%;
    margin-left:-12px;
    width:24px;
    height:12px;
    overflow:hidden;
  }

  .tooltipp .topp i::after {
    content:'';
    position:absolute;
    width:12px;
    height:12px;
    left:50%;
    transform:translate(-50%,-50%) rotate(45deg);
    background-color:#EEEEEE;
    box-shadow:0 1px 8px #FFFFFF;
  }

  .content-wrapper {
    /*background-color: #312f568f;*/
    background-image: url('<?=base_url()?>/dist/css/login_img.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    height: 100%;
    margin: 0;
    padding: 0;
}

#title {
  color: #fff;
}

h1{
  color: #fff;
}
h4{
  color: #fff;
}

h5{
  color: #fff;
}

h3{
  color: #fff;
}

h2{
  color: #fff;
}

.list-group-item-heading{
  color: #000;
}


</style>

<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <img height="13.5%" width="100%" src="<?=base_url()?>uploads/wasili.jpg" alt=""   class="brand-image">
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <a href="#" class="d-block"><i class="fa fa-user"></i> <?=$this->session->userdata('USER_NAME')?></a>
      </div>
      <!--  <div class="info">
       
      </div> -->
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
<!-- 



      <li class="nav-item">
           <a href="#" class="nav-link <?php if($this->router->class == 'Rapport') echo 'active';?>">
           <i class="nav-icon fas fa-chart-pie"></i>
           <p>
             Rapport
             <i class="right fas fa-angle-left"></i>
           </p>
         </a> -->


      <li class="nav-item">
           <a href="#" class="nav-link <?php if($this->router->class == 'Rapport') echo 'active';?>">
           <i class="nav-icon fas fa-tachometer-alt"></i>
           <p>
             PSR
             <i class="right fas fa-angle-left"></i>
           </p>
         </a>
           <ul class="nav nav-treeview">
           <li class="nav-item">
             <a href="<?=base_url('PSR/Infra_infractions/index')?>" class="nav-link <?php if($this->router->class == 'restaurants') echo 'active';?>">
               <i class="far fa-circle nav-icon"></i>
               <p>Infractions </p>
             </a>
           </li>

            <li class="nav-item">
             <a href="<?=base_url('PSR/Psr_elements/index')?>" class="nav-link <?php if($this->router->class == 'gerants') echo 'active';?>">
               <i class="far fa-circle nav-icon"></i>
               <p>ELement de la police</p>
             </a>
           </li>

            <li class="nav-item">
             <a href="<?=base_url('PSR/Infra_peines/index')?>" class="nav-link <?php if($this->router->class == 'gerants') echo 'active';?>">
               <i class="far fa-circle nav-icon"></i>
               <p>PEines</p>
             </a>
           </li>

           <li class="nav-item">
             <a href="<?=base_url('PSR/Psr_affectation/index')?>" class="nav-link <?php if($this->router->class == 'menu') echo 'active';?>">
               <i class="far fa-circle nav-icon"></i>
               <p>Les affectations</p>
             </a>
           </li>

         </ul> 
         
        </li>

    </ul>
   </nav>
   <!-- /.sidebar-menu -->
 </div>
 <!-- /.sidebar -->
</aside>