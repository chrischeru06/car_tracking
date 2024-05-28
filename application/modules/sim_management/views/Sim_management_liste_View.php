<!DOCTYPE html>
<html lang="en">

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

    <div class="pagetitle">

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
      <h1><i class="fa fa-hdd-o" style="font-size:18px;"></i> <font><?=lang('device_mot')?></font></h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html"><?=lang('device_mot')?></a></li>
          <li class="breadcrumb-item "><?=lang('title_list')?></li>
        </ol>
      </nav>
    </div>
    <div class="col-md-2">

      <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('sim_management/Sim_management/ajouter')?>" class="nav-link position-relative"><i class="bi bi-plus"></i> <?=lang('btn_nouveau')?></a>

    </div>
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
                <br>
                <label class="text-muted"><?=lang('device_mot')?> (<font id="nbr_device">0</font>) </label>

                <?= $this->session->flashdata('message'); ?>

                <div class="table-responsive"><br>

                  <table id="mytable" class="table table-hover" style="padding-top: 20px;">
                    <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                      <tr>
                        <th class="">#</th>

                        <th class=""><?=lang('list_code')?></th>
                        <th class=""><?=lang('veh_maj_mot')?></th>
                        <th class=""><?=lang('th_proprio')?></th>
                        <th class=""><?=lang('dte_install_list')?></th>
                        <th class=""><?=lang('nom_reseau')?></th>
                        <th class=""><?=lang('mot_numero')?></th>
                        <th class=""><?=lang('dte_activ_th')?></th>
                        <th class=""><?=lang('dte_expiration_th')?></th>
                        <th class=""><?=lang('th_statut')?></th>
                        <th class=""><?=lang('validation_val')?></th>
                        <th class=""><?=lang('list_dte_enregistrement')?></th>
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


  <!--******** Modal pour assurance et controle technique *********-->

  <div class="modal fade" id="Modal_forfait" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class='modal-header' style='background:cadetblue;color:white;'>

          <h5 class="modal-title" id="titre"><?=lang('modal_renouvlement')?></h5>

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="form_forfait" enctype="multipart/form-data" action="#" method="post">
            <div class="modal-body mb-1">
              <div class="row">
                <input type="hidden" name="DEVICE_ID" id="DEVICE_ID">

                <div class="col-md-6">
                  <div class="form-group">
                    <label ><small> <?=lang('dte_activ_forfait')?></small><span  style="color:red;">*</span></label>

                    <input class="form-control" type='date' name="DATE_ACTIVE_MEGA" id="DATE_ACTIVE_MEGA" placeholder='' max="<?= date('Y-m-d')?>" onchange="get_date_expire();"/>

                  </div>
                  <span id="errorDATE_ACTIVE_MEGA" class="text-danger"></span>
                  <?php echo form_error('DATE_ACTIVE_MEGA', '<div class="text-danger">', '</div>'); ?>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label ><small> <?=lang('dte_expiration_forfait')?></small><span  style="color:red;">*</span></label>

                    <input class="form-control" type='date' name="DATE_EXPIRE_MEGA" id="DATE_EXPIRE_MEGA" placeholder='' readonly/>

                  </div>
                  <span id="errorDATE_EXPIRE_MEGA" class="text-danger"></span>
                  <?php echo form_error('DATE_EXPIRE_MEGA', '<div class="text-danger">', '</div>'); ?>
                </div>

              </div>
            </div> 
            <div class="modal-footer">

              <button type="button" class="btn btn-outline-primary rounded-pill" id="btnSave" onclick="save_forfait()"> <i class="fa fa-save"> </i> <?=lang('btn_enregistrer')?></button>

              <button type="reset" class='btn btn-outline-warning rounded-pill' style="float:right;" data-dismiss="modal" id="btnCancel"><i class="fa fa-close"> </i> <?=lang('btn_annuler')?></button>

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
    get_nbr_device();

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
        alert('<?=lang('msg_erreur')?>');
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
      $('#errorDATE_ACTIVE_MEGA').text('<?=lang('msg_validation')?>');
      statut = 2;
    }else{$('#errorDATE_ACTIVE_MEGA').text('');}

    if($('#DATE_EXPIRE_MEGA').val() == '<?=lang('msg_validation')?>')
    {
      $('#errorDATE_EXPIRE_MEGA').text('');
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
            text: '<?=lang('renouvlement_forfait_success')?>',
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

<script>
 function get_nbr_device()
 {

  $.ajax({
    url: "<?= base_url() ?>sim_management/Sim_management/get_nbr_device/",
    type: "POST",
    dataType: "JSON",
    success: function(data) {
     $('#nbr_device').text(data);
   },

 });
}
</script>

<script>
  function verif_check()
  {
    Swal.fire({
          icon: 'success',
          title: 'Annulé',
          text: '<?=lang('msg_modif_annule')?>',
          timer: 1000,
        }).then(() => {
          window.location.reload();
        })

    // var check = document.getElementById('myChecked');

    // if(check == false)
    // {
    //   check.checked = true;
    // }else{
    //   check.checked = false;
    // }

    // var uncheck = document.getElementById('myCheck');

    // if(uncheck == false)
    // {
    //   uncheck.checked = true;
    // }else{
    //   uncheck.checked = false;
    // }
    
  }
</script>

</html>