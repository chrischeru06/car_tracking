<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>
  <!-- debut ajouter pour afficher bein la photo dans un petit popup -->
  <link href="<?=base_url()?>photoviewer-master/dist/photoviewer.css" rel="stylesheet">

  <style>
    #eye {
      color: black;
    }

    #eye:hover {
      color: blue;
      cursor: pointer;
    }
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
              <h4 class="text-dark text-center" style="margin-bottom: 1px;">Sortie des véhicules</h4>
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

    <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('etat_vehicule/Sortie_Vehicule/ajouter')?>" class="nav-link position-relative"><i class="bi bi-plus"></i> <?=lang('btn_nouveau')?></a>

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
            <button class="nav-link active " data-bs-toggle="tab" data-bs-target="#vehicul_pris">Véhicule en attente de validation</button>
          </li>

          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#vehicul_remis">Véhicule validé</button>
          </li>
          <li class="nav-item">
            <button class="nav-link " data-bs-toggle="tab" data-bs-target="#anomalie">Véhicule refusé<span  style="font-size:10px;position:relative;top:-10px;left:-2px;"></span></button>
          </li>


        </ul>

        <div class="tab-content pt-2">

         <!-- debut taps1 -->

         <!-- <div class="tab-pane fade active" id="vehicul_pris"> -->
           <div class="tab-pane fade show active" id="vehicul_pris">
            <div class="table-responsive">

              <?= $this->session->flashdata('message'); ?>

              <table id="mytable" class="table table-hover text-dark" style="width:100%">
                <thead class="text-dark" style="background-color: rgba(0, 0, 0, 0.075);">
                  <tr>
                    <th class="text-dark">#</th>
                    <th class="text-dark"><?=lang('th_chauffeur')?></th>
                    <th class="text-dark">VEHICULE</th>
                    <th class="text-dark">AUTEUR&nbsp;COURSE</th>
                    <th class="text-dark">DESTINATION</th>

                    <th class="text-dark">DATE</th>
                    <th class="text-dark">HEURE&nbsp;DEPART</th>
                    <th class="text-dark">HEURE&nbsp;RETOUR</th>
                    <th class="text-dark">MOTIF</th>
                    <th class="text-dark">KILOMETRAGE&nbsp;DEPART</th>
                    <th class="text-dark">CARBURANT&nbsp;DEPART</th>

                    <th class="text-dark">CHANGEMENT&nbsp;ETAT</th>
                    <th class="text-dark">STATUT</th>

                    <th class="text-dark">OPTION</th>
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
                    <th class="text-dark"><?=lang('th_chauffeur')?></th>
                    <th class="text-dark">VEHICULE</th>
                    <th class="text-dark">AUTEUR&nbsp;COURSE</th>
                    <th class="text-dark">DESTINATION</th>

                    <th class="text-dark">DATE</th>
                    <th class="text-dark">HEURE&nbsp;DEPART</th>
                    <th class="text-dark">HEURE&nbsp;RETOUR</th>
                    <th class="text-dark">MOTIF</th>
                    <th class="text-dark">KILOMETRAGE&nbsp;DEPART</th>
                    <th class="text-dark">CARBURANT&nbsp;DEPART</th>

                    <th class="text-dark">CHANGEMENT&nbsp;ETAT</th>
                    <th class="text-dark">STATUT</th>

                    <th class="text-dark">OPTION</th>
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
                    <tr>
                    <th class="text-dark">#</th>
                    <th class="text-dark"><?=lang('th_chauffeur')?></th>
                    <th class="text-dark">VEHICULE</th>
                    <th class="text-dark">AUTEUR&nbsp;COURSE</th>
                    <th class="text-dark">DESTINATION</th>

                    <th class="text-dark">DATE</th>
                    <th class="text-dark">HEURE&nbsp;DEPART</th>
                    <th class="text-dark">HEURE&nbsp;RETOUR</th>
                    <th class="text-dark">MOTIF</th>
                    <th class="text-dark">KILOMETRAGE&nbsp;DEPART</th>
                    <th class="text-dark">CARBURANT&nbsp;DEPART</th>

                    <th class="text-dark">CHANGEMENT&nbsp;ETAT</th>
                    <th class="text-dark">STATUT</th>

                    <th class="text-dark">OPTION</th>
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



<!--******** Modal pour le traitement de la demande *********-->

<div class="modal fade" id="Modal_traiter" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'>

        <h5 class="modal-title"><?=lang('modal_title_traiter_dem')?> </h5>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="traite_dem_form" enctype="multipart/form-data" action="#" method="post">
          <div class="modal-body mb-1">
            <div class="row">
              <input type="hidden" name="ID_SORTIE" id="ID_SORTIE">
              <input type="hidden" name="USER_ID" id="USER_ID" value="<?=$this->session->userdata('USER_ID')?>">

              <div class="col-md-12">
                <label for="description"><small><?=lang('i_stat')?></small><span  style="color:red;">*</span></label>
                <select class="form-control" id="IS_VALIDATED" name="IS_VALIDATED" onchange="">
                </select>
                <span id="errorIS_VALIDATED" class="text-danger"></span>
              </div>


              <div class = 'col-md-12'>
                <label><small><?=lang('modal_commentaire')?></small><span  style="color:red;">*</span></label>
                <textarea class='form-control' name ='COMMENTAIRE' id="COMMENTAIRE"></textarea>
                <span id="errorCOMMENTAIRE" class="text-danger"></span>
              </div>
            </div>
          </div> 
          <div class="modal-footer">
            <input type="button"class="btn btn-outline-primary rounded-pill " type="button" id="btn_add" value="<?=lang('btn_traiter')?>" onclick="save_stat_sortie_v();" />
            <!--  <input type="button" class="btn btn-light" data-dismiss="modal" id="cancel" value="Fermer"/> -->

          </div>
        </form>
      </div>
    </div>
  </div>
</div><!-- End Modal-->


<!------------------------ Modal historique demande sortie vehicule' ----------------------->
<div class="row">
  <div class="modal" id="Modal_histo_sortie" role="dialog">
    <div class="modal-dialog modal-lg ">
      <div class="modal-content">
        <div class='modal-header' style='background:cadetblue;color:white;'>

        <h5 class="modal-title">Historique validation sortie véhicule </h5>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table id='table_histo' class="table table-bordered table-striped table-hover table-condensed " style="width: 100%;">
            <thead>
              <tr>
                <th>#</th>
                <th>STATUT</th>
                <th><font style="">COMMENTAIRE</font></th>
                <th><font style="">FAIT PAR</font></th>
                <th><font style="">DATE</font></th>

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
    var IS_VALIDATED = 0;
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
          url:"<?php echo base_url('etat_vehicule/Sortie_Vehicule/listing');?>",
          type:"POST",
          data : {
          IS_VALIDATED:IS_VALIDATED,
        }, 
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
    var IS_VALIDATED = 1;
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
          url:"<?php echo base_url('etat_vehicule/Sortie_Vehicule/listing');?>",
          type:"POST",
          data : {
          IS_VALIDATED:IS_VALIDATED,
        }, 
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
    var IS_VALIDATED = 2;
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
          url:"<?php echo base_url('etat_vehicule/Sortie_Vehicule/listing');?>",
          type:"POST",
          data : {
          IS_VALIDATED:IS_VALIDATED,
        }, 
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

<script>
  //Affichage du modal pour le traitement de la demade
  function traiter_demande(ID_SORTIE)
  {
    $('#Modal_traiter').modal('show');

    $('#ID_SORTIE').val(ID_SORTIE);

    $('#errorIS_VALIDATED').html('');
    $.ajax(
    {
      url: "<?= base_url() ?>etat_vehicule/Sortie_Vehicule/get_all_statut/",

      type: "GET",
      dataType: "JSON",
      success: function(data)
      {

        $('#IS_VALIDATED').html(data);

      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        alert('Erreur');
      }
    });
  }
</script>

<script>
  //Fonction pour appel à l'enregistrement du traitement demande
  function save_stat_sortie_v()
  {
    var statut=1;
    $('#errorCOMMENTAIRE').html('');
    $('#errorIS_VALIDATED').html('');

    if($('#IS_VALIDATED').val()=='')
    {
      $('#errorIS_VALIDATED').html('<?=lang('msg_validation')?>');
      statut=2;
    }
    if($('#COMMENTAIRE').val()=='')
    {
      $('#errorCOMMENTAIRE').html('<?=lang('msg_validation')?>');
      statut=2;
    } 

    if(statut<2)
    {
      var form_data = new FormData($("#traite_dem_form")[0]);
      var url="<?= base_url('etat_vehicule/Sortie_Vehicule/save_stat_demande')?>";
      $.ajax(
      {
        url: url,
        type: 'POST',
        dataType:'JSON',
        data: form_data ,
        contentType: false,
        cache: false,
        processData: false,
        success: function(data)
        {
          if(data == 1)
          {
            Swal.fire(
            {
              icon: 'success',
              title: 'Success',
              text: '<?=lang('msg_traitement_success')?>',
              timer: 1000,
            }).then(() =>
            {
              window.location.reload('<?=base_url('etat_vehicule/Sortie_Vehicule')?>');
            });
          }
          else if(data == 2)
          {
            Swal.fire(
            {
              icon: 'error',
              title: '<?=lang('msg_erreur')?>',
              text: '<?=lang('msg_traitement_echec')?>',
              timer: 1000,
            }).then(() =>
            {
              window.location.reload('<?=base_url('etat_vehicule/Sortie_Vehicule')?>');
            });
          }
          else
          {
            alert('Ene erreur s\'est produite !')
          }
        }
      });
    }
  }
</script>


<!-- script pour l'historique de paiement commande fertilisant-->
<script>
  function get_historique(id)
  {
   $("#Modal_histo_sortie").modal("show");

   var row_count ="1000000";
   table=$("#table_histo").DataTable({
    "processing":true,
    "destroy" : true,
    "serverSide":true,
    "oreder":[[ 0, 'desc' ]],
    "ajax":{
      url:"<?=base_url()?>etat_vehicule/Sortie_Vehicule/getHistorique/"+id,
      type:"POST"
    },
    lengthMenu: [[10,50, 100, row_count], [10,50, 100, "All"]],
    pageLength: 10,
    "columnDefs":[{
      "targets":[],
      "orderable":false
    }],
    dom: 'Bfrtlip',
    buttons: ['excel', 'pdf'],  

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

</html>