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
              <h1 class="text-center" style="margin-bottom: 1px;"><font class="fa fa-list" style="font-size:18px;"></font> <?=lang('chauff_liste')?></h1>
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

    <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('chauffeur/Chauffeur/ajouter')?>" class="nav-link position-relative"><i class="bi bi-plus"></i> <?=lang('btn_nouveau')?></a>

  </div>
</div>
</div>


<!-- End Page Title -->


<!--******** Debut Modal pour modifier l'affectation du chauffeur a une voiture *********-->
<div class="modal fade" id="modifvoitureModal" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'>      
        <h5 class="modal-title"><?=lang('modal_modif_affectation')?> </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <form id="modf_affect_form" enctype="multipart/form-data" action="#" method="post">
        <div class="modal-body mb-1">
          <div class="row">
           <!--  <input type="hidden" name="code_vehicule" id="code_vehicule">  -->
           <input type="hidden" name="CHAUFFEUR_ID_MOD" id="CHAUFFEUR_ID_MOD">

               <!--  <div class="col-md-6">
                  <label for="description" class="text-dark">Zone d'affectation</label>
                  <select class="form-control" id="CHAUFF_ZONE_AFFECTATION_ID_MOD" name="CHAUFF_ZONE_AFFECTATION_ID_MOD">
                  </select>

                  <span id="errorCHAUFF_ZONE_AFFECTATION_ID_MOD" class="text-danger"></span>
                </div> -->

                <div class="col-md-6">
                  <label type="date" class="text-dark"><?=lang('input_date_deb')?></label>
                  <input type="date" name="DATE_DEBUT_AFFECTATION_MOD" autocomplete="off" id="DATE_DEBUT_AFFECTATION_MOD"  value="<?= set_value('DATE_DEBUT_AFFECTATION_MOD') ?>"  onchange="get_date_fin_modif(this.value)" class="form-control"  min="<?= date('Y-m-d')?>">
                  <span id="errorDATE_DEBUT_AFFECTATION_MOD" class="text-danger"></span>
                </div> 
                <div class="col-md-6">
                  <label type="date" class="text-dark"><?=lang('input_date_fin')?></label>
                  <input type="date" name="DATE_FIN_AFFECTATION_MOD" autocomplete="off" id="DATE_FIN_AFFECTATION_MOD" value="<?= set_value('DATE_FIN_AFFECTATION_MOD') ?>" onchange="get_dates_deb_modif(this.value)" class="form-control"  min="<?= date('Y-m-d')?>">
                  <span id="errorDATE_FIN_AFFECTATION_MOD" class="text-danger"></span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <input type="button" class="btn btn-outline-primary rounded-pill " type="button" id="btn_add" value="<?=lang('btn_modifier')?>" onclick="save_modif_chauffeur();" />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!--******** Fin Modal pour modifier l'affectation du chauffeur a une voiture  ***********-->

  <!-- fin modal pour retirer la voiture -->
  <div class="modal fade" id="retirvoiture" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel3" aria-hidden="true">
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
</div>
<!-- fin modal pour retirer la voiture -->

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
              <input type="hidden" name="CHAUFFEUR_ID_I" id="CHAUFFEUR_ID_I">

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
            <input type="button"class="btn btn-outline-primary rounded-pill " type="button" id="btn_add" value="Activer" onclick="save_motif_active();" />
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
              <input type="hidden" name="CHAUFFEUR_ID_ID" id="CHAUFFEUR_ID_ID">

              <div class="col-md-12" id="div_type">
                <label class="text-dark"><?=lang('label_motif')?> <font color="red">*</font></label>
                <select class="form-control" id="ID_MOTIF_des" name="ID_MOTIF_des" >
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

                <table id="mytable" class="table table-hover" style="padding-top: 20px;">
                 <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                  <tr>

                    <th class="text-dark">#</th>
                    <th class="text-dark"><?=lang('th_chauffeur')?></th>
                    <th class="text-dark"><?=lang('th_tlphone')?></th>
                    <th class="text-dark"><?=lang('th_email')?></th>
                    <th class="text-dark"><?=lang('th_statut')?></th>
                    <th class="text-dark"><?=lang('th_options')?></th>
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
<script>
  function attribue_voiture(CHAUFFEUR_ID='',NOM='',PRENOM='')

  {
      // alert(NOM)
    // var CHAUFFEUR_ID = $CHAUFFEUR_ID;
    $('#CHAUFFEUR_ID').val(CHAUFFEUR_ID);
    $('#NOM').html(NOM);
    $('#PRENOM').html(PRENOM);

    $('#CHAUFF_ZONE_AFFECTATION_ID').val(CHAUFF_ZONE_AFFECTATION_ID);
    $('#errorVEHICULE_ID').html('');
    $('#errorCHAUFF_ZONE_AFFECTATION_ID').html('');
    $('#errorDATE_DEBUT_AFFECTATION').html('');
    $('#errorDATE_FIN_AFFECTATION').html('');
    $.ajax(
    {

      url: "<?= base_url() ?>chauffeur/Chauffeur/get_all_voiture/",

      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
        $('#VEHICULE_ID').html(data.html);
        // $('#CHAUFF_ZONE_AFFECTATION_ID').html(data.html1);
        // $('#code_vehicule').val(CODE);
        $('#carteModal').modal('show');
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        alert('<?=lang('msg_erreur')?>');
      }
    });
  }
  
  function modif_affectation(CHAUFFEUR_ID)

  {
    var CHAUFFEUR_ID = CHAUFFEUR_ID;
    $('#CHAUFFEUR_ID_MOD').val(CHAUFFEUR_ID);
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

      $('#DATE_DEBUT_AFFECTATION_MOD').val(data.htmldbut);
      $('#DATE_FIN_AFFECTATION_MOD').val(data.htmlfin);
      $('#modifvoitureModal').modal('show');
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
      alert('<?=lang('msg_erreur')?>');
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
      $('#errorCHAUFF_ZONE_AFFECTATION_ID_MOD').html('<?=lang('msg_validation')?>');
      statut=2;
    } if($('#DATE_DEBUT_AFFECTATION_MOD').val()=='')
    {
      $('#errorDATE_DEBUT_AFFECTATION_MOD').html('<?=lang('msg_validation')?>');
      statut=2;
    } if($('#DATE_FIN_AFFECTATION_MOD').val()=='')
    {
      $('#errorDATE_FIN_AFFECTATION_MOD').html('<?=lang('msg_validation')?>');
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
              text: '<?=lang('modif_affectation_success')?>',
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
              text: '<?=lang('msg_chauffeur_affecte')?> ',
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
              text: '<?=lang('msg_modif_affect_echoue')?>',
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
   var CHAUFFEUR_ID=$('#CHAUFFEUR_ID_I').val(CHAUFFEUR_ID);
  $('#Modal_activation').modal('show');

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
  function myFunction_desactive(CHAUFFEUR_ID=0,STATUT_VEHICULE=0) {
  // Get the checkbox
  //STATUT_VEHICULE:debut tester si le chauffeur a une voiture pour le desactiver
   var CHAUFFEUR_ID=$('#CHAUFFEUR_ID_ID').val(CHAUFFEUR_ID);
    $('#Modal_desactivation').modal('show');
    if (STATUT_VEHICULE==2) 
    {
      var url="<?= base_url('chauffeur/Chauffeur/retirer_voiture')?>";
      $.ajax(
      {
        url: url,
        type: 'POST',
        dataType:'JSON',
        // data: form_data ,
        contentType: false,
        cache: false,
        processData: false,
        success: function(data)
        {
          if(data==2)
          {
            Swal.fire(
            {
              icon: 'error',
              title: '<?=lang('msg_erreur')?>',
              text: '<?=lang('modal_retirer_cond')?>',
              timer: 3000,
            }).then(() =>
            {
              window.location.reload('<?=base_url('chauffeur/Chauffeur')?>');
            });
          }
        }
      });
      //STATUT_VEHICULE:fin tester si le chauffeur a une voiture pour le desactiver
    }else
    {
    //debut desactiver le chauffeur
     var checkBox = document.getElementById("myCheck");
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
//fin desactiver le chauffeur

 }

</script>
<script>

function save_motif_active() {
  //activation
 var CHAUFFEUR_ID=$('#CHAUFFEUR_ID_I').val();

  var statut=1;
  $('#errorID_MOTIF').html('');

  if($('#ID_MOTIF').val()=='')
  {
    $('#errorID_MOTIF').html('<?=lang('msg_validation')?>');
    statut=2;
  }
  if (statut==1) {

    var checkBox = document.getElementById("myCheck");
    var status=$('#status').val();
    status = 2;

    var form_data = new FormData($("#active_form")[0]);
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

       if(data.status==2)
       {
        Swal.fire(
        {
          icon: 'success',
          title: 'Success',
          text: '<?=lang('swal_active_proprio')?>',
          timer: 1500,
        }).then(() =>
        {
         window.location.href='<?=base_url('')?>chauffeur/Chauffeur';
       });
      }
    }
  });
  }
}

function save_motif_desactive() {
  //desactivation
 var CHAUFFEUR_ID=$('#CHAUFFEUR_ID_ID').val();
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
    url:"<?=base_url()?>chauffeur/Chauffeur/active_desactive/"+status+'/'+CHAUFFEUR_ID,
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
        text: '<?=lang('swal_desactive_proprio')?>',
        timer: 1500,
      }).then(() =>
      {
       window.location.href='<?=base_url('')?>chauffeur/Chauffeur';
     });
    }
  }
});
 }
}
</script>


<script type="text/javascript">

 function get_date_fin()
 {
  $("#DATE_FIN_AFFECTATION").prop('min',$("#DATE_DEBUT_AFFECTATION").val());

}
  // DATE_DEBUT_AFFECTATION,DATE_FIN_AFFECTATION

function get_dates_deb()
{
  $("#DATE_DEBUT_AFFECTATION").prop('min',$("#DATE_FIN_AFFECTATION").val());

}
  ///verifier dates pour la modification de l'affectation

function get_date_fin_modif()
{
  $("#DATE_FIN_AFFECTATION_MOD").prop('min',$("#DATE_DEBUT_AFFECTATION_MOD").val());

}


function get_dates_deb_modif()
{
  $("#DATE_DEBUT_AFFECTATION_MOD").prop('min',$("#DATE_FIN_AFFECTATION_MOD").val());

}
</script>


</html>