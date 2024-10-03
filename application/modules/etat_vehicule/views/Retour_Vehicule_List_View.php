<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>
  <style type="text/css">

    .btn-md:hover{
      background-color: rgba(210, 232, 249,100);
      border-radius: 5px;
    }
    .dashboard .table-responsive .dropdown .dropdown-menu  li {
     margin: 1rem 0; 
     padding: 10px 0 10px 40px;
   }

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
  </div>
  <div class="pagetitle">
   <div class="row page-titles mx-0">
    <div class="col-sm-10 p-md-0">
      <div class="welcome-text">
        <center>
         <table>
          <tr>

            <td>  
              <h1 class="text-center" style="margin-bottom: 1px;"><font class="fa fa-list" style="font-size:18px;"></font> Remise des vehicules</h1>
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
  <div class="col-md-2">

    <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('etat_vehicule/Retour_Vehicule/ajouter')?>" class="nav-link position-relative"><i class="bi bi-plus"></i> <?=lang('btn_nouveau')?></a>

  </div>
</div>
</div>


<!-- End Page Title -->

  <!-- debut modal pour retirer la voiture -->
 <!--  <div class="modal fade" id="retirvoiture" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">


        <div class="modal-body mb-1">
         <center><h5><strong style='color:black'><?=lang('modal_retirer_cond')?> </strong> <br><b style='background-color:prink;color:green;'> </b></h5></center>
       </div>
       <div class="modal-footer">
        <input type="button" class="btn btn-light" data-dismiss="modal" id="cancel" value="<?=lang('btn_fermer')?>"/>

        
      </div>
    </div>
  </div>
</div> -->
<!-- fin modal pour retirer la voiture -->





<section class="section dashboard">
  <div class="row">

    <!-- Left side columns -->
    <div class="col-lg-12">
      <div class="row">


        <!-- Reports -->
        <div class="col-12">
          <div class="card">


            <div class="card-body">

              <div class="table-responsive" style="padding-top: 20px;">

                <table id="mytable" class="table table-hover" style="width:100%;">
                  <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                    <tr>

                      <th class="text-dark">#</th>
                      <th class="text-dark"><?=lang('th_chauffeur')?></th>
                      <th class="text-dark">Heure&nbsp;retour</th>
                      <th class="text-dark">Kilometrage&nbsp;retour</th>
                      <th class="text-dark">Caburant&nbsp;retour</th>
                      <th class="text-dark">Commentaire</th>
                      <th class="text-dark">Statut</th>
                      <th class="text-dark">Commentaire&nbsp;validation</th>
                      <th class="text-dark">Options</th>

                     


                     <!--  <th class="text-dark"><?=lang('th_tlphone')?></th>
                      <th class="text-dark"><?=lang('th_email')?></th>
                      <th class="text-dark"><?=lang('th_statut')?></th>
                      <th class="text-dark"><?=lang('th_options')?></th> -->
                    </tr>
                  </thead>
                  
                  <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                  </tbody>
                </table>
              </div>

            </div>

          </div>
        </div>



      </div>
    </div>



  </div>
</section>

</main><!-- End #main -->

<?php include VIEWPATH . 'includes/footer.php'; ?>

</body>


<script>

  // Fonction pour le chargement de donnees par defaut
  $(document).ready( function ()
  {
    liste();

  });

  function liste()
  {
    var row_count=10000;
    $('#message').delay('slow').fadeOut(10000);
    $(document).ready(function()
    {
    // var row_count ="1000000";

      $("#mytable").DataTable({
        "processing":true,
        "destroy" : true,
        "serverSide":true,
        "oreder":[[ 0, 'desc' ]],
        "ajax":{
          url:"<?php echo base_url('etat_vehicule/Retour_Vehicule/listing');?>",
          type:"POST", 
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