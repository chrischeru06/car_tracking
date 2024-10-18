<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>
  <!-- debut ajouter pour afficher bein la photo dans un petit popup -->
  <link href="<?=base_url()?>photoviewer-master/dist/photoviewer.css" rel="stylesheet">

  <style>
    .photoviewer-modal {
      background-color: transparent;
      border: none;
      border-radius: 0;
      box-shadow: 0 0 6px 2px rgba(0, 0, 0, .3);
    }

    .photoviewer-header .photoviewer-toolbar {
      background-color: rgba(0, 0, 0, .5);
    }

    .photoviewer-stage {
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      background-color: rgba(0, 0, 0, .85);
      border: none;
    }

    .photoviewer-footer .photoviewer-toolbar {
      background-color: rgba(0, 0, 0, .5);
      border-top-left-radius: 5px;
      border-top-right-radius: 5px;
    }

    .photoviewer-header,
    .photoviewer-footer {
      border-radius: 0;
      pointer-events: none;
    }

    .photoviewer-title {
      color: #ccc;
    }

    .photoviewer-button {
      color: #ccc;
      pointer-events: auto;
    }

    .photoviewer-header .photoviewer-button:hover,
    .photoviewer-footer .photoviewer-button:hover {
      color: white;
    }
  </style>
   <!-- fin ajouter pour afficher bein la photo dans un petit popup -->

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
                <li class="nav-item">
                          <button class="nav-link active " data-bs-toggle="tab" data-bs-target="#vehicul_attente">Véhicule en attente de validation</button>
                        </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#vehicul_valide">Véhicule validé</button>
                </li>
                 <li class="nav-item">
                  <button class="nav-link " data-bs-toggle="tab" data-bs-target="#vehicul_refuse">Véhicule refusé<span  style="font-size:10px;position:relative;top:-10px;left:-2px;"></span></button>
                </li>


              </ul>

              <div class="tab-content pt-2">

             <!-- debut taps1 -->

             <!-- <div class="tab-pane fade active" id="vehicul_pris"> -->
               <div class="tab-pane fade show active" id="vehicul_attente">
                <div class="table-responsive">

                  <table id="mytable" class="table table-hover text-dark" style="width:100%">
                    <thead class="text-dark" style="background-color: rgba(0, 0, 0, 0.075);">
                      <tr>
                      <th class="text-dark">#</th>
                      <th class="text-dark"><?=lang('th_chauffeur')?></th>
                      <th class="text-dark">Destination</th>

                      <th class="text-dark">Heure&nbsp;départ</th>
                      <th class="text-dark">Heure&nbsp;retour</th>
                      <th class="text-dark">Date&nbsp;course</th>
                      <th class="text-dark">Motif&nbsp;course</th>

                      <th class="text-dark">Kilometrage&nbsp;départ</th>
                      <th class="text-dark">Caburant&nbsp;départ</th>
                      <th class="text-dark">Image&nbsp;avant</th>
                      <th class="text-dark">Image&nbsp;arrière</th>
                      <th class="text-dark">Latérale&nbsp;gauche</th>
                      <th class="text-dark">Latérale&nbsp;droite</th>
                      <th class="text-dark">Tableau&nbsp;de&nbsp;bord</th>
                      <th class="text-dark">Siège&nbsp;avant</th>
                      <th class="text-dark">Siège&nbsp;arrière</th>
                      <th class="text-dark">Statut</th>

                      <th class="text-dark">Commentaire</th>
                      <th class="text-dark">Options</th>

                    </tr>
                    </thead>
                    <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                    </tbody>
                  </table>
                </div>
              </div>
            <!--  fin taps1 -->

             <!--  debut taps2 -->
               <div class="tab-pane fade  pt-3" id="vehicul_valide">

                <div class="table-responsive">

                  <table id="mytable2" class="table table-hover text-dark" style="width:100%">
                    <thead class="text-dark" style="background-color: rgba(0, 0, 0, 0.075);">
                        <tr>
                    <th class="text-dark">#</th>
                      <th class="text-dark"><?=lang('th_chauffeur')?></th>
                      <th class="text-dark">Destination</th>

                      <th class="text-dark">Heure&nbsp;départ</th>
                      <th class="text-dark">Heure&nbsp;retour</th>
                      <th class="text-dark">Date&nbsp;course</th>
                      <th class="text-dark">Motif&nbsp;course</th>

                      <th class="text-dark">Kilometrage&nbsp;départ</th>
                      <th class="text-dark">Caburant&nbsp;départ</th>
                      <th class="text-dark">Image&nbsp;avant</th>
                      <th class="text-dark">Image&nbsp;arrière</th>
                      <th class="text-dark">Latérale&nbsp;gauche</th>
                      <th class="text-dark">Latérale&nbsp;droite</th>
                      <th class="text-dark">Tableau&nbsp;de&nbsp;bord</th>
                      <th class="text-dark">Siège&nbsp;avant</th>
                      <th class="text-dark">Siège&nbsp;arrière</th>
                      <th class="text-dark">Statut</th>

                      <th class="text-dark">Commentaire</th>
                      <th class="text-dark">Options</th>

                    </tr>
                    </thead>
                    <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                    </tbody>
                  </table>
                </div>
              </div>
              
           <!--    fin taps2 -->


              <!--     debut taps3 -->

              <div class="tab-pane fade  pt-3" id="vehicul_refuse">

                <div class="table-responsive">

                  <table id="mytable3" class="table table-hover text-dark" style="width:100%">
                    <thead class="text-dark" style="background-color: rgba(0, 0, 0, 0.075);">
                      <tr>
                    <th class="text-dark">#</th>
                      <th class="text-dark"><?=lang('th_chauffeur')?></th>
                      <th class="text-dark">Destination</th>

                      <th class="text-dark">Heure&nbsp;départ</th>
                      <th class="text-dark">Heure&nbsp;retour</th>
                      <th class="text-dark">Date&nbsp;course</th>
                      <th class="text-dark">Motif&nbsp;course</th>

                      <th class="text-dark">Kilometrage&nbsp;départ</th>
                      <th class="text-dark">Caburant&nbsp;départ</th>
                      <th class="text-dark">Image&nbsp;avant</th>
                      <th class="text-dark">Image&nbsp;arrière</th>
                      <th class="text-dark">Latérale&nbsp;gauche</th>
                      <th class="text-dark">Latérale&nbsp;droite</th>
                      <th class="text-dark">Tableau&nbsp;de&nbsp;bord</th>
                      <th class="text-dark">Siège&nbsp;avant</th>
                      <th class="text-dark">Siège&nbsp;arrière</th>
                      <th class="text-dark">Statut</th>

                      <th class="text-dark">Commentaire</th>
                      <th class="text-dark">Options</th>

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
    // var ID_OPERATION=1;
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
          url:"<?php echo base_url('gestionnaire/Sortie_Vehicule/listing');?>",
          type:"POST",
          data : {}, 
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
    // var ID_OPERATION=1;
    $('#message').delay('slow').fadeOut(10000);
    $(document).ready(function()
    {
      var row_count ="1000000";

      $("#mytable2").DataTable({
        "processing":true,
        "destroy" : true,
        "serverSide":true,
        "oreder":[[ 0, 'desc' ]],
        "ajax":{
          url:"<?php echo base_url('gestionnaire/Sortie_Vehicule/listing2');?>",
          type:"POST",
          data : {}, 
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
    // var ID_OPERATION=1;
    $('#message').delay('slow').fadeOut(10000);
    $(document).ready(function()
    {
      var row_count ="1000000";

      $("#mytable3").DataTable({
        "processing":true,
        "destroy" : true,
        "serverSide":true,
        "oreder":[[ 0, 'desc' ]],
        "ajax":{
          url:"<?php echo base_url('gestionnaire/Sortie_Vehicule/listing3');?>",
          type:"POST",
          data : {}, 
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

<script src="<?=base_url()?>photoviewer-master/dist/photoviewer.js"></script>

<script>
    $(document).ready(function () {
  // Déléguer l'événement de clic pour les éléments générés dynamiquement
  $(document).on('click', '[data-gallery=photoviewer]', function (e) {
    e.preventDefault();

    var items = [];

    // Ajouter chaque élément à l'array `items`
    $('[data-gallery=photoviewer]').each(function () {
      items.push({
        src: $(this).attr('href'),
        title: $(this).attr('data-title')
      });
    });

    // Obtenir l'index de l'élément cliqué
    var index = $(this).index('[data-gallery=photoviewer]');

    // Initialiser le PhotoViewer avec les éléments et définir l'index
    var options = {
      index: index // Définir l'index pour démarrer à partir de l'élément cliqué
    };

    new PhotoViewer(items, options);
  });
});


</script>
 <!-- fin script  pour afficher bien la photo dans un petit popup -->


 </html>