<!DOCTYPE html>
<html lang="en">

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
</style>

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>

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
        <div class="welcome-text">
          <table>
            <tr>
              <td> 
              </td>
              <td>  
                <h1 class="pt-2 ps-1"> <i class="fa fa-rotate-left" style="font-size: 15px;"></i> <?=lang('historique_device_title')?></h1>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                  </ol>
                </nav>
              </td>
            </tr>
          </table>
        </div>
      </div>
    <div class="col-md-2">

      <!-- <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('sim_management/Sim_management/ajouter')?>" class="nav-link position-relative"><i class="bi bi-plus"></i> Nouveau</a> -->

    </div>
  </div>
  </div>


  <section class="section">
     <div class="container text-center">
      <div class="row">
        <div class="text-left col-sm-12">
          <div class="card" style="border-radius: 20px;">

            <!-- <br> -->

            <div class="card-body">
             

              <div class="row">

                <input type="hidden" name="DEVICE_ID" id="DEVICE_ID" value="<?=$DEVICE_ID?>">

                <div class="col-md-12">

                  <!-- <table class="table table-borderless" style="width:108%">
                    <tr>
                      <td> -->
                       <ul class="nav nav-tabs nav-tabs-bordered">

                        <li class="nav-item">
                          <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#activation_forfait" onclick="liste_histo(1);"><i class="fa fa-check"></i> <?=lang('activ_forfait_check')?></button>
                        </li>

                        <li class="nav-item">
                          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#statut"onclick="liste_histo2(2);"><i class="fa fa-cog"></i> <?=lang('i_stat')?></button>
                        </li>

                      </ul>
                    <!-- </td>
                  </tr>
                </table> -->


              </div>


            </div>

          </div>

          <!-- </div> -->

        </div>
      </div>
    </div>
  </div>
  <!-- </div> -->
</section>




<section class="section">
 <div class="container">
  <div class="row">
    <div class="col-sm-12">
      <div class="card" style="border-radius: 20px;">

        <br>

        <div class="card-body">

         <!-- begin tab content -->
         <div class="tab-content pt-2"> 


         <div class="tab-pane fade show active" id="activation_forfait">

              <div class="row">

                <div class="table-responsive">

                  <table class="table_historique" class="table table-hover" style="padding-top: 20px;">
                    <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                      <tr>
                        <th class="">#</th>
                        <th class=""><?=lang('list_code')?></th>
                        <th class=""><?=lang('dte_activ_th')?></th>
                        <th class=""><?=lang('dte_expiration_th')?></th>
                        <th class=""><?=lang('validation_val')?></th>
                        <!-- <th class="">STATUT</th> -->
                        <th class=""><?=lang('list_fait_par')?></th>
                        <th class=""><?=lang('list_date')?></th>
                      </tr>
                    </thead>
                    <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                    </tbody>
                  </table>
                  
                </div>
                
              </div>

            </div>


          <div class="tab-pane fade " id="statut">

           <div class="row">

            <div class="table-responsive">

              <table class="table_historique2" class="table table-hover" style="padding-top: 20px;">
                    <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                      <tr>
                        <th class="">#</th>
                        <th class=""><?=lang('list_code')?></th>
                        <th class=""><?=lang('th_statut')?></th>
                        <th class=""><?=lang('list_fait_par')?></th>
                        <th class=""><?=lang('list_date')?></th>
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


</main><!-- End #main -->

<?php include VIEWPATH . 'includes/footer.php'; ?>

</body>


<script>

  // Fonction pour le chargement de donnees par defaut
  $(document).ready( function ()
  {
    liste_histo(1);
    liste_histo2(2);

  });
</script>


<script >
  //Fonction pour afficher l'historique d'assurance
    function liste_histo(val)
    {
      var ID = val;
      //alert(ID)
      var DEVICE_ID = $('#DEVICE_ID').val();

      var row_count ="1000000";
      $(".table_historique").DataTable({
        "destroy" : true,
        "processing":true,
        "serverSide":true,
        "destroy":true,
        "oreder":[[ 1, 'asc' ]],
        "ajax":{
          url: "<?php echo base_url('/sim_management/Sim_management/liste_historique1');?>", 
          type:"POST",
          data : {
            DEVICE_ID:DEVICE_ID,
            ID:ID
          },
          beforeSend : function() {
          }
        },
        lengthMenu: [[10,50, 100, -1], [10,50, 100, "All"]],
        pageLength: 10,
        "columnDefs":[{
          "targets":[],
          "orderable":false
        }],
        dom: 'Bfrtlip',
        buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'  ],
        language: {
          "sProcessing":     "Traitement en cours...",
          "sSearch":         "Recherche&nbsp;:",
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


  <script >
  //Fonction pour afficher l'historique d'assurance
    function liste_histo2(val)
    {
      var ID = val;
      //alert(ID)
      var DEVICE_ID = $('#DEVICE_ID').val();

      var row_count ="1000000";
      $(".table_historique2").DataTable({
        "destroy" : true,
        "processing":true,
        "serverSide":true,
        "destroy":true,
        "oreder":[[ 1, 'asc' ]],
        "ajax":{
          url: "<?php echo base_url('/sim_management/Sim_management/liste_historique2');?>", 
          type:"POST",
          data : {
            DEVICE_ID:DEVICE_ID,
            ID:ID
          },
          beforeSend : function() {
          }
        },
        lengthMenu: [[10,50, 100, -1], [10,50, 100, "All"]],
        pageLength: 10,
        "columnDefs":[{
          "targets":[],
          "orderable":false
        }],
        dom: 'Bfrtlip',
        buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'  ],
        language: {
          "sProcessing":     "Traitement en cours...",
          "sSearch":         "Recherche&nbsp;:",
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


</html>