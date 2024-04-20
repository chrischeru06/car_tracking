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
                          <th class="text-dark">STATUT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                          <th class="text-dark">ASSURANCE</th>
                          <th class="text-dark">CONTROLE&nbsp;TECHNIQUE</th>
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
</html>