<!DOCTYPE html>
<html lang="en">

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
      <div class="row">

        <center>
          <span class="bi bi-car"></span>
          <h1>Liste des Véhicules</h1>

        </center>
        <div class="justify-content-sm-end d-flex">

          <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('proprietaire/Vehicule/ajouter')?>" ><i class="bi bi-plus"></i> Nouveau</a>
        </div>
      </div>
    </div><!-- End Page Title -->


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
                    <table id="mytable" class="table table-hover" >
                      <thead >
                        <tr>
                          <th class="text-dark">CODE</th>
                          <th class="text-dark">MARQUE</th>
                          <th class="text-dark">MODELE</th>
                          <th class="text-dark">PLAQUE</th>
                          <th class="text-dark">COULEUR</th>
                          <th class="text-dark">STATUT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                          <!-- <th class="text-dark">CHAUFFEUR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th> -->
                          <th class="text-dark">DATE&nbsp;D'ENREGISTREMENT</th>



                          <th class="text-dark">Action</th>
                        </tr>
                      </thead>
                      <tbody class="text-dark">
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
    <!--******** Debut Modal pour attribue une voiture *********-->

    <div class="modal fade" id="carteModal" tabindex="-1" >
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class='modal-header' style='background:cadetblue;color:white;'>      
            <h5 class="modal-title">Attribué la voiture au chauffeur :<a id="NOM"></a>&nbsp;&nbsp;<a id="PRENOM"></a></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="attribution_form" enctype="multipart/form-data" action="#" method="post">
              <div class="modal-body mb-1">
                <div class="row">
                  <input type="hidden" name="VEHICULE_ID" id="VEHICULE_ID">
                  <!--  <input type="hidden" name="VEHICULE_ID" id="code_vehicule">  -->
                  <div class="col-md-6">
                    <label for="description" class="text-dark">Chauffeur</label>
                    <select class="form-control" id="CHAUFFEUR_ID" name="CHAUFFEUR_ID">
                    </select>
                    <span id="errorCHAUFFEUR_ID" class="text-danger"></span>
                  </div>
                  <div class="col-md-6">
                    <label for="description" class="text-dark">Zone d'affectation</label>
                    <select class="form-control" id="CHAUFF_ZONE_AFFECTATION_ID" name="CHAUFF_ZONE_AFFECTATION_ID">
                    </select>
                    <span id="errorCHAUFF_ZONE_AFFECTATION_ID" class="text-danger"></span>
                  </div>
                  <div class="col-md-6">
                    <label type="date" class="text-dark">Date début</label>
                    <input type="date" name="DATE_DEBUT_AFFECTATION" autocomplete="off" id="DATE_DEBUT_AFFECTATION" value="<?= set_value('DATE_DEBUT_AFFECTATION') ?>" onchange="get_date_fin(this.value)" class="form-control"  min="<?= date('Y-m-d')?>">
                    <span id="errorDATE_DEBUT_AFFECTATION" class="text-danger"></span>
                  </div>
                  <div class="col-md-6">
                    <label type="date" class="text-dark">Date fin</label>
                    <input type="date" name="DATE_FIN_AFFECTATION" autocomplete="off" id="DATE_FIN_AFFECTATION" value="<?= set_value('DATE_FIN_AFFECTATION') ?>"  onchange="get_dates_deb(this.value)" class="form-control"  min="<?= date('Y-m-d')?>">
                    <span id="errorDATE_FIN_AFFECTATION" class="text-danger"></span>
                  </div>
                </div>
              </div> 
              <div class="modal-footer">
                <input type="button"class="btn btn-outline-primary rounded-pill " type="button" id="btn_add" value="Attribuer" onclick="save_vehicule();" />
                <!--  <input type="button" class="btn btn-light" data-dismiss="modal" id="cancel" value="Fermer"/> -->

              </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- End Modal-->

    <!--******** Fin Modal pour attribue un voiture ***********-->


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
        url:"<?php echo base_url('proprietaire/Proprietaire_vehicule/listing');?>",
        type:"POST", 
      },
      // lengthMenu: [[10,50, 100, row_count], [10,50, 100, "All"]],
      pageLength: 10,
      "columnDefs":[{
        "targets":[],
        "orderable":false
      }],

      dom: 'Bfrtlip',
      buttons: [
        'pdf', 'print'
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
<script type="text/javascript">

 function attribue_voiture(VEHICULE_ID='')
 {
  $('#VEHICULE_ID').val(VEHICULE_ID);
    // $('#PLAQUE').html(PLAQUE);
    // $('#PRENOM').html(PRENOM);

  $('#CHAUFF_ZONE_AFFECTATION_ID').val(CHAUFF_ZONE_AFFECTATION_ID);
  $('#errorCHAUFFEUR_ID').html('');
  $('#errorCHAUFF_ZONE_AFFECTATION_ID').html('');
  $('#errorDATE_DEBUT_AFFECTATION').html('');
  $('#errorDATE_FIN_AFFECTATION').html('');
  $.ajax(
  {

    url: "<?= base_url() ?>proprietaire/Proprietaire_vehicule/get_all_choffeur/",

    type: "GET",
    dataType: "JSON",
    success: function(data)
    {
      $('#CHAUFFEUR_ID').html(data.html);
      $('#CHAUFF_ZONE_AFFECTATION_ID').html(data.html1);
        // $('#code_vehicule').val(CODE);
      $('#carteModal').modal('show');
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
      alert('Erreur');
    }
  });
}
function save_vehicule()
{
  var statut=1;
  $('#errorCHAUFFEUR_ID').html('');
  $('#errorCHAUFF_ZONE_AFFECTATION_ID').html('');
  $('#errorDATE_DEBUT_AFFECTATION').html('');
  $('#errorDATE_FIN_AFFECTATION').html('');


  if($('#CHAUFFEUR_ID').val()=='')
  {
    $('#errorCHAUFFEUR_ID').html('Le champ est obligatoire');
    statut=2;
  }
  if($('#CHAUFF_ZONE_AFFECTATION_ID').val()=='')
  {
    $('#errorCHAUFF_ZONE_AFFECTATION_ID').html('Le champ est obligatoire');
    statut=2;
  } if($('#DATE_DEBUT_AFFECTATION').val()=='')
  {
    $('#errorDATE_DEBUT_AFFECTATION').html('Le champ est obligatoire');
    statut=2;
  } if($('#DATE_FIN_AFFECTATION').val()=='')
  {
    $('#errorDATE_FIN_AFFECTATION').html('Le champ est obligatoire');
    statut=2;
  }

  if(statut<2)
  {
    var form_data = new FormData($("#attribution_form")[0]);
    var url="<?= base_url('proprietaire/Proprietaire_vehicule/save_choffeur')?>";
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
        if(data==1)
        {
          Swal.fire(
          {
            icon: 'success',
            title: 'Success',
            text: 'Affectation faite avec succès',
            timer: 1500,
          }).then(() =>
          {
            window.location.reload('<?=base_url('proprietaire/Proprietaire_vehicule')?>');
          });
        }
        else if(data==2)
        {
          Swal.fire(
          {
            icon: 'success',
            title: 'Success',
            text: 'Le chauffeur possède déjà une voiture ',
            timer: 1500,
          }).then(() =>
          {
            window.location.reload('<?=base_url('proprietaire/Proprietaire_vehicule')?>');
          });
        }
        else
        {
          Swal.fire(
          {
            icon: 'success',
            title: 'Success',
            text: 'Affectation échouée',
            timer: 1500,
          }).then(() =>
          {
            window.location.reload('<?=base_url('proprietaire/Proprietaire_vehicule')?>');
          });
        }
      }
    });
  }
}
</script>

</html>