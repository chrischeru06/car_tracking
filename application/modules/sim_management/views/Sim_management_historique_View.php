<!DOCTYPE html>
<html lang="en">

<style type="text/css">
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

    <div class="row page-titles mx-0">
      <div class="col-sm-10 p-md-0">
      <h4><i class="fa fa-code"></i> <font>Devices</font></h4>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Devices</a></li>
          <li class="breadcrumb-item ">Historique</li>
        </ol>
      </nav>
    </div>
    <div class="col-md-2">

      <!-- <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('sim_management/Sim_management/ajouter')?>" class="nav-link position-relative"><i class="bi bi-plus"></i> Nouveau</a> -->

    </div>
  </div>

  <section class="section dashboard">

    <div class="row">
      <!-- Left side columns -->
      <div class="col-lg-12">
        <div class="row">

          <!-- Reports -->
          <div class="col-12">
            <div class="card" style="border-radius: 20px;">
              <div class="card-body">

                <?= $this->session->flashdata('message'); ?>

                <div class="table-responsive"><br>

                  <table id="table_historique" class="table table-hover" style="padding-top: 20px;">
                    <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                      <tr>
                        <th class="">#</th>
                        <th class="">CODE</th>
                        <th class="">DATE&nbsp;ACTIVATION&nbsp;FORFAIT</th>
                        <th class="">DATE&nbsp;EXPIRATION&nbsp;FORFAIT</th>
                        <th class="">VALIDITE</th>
                        <th class="">STATUT</th>
                        <th class="">FAIT&nbsp;PAR</th>
                        <th class="">DATE</th>
                      </tr>
                    </thead>
                    <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                    </tbody>
                  </table>
                </div>

              </div>

            </div>
          </div>

          <input type="hidden" name="DEVICE_ID" id="DEVICE_ID" value="<?=$DEVICE_ID?>">

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
    liste_histo();
    //get_nbr_vehicule();

  });
</script>


<script >
  //Fonction pour afficher l'historique d'assurance
    function liste_histo()
    {
      var DEVICE_ID = $('#DEVICE_ID').val();

      var row_count ="1000000";
      $("#table_historique").DataTable({
        "destroy" : true,
        "processing":true,
        "serverSide":true,
        "destroy":true,
        "oreder":[[ 1, 'asc' ]],
        "ajax":{
          url: "<?php echo base_url('/sim_management/Sim_management/liste_historique');?>", 
          type:"POST",
          data : {DEVICE_ID:DEVICE_ID},
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