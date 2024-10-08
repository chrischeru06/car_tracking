<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>

  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>

  <style type="text/css">
    /* The switch - the box around the slider */
    .switch {
      position: relative;
      display: inline-block;
      width: 30px;
      height: 20px;
    }


/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 20px;
  width: 20px;
  left: -8px;
  bottom: 0px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.btn-md:hover{
  background-color: rgba(210, 232, 249,100);
  border-radius: 5px;
}

.map-overlay {

  position: absolute;
  width: 300px;
  top:200px;
  right: 25px;
  padding: 5px;
  z-index: 100;
}

.statistic{
  font-size: 12px;
}

</style>

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

      <div class="row page-titles mx-0">
        <div class="col-sm-10 p-md-0">
       <!-- <div class="welcome-text">
          <center>
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
             <table>
              <tr>
                <td> 

                </td>
                <td>  
                  <h4 class="text-dark"><?=$title?></h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">

                    </ol>
                  </nav>
                </td>
              </tr>
            </table>
          </div>
        </center>
      </div>-->
      <h1><i class="fa fa-hdd-o" style="font-size:18px;"></i> <font><?=lang('device_mot')?></font></h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html"><?=lang('device_mot')?></a></li>
          <li class="breadcrumb-item "><?=lang('title_list')?></li>
        </ol>
      </nav>
    </div>
    <div class="col-md-2">

      <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('sim_management/Sim_management/ajouter')?>" class="nav-link position-relative"><i class="bi bi-plus"></i> <?=lang('btn_nouveau')?></a>

    </div>
  </div>

</div>

<section class="section dashboard">

  <div class="row">
    <!-- Left side columns -->
    <div class="col-lg-12">
      <div class="row">

        <!-- Reports -->
        <div class="col-12">
          <ul class="nav nav-tabs nav-tabs-bordered">

            <li class="nav-item">
              <button class="nav-link active btn-sm" data-bs-toggle="tab" data-bs-target="#all_devices"><label class=""><img src="<?= base_url()?>/upload/icon_total1.png" style="width: 15px;"> Total <?=lang('device_mot')?> (<font id="nbr_device">0</font>) </label></button>
            </li>

            <li class="nav-item">
              <button class="nav-link btn-sm" data-bs-toggle="tab" data-bs-target="#forfaits_valides"><label class=""><i class="fa fa-check-circle text-success"></i> forfaits valides (<font id="nbr_forfait_valide">0</font>)</label></button>
            </li>

            <li class="nav-item">
              <button class="nav-link btn-sm" data-bs-toggle="tab" data-bs-target="#forfaits_expires"><label class=""><i class="fa fa-ban text-danger"></i> forfaits expirés (<font id="nbr_forfait_expire">0</font>)</label></button>
            </li>

            <li class="nav-item">
              <button class="nav-link btn-sm" data-bs-toggle="tab" data-bs-target="#forfait_proche_exp"><label class=""><i class="fa fa-rotate-right text-warning"></i> proches de l'expiration (<font id="nbr_forfait_proche_exp">0</font>)</label></button>
            </li>

            <li class="nav-item btn-sm card">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#etat_vehicule" onclick="open_popup();"><label class=""><i class="fa fa-area-chart text-dark"></i> Statistiques du tableau </label></button>
            </li>

          </ul>

          <div class="map-overlay top">
            <div class="card">
              <div class="card-body">
                <!-- <div class="scroller"> -->
                  <div class="map-overlay-inner">
                    <font onclick="close_popup()" style="float: right;font-size: 10px;" class="btn btn-outline-secondary rounded-pill fa fa-close " type="button"></font>
                    <br>
                    <table class="table table-borderless card alert alert-primary statistic">
                      <tr>
                        <td class="fa fa-info-circle"></td>
                        <td >
                          <font class="text-small text-muted">Le filtrage de la table va affécter les stastistiques ci-dessous</font> 
                        </td>
                      </tr>

                    </table>

                    <div class="row">
                      <div id="container"  class="col-md-12" >
                        
                      </div> 

                      <center><font id="nouveau"></font></center>
                    </div>

                    

                    <table class="table table-borderless statistic">

                      <tr>
                        <td class="text-small text-muted"><label style="border: solid 1px green;width: 2px;height: 30px;"></label>
                        </td>
                        <td class="text-small text-muted">
                          <font><b id="nbrValide">0</b><br>Forfaits valides</font>
                        </td>
                      </tr>

                      <tr>
                        <td class="text-small text-muted"><label style="border: solid 1px red;width: 2px;height: 30px;"></label>
                        </td>
                        <td class="text-small text-muted">
                          <font><b id="nbrExpire">0</b><br>Forfaits expirés</font>
                        </td>
                      </tr>

                      <tr>
                        <td class="text-small text-muted"><label style="border: solid 1px yellow;width: 2px;height: 30px;"></label>
                        </td>
                        <td class="text-small text-muted">
                          <font><b id="nbrProcheExp">0</b><br>Proches d'expiration</font>
                        </td>
                      </tr>

                    </tr>

                  </table>



                </div>
                <!-- </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<section class="section">
 <div class="">
  <div class="row">
    <div class="col-sm-12">
      <div class="card" style="border-radius: 10px;">

        <br>

        <div class="card-body">

         <!-- begin tab content -->
         <div class="tab-content pt-2"> 

          <div class="tab-pane fade show active" id="all_devices">

            <div class="row">
              <div class="col-md-4 text-sm">
                <label class="text-dark"><b>Statut forfait</b>&nbsp;<span class="badge bg-primary rounded-pill nbr_vehicule" style="font-size:10px;">0</span></label>
                <select class="form-control text-sm" id="CHECK_VALIDE" name="CHECK_VALIDE" onchange="listing();get_nbr_device_selected();get_rapport();">
                  <option value="0"> <?=lang('selectionner')?></option>
                  <option value="1"> Forfaits valides </option>
                  <option value="2"> Forfaits expirés </option>
                  <option value="3"> Proches de l'expiration ( < 5 jrs ) </option>

                </select>

                <label class="fa fa-check-circle text-success" id="check" style="position: relative;top: -33%;left: 93%;"></label>

                <label class="fa fa-ban text-danger" id="close" style="position: relative;top: -33%;left: 93%;"></label>

                <label class="fa fa-rotate-right text-warning" id="rotate" style="position: relative;top: -33%;left: 93%;"></label>

              </div>

            </div><br>

            <div class="row table-responsive">

              <?= $this->session->flashdata('message'); ?>

              <table id="mytable" class="table table-hover" style="padding-top: 20px;">
                <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                  <tr>
                    <th class="">#</th>
                    <th class=""><?=lang('list_code')?></th>
                    <th class=""><?=lang('veh_maj_mot')?></th>
                    <th class=""><?=lang('th_proprio')?></th>
                    <th class="">INSTALLATION</th>
                    <th class="">RESEAU</th>
                    <th class="">ECHEANCE</th>
                    <th class=""><?=lang('th_statut')?></th>
                    <th class=""><?=lang('validation_val')?></th>
                    <th class="">ENREGISTREMENT</th>
                    <th class=""><?=lang('list_action')?></th>
                  </tr>
                </thead>
                <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                </tbody>
              </table>

            </div>

          </div>


          <div class="tab-pane fade " id="forfaits_valides">

            <div class="row">

              <div class="table-responsive">

                <table id="tableFvalide" class="table table-hover" style="padding-top: 20px;">
                  <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                    <tr>
                      <th class="">#</th>

                      <th class=""><?=lang('list_code')?></th>
                      <th class=""><?=lang('veh_maj_mot')?></th>
                      <th class=""><?=lang('th_proprio')?></th>
                      <th class="">INSTALLATION</th>
                      <th class="">RESEAU</th>
                      <th class="">ECHEANCE</th>
                      <th class=""><?=lang('th_statut')?></th>
                      <th class=""><?=lang('validation_val')?></th>
                      <th class="">ENREGISTREMENT</th>
                      <th class=""><?=lang('list_action')?></th>
                    </tr>
                  </thead>
                  <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                  </tbody>
                </table>

              </div>

            </div>

          </div>


          <div class="tab-pane fade " id="forfaits_expires">

            <div class="row">

              <div class="table-responsive">

                <table id="tableFexpire" class="table table-hover" style="padding-top: 20px;">
                  <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                    <tr>
                      <th class="">#</th>

                      <th class=""><?=lang('list_code')?></th>
                      <th class=""><?=lang('veh_maj_mot')?></th>
                      <th class=""><?=lang('th_proprio')?></th>
                      <th class="">INSTALLATION</th>
                      <th class="">RESEAU</th>
                      <!-- <th class=""><//?=lang('mot_numero')?></th> -->
                      <th class="">ECHEANCE</th>
                      <!-- <th class=""><//?=lang('dte_expiration_th')?></th> -->
                      <th class=""><?=lang('th_statut')?></th>
                      <th class=""><?=lang('validation_val')?></th>
                      <th class="">ENREGISTREMENT</th>
                      <th class=""><?=lang('list_action')?></th>
                    </tr>
                  </thead>
                  <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                  </tbody>
                </table>

              </div>

            </div>

          </div>


          <div class="tab-pane fade " id="forfait_proche_exp">

            <div class="row">

              <div class="table-responsive">

                <table id="tableFprocheExp" class="table table-hover" style="padding-top: 20px;">
                  <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                    <tr>
                      <th class="">#</th>

                      <th class=""><?=lang('list_code')?></th>
                      <th class=""><?=lang('veh_maj_mot')?></th>
                      <th class=""><?=lang('th_proprio')?></th>
                      <th class="">INSTALLATION</th>
                      <th class="">RESEAU</th>
                      <!-- <th class=""><//?=lang('mot_numero')?></th> -->
                      <th class="">ECHEANCE</th>
                      <!-- <th class=""><//?=lang('dte_expiration_th')?></th> -->
                      <th class=""><?=lang('th_statut')?></th>
                      <th class=""><?=lang('validation_val')?></th>
                      <th class="">ENREGISTREMENT</th>
                      <th class=""><?=lang('list_action')?></th>
                    </tr>
                  </thead>
                  <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                  </tbody>
                </table>

              </div>

            </div>

          </div>


        </div>
        <!-- end tab content -->

      </div>

      <!-- </div> -->

    </div>
  </div>
</div>
</div>
<!-- </div> -->
</section>


<!--******** Modal pour assurance et controle technique *********-->

<div class="modal fade" id="Modal_forfait" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'>

        <h5 class="modal-title" id="titre"><?=lang('modal_renouvlement')?></h5>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form_forfait" enctype="multipart/form-data" action="#" method="post">
          <div class="modal-body mb-1">
            <div class="row">
              <input type="hidden" name="DEVICE_ID" id="DEVICE_ID">

              <div class="col-md-6">
                <div class="form-group">
                  <label ><small> <?=lang('dte_activ_forfait')?></small><span  style="color:red;">*</span></label>

                  <input class="form-control" type='date' name="DATE_ACTIVE_MEGA" id="DATE_ACTIVE_MEGA" placeholder='' max="<?= date('Y-m-d')?>" onchange="get_date_expire();"/>

                </div>
                <span id="errorDATE_ACTIVE_MEGA" class="text-danger"></span>
                <?php echo form_error('DATE_ACTIVE_MEGA', '<div class="text-danger">', '</div>'); ?>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label ><small> <?=lang('dte_expiration_forfait')?></small><span  style="color:red;">*</span></label>

                  <input class="form-control" type='date' name="DATE_EXPIRE_MEGA" id="DATE_EXPIRE_MEGA" placeholder='' readonly/>

                </div>
                <span id="errorDATE_EXPIRE_MEGA" class="text-danger"></span>
                <?php echo form_error('DATE_EXPIRE_MEGA', '<div class="text-danger">', '</div>'); ?>
              </div>

            </div>
          </div> 
          <div class="modal-footer">

            <button type="button" class="btn btn-outline-primary rounded-pill" id="btnSave" onclick="save_forfait()"> <i class="fa fa-save"> </i> <?=lang('btn_enregistrer')?></button>

            <button type="reset" class='btn btn-outline-warning rounded-pill' style="float:right;" data-dismiss="modal" id="btnCancel"><i class="fa fa-close"> </i> <?=lang('btn_annuler')?></button>

          </div>
        </form>
      </div>
    </div>
  </div>
</div><!-- End Modal-->


</main><!-- End #main -->

<?php include VIEWPATH . 'includes/footer.php'; ?>

</body>


<script>

  // Fonction pour le chargement de donnees par defaut
  $(document).ready( function ()
  {

    $('#close').hide();
    $('#check').hide();
    $('#rotate').hide();

    listing();
    forfait_valide();
    forfait_expire();
    forfait_proch_exp();

    get_nbr_device();
    get_nbr_device_selected();
    get_nbr_device_valide();
    get_nbr_device_expire();
    get_nbr_device_proche_exp();

    document.getElementsByClassName('map-overlay')[0].style.display = 'none';

    get_rapport();
    

  });
</script>


<script>
  //Fonction pour l'affichage
  function listing()
  {
    var CHECK_VALIDE = $('#CHECK_VALIDE').val();

    if(CHECK_VALIDE == 1)
    {
      $('#check').show();
      $('#close').hide();
      $('#rotate').hide();
    }
    else if(CHECK_VALIDE == 2)
    {
      $('#check').hide();
      $('#close').show();
      $('#rotate').hide();
    }
    else if(CHECK_VALIDE == 3)
    {
      $('#check').hide();
      $('#close').hide();
      $('#rotate').show();
    }
    else
    {
      $('#check').hide();
      $('#close').hide();
      $('#rotate').hide();
    }

    $('#message').delay('slow').fadeOut(10000);

    $("#mytable").DataTable({
      "processing":true,
      "destroy" : true,
      "serverSide":true,
      "oreder":[[ 0, 'desc' ]],
      "ajax":{
        url:"<?php echo base_url('sim_management/Sim_management/listing');?>",
        type:"POST",
        data : {CHECK_VALIDE:CHECK_VALIDE},
        beforeSend : function() {

        } 
      },
      // lengthMenu: [[10,50, 100, row_count], [10,50, 100, "All"]],
      pageLength: 10,
      "columnDefs":[{
        "targets":[],
        "orderable":false
      }],

      dom: 'Bfrtlip',
      buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'  ],

      language: {
        "sProcessing":     "Traitement en cours...",
        "sSearch":         "Rechercher&nbsp;:",
        "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
        "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
        "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
        "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        "sInfoPostFix":    "",
        "sLoadingRecords": "Chargement en cours...",
        "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
        "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
        "oPaginate": {
          "sFirst":      "Premier",
          "sPrevious":   "Pr&eacute;c&eacute;dent",
          "sNext":       "Suivant",
          "sLast":       "Dernier"
        },
        "oAria": {
          "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
          "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
        }
      }



    });

  }
</script>

<script>
  //Appel Liste pour les forfaits valides
  function forfait_valide()
  {
    var CHECK_VALIDE = 1; // Forfaits valides

    $('#message').delay('slow').fadeOut(10000);

    $("#tableFvalide").DataTable({
      "processing":true,
      "destroy" : true,
      "serverSide":true,
      "oreder":[[ 0, 'desc' ]],
      "ajax":{
        url:"<?php echo base_url('sim_management/Sim_management/listing');?>",
        type:"POST",
        data : {CHECK_VALIDE:CHECK_VALIDE},
        beforeSend : function() {

        } 
      },
      // lengthMenu: [[10,50, 100, row_count], [10,50, 100, "All"]],
      pageLength: 10,
      "columnDefs":[{
        "targets":[],
        "orderable":false
      }],

      dom: 'Bfrtlip',
      buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'  ],

      language: {
        "sProcessing":     "Traitement en cours...",
        "sSearch":         "Rechercher&nbsp;:",
        "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
        "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
        "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
        "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        "sInfoPostFix":    "",
        "sLoadingRecords": "Chargement en cours...",
        "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
        "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
        "oPaginate": {
          "sFirst":      "Premier",
          "sPrevious":   "Pr&eacute;c&eacute;dent",
          "sNext":       "Suivant",
          "sLast":       "Dernier"
        },
        "oAria": {
          "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
          "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
        }
      }



    });

  }
</script>

<script>
  //Appel Liste pour les forfaits expires
  function forfait_expire()
  {
    var CHECK_VALIDE = 2; // Forfaits expires

    $('#message').delay('slow').fadeOut(10000);

    $("#tableFexpire").DataTable({
      "processing":true,
      "destroy" : true,
      "serverSide":true,
      "oreder":[[ 0, 'desc' ]],
      "ajax":{
        url:"<?php echo base_url('sim_management/Sim_management/listing');?>",
        type:"POST",
        data : {CHECK_VALIDE:CHECK_VALIDE},
        beforeSend : function() {

        } 
      },
      // lengthMenu: [[10,50, 100, row_count], [10,50, 100, "All"]],
      pageLength: 10,
      "columnDefs":[{
        "targets":[],
        "orderable":false
      }],

      dom: 'Bfrtlip',
      buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'  ],

      language: {
        "sProcessing":     "Traitement en cours...",
        "sSearch":         "Rechercher&nbsp;:",
        "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
        "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
        "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
        "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        "sInfoPostFix":    "",
        "sLoadingRecords": "Chargement en cours...",
        "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
        "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
        "oPaginate": {
          "sFirst":      "Premier",
          "sPrevious":   "Pr&eacute;c&eacute;dent",
          "sNext":       "Suivant",
          "sLast":       "Dernier"
        },
        "oAria": {
          "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
          "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
        }
      }



    });

  }
</script>


<script>

  function forfait_proch_exp()
  {
    var CHECK_VALIDE = 3; // Forfaits proches à expirer

    $('#message').delay('slow').fadeOut(10000);

    $("#tableFprocheExp").DataTable({
      "processing":true,
      "destroy" : true,
      "serverSide":true,
      "oreder":[[ 0, 'desc' ]],
      "ajax":{
        url:"<?php echo base_url('sim_management/Sim_management/listing');?>",
        type:"POST",
        data : {CHECK_VALIDE:CHECK_VALIDE},
        beforeSend : function() {

        } 
      },
      // lengthMenu: [[10,50, 100, row_count], [10,50, 100, "All"]],
      pageLength: 10,
      "columnDefs":[{
        "targets":[],
        "orderable":false
      }],

      dom: 'Bfrtlip',
      buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'  ],

      language: {
        "sProcessing":     "Traitement en cours...",
        "sSearch":         "Rechercher&nbsp;:",
        "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
        "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
        "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
        "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        "sInfoPostFix":    "",
        "sLoadingRecords": "Chargement en cours...",
        "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
        "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
        "oPaginate": {
          "sFirst":      "Premier",
          "sPrevious":   "Pr&eacute;c&eacute;dent",
          "sNext":       "Suivant",
          "sLast":       "Dernier"
        },
        "oAria": {
          "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
          "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
        }
      }



    });

  }
</script>

<script>
    //Fonction pour afficher le formulaire de renouvelement du forfait
  function renouvelerForfait(DEVICE_ID)
  {
    $('#DEVICE_ID').val(DEVICE_ID);
    $.ajax(
    {
      url:"<?=base_url()?>sim_management/Sim_management/get_date_expire_old/"+DEVICE_ID,
      type:"GET",
      dataType:"JSON",
      success: function(data)
      {
        $("#DATE_ACTIVE_MEGA").prop('min',data);
      }
    });

    $('#Modal_forfait').modal('show');
  }
</script>

<script type="text/javascript">
  //Dertermination de la date expiration automatique
  function get_date_expire()
  {
    var DATE_ACTIVE_MEGA = $('#DATE_ACTIVE_MEGA').val();

    $.ajax(
    {
      url:"<?=base_url('sim_management/Sim_management/get_date_expire/')?>",
      type : "POST",
      dataType: "JSON",
      cache:false,
      data: {
        DATE_ACTIVE_MEGA:DATE_ACTIVE_MEGA,
      },
      success: function(data)
      {
        $("#DATE_EXPIRE_MEGA").val(data);
        $("#DATE_EXPIRE_MEGA").prop('min',data);
        $("#DATE_EXPIRE_MEGA").prop('max',data);
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        alert('<?=lang('msg_erreur')?>');
      }
    });
    
  }
</script>

<script>
  //Fonction pour proceder à l'enregistrement du renouvelement forfait
  function save_forfait()
  {
    var statut = 1;

    if($('#DATE_ACTIVE_MEGA').val() == '')
    {
      $('#errorDATE_ACTIVE_MEGA').text('<?=lang('msg_validation')?>');
      statut = 2;
    }else{$('#errorDATE_ACTIVE_MEGA').text('');}

    if($('#DATE_EXPIRE_MEGA').val() == '<?=lang('msg_validation')?>')
    {
      $('#errorDATE_EXPIRE_MEGA').text('');
      statut = 2;
    }
    else{$('#errorDATE_EXPIRE_MEGA').text('');}

    if(statut == 1)
    {
      var form_data = new FormData($("#form_forfait")[0]);
      url = "<?= base_url('sim_management/Sim_management/save_renouv_fortait/') ?>";
      $.ajax({
        url: url,
        type: 'POST',
        dataType:'JSON',
        data: form_data ,
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
          console.log(data)
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?=lang('renouvlement_forfait_success')?>',
            timer: 2000,
          }).then(() => {
            window.location.reload();
          })
          $("#form_forfait")[0].reset();
        }
      })
    }
  }
</script>

<script>
 function get_nbr_device()
 {

  $.ajax({
    url: "<?= base_url() ?>sim_management/Sim_management/get_nbr_device/",
    type: "POST",
    dataType: "JSON",
    success: function(data) {
     $('#nbr_device').text(data);
   },

 });
}
</script>

<script>
 function get_nbr_device_selected()
 {
  var CHECK_VALIDE = $('#CHECK_VALIDE').val();

  $.ajax({
    url: "<?= base_url() ?>sim_management/Sim_management/get_nbr_device/" + CHECK_VALIDE,
    type: "POST",
    dataType: "JSON",
    success: function(data) {
     $('.nbr_vehicule').text(data);
   },

 });
}
</script>

<script>
 function get_nbr_device_valide()
 {
  var CHECK_VALIDE = 1; // Forfaits valides

  $.ajax({
    url: "<?= base_url() ?>sim_management/Sim_management/get_nbr_device/" + CHECK_VALIDE,
    type: "POST",
    dataType: "JSON",
    success: function(data) {
     $('#nbr_forfait_valide').text(data);
   },

 });
}
</script>

<script>
 function get_nbr_device_expire()
 {
  var CHECK_VALIDE = 2; // Forfaits expires

  $.ajax({
    url: "<?= base_url() ?>sim_management/Sim_management/get_nbr_device/" + CHECK_VALIDE,
    type: "POST",
    dataType: "JSON",
    success: function(data) {
     $('#nbr_forfait_expire').text(data);
   },

 });
}
</script>

<script>
 function get_nbr_device_proche_exp()
 {
  var CHECK_VALIDE = 3; // Forfaits à expirer

  $.ajax({
    url: "<?= base_url() ?>sim_management/Sim_management/get_nbr_device/" + CHECK_VALIDE,
    type: "POST",
    dataType: "JSON",
    success: function(data) {
     $('#nbr_forfait_proche_exp').text(data);
   },

 });
}
</script>

<script>
  function verif_check()
  {
    Swal.fire({
      icon: 'success',
      title: 'Annulé',
      text: '<?=lang('msg_modif_annule')?>',
      timer: 1000,
    }).then(() => {
      window.location.reload();
    })

    // var check = document.getElementById('myChecked');

    // if(check == false)
    // {
    //   check.checked = true;
    // }else{
    //   check.checked = false;
    // }

    // var uncheck = document.getElementById('myCheck');

    // if(uncheck == false)
    // {
    //   uncheck.checked = true;
    // }else{
    //   uncheck.checked = false;
    // }
    
  }
</script>

<script type="text/javascript">
  function close_popup() {
    document.getElementsByClassName('map-overlay')[0].style.display = 'none';
    // $("#btn_list").show();
  }

  function open_popup() {
    // $("#btn_list").hide();

          //document.getElementsByClassName('map-overlay')[0].style.display = 'block';

    $('.map-overlay').toggle('slow', function() {
              // Animation complete.
    });
  }

</script>
</script>


<script>
  function get_rapport()
  {
    var CHECK_VALIDE = $('#CHECK_VALIDE').val();
    $.ajax(
    {
      url : "<?=base_url()?>sim_management/Sim_management/get_rapport",
      type : "POST",
      dataType: "JSON",
      cache:false,
      data : {CHECK_VALIDE:CHECK_VALIDE},
      success:function(data)
      {   
        $('#container').html('');
        $('#nouveau').html(data.rapp);
        $('#nbrValide').html(data.nbrValide);
        $('#nbrProcheExp').html(data.nbrProcheExp);
        $('#nbrExpire').html(data.nbrExpire);

      },            
    });  
  }
</script>
</html>