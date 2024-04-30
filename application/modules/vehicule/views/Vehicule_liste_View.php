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

.btn-md:hover{
  background-color: rgba(95, 158, 160,0.3);
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


  <div class="card">
    <div class="card-body">
      <br>
      <div class="row">

        <div class="col-md-5">
          <label class="text-dark" style="font-weight: 1000; color:#454545">Filtrage selon la validité des documents</label>
          <select class="form-control" id="CHECK_VALIDE" name="CHECK_VALIDE" onchange="listing();get_nbr_vehicule();">
            <option value="0"> Sélectionner</option>
            <option value="1"> Véhicules avec assurances valides </option>
            <option value="2"> Véhicules avec assurances invalides </option>
            <option value="3"> Véhicules avec contrôles techniques valides </option>
            <option value="4"> Véhicules avec contrôles techniques invalides </option>
            
          </select>

          <label class="fa fa-check text-success" id="check" style="position: relative;top: -33%;left: 93%;"></label>

          <label class="fa fa-ban text-danger" id="close" style="position: relative;top: -33%;left: 93%;"></label>

        </div>

        <div class="col-md-6">
          <span class="badge bg-primary rounded-pill nbr_vehicule" style="font-size:10px;position:relative;top:6px;left:-23px;">0</span>
        </div>


      </div>
    </div>
  </div>


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
                      <?php
                      if($this->session->userdata('PROFIL_ID') == 1)
                        {?>
                          <th class="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CODE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                          <?php
                        }
                        ?>
                        <th class="">PLAQUE</th>
                        <th class="">MARQUE</th>
                        <!--  <th class="">MODELE</th> -->
                        <th class="">COULEUR</th>
                        <!-- <th class="">CONSOMMATION</th> -->
                        <th class="">DATE&nbsp;D'ENREGISTREMENT</th>
                        <!-- <th class="">TRAITEMENT&nbsp;DEMANDE</th> -->
                        <th class="">STATUT&nbsp;&nbsp;</th>
                        <th class="">ASSURANCE</th>
                        <th class="">C&nbsp;T</th>
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

        --><h5 class="modal-title">Traiter la demande </h5>


        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="attribution_form" enctype="multipart/form-data" action="#" method="post">
          <div class="modal-body mb-1">
            <div class="row">
              <input type="hidden" name="VEHICULE_TRAITE" id="VEHICULE_TRAITE">
              <input type="hidden" name="STATUT_VEH_AJOUT" id="STATUT_VEH_AJOUT">

              <div class="col-md-4">
                <label for="description"><small>Statut</small><span  style="color:red;">*</span></label>
                <select class="form-control" id="TRAITEMENT_DEMANDE_ID" name="TRAITEMENT_DEMANDE_ID" onchange="traiter_view_code()">
                </select>
                <span id="errorTRAITEMENT_DEMANDE_ID" class="text-danger"></span>
              </div>
              <div class="col-md-4" id="code_device_uid">
                <div class="form-group">
                  <label ><small> Code (device uid)</small><span  style="color:red;">*</span></label>

                  <input class="form-control" type='text' name="CODE" id="CODE" placeholder='' onchange="check_val_code();" value=""/>

                </div>
                <span id="errorCODE" class="text-danger"></span>
                <?php echo form_error('CODE', '<div class="text-danger">', '</div>'); ?>
              </div>

              <div class = 'col-md-4'>
                <label><small>Commentaire</small><span  style="color:red;">*</span></label>
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



<!--******** Modal pour assurance et controle technique *********-->

<div class="modal fade" id="Modal_assure_controle" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class='modal-header' style='background:cadetblue;color:white;'>      <!-- <h5 class="modal-title">Traiter la demande de :<a id="NOM"></a>&nbsp;&nbsp;<a id="PRENOM"></a></h5>

        --><h5 class="modal-title" id="titre">Assurance et contrôle technique</h5>


        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form_assure_controle" enctype="multipart/form-data" action="#" method="post">
          <div class="modal-body mb-1">
            <div class="row">

              <input type="hidden" name="VEHICULE_ID_ASSURE_CONTROLE" id="VEHICULE_ID_ASSURE_CONTROLE">
              <input type="hidden" name="ACTION" id="ACTION">
              <input type="hidden" name="USER_ID" id="USER_ID" value="<?=$this->session->userdata('USER_ID')?>">

              <div class="col-md-6" id="assureur">
                <label>Assureur<font color="red">*</font></label> 
                <select class="form-control" name="ID_ASSUREUR"  id="ID_ASSUREUR">
                 <option value="">Sélectionner</option>

               </select>
               <font id="error_ID_ASSUREUR" color="red"></font>
             </div>

             <div class="col-md-6" id="debut_assurance">
              <label ><small>Date début assurance</small><span  style="color:red;">*</span></label>
              <input type="date" name="DATE_DEBUT_ASSURANCE" autocomplete="off" id="DATE_DEBUT_ASSURANCE" value=""  class="form-control" onchange="get_date_fin_assurance(this.value)" min="<?= date('Y-m-d')?>">

              <font id="error_DATE_DEBUT_ASSURANCE" color="red"></font>

            </div>

            <div class="col-md-6" id="debut_controle">
              <label ><small>Date début contrôle technique</small><span  style="color:red;">*</span></label>
              <input type="date" name="DATE_DEBUT_CONTROTECHNIK" autocomplete="off" id="DATE_DEBUT_CONTROTECHNIK" value=""  class="form-control" onchange="get_date_fin_controle(this.value)" min="<?= date('Y-m-d')?>">

              <font id="error_DATE_DEBUT_CONTROTECHNIK" color="red"></font>

            </div>

            <div class="col-md-6" id="fin_assurance">
              <label ><small>Date fin assurance</small><span  style="color:red;">*</span></label>
              <input type="date" name="DATE_FIN_ASSURANCE" autocomplete="off" id="DATE_FIN_ASSURANCE" value=""  class="form-control" >

              <font id="error_DATE_FIN_ASSURANCE" color="red"></font>

            </div>

            <div class="col-md-6" id="fin_controle">
              <label ><small>Date fin contrôle technique</small><span  style="color:red;">*</span></label>
              <input type="date" name="DATE_FIN_CONTROTECHNIK" autocomplete="off" id="DATE_FIN_CONTROTECHNIK" value=""  class="form-control" >

              <font id="error_DATE_FIN_CONTROTECHNIK" color="red"></font>
            </div>


            <div class="col-md-6" id="photo_assurance">
              <label> <small>Photo assurance</small> </label>

              <input type="file" class="form-control" name="FILE_ASSURANCE" id="FILE_ASSURANCE" value="<?=set_value('FILE_ASSURANCE')?>" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" class="form-control" title='Veuillez mettre une photo avec extension:  .png,.PNG,.jpg,.JPG,.JEPG,.jepg'>

              <span id="error_FILE_ASSURANCE" class="text-danger"></span>
            </div>

            <div class="col-md-6" id="photo_controle">
              <label> <small>Photo contrôle technique</small> </label>

              <input type="file" class="form-control" name="FILE_CONTRO_TECHNIQUE" id="FILE_CONTRO_TECHNIQUE" value="<?=set_value('FILE_CONTRO_TECHNIQUE')?>" accept=".png,.PNG,.jpg,.JPG,.JEPG,.jepg" class="form-control" title='Veuillez mettre une photo avec extension:  .png,.PNG,.jpg,.JPG,.JEPG,.jepg'>

              <span id="error_FILE_CONTRO_TECHNIQUE" class="text-danger"></span>
            </div>

          </div>
        </div> 
        <div class="modal-footer">
          <!-- <input type="button"class="btn btn-outline-primary rounded-pill " type="button" id="btn_add" value="Traiter" onclick="save_statut_vehicul();" /> -->
          <button type="button" class="btn btn-outline-primary rounded-pill" id="btnSave" onclick="save_assure_controle()"> <i class="fa fa-save"> </i> Enregistrer</button>

          <button type="reset" class='btn btn-outline-warning rounded-pill' style="float:right;" data-dismiss="modal" id="btnCancel"><i class="fa fa-close"> </i> Annuler</button>

        </div>
      </form>
    </div>
  </div>
</div>
</div><!-- End Modal-->

<!--******** Debut Modal pour motif_activation *********-->

<div class="modal fade" id="Modal_activation" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered ">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'>      
        <h5 class="modal-title">Activation  </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="active_form" enctype="multipart/form-data" action="#" method="post">
          <div class="modal-body mb-1">
            <div class="row">
              <input type="hidden" name="VEHICULE_ID_I" id="VEHICULE_ID_I">

              <div class="col-md-12" id="div_type">
                <label class="text-dark">Motif <font color="red">*</font></label>
                <select class="form-control" id="ID_MOTIF" name="ID_MOTIF" >
                  <option value="">-- Sélectionner --</option>
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
        <h5 class="modal-title">Désactivation  </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="desactive_form" enctype="multipart/form-data" action="#" method="post">
          <div class="modal-body mb-1">
            <div class="row">
              <input type="hidden" name="VEHICULE_ID_ID" id="VEHICULE_ID_ID">

              <div class="col-md-12" id="div_type">
                <label class="text-dark">Motif <font color="red">*</font></label>
                <select class="form-control" id="ID_MOTIF_des" name="ID_MOTIF_des" >
                  <option value="">-- Sélectionner --</option>
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
            <input type="button"class="btn btn-outline-primary rounded-pill " type="button" id="btn_add" value="Désactiver" onclick="save_motif_desactive();" />

          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- fin modal motif_desactivation -->
</main><!-- End #main -->

<?php include VIEWPATH . 'includes/footer.php'; ?>

</body>


<script>

  // Fonction pour le chargement de donnees par defaut
  $(document).ready( function ()
  {

    $('#close').hide();
    $('#check').hide();

    listing();

    get_nbr_vehicule();

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
          url:"<?php echo base_url('vehicule/Vehicule/listing');?>",
          type:"POST",
          data : {CHECK_VALIDE:CHECK_VALIDE},
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
  // Fonction pour activer le statut du véhicule
 function statut_active(VEHICULE_ID) {
  var VEHICULE_ID=$('#VEHICULE_ID_I').val(VEHICULE_ID);

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
    status = 4;

    var form_data = new FormData($("#active_form")[0]);
    $.ajax(
    {
      url:"<?=base_url()?>vehicule/Vehicule/active_desactive/"+status,
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
          text: 'Activation faite avec succès',
          timer: 1500,
        }).then(() =>
        {
         window.location.href='<?=base_url('')?>vehicule/Vehicule';
       });
      }
    }
  });
  }
  

}
</script>

<script>
  // Fonction pour désactiver le statut du véhicule
 function statut_desactive(VEHICULE_ID) {
  var VEHICULE_ID=$('#VEHICULE_ID_ID').val(VEHICULE_ID);

  $('#Modal_desactivation').modal('show'); 
}
function save_motif_desactive() {
  var statut=1;
  $('#errorID_MOTIF_des').html('');

  if($('#ID_MOTIF_des').val()=='')
  {
    $('#errorID_MOTIF_des').html('Le champ est obligatoire');
    statut=2;
  }
  if (statut==1) {
   var checkBox = document.getElementById("myCheck");
   var status=$('#status').val();

   status = 2;

   var form_data = new FormData($("#desactive_form")[0]);
   $.ajax(
   {
    url:"<?=base_url()?>vehicule/Vehicule/active_desactive/"+status,
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
        text: 'désactivation faite avec succès',
        timer: 1500,
      }).then(() =>
      {
       window.location.href='<?=base_url('')?>vehicule/Vehicule';
     });
    }
  }
});
 }
 

}
</script>

<script type="text/javascript">

  function traiter_view_code() {
   var decision = $('#TRAITEMENT_DEMANDE_ID').val();
   if (decision==1) {
    $('#code_device_uid').show();
  }else{
    $('#code_device_uid').hide();
  }
}
function traiter_demande(VEHICULE_ID='',STATUT_VEH_AJOUT='')

{
  $('#Modal_traiter').modal('show');
    //VEHICULE_ID du 2eme ca vient du VEHICULE_ID du paramentre la en haut 
  $('#VEHICULE_TRAITE').val(VEHICULE_ID);
  $('#STATUT_VEH_AJOUT').val(STATUT_VEH_AJOUT);
  $('#code_device_uid').hide();
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

function check_val_code() {
  var code_vehicule=$('#CODE').val();
  var form_data = new FormData($("#attribution_form")[0]);
  // alert(code_vehicule)
  $.ajax(
  {
    url:"<?=base_url()?>vehicule/Vehicule/check_val_code/",
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
        $('#errorCODE').text("");
      }
      else
        {$('#errorCODE').text("Ce Code du véhicule existe déjà!");}
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
    $('#errorTRAITEMENT_DEMANDE_ID').html('Le champ est obligatoire !');
    statut=2;
  }
  if($('#COMMENTAIRE').val()=='')
  {
    $('#errorCOMMENTAIRE').html('Le champ est obligatoire !');
    statut=2;
  } 
  if($('#TRAITEMENT_DEMANDE_ID').val()==1)
  {
    if ($('#CODE').val()=='') {

     $('#errorCODE').html('Le champ est obligatoire !');
     statut=2;

   }
   else if( $('#errorCODE').val()!='')
   {
    statut=2;
  }

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
          icon: 'error',
          title: 'Erreur',
          text: 'Traitement échoué !',
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
          icon: 'error',
          title: 'Erreur',
          text: 'Le code du véhicule existe déjà !',
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

<script>
  //Fonction pour afficher le formulaire de renouvellement assurance et controle technique
  function assure_controle(VEHICULE_ID_ASSURE_CONTROLE ='',ACTION = '')
  {
    $('#VEHICULE_ID_ASSURE_CONTROLE').val(VEHICULE_ID_ASSURE_CONTROLE);

    //alert(VEHICULE_ID_ASSURE_CONTROLE)
    $('#ACTION').val(ACTION);


    if($('#ACTION').val() == 1) //Assurance
    {
      $('#titre').text('Renouvelement de l\'assurance');

      $('#assureur').show();
      $('#debut_assurance').show();
      $('#fin_assurance').show();
      $('#photo_assurance').show();

      $('#debut_controle').hide();
      $('#fin_controle').hide();
      $('#photo_controle').hide();
    }
    else if($('#ACTION').val() == 2) //Controle technique
    {
      $('#titre').text('Renouvellement du contrôle technique');

      $('#assureur').hide();
      $('#debut_assurance').hide();
      $('#fin_assurance').hide();
      $('#photo_assurance').hide();

      $('#debut_controle').show();
      $('#fin_controle').show();
      $('#photo_controle').show();
    }

    $('#Modal_assure_controle').modal('show');

    $.ajax(
    {
      url: "<?= base_url() ?>vehicule/Vehicule/select_assureur/"+VEHICULE_ID_ASSURE_CONTROLE,
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
        if (data) {
          $('#ID_ASSUREUR').html(data.html_assureur);
        }
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        alert('Erreur');
      }
    });
  }
</script>


<!---CONTROLE DES DATES------>
<script type="text/javascript">
  function get_date_fin_assurance()
  {
    $("#DATE_FIN_ASSURANCE").prop('min',$("#DATE_DEBUT_ASSURANCE").val());

    if($("#DATE_FIN_ASSURANCE").val() < $("#DATE_DEBUT_ASSURANCE").val() )
    {
      $("#DATE_FIN_ASSURANCE").val($("#DATE_DEBUT_ASSURANCE").val());
    }
    
  }
</script>

<script type="text/javascript">
  function get_date_fin_controle()
  {
    $("#DATE_FIN_CONTROTECHNIK").prop('min',$("#DATE_DEBUT_CONTROTECHNIK").val());

    if($("#DATE_FIN_CONTROTECHNIK").val() < $("#DATE_DEBUT_CONTROTECHNIK").val() )
    {
      $("#DATE_FIN_CONTROTECHNIK").val($("#DATE_DEBUT_CONTROTECHNIK").val());
    }
    
  }
</script>

<script>
  //Fonction pour proceder à l'enregistrement de l'assurance et controle technique
  function save_assure_controle()
  {
    var statut = 1;

    var ACTION = $('#ACTION').val();

    var ID_ASSUREUR = $('#ID_ASSUREUR').val();
    var DATE_DEBUT_ASSURANCE = $('#DATE_DEBUT_ASSURANCE').val();
    var DATE_FIN_ASSURANCE = $('#DATE_FIN_ASSURANCE').val();
    var FILE_ASSURANCE = $('#FILE_ASSURANCE').val();

    var DATE_DEBUT_CONTROTECHNIK = $('#DATE_DEBUT_CONTROTECHNIK').val();
    var DATE_FIN_CONTROTECHNIK = $('#DATE_FIN_CONTROTECHNIK').val();
    var FILE_CONTRO_TECHNIQUE = $('#FILE_CONTRO_TECHNIQUE').val();

    if(ACTION == 1)
    {

     if(ID_ASSUREUR == '')
     {
      $('#error_ID_ASSUREUR').text('Le champ est obligatoire !');
      statut = 2;
    }else{$('#error_ID_ASSUREUR').text('');}

    if(DATE_DEBUT_ASSURANCE == '')
    {
      $('#error_DATE_DEBUT_ASSURANCE').text('Le champ est obligatoire !');
      statut = 2;
    }else{$('#error_DATE_DEBUT_ASSURANCE').text('');}

    if(DATE_FIN_ASSURANCE == '')
    {
      $('#error_DATE_FIN_ASSURANCE').text('Le champ est obligatoire !');
      statut = 2;
    }else{$('#error_DATE_FIN_ASSURANCE').text('');}

    if(FILE_ASSURANCE == '')
    {
      $('#error_FILE_ASSURANCE').text('Le champ est obligatoire !');
      statut = 2;
    }else{$('#error_FILE_ASSURANCE').text('');}


  }
  else if(ACTION == 2)
  {

    if(DATE_DEBUT_CONTROTECHNIK == '')
    {
      $('#error_DATE_DEBUT_CONTROTECHNIK').text('Le champ est obligatoire !');
      statut = 2;
    }else{$('#error_DATE_DEBUT_CONTROTECHNIK').text('');}

    if(DATE_FIN_CONTROTECHNIK == '')
    {
      $('#error_DATE_FIN_CONTROTECHNIK').text('Le champ est obligatoire !');
      statut = 2;
    }else{$('#error_DATE_FIN_CONTROTECHNIK').text('');}

    if(FILE_CONTRO_TECHNIQUE == '')
    {
      $('#error_FILE_CONTRO_TECHNIQUE').text('Le champ est obligatoire !');
      statut = 2;
    }else{$('#error_FILE_CONTRO_TECHNIQUE').text('');}

  }

  if(statut == 1)
  {
    var form_data = new FormData($("#form_assure_controle")[0]);
    url = "<?= base_url('vehicule/Vehicule/save_assure_controle/') ?>";
    $.ajax({
      url: url,
      type: 'POST',
      dataType:'JSON',
      data: form_data ,
      contentType: false,
      cache: false,
      processData: false,
      success: function(data) {
        console.log(data)
                               //alert(data)
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: 'Enregistrement avec succès !',
          timer: 2000,
        }).then(() => {
          window.location.reload();
        })
        $("#form_assure_controle")[0].reset();
      }
    })
  }

}
</script>


<script>
 function get_nbr_vehicule()
 {
  var CHECK_VALIDE = $('#CHECK_VALIDE').val();

  $.ajax({
    url: "<?= base_url() ?>vehicule/Vehicule/get_nbr_vehicule/" + CHECK_VALIDE,
    type: "POST",
    dataType: "JSON",
    success: function(data) {
     $('.nbr_vehicule').text(data);
   },

 });
}
</script>




</html>