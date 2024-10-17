<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>
  <link href="<?=base_url()?>photoviewer-master/dist/photoviewer.css" rel="stylesheet">


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

.btn-md:hover{
  background-color: rgba(95, 158, 160,0.3);
  border-radius: 5px;
}
</style>

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
            <div class="col-md-2">
            </div>
            <div class="col-md-6">
             <table>
              <tr>
                <td> 
                  <!-- <img src="<?= base_url()?>template/imagespopup/IconeMuyingajdfss-04.png" width="60px" height="60px" alt=""> -->
                </td>
                <td>  
                  <h4 class="text-dark">Gestionnaires des véhicules</h4>
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

    <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('gestionnaire/Gestionnaire/ajouter')?>" class="nav-link position-relative"><i class="bi bi-plus"></i> <?=lang('btn_nouveau')?></a>

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
            <div class="card-body"><br>

              <?= $this->session->flashdata('message'); ?>

              <div class="table-responsive">
                <table id="mytable" class="table table-hover" style="width:100%;">
                  <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                    <tr>
                      <th class="">#</th>
                      <th class="">IDENTIFICATION</th>
                      <th class="">TELEPHONE</th>
                      <th class="">EMAIL</th>
                      <th class="">PROPRIETAIRE</th>
                      <th class="">STATUT</th>
                      <th class="">DATE</th>
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



      </div>
    </div>
  </div>
</section>

</main><!-- End #main -->


<!--******** Debut Modal pour motif_activation *********-->

<div class="modal fade" id="Modal_activation" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered ">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'>      
        <h5 class="modal-title"><?=lang('title_modal_activation')?>  </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="active_form" enctype="multipart/form-data" action="#" method="post">
          <div class="modal-body mb-1">
            <div class="row">
              <input type="hidden" name="ID_GESTIONNAIRE_VEHICULE_I" id="ID_GESTIONNAIRE_VEHICULE_I">

              <div class="col-md-12" id="div_type">
                <label class="text-dark"><?=lang('label_motif')?> <font color="red">*</font></label>
                <select class="form-control" id="ID_MOTIF" name="ID_MOTIF" >
                  <option value="">-- <?=lang('selectionner')?> --</option>
                  <?php
                  foreach ($motif_ativ as $key) 
                  {
                    echo "<option value=".$key['ID_MOTIF'].">".$key['DESC_MOTIF']."</option>";
                  }
                  ?>
                </select>

                <font class="text-danger" id="errorID_MOTIF"></font>
              </div>

            </div>
          </div> 
          <div class="modal-footer">
            <input type="button"class="btn btn-outline-primary rounded-pill " type="button" id="btn_add" value="<?=lang('btn_active')?>" onclick="save_motif_active();" />
            <!--  <input type="button" class="btn btn-light" data-dismiss="modal" id="cancel" value="Fermer"/> -->

          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- fin modal motif_activation -->

<!--******** Debut Modal pour motif_desactivationa *********-->

<div class="modal fade" id="Modal_desactivation" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered ">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'>      
        <h5 class="modal-title"><?=lang('modal_desactivation')?>  </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="desactive_form" enctype="multipart/form-data" action="#" method="post">
          <div class="modal-body mb-1">
            <div class="row">
              <input type="hidden" name="ID_GESTIONNAIRE_VEHICULE" id="ID_GESTIONNAIRE_VEHICULE">

              <div class="col-md-12" id="div_type">
                <label class="text-dark"><?=lang('label_motif')?> <font color="red">*</font></label>
                <select class="form-control" id="ID_MOTIF_des" name="ID_MOTIF_des">
                  <option value="">-- <?=lang('selectionner')?> --</option>
                  <?php
                  foreach ($motif_des as $key) 
                  {
                    echo "<option value=".$key['ID_MOTIF'].">".$key['DESC_MOTIF']."</option>";
                  }
                  ?>
                </select>

                <font class="text-danger" id="errorID_MOTIF_des"></font>
              </div>

            </div>
          </div> 
          <div class="modal-footer">
            <input type="button"class="btn btn-outline-primary rounded-pill " type="button" id="btn_add" value="<?=lang('checkbox_desactiver')?>" onclick="save_motif_desactive();" />

          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- fin modal motif_desactivation -->

<?php include VIEWPATH . 'includes/footer.php'; ?>

<script src="<?=base_url()?>photoviewer-master/dist/photoviewer.js"></script>

</body>


<script>

  // Fonction pour le chargement de donnees par defaut
  $(document).ready( function ()
  {

    $('#close').hide();
    $('#check').hide();

    listing();

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

  //Fonction pour l'affichage
  function listing()
  {
    var CHECK_VALIDE = $('#CHECK_VALIDE').val();

    if(CHECK_VALIDE == 1 || CHECK_VALIDE == 3)
    {
      $('#close').hide();
      $('#check').show();
    }
    else if(CHECK_VALIDE == 2 || CHECK_VALIDE == 4)
    {
      $('#close').show();
      $('#check').hide();
    }
    else
    {
      $('#close').hide();
      $('#check').hide();
    }


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
          url:"<?php echo base_url('gestionnaire/Gestionnaire/listing');?>",
          type:"POST",
          data : {},
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
  // Fonction pour désactiver
 function statut_desactive(ID_GESTIONNAIRE_VEHICULE) {
  var ID_GESTIONNAIRE_VEHICULE=$('#ID_GESTIONNAIRE_VEHICULE').val(ID_GESTIONNAIRE_VEHICULE);

  $('#Modal_desactivation').modal('show'); 
}
function save_motif_desactive() {
  var statut=1;
  $('#errorID_MOTIF_des').html('');

  if($('#ID_MOTIF_des').val()=='')
  {
    $('#errorID_MOTIF_des').html('<?=lang('msg_validation')?>');
    statut=2;
  }
  if (statut==1) {
   var checkBox = document.getElementById("myCheck");
   var status=$('#status').val();

   status = 1;

   var form_data = new FormData($("#desactive_form")[0]);
   $.ajax(
   {
    url:"<?=base_url()?>gestionnaire/Gestionnaire/active_desactive/"+status,
    type: 'POST',
    dataType:'JSON',
    data: form_data ,
    contentType: false,
    cache: false,
    processData: false,
    success: function(data)
    {

     if(data.status==2)
     {
      Swal.fire(
      {
        icon: 'success',
        title: 'Success',
        text: '<?=lang('swal_desactive_proprio')?>',
        timer: 1500,
      }).then(() =>
      {
       window.location.href='<?=base_url('')?>gestionnaire/Gestionnaire';
     });
    }
  }
});
 }
 

}
</script>


<script>
  // Fonction pour l'activatio
 function statut_active(ID_GESTIONNAIRE_VEHICULE) {
  var ID_GESTIONNAIRE_VEHICULE=$('#ID_GESTIONNAIRE_VEHICULE_I').val(ID_GESTIONNAIRE_VEHICULE);

  $('#Modal_activation').modal('show'); 
}
function save_motif_active() {
  var statut=1;
  $('#errorID_MOTIF').html('');

  if($('#ID_MOTIF').val()=='')
  {
    $('#errorID_MOTIF').html('Le champ est obligatoire');
    statut=2;
  }
  if (statut==1) {

    var checkBox = document.getElementById("myCheck");
    var status=$('#status').val();
    status = 2;

    var form_data = new FormData($("#active_form")[0]);
    $.ajax(
    {
      url:"<?=base_url()?>gestionnaire/Gestionnaire/active_desactive/"+status,
      type: 'POST',
      dataType:'JSON',
      data: form_data ,
      contentType: false,
      cache: false,
      processData: false,
      success: function(data)
      {

       if(data.status==1)
       {
        Swal.fire(
        {
          icon: 'success',
          title: 'Success',
          text: '<?=lang('swal_active_proprio')?>',
          timer: 1500,
        }).then(() =>
        {
         window.location.href='<?=base_url('')?>gestionnaire/Gestionnaire';
       });
      }
    }
  });
  }
  

}
</script>


</html>