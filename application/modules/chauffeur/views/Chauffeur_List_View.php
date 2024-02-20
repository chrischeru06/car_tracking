<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>
  <style type="text/css">

    /* The switch - the box around the slider */
    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 26px;
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
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
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
     <span style="margin-right: 15px">
      <div class="col-sm-3" style="float:right;">
        <a href="<?=base_url('chauffeur/Chauffeur/ajouter')?>" style="width: 100px;" class='btn btn-primary btn-sm float-right'>
          <i class="fa fa-plus"></i>
          Nouveau
        </a>
      </div>
    </span>


    <div class="pagetitle">
      <h1>Chauffeur</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Chauffeur</a></li>
          <li class="breadcrumb-item active">Liste</li>
        </ol>
      </nav>
    </div>
   

    <!-- End Page Title -->
    <!--******** Debut Modal pour attribue une voiture *********-->
<div class="modal fade" id="carteModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-center" id="exampleModalLabel">Attribution d'une voiture</h3>
        <button type="button"  class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div>
        <form id="attribution_form" enctype="multipart/form-data" action="#" method="post">
          <div class="modal-body mb-1">
            <div class="row">
              <!-- <input type="hidden" name="code_vehicule" id="code_vehicule"> -->
              <input type="hidden" name="CHAUFFEUR_ID" id="CHAUFFEUR_ID">

              <div class="col-md-6">
                <label for="description" class="text-dark">Voiture</label>
                <select class="form-control" id="VEHICULE_ID" name="VEHICULE_ID">
                </select>
                <span id="errorVEHICULE_ID" class="text-danger"></span>
              </div>
              <div class="col-md-6">
                <label for="description" class="text-dark">Zone d'affectation</label>
                <select class="form-control" id="CHAUFF_ZONE_AFFECTATION_ID" name="CHAUFF_ZONE_AFFECTATION_ID">
                </select>
                <span id="errorCHAUFF_ZONE_AFFECTATION_ID" class="text-danger"></span>
              </div>

               <div class="col-md-6">
                <label type="date" class="text-dark">Date début</label>
                <input type="date" name="DATE_DEBUT_AFFECTATION" autocomplete="off" id="DATE_DEBUT_AFFECTATION" value="<?= set_value('DATE_DEBUT_AFFECTATION') ?>"  class="form-control"  min="<?= date('Y-m-d')?>">
                <span id="errorDATE_DEBUT_AFFECTATION" class="text-danger"></span>
              </div>
               <div class="col-md-6">
                <label type="date" class="text-dark">Date fin</label>
                <input type="date" name="DATE_FIN_AFFECTATION" autocomplete="off" id="DATE_FIN_AFFECTATION" value="<?= set_value('DATE_FIN_AFFECTATION') ?>"  class="form-control"  min="<?= date('Y-m-d')?>">
                <span id="errorDATE_FIN_AFFECTATION" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-secondary" type="button" id="btn_add" value="Attribuer" onclick="save_vehicule();" />
            <input type="button" class="btn btn-light" data-dismiss="modal" id="cancel" value="Fermer"/>
            
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--******** Fin Modal pour attribue un voiture ***********-->

   <!--******** Debut Modal pour modifier l'affectation du chauffeur a une voiture *********-->
<div class="modal fade" id="modifvoitureModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-center" id="exampleModalLabel2">Modifier l'affectation du chauffeur</h3>
        <button type="button"  class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div>
        <form id="modf_affect_form" enctype="multipart/form-data" action="#" method="post">
          <div class="modal-body mb-1">
            <div class="row">
              <!-- <input type="hidden" name="code_vehicule" id="code_vehicule"> -->
              <input type="hidden" name="CHAUFFEUR_ID_MOD" id="CHAUFFEUR_ID_MOD">

              <div class="col-md-6">
                <label for="description" class="text-dark">Zone d'affectation</label>
                <select class="form-control" id="CHAUFF_ZONE_AFFECTATION_ID_MOD" name="CHAUFF_ZONE_AFFECTATION_ID_MOD">
                </select>
                
                <span id="errorCHAUFF_ZONE_AFFECTATION_ID_MOD" class="text-danger"></span>
              </div>

               <div class="col-md-6">
                <label type="date" class="text-dark">Date début</label>
                <input type="date" name="DATE_DEBUT_AFFECTATION_MOD" autocomplete="off" id="DATE_DEBUT_AFFECTATION_MOD"  value="<?= set_value('DATE_DEBUT_AFFECTATION_MOD') ?>"   class="form-control"  min="<?= date('Y-m-d')?>">
                <span id="errorDATE_DEBUT_AFFECTATION_MOD" class="text-danger"></span>
              </div>
               <div class="col-md-6">
                <label type="date" class="text-dark">Date fin</label>
                <input type="date" name="DATE_FIN_AFFECTATION_MOD" autocomplete="off" id="DATE_FIN_AFFECTATION_MOD" value="<?= set_value('DATE_FIN_AFFECTATION_MOD') ?>" class="form-control"  min="<?= date('Y-m-d')?>">
                <span id="errorDATE_FIN_AFFECTATION_MOD" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="button" class="btn btn-secondary" type="button" id="btn_add" value="Modifier" onclick="save_modif_chauffeur();" />
            <input type="button" class="btn btn-light" data-dismiss="modal" id="cancel" value="Fermer"/>
            
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--******** Fin Modal pour modifier l'affectation du chauffeur a une voiture  ***********-->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">


            <!-- Reports -->
            <div class="col-12">
              <div class="card">


                <div class="card-body">

                  <div class="table-responsive" style="padding-top: 60px;">
                    <table id="mytable" class="table table-hover" >
                      <thead >
                        <tr>
    
                          <th class="text-dark">#</th>
                          <th class="text-dark">Chauffeur&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                      
                          <!-- <th class="text-dark">ADRESSE</th> -->
                          <th class="text-dark">Province&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                          <th class="text-dark">Commune&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                          <th class="text-dark">Zonne&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                          <th class="text-dark">Colline&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                          <th class="text-dark">Telephone&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                          <th class="text-dark">Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                          <th class="text-dark">Statut&nbsp;&nbsp;&nbsp;&nbsp;</th>
                          <th class="text-dark">Statut&nbsp;&nbsp;&nbsp;</th>
  
                          <th class="text-dark">Option</th>
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
        url:"<?php echo base_url('chauffeur/Chauffeur/listing');?>",
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
  function attribue_voiture(CHAUFFEUR_ID)

  {
    // alert(CHAUFFEUR_ID)
    // var CHAUFFEUR_ID = $CHAUFFEUR_ID;
    $('#CHAUFFEUR_ID').val(CHAUFFEUR_ID);
    $('#CHAUFF_ZONE_AFFECTATION_ID').val(CHAUFF_ZONE_AFFECTATION_ID);
    $('#errorVEHICULE_ID').html('');
     $('#errorCHAUFF_ZONE_AFFECTATION_ID').html('');
    $('#errorDATE_DEBUT_AFFECTATION').html('');
    $('#errorDATE_FIN_AFFECTATION').html('');
    $.ajax(
    {
      // url : "<?php echo base_url('chauffeur/Chauffeur/get_all_voiture')?>",
     url: "<?= base_url() ?>chauffeur/Chauffeur/get_all_voiture/",

      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
        $('#VEHICULE_ID').html(data.html);
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
    $('#errorVEHICULE_ID').html('');
    $('#errorCHAUFF_ZONE_AFFECTATION_ID').html('');
    $('#errorDATE_DEBUT_AFFECTATION').html('');
    $('#errorDATE_FIN_AFFECTATION').html('');

    if($('#code_vehicule').val()=='')
    {
      $('#errorVEHICULE_ID').html('Actualise ta page');
      statut=2;
    }

    if($('#VEHICULE_ID').val()=='')
    {
      $('#errorVEHICULE_ID').html('Le champ est obligatoire');
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
      var url="<?= base_url('chauffeur/Chauffeur/save_voiture')?>";
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
              text: 'Attribution faite avec succès',
              timer: 1500,
            }).then(() =>
            {
              window.location.reload('<?=base_url('chauffeur/Chauffeur')?>');
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
              window.location.reload('<?=base_url('chauffeur/Chauffeur')?>');
            });
          }
          else
          {
            Swal.fire(
            {
              icon: 'success',
              title: 'Success',
              text: 'Attribution échouée',
              timer: 1500,
            }).then(() =>
            {
              window.location.reload('<?=base_url('chauffeur/Chauffeur')?>');
            });
          }
        }
      });
    }
  }

  function modif_affectation(CHAUFFEUR_ID)

  {
    // alert(CHAUFFEUR_ID)
     var CHAUFFEUR_ID = CHAUFFEUR_ID;
    $('#CHAUFFEUR_ID_MOD').val(CHAUFFEUR_ID);
    $('#CHAUFF_ZONE_AFFECTATION_ID_MOD').val(CHAUFF_ZONE_AFFECTATION_ID);
    $('#errorVEHICULE_ID').html('');
     $('#errorCHAUFF_ZONE_AFFECTATION_ID_MOD').html('');
    $('#errorDATE_DEBUT_AFFECTATION_MOD').html('');
    $('#errorDATE_FIN_AFFECTATION_MOD').html('');
    $.ajax(
    {
     url: "<?= base_url() ?>chauffeur/Chauffeur/get_zone_affect/"+CHAUFFEUR_ID,

      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
        //alert(data.htmldbut)
        $('#CHAUFF_ZONE_AFFECTATION_ID_MOD').html(data.html1);
        $('#DATE_DEBUT_AFFECTATION_MOD').val(data.htmldbut);
        $('#DATE_FIN_AFFECTATION_MOD').val(data.htmlfin);

        // $('#code_vehicule').val(CODE);
        $('#modifvoitureModal').modal('show');
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        alert('Erreur');
      }
    });
  }

  //save modification affectation chauffeur
    function save_modif_chauffeur()
  {

    var statut=1;
    $('#errorCHAUFF_ZONE_AFFECTATION_ID_MOD').html('');
    $('#errorDATE_DEBUT_AFFECTATION_MOD').html('');
    $('#errorDATE_FIN_AFFECTATION_MOD').html('');

     if($('#CHAUFF_ZONE_AFFECTATION_ID_MOD').val()=='')
    {
      $('#errorCHAUFF_ZONE_AFFECTATION_ID_MOD').html('Le champ est obligatoire');
      statut=2;
    } if($('#DATE_DEBUT_AFFECTATION_MOD').val()=='')
    {
      $('#errorDATE_DEBUT_AFFECTATION_MOD').html('Le champ est obligatoire');
      statut=2;
    } if($('#DATE_FIN_AFFECTATION_MOD').val()=='')
    {
      $('#errorDATE_FIN_AFFECTATION_MOD').html('Le champ est obligatoire');
      statut=2;
    }

    if(statut<2)
    {
      var form_data = new FormData($("#modf_affect_form")[0]);
      var url="<?= base_url('chauffeur/Chauffeur/save_modif_chauff')?>";
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
              text: 'Modification affectation faite avec succès',
              timer: 1500,
            }).then(() =>
            {
              window.location.reload('<?=base_url('chauffeur/Chauffeur')?>');
            });
          }
          else if(data==2)
          {
            Swal.fire(
            {
              icon: 'success',
              title: 'Success',
              text: 'Le chauffeur a une autre voiture ',
              timer: 1500,
            }).then(() =>
            {
              window.location.reload('<?=base_url('chauffeur/Chauffeur')?>');
            });
          }
          else
          {
            Swal.fire(
            {
              icon: 'success',
              title: 'Success',
              text: 'Modificationde l\'affectation échouée',
              timer: 1500,
            }).then(() =>
            {
              window.location.reload('<?=base_url('chauffeur/Chauffeur')?>');
            });
          }
        }
      });
    }
  }


 function myFunction(CHAUFFEUR_ID) {
  // Get the checkbox
  var checkBox = document.getElementById("myCheck");
  // Get the output text

  var status=$('#status').val();
  status=2;
  var form_data = new FormData($("#myform_checked")[0]);
  $.ajax(
  {
    url:"<?=base_url()?>chauffeur/Chauffeur/active_desactive/"+status+'/'+CHAUFFEUR_ID,
    type: 'POST',
    dataType:'JSON',
    data: form_data ,
    contentType: false,
    cache: false,
    processData: false,
    success: function(data)
    {
      window.location.href='<?=base_url('')?>chauffeur/Chauffeur';

    }
  });

}
function myFunction_desactive(CHAUFFEUR_ID) {
  // Get the checkbox
  var checkBox = document.getElementById("myCheck");
  // Get the output text

  var status=$('#status').val();

  status=1;

  var form_data = new FormData($("#myform_check")[0]);
  $.ajax(
  {
    url:"<?=base_url()?>chauffeur/Chauffeur/active_desactive/"+status+'/'+CHAUFFEUR_ID,

    type: 'POST',
    dataType:'JSON',
    data: form_data ,
    contentType: false,
    cache: false,
    processData: false,
    success: function(data)
    {
      window.location.href='<?=base_url('')?>chauffeur/Chauffeur';
    }
  });

}

</script>


</html>