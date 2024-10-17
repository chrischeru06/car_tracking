<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>
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
.scroller {
  height: 400px;
  overflow-y: scroll;
  border-radius: 10px;
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
   <div class="row page-titles mx-0">
    <div class="col-sm-10 p-md-0">
      <div class="welcome-text">
        <center>
         <table>
          <tr>

            <td>  
              <h4 class="text-dark text-center" style="margin-bottom: 1px;"><?=lang('etat_vehicul')?></h4>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                 
                </ol>
              </nav>
            </td>
          </tr>
        </table>
      </center>
    </div>
  </div>
 
</div>


<!-- End Page Title -->

<section class="section dashboard">
   <div class="row">


        <div class="col-xl-12">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

               <!--  <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#vehicul_pris">Prise de vehicule</button>
                </li> -->
                <li class="nav-item">
                          <button class="nav-link active " data-bs-toggle="tab" data-bs-target="#vehicul_pris"><?=lang('vehicul_pris')?></button>
                        </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#vehicul_remis"><?=lang('vehicul_remis')?></button>
                </li>
                 <li class="nav-item">
                  <button class="nav-link " data-bs-toggle="tab" data-bs-target="#anomalie"><?=lang('anomalies')?><span  style="font-size:10px;position:relative;top:-10px;left:-2px;"></span></button>
                </li>

<!-- 
                <li class="nav-item">
                  <button class="nav-link <?php if($ID_OPERATION !=" "){echo "active";}else{echo "";}?>" data-bs-toggle="tab" data-bs-target="#voitures"><?=lang('btn_vehicules')?><span class="badge bg-primary rounded-pill nbr_vehicule" style="font-size:10px;position:relative;top:-10px;left:-2px;">4</span></button>
                </li> -->

              </ul>

              <div class="tab-content pt-2">

             <!-- debut taps1 -->

             <!-- <div class="tab-pane fade active" id="vehicul_pris"> -->
               <div class="tab-pane fade show active" id="vehicul_pris">
                <div class="table-responsive">

                  <table id="mytable" class="table table-hover text-dark" style="width:100%">
                    <thead class="text-dark" style="background-color: rgba(0, 0, 0, 0.075);">
                      <tr>
                    <th class="text-dark">#</th>
                    <th class="text-dark"><?=lang('th_chauffeur')?></th>

                    <th class="text-dark"><?=lang('img_avant')?></th>
                    <th class="text-dark"><?=lang('img_arriere')?></th>
                    <th class="text-dark"><?=lang('img_lat_gauche')?></th>
                    <th class="text-dark"><?=lang('img_lat_droite')?> </th>
                    <th class="text-dark"><?=lang('tableau_voiture')?></th>
                    <th class="text-dark"><?=lang('img_siege_av')?></th>
                    <th class="text-dark"><?=lang('siege_arriere')?></th>
                    <!-- <th class="text-dark"><?=lang('operationfaite')?></th> -->
                    <!--<th class="text-dark">Anomalie</th> -->
                    </tr>
                    </thead>
                    <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                    </tbody>
                  </table>
                </div>
              </div>
            <!--  fin taps1 -->

             <!--  debut taps2 -->
               <div class="tab-pane fade  pt-3" id="vehicul_remis">

                <div class="table-responsive">

                  <table id="mytable2" class="table table-hover text-dark" style="width:100%">
                    <thead class="text-dark" style="background-color: rgba(0, 0, 0, 0.075);">
                        <tr>
                    <th class="text-dark">#</th>
                    <th class="text-dark"><?=lang('p_chauffeur')?></th>

                    <th class="text-dark"><?=lang('img_avant')?></th>
                    <th class="text-dark"><?=lang('img_arriere')?></th>
                    <th class="text-dark"><?=lang('img_lat_gauche')?></th>
                    <th class="text-dark"><?=lang('img_lat_droite')?> </th>
                    <th class="text-dark"><?=lang('tableau_voiture')?></th>
                    <th class="text-dark"><?=lang('img_siege_av')?></th>
                    <th class="text-dark"><?=lang('siege_arriere')?></th>
                    <!-- <th class="text-dark"><?=lang('operationfaite')?></th> -->
                    <!--<th class="text-dark">Anomalie</th> -->
                    </tr>
                    </thead>
                    <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                    </tbody>
                  </table>
                </div>
              </div>
              
           <!--    fin taps2 -->


              <!--     debut taps3 -->

              <div class="tab-pane fade  pt-3" id="anomalie">

                <div class="table-responsive">

                  <table id="mytable3" class="table table-hover text-dark" style="width:100%">
                    <thead class="text-dark" style="background-color: rgba(0, 0, 0, 0.075);">
                      <tr>
                    <th class="text-dark">#</th>

                    <th class="text-dark"><?=lang('th_chauffeur')?></th>

                    <!-- <th class="text-dark">Opération</th> -->
                    <!-- <th class="text-dark"><?=lang('operationfaite')?></th> -->

                    <th class="text-dark"><?=lang('anomalie')?></th>
                    </tr>
                    </thead>
                    <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                    </tbody>
                  </table>
                </div>
              </div>
         <!--      fin taps3 -->


            </div><!-- End Bordered Tabs -->

          </div>
        </div>

      </div>
    </div>

</section>



<!-- Modal photo du vehicule-->

<div class="modal fade" id="Modal_photo_vehicule">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style='background:cadetblue;color:white;'>
        <h6 class="modal-title"></h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
      </div>
      <div class="modal-body">

        <div class="row text-center" style="background-color:rgba(230,230,200,0.3);margin-top:-10px;border-radius:50%;">

          <div class="col-md-4">

          </div>

          <div class="col-md-1">
            <i onclick="zoomIn()" class="fa fa-plus-circle text-muted"></i>

            <input type="hidden" id="rotation" value="0">
          </div>

          <div class="col-md-1">
            <i onclick="zoomOut()" class="fa fa-minus-circle text-muted"></i>
          </div>

                <div class="col-md-1">
                  <i onclick="rotate_op()" class="fa fa-rotate-right text-muted"></i>
                </div>


              </div>

              <div class="row">

                <div class="col-md-12" id="image-container">
                  <img src="" id="phot_v" alt="Description de l'image">
                </div>

              </div>

            </div>
            <!-- footer here -->
          </div>
        </div>
      </div>

</main><!-- End #main -->

<?php include VIEWPATH . 'includes/footer.php'; ?>

</body>


<script>

  // Fonction pour le chargement de donnees par defaut
  $(document).ready( function ()
  {
    liste();
    liste2();
    liste3();


  });

  function liste()
  {
    var ID_OPERATION=1;
    $('#message').delay('slow').fadeOut(10000);
    $(document).ready(function()
    {
      var row_count ="1000000";

      $("#mytable").DataTable({
        "processing":true,
        "destroy" : true,
        "serverSide":true,
        "oreder":[[ 0, 'desc' ]],
        "ajax":{
          url:"<?php echo base_url('etat_vehicule/Liste_Etat_Vehicule/listing');?>",
          type:"POST",
          data : {ID_OPERATION:ID_OPERATION}, 
        },
        lengthMenu: [[10,50, 100, row_count], [10,50, 100, "All"]],
      //pageLength: 10,
        "columnDefs":[{
          "targets":[],
          "orderable":false
        }],

        dom: 'Bfrtlip',
        buttons: [
          'pdf', 'print',
          ],
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
    });
  }
  function liste2()
  {
    var row_count=10000;
    $('#message').delay('slow').fadeOut(10000);
    $(document).ready(function()
    {
      var ID_OPERATION =2;

      $("#mytable2").DataTable({
        "processing":true,
        "destroy" : true,
        "serverSide":true,
        "oreder":[[ 0, 'desc' ]],
        "ajax":{
          url:"<?php echo base_url('etat_vehicule/Liste_Etat_Vehicule/listing');?>",
          type:"POST",
          data : {ID_OPERATION:ID_OPERATION},
           beforeSend : function() 
           {

          } 
        },
        lengthMenu: [[10,50, 100, row_count], [10,50, 100, "All"]],
      //pageLength: 10,
        "columnDefs":[{
          "targets":[],
          "orderable":false
        }],

        dom: 'Bfrtlip',
        buttons: [
          'pdf', 'print',
          ],
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
    });
  }
  function liste3()
  {
    var row_count=10000;
    $('#message').delay('slow').fadeOut(10000);
    $(document).ready(function()
    {
     var ID_OPERATION =3;

      $("#mytable3").DataTable({
        "processing":true,
        "destroy" : true,
        "serverSide":true,
        "oreder":[[ 0, 'desc' ]],
        "ajax":{
          url:"<?php echo base_url('etat_vehicule/Liste_Etat_Vehicule/listing');?>",
          type:"POST",
          data : {ID_OPERATION:ID_OPERATION}, 
        },
        lengthMenu: [[10,50, 100, row_count], [10,50, 100, "All"]],
      //pageLength: 10,
        "columnDefs":[{
          "targets":[],
          "orderable":false
        }],

        dom: 'Bfrtlip',
        buttons: [
          'pdf', 'print',
          ],
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
    });
  }

</script>
 </html>