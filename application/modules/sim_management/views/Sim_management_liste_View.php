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
       <!-- <div class="welcome-text">
          <center>
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
             <table>
              <tr>
                <td> 

                </td>
                <td>  
                  <h4 class="text-dark"><?=$title?></h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">

                    </ol>
                  </nav>
                </td>
              </tr>
            </table>
          </div>
        </center>
      </div>-->
      <h4><i class="fa fa-code"></i> <font>Devices</font></h4>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Devices</a></li>
          <li class="breadcrumb-item ">Liste</li>
        </ol>
      </nav>
    </div>
    <div class="col-md-2">

      <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('sim_management/Sim_management/ajouter')?>" class="nav-link position-relative"><i class="bi bi-plus"></i> Nouveau</a>

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

                  <table id="mytable" class="table table-hover" style="padding-top: 20px;">
                    <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                      <tr>
                        <th class="">#</th>

                        <th class="">CODE</th>
                        <th class="">VIHICULE</th>
                        <th class="">PROPRIETAIRE</th>
                        <th class="">DATE&nbsp;INSTALLATION</th>
                        <th class="">NOM&nbsp;RESEAU</th>
                        <th class="">NUMERO</th>
                        <th class="">DATE&nbsp;ACTIVATION&nbsp;FORFAIT</th>
                        <th class="">DATE&nbsp;EXPIRATION&nbsp;FORFAIT</th>
                        <th class="">STATUT</th>
                        <th class="">DATE&nbsp;ENREGISTREMENT</th>
                        <th class="">ACTION</th>
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


  <!--******** Modal pour assurance et controle technique *********-->

  <div class="modal fade" id="Modal_forfait" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class='modal-header' style='background:cadetblue;color:white;'>

          <h5 class="modal-title" id="titre">Renouvèlement du forfait</h5>

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="form_forfait" enctype="multipart/form-data" action="#" method="post">
            <div class="modal-body mb-1">
              <div class="row">
                <input type="hidden" name="DEVICE_ID" id="DEVICE_ID">

                <div class="col-md-6">
                  <div class="form-group">
                    <label ><small> Date activation forfait</small><span  style="color:red;">*</span></label>

                    <input class="form-control" type='date' name="DATE_ACTIVE_MEGA" id="DATE_ACTIVE_MEGA" placeholder='' max="<?= date('Y-m-d')?>" onchange="get_date_expire();"/>

                  </div>
                  <span id="errorDATE_ACTIVE_MEGA" class="text-danger"></span>
                  <?php echo form_error('DATE_ACTIVE_MEGA', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label ><small> Date expiration forfait</small><span  style="color:red;">*</span></label>

                    <input class="form-control" type='date' name="DATE_EXPIRE_MEGA" id="DATE_EXPIRE_MEGA" placeholder='' readonly/>

                  </div>
                  <span id="errorDATE_EXPIRE_MEGA" class="text-danger"></span>
                  <?php echo form_error('DATE_EXPIRE_MEGA', '<div class="text-danger">', '</div>'); ?>
                </div>

              </div>
            </div> 
            <div class="modal-footer">

              <button type="button" class="btn btn-outline-primary rounded-pill" id="btnSave" onclick="save_forfait()"> <i class="fa fa-save"> </i> Enregistrer</button>

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
    listing();
    //get_nbr_vehicule();

  });
</script>


<script>
  //Fonction pour l'affichage
  function listing()
  {
    $('#message').delay('slow').fadeOut(10000);

    $("#mytable").DataTable({
      "processing":true,
      "destroy" : true,
      "serverSide":true,
      "oreder":[[ 0, 'desc' ]],
      "ajax":{
        url:"<?php echo base_url('sim_management/Sim_management/listing');?>",
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

  }
</script>

<script>
    //Fonction pour afficher le formulaire de renouvelement du forfait
  function renouvelerForfait(DEVICE_ID)
  {
    $('#DEVICE_ID').val(DEVICE_ID);
    $.ajax(
    {
      url:"<?=base_url()?>sim_management/Sim_management/get_date_expire_old/"+DEVICE_ID,
      type:"GET",
      dataType:"JSON",
      success: function(data)
      {
        $("#DATE_ACTIVE_MEGA").prop('min',data);
      }
    });

    $('#Modal_forfait').modal('show');
  }
</script>

<script type="text/javascript">
  //Dertermination de la date expiration automatique
  function get_date_expire()
  {
    var DATE_ACTIVE_MEGA = $('#DATE_ACTIVE_MEGA').val();

    $.ajax(
    {
      url:"<?=base_url('sim_management/Sim_management/get_date_expire/')?>",
      type : "POST",
      dataType: "JSON",
      cache:false,
      data: {
        DATE_ACTIVE_MEGA:DATE_ACTIVE_MEGA,
      },
      success: function(data)
      {
        $("#DATE_EXPIRE_MEGA").val(data);
        $("#DATE_EXPIRE_MEGA").prop('min',data);
        $("#DATE_EXPIRE_MEGA").prop('max',data);
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        alert('Erreur');
      }
    });
    
  }
</script>

<script>
  //Fonction pour proceder à l'enregistrement du renouvelement forfait
  function save_forfait()
  {
    var statut = 1;

    if($('#DATE_ACTIVE_MEGA').val() == '')
    {
      $('#errorDATE_ACTIVE_MEGA').text('Le champ est obligatoire');
      statut = 2;
    }else{$('#errorDATE_ACTIVE_MEGA').text('');}

    if($('#DATE_EXPIRE_MEGA').val() == '')
    {
      $('#errorDATE_EXPIRE_MEGA').text('Le champ est obligatoire');
      statut = 2;
    }
    else{$('#errorDATE_EXPIRE_MEGA').text('');}

    if(statut == 1)
    {
      var form_data = new FormData($("#form_forfait")[0]);
      url = "<?= base_url('sim_management/Sim_management/save_renouv_fortait/') ?>";
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
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Renouvèlement forfait avec succès !',
            timer: 2000,
          }).then(() => {
            window.location.reload();
          })
          $("#form_forfait")[0].reset();
        }
      })
    }
  }
</script>

</html>