<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>

  <style>

    .dropdown-toggle{

      cursor: pointer;


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

.dropdown-toggle{

  cursor: pointer;
  

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
    <div class="pagetitle">
      <div class="row">
        <div class="col-md-6">
          <h1><font class="fa fa-user" style="font-size:18px;"></font>  <?=lang('Utilisateurs_lng')?></h1>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.html"><?=lang('Utilisateurs_lng')?></a></li>
              <li class="breadcrumb-item active">Liste</li>
            </ol>
          </nav>
        </div>
        <!-- <div class="col-md-6">

          <div class="justify-content-sm-end d-flex">
            <a class="btn btn-outline-primary" href="<?=base_url('administration/Profil/add')?>"><i class="bi bi-plus h5"></i> Nouveau</a>
          </div>
        </div> --><!-- End Page Title -->
      </div>
    </div>
    <section class="section">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">
           <!-- Reports -->
           <?= $this->session->flashdata('message');?>

           <div class="col-12">
            <div class="card">
              <div class="card-body">
                
                <div class="table-responsive" style="padding-top: 20px;">
                  <table id="mytable" class="table table-hover" style="padding-top: 20px;">
                    <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                      <tr>
                        <th class="text-dark">#</th>
                        <th class="text-dark"><?=lang('mot_nom_prnom')?></th>
                        <th class="text-dark"><?=lang('th_email')?></th>
                        <th class="text-dark"><?=lang('th_tlphone')?></th>
                        <th class="text-dark"><?=lang('profil_maj')?></th>
                        <th class="text-dark"><?=lang('th_statut')?></th>
                        <th class="text-dark" style="float: right;"><?=lang('th_options')?></th>
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

<!------------------------ Modal detail proprietaire type physique' ------------------------>


<div class="modal fade" id="myModal" tabindex="-1" data-bs-backdrop="false">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'>
        <h5 class="modal-title"><?=lang('btn_detail')?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <div class="row">
            <div class='col-md-6' id="div_info">
            </div>
            <div class='col-md-6'>
              <table class="table table-striped">
                <tr>            
                  <td><span class="fa fa-user"></span> &nbsp;&nbsp; <?=lang('td_type')?></td>
                  <td><a id="DESC_TYPE_PROPRIETAIRE"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-user-plus"></span>  <?=lang('td_pers_reference')?></td>
                  <td><a id="PERSONNE_REFERENCE"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-newspaper-o"></span> &nbsp;&nbsp; <?=lang('input_cni_passeport')?></td>
                  <td><a id="IDENTITE"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-phone"></span> &nbsp;&nbsp; <?=lang('input_tlphone')?></td>
                  <td><a id="TELEPHONE"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-envelope-o"></span> &nbsp;&nbsp; <?=lang('input_email')?></td>
                  <td><a id="EMAIL"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-bank"></span> &nbsp;&nbsp; <?=lang('input_adresse')?></td>
                  <td><a id="ADRESSE"></a></td>
                </tr>


              </table>
            </div>
          </div>


          <!-- <div id="CNI"></div> -->



        </div>

      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div> -->
    </div>
  </div>
</div><!-- End Modal-->

<!------------------------ Modal detail proprietaire type moral' ------------------------>

<div class="modal fade" id="myModal_Modal" tabindex="-1" data-bs-backdrop="false">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'>
        <h5 class="modal-title"><?=lang('btn_detail')?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <div class="row">
            <table class="table table-striped">
              <tr>            
                <td><span class="fa fa-user"></span> &nbsp;&nbsp; <?=lang('td_type')?></td>
                <td><a id="DESC_TYPE_PROPRIETAIRE_MORAL"></a></td>
              </tr>
              <tr>
                <td><span class="fa fa-user-plus"></span> &nbsp;&nbsp; <?=lang('td_pers_reference')?></td>
                <td><a id="PERSONNE_REFERENCE_MORAL"></a></td>
              </tr>

              <tr>
                <td><span class="fa fa-phone"></span> &nbsp;&nbsp; <?=lang('input_tlphone')?></td>
                <td><a id="TELEPHONE_MORAL"></a></td>
              </tr>
              <tr>
                <td><span class="fa fa-envelope-o"></span> &nbsp;&nbsp; <?=lang('input_email')?></td>
                <td><a id="EMAIL_MORAL"></a></td>
              </tr>
              <tr>
                <td><span class="fa fa-bank"></span> &nbsp;&nbsp; <?=lang('input_adresse')?></td>
                <td><a id="ADRESSE_MORAL"></a></td>
              </tr>
            </table>          
          </div>
        </div>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div> -->
    </div>
  </div>
</div><!-- End Modal-->

<script>
  // Fonction pour le chargement de donnees par defaut
  $(document).ready( function ()
  {
    liste();

    

  });
  


  function liste()
  {

    var row_count = 10000;
    $('#message').delay('slow').fadeOut(3000);
    $("#mytable").DataTable(
    {
      "destroy": true,
      "processing": true,
      "serverSide": true,
      "oreder": [],
      "ajax":
      {
        url: "<?php echo base_url('administration/Users/listing'); ?>",
        type: "POST",
        data: {},
        beforeSend: function()
        {
        }
      },
      lengthMenu:
      [
        [10, 50, 100, row_count],
        [10, 50, 100, "All"]
        ],
      pageLength: 10,
      "columnDefs": [
      {
        "targets": [],
        "orderable": false
      }],
      dom: 'Bfrtlip',
      buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
      language:
      {
        "sProcessing": "Traitement en cours...",
        "sSearch": "Recherche&nbsp;:",
        "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
        "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
        "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
        "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        "sInfoPostFix": "",
        "sLoadingRecords": "Chargement en cours...",
        "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
        "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
        "oPaginate":
        {
          "sFirst": "Premier",
          "sPrevious": "Pr&eacute;c&eacute;dent",
          "sNext": "Suivant",
          "sLast": "Dernier"
        },
        "oAria":
        {
          "sSortAscending": ": activer pour trier la colonne par ordre croissant",
          "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
        }
      }
    });
  }


</script>

<script>
 function myFunction(USER_ID) {
  // Get the checkbox
  var checkBox = document.getElementById("myCheck");
  // Get the output text

  var status=$('#status').val();
  status=2;
  var form_data = new FormData($("#myform_checked")[0]);
  $.ajax(
  {
    url:"<?=base_url()?>administration/Users/active_desactive/"+status+'/'+USER_ID,

    type: 'POST',
    dataType:'JSON',
    data: form_data ,
    contentType: false,
    cache: false,
    processData: false,
    success: function(data)
    {
      window.location.href='<?=base_url('')?>administration/Users/index';

    }
  });

}

function myFunction_desactive(USER_ID) {

  if(USER_ID==1){

    var url="<?= base_url('administration/Users/non_desactive_user')?>";
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
            text: '<?=lang('error_desactivation')?>',
            timer: 3000,
          }).then(() =>
          {
            window.location.reload('<?=base_url('administration/Users')?>');
          });
        }
      }
    });
  }else{

    // Get the checkbox
    var checkBox = document.getElementById("myCheck");
  // Get the output text

    var status=$('#status').val();

    status=1;

    var form_data = new FormData($("#myform_check")[0]);
    $.ajax(
    {
      url:"<?=base_url()?>administration/Users/active_desactive/"+status+'/'+USER_ID,

      type: 'POST',
      dataType:'JSON',
      data: form_data ,
      contentType: false,
      cache: false,
      processData: false,
      success: function(data)
      {
        window.location.href='<?=base_url('')?>administration/Users/index';
      }
    });


  }
  

}
</script>

<script>

  function get_detail(PROPRIETAIRE_ID)
  {
   $("#myModal").modal("show");
   $.ajax({
    url: "<?= base_url() ?>proprietaire/Proprietaire/get_detail/" + PROPRIETAIRE_ID,
    type: "POST",
    dataType: "JSON",
    success: function(data) {

      // alert(data.CNI)

      $('#IDENTITE').html(data.CNI);
      $('#TELEPHONE').html(data.TELEPHONE);
      $('#EMAIL').html(data.EMAIL);
      $('#DESC_TYPE_PROPRIETAIRE').html(data.DESC_TYPE_PROPRIETAIRE);
      $('#PERSONNE_REFERENCE').html(data.PERSONNE_REFERENCE);
      $('#div_info').html(data.div_info);
      $('#ADRESSE').html(data.ADRESSE);

    },

  });


 }

 function get_detail_pers_moral(PROPRIETAIRE_ID) {


   $("#myModal_Modal").modal("show");
   $.ajax({
    url: "<?= base_url() ?>proprietaire/Proprietaire/get_detail/" + PROPRIETAIRE_ID,
    type: "POST",
    dataType: "JSON",
    success: function(data) {


      // alert(data.CNI)

      // $('#IDENTITE').html(data.CNI);
      $('#TELEPHONE_MORAL').html(data.TELEPHONE);
      $('#EMAIL_MORAL').html(data.EMAIL);
      $('#DESC_TYPE_PROPRIETAIRE_MORAL').html(data.DESC_TYPE_PROPRIETAIRE);
      $('#PERSONNE_REFERENCE_MORAL').html(data.PERSONNE_REFERENCE);
      // $('#div_info').html(data.div_info);
      $('#ADRESSE_MORAL').html(data.ADRESSE);





    },

  });

 }
</script>

</html>