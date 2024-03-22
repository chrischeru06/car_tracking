<!DOCTYPE html>
<html lang="en">

<style type="text/css">
  #eye{
    color: black;
  }
  #eye:hover {
    color: blue;
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
        <div class="welcome-text">
          <center>
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
             <table>
              <tr>
                <td> 
                  <!-- <img src="<?= base_url()?>template/imagespopup/IconeMuyingajdfss-04.png" width="60px" height="60px" alt=""> -->
                </td>
                <td>  
                  <h4 class="text-dark">Liste des véhicules</h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                  <!-- <li class="breadcrumb-item"><a href="#">Véhicule</a></li>
                    <li class="breadcrumb-item"><a href="#">Liste</a></li> -->
                    <!-- <li class="breadcrumb-item active" aria-current="page">Saving slides</li> -->
                  </ol>
                </nav>
              </td>
            </tr>
          </table>
        </div>
      </center>
    </div>
  </div>
  <div class="col-md-2">

    <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('vehicule/Vehicule/ajouter')?>" class="nav-link position-relative"><i class="bi bi-plus"></i> Nouveau</a>

  </div>
</div>

<section class="section dashboard">
  <div class="row">

    <!-- Left side columns -->
    <div class="col-lg-12">
      <div class="row">


        <!-- Reports -->
        <div class="col-12">
          <div class="card">
            <div class="card-body">

              <?= $this->session->flashdata('message'); ?>

              <div class="table-responsive">
                <table id="mytable" class="table table-hover" style="width:100%;">
                  <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                    <tr>
                    <!--   <th class="">CODE</th> -->
                      <th class="">#</th>
                     <th class="">PROPRIETAIRE</th>
                      <th class="">MARQUE</th>
                     <!--  <th class="">MODELE</th> -->
                      <th class="">PLAQUE</th>
                      <th class="">COULEUR</th>
                      <!-- <th class="">CONSOMMATION</th> -->
                      <th class="">DATE&nbsp;D'ENREGISTREMENT</th>
                      <th class="">TRAITEMENT&nbsp;DEMANDE</th>
                      <th class="">STATUT</th>
                      <th class="">ACTION</th>
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

<div class="modal fade" id="Modal_traiter" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class='modal-header' style='background:cadetblue;color:white;'>      <!-- <h5 class="modal-title">Traiter la demande de :<a id="NOM"></a>&nbsp;&nbsp;<a id="PRENOM"></a></h5>

        --><h5 class="modal-title">Traiter la demande  de</h5>


        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="attribution_form" enctype="multipart/form-data" action="#" method="post">
          <div class="modal-body mb-1">
            <div class="row">
              <input type="hidden" name="VEHICULE_ID" id="VEHICULE_ID">
              <input type="hidden" name="STATUT_VEH_AJOUT" id="STATUT_VEH_AJOUT">


              <div class="col-md-6">
                <label for="description" class="text-dark">Statut</label>
                <select class="form-control" id="TRAITEMENT_DEMANDE_ID" name="TRAITEMENT_DEMANDE_ID">
                </select>
                <span id="errorTRAITEMENT_DEMANDE_ID" class="text-danger"></span>
              </div>
              <div class = 'col-md-4'>

                <label style='color:black'>Commentaire</label>
                <textarea class='form-control' name ='COMMENTAIRE' id="COMMENTAIRE"></textarea>
                <span id="errorCOMMENTAIRE" class="text-danger"></span>

              </div>
            </div>
          </div> 
          <div class="modal-footer">
            <input type="button"class="btn btn-outline-primary rounded-pill " type="button" id="btn_add" value="Traiter" onclick="save_statut_vehicul();" />
            <!--  <input type="button" class="btn btn-light" data-dismiss="modal" id="cancel" value="Fermer"/> -->

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
        url:"<?php echo base_url('vehicule/Vehicule/listing');?>",
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

<script>
  // Fonction pour activer le statut du véhicule
  function statut_active(VEHICULE_ID) {
    var checkBox = document.getElementById("myCheck");
    var status=$('#status').val();
    status = 2;

    var form_data = new FormData($("#myform_check")[0]);
    $.ajax(
    {
      url:"<?=base_url()?>vehicule/Vehicule/active_desactive/"+status+'/'+VEHICULE_ID,
      type: 'POST',
      dataType:'JSON',
      data: form_data ,
      contentType: false,
      cache: false,
      processData: false,
      success: function(data)
      {
       window.location.href='<?=base_url('')?>vehicule/Vehicule';
     }
   });

  }
</script>

<script>
  // Fonction pour désactiver le statut du véhicule
  function statut_desactive(VEHICULE_ID) {
    var checkBox = document.getElementById("myCheck");
    var status=$('#status').val();

    status = 1;

    var form_data = new FormData($("#myform_check")[0]);
    $.ajax(
    {
      url:"<?=base_url()?>vehicule/Vehicule/active_desactive/"+status+'/'+VEHICULE_ID,
      type: 'POST',
      dataType:'JSON',
      data: form_data ,
      contentType: false,
      cache: false,
      processData: false,
      success: function(data)
      {
       window.location.href='<?=base_url('')?>vehicule/Vehicule';
     }
   });

  }
</script>

<script type="text/javascript">
 function traiter_demande(VEHICULE_ID='',STATUT_VEH_AJOUT='')

 {
  $('#Modal_traiter').modal('show');
    //VEHICULE_ID du 2eme ca vient du VEHICULE_ID du paramentre la en haut 
  $('#VEHICULE_ID').val(VEHICULE_ID);
  $('#STATUT_VEH_AJOUT').val(STATUT_VEH_AJOUT);

    // var TRAITEMENT_DEMANDE_ID=$('#TRAITEMENT_DEMANDE_ID').val();
  $('#errorVEHICULE_ID').html('');
  $('#errorTRAITEMENT_DEMANDE_ID').html('');
  $.ajax(
  {
    url: "<?= base_url() ?>vehicule/Vehicule/get_all_statut/",

    type: "GET",
    dataType: "JSON",
    success: function(data)
    {

      $('#TRAITEMENT_DEMANDE_ID').html(data);
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
      alert('Erreur');
    }
  });
}

function save_statut_vehicul()
{

  var statut=1;
  $('#errorCOMMENTAIRE').html('');
  $('#errorTRAITEMENT_DEMANDE_ID').html('');

  if($('#TRAITEMENT_DEMANDE_ID').val()=='')
  {
    $('#errorTRAITEMENT_DEMANDE_ID').html('Le champ est obligatoire');
    statut=2;
  }
  if($('#COMMENTAIRE').val()=='')
  {
    $('#errorCOMMENTAIRE').html('Le champ est obligatoire');
    statut=2;
  } 
  if(statut<2)
  {
    var form_data = new FormData($("#attribution_form")[0]);
    var url="<?= base_url('vehicule/Vehicule/save_stat_vehicul')?>";
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
            text: 'Traitement fait avec succès',
            timer: 1500,
          }).then(() =>
          {
            window.location.reload('<?=base_url('vehicule/Vehicule')?>');
          });
        }
        else if(data==2)
        {
          Swal.fire(
          {
            icon: 'success',
            title: 'Success',
            text: 'Le vehicule possède déjà chauffeur ',
            timer: 1500,
          }).then(() =>
          {
            window.location.reload('<?=base_url('vehicule/Vehicule')?>');
          });
        }
        else
        {
          Swal.fire(
          {
            icon: 'success',
            title: 'Success',
            text: 'Traitement échoué',
            timer: 1500,
          }).then(() =>
          {
            window.location.reload('<?=base_url('vehicule/Vehicule')?>');
          });
        }
      }
    });
  }
}
</script>

</html>