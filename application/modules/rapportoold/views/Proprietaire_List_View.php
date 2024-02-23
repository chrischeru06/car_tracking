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
      height: 34px;
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
    <div class="pagetitle">
      <div class="row">
        <div class="col-md-6">
          <h1>Propriétaires</h1>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.html">Propriétaire</a></li>
              <li class="breadcrumb-item active">Liste</li>
            </ol>
          </nav>
        </div>
        <div class="col-md-6">

          <div class="justify-content-sm-end d-flex">
            <a class="btn btn-secondary" href="<?=base_url('proprietaire/Proprietaire/index')?>"><i class="bi bi-plus"></i> Nouveau</a>
          </div>
        </div><!-- End Page Title -->
      </div>
    </div>
    <section class="section">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">
           <!-- Reports -->
           <div class="col-12">
            <div class="card">
              <div class="card-body">
                <br>
                <div class="row text-dark">
                  <div class="col-md-12">
                    <div class="row text-dark">

                      <div class="col-md-3">
                        <label class="text-dark" style="font-weight: 1000; color:#454545">Type de propriétaire</label>
                        <select class="form-control" id="TYPE_PROPRIETAIRE_ID" name="TYPE_PROPRIETAIRE_ID" onchange="change_type_personne();">
                          <option value="">Sélectionner</option>
                          <option value="1">Personne morale</option>
                          <option value="2">Personne physique</option>
                        </select>
                      </div>

                      <div class="col-md-3">
                        <label class="text-dark" style="font-weight: 1000; color:#454545">Statut propriétaire</label>
                        <select class="form-control" id="IS_ACTIVE" name="IS_ACTIVE" onchange="change_activation();">
                          <option value="">Sélectionner</option>
                          <option value="2">Désactivés</option>
                          <option value="1">Activés</option>
                        </select>
                      </div>

                      <div class="col-md-3">
                        <label class="text-dark" style="font-weight: 1000; color:#454545">Province</label>
                        <select class="form-control" id="PROVINCE_ID" name="PROVINCE_ID" onchange="change_province()">
                          <option value="0">Sélectionner</option>
                          <?php
                          foreach ($provinces as $key)
                          {
                            ?>
                            <option value="<?=$key['PROVINCE_ID']?>"><?=$key['PROVINCE_NAME']?></option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>

                      <div class="col-md-3">
                        <label class="text-dark" style="font-weight: 1000; color:#454545">Commune</label>
                        <select class="form-control" id="COMMUNE_ID" name="COMMUNE_ID" onchange="change_commune();">
                          <option value="">Sélectionner</option>
                        </select>
                      </div>

                    </div>

                  </div>

                </div>
                <br>
                <div class="table-responsive" style="padding-top: 20px;">
                  <table id="mytable" class="table table-hover" >
                    <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                      <tr>
                        <th class="text-dark">#</th>

                        <th class="text-dark">Identification&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="text-dark">Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="text-dark">Téléphone&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="text-dark">Statut&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="text-dark">Statut&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="text-dark">Option&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
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
<!------------------------ Modal detail proprietaire type physique' ------------------------>


<div class="modal fade" id="myModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <div class="row">
            <div class='col-md-6' id="div_info">
            </div>
            <div class='col-md-6'>
              <table class="table table-hover text-dark">
                <tr>            
                  <td><span class="fa fa-user"></span> &nbsp;&nbsp; Type</td>
                  <td><a id="DESC_TYPE_PROPRIETAIRE"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-user-plus"></span> &nbsp;&nbsp; Personne de référence</td>
                  <td><a id="PERSONNE_REFERENCE"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-newspaper-o"></span> &nbsp;&nbsp; CNI</td>
                  <td><a id="IDENTITE"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-phone"></span> &nbsp;&nbsp; Téléphone</td>
                  <td><a id="TELEPHONE"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-envelope-o"></span> &nbsp;&nbsp; Email</td>
                  <td><a id="EMAIL"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-bank"></span> &nbsp;&nbsp; Adresse</td>
                  <td><a id="ADRESSE"></a></td>
                </tr>


              </table>
            </div>
          </div>


          <!-- <div id="CNI"></div> -->



        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div><!-- End Modal-->

<!------------------------ Modal detail proprietaire type moral' ------------------------>

<div class="modal fade" id="myModal_Modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <div class="row">
            <table class="table table-hover text-dark">
              <tr>            
                <td><span class="fa fa-user"></span> &nbsp;&nbsp; Type</td>
                <td><a id="DESC_TYPE_PROPRIETAIRE_MORAL"></a></td>
              </tr>
              <tr>
                <td><span class="fa fa-user-plus"></span> &nbsp;&nbsp; Personne de référence</td>
                <td><a id="PERSONNE_REFERENCE_MORAL"></a></td>
              </tr>

              <tr>
                <td><span class="fa fa-phone"></span> &nbsp;&nbsp; Téléphone</td>
                <td><a id="TELEPHONE_MORAL"></a></td>
              </tr>
              <tr>
                <td><span class="fa fa-envelope-o"></span> &nbsp;&nbsp; Email</td>
                <td><a id="EMAIL_MORAL"></a></td>
              </tr>
              <tr>
                <td><span class="fa fa-bank"></span> &nbsp;&nbsp; Adresse</td>
                <td><a id="ADRESSE_MORAL"></a></td>
              </tr>
            </table>          
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div><!-- End Modal-->
<script>
  // Fonction pour le chargement de donnees par defaut
  $(document).ready( function ()
  {
    liste($('#TYPE_PROPRIETAIRE_ID').val(),0,0,$('#IS_ACTIVE').val());

    

  });
  
  //Fonction de filtrage de clients selon le type de personne
  function change_type_personne()
  {



    liste($('#TYPE_PROPRIETAIRE_ID').val(),$('#PROVINCE_ID').val(),$('#COMMUNE_ID').val(),$('#IS_ACTIVE').val());
  }

   //Fonction de filtrage de clients selon le statut
  function change_activation()
  {

    liste($('#TYPE_PROPRIETAIRE_ID').val(),$('#PROVINCE_ID').val(),$('#COMMUNE_ID').val(),$('#IS_ACTIVE').val());
  }

  function get_commune(PROVINCE_ID)
  {

  }

  function change_province()
  {
    get_commune($('#PROVINCE_ID').val());
    liste($('#TYPE_PROPRIETAIRE_ID').val(),$('#PROVINCE_ID').val(),0,$('#IS_ACTIVE').val());

    $.ajax(
    {
      url:"<?=base_url('proprietaire/Proprietaire/get_communes/')?>"+$('#PROVINCE_ID').val(),
      type: "GET",
      dataType:"JSON",
      success: function(data)
      {
        $('#COMMUNE_ID').html(data);
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        alert('Erreur');
      }
    });
    
  }


  function change_commune()
  {
    liste($('#TYPE_PROPRIETAIRE_ID').val(),$('#PROVINCE_ID').val(),$('#COMMUNE_ID').val(),$('#IS_ACTIVE').val());
  }

  function liste(TYPE_PROPRIETAIRE_ID,PROVINCE_ID,COMMUNE_ID,IS_ACTIVE)
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
        url: "<?php echo base_url('proprietaire/Proprietaire/listing'); ?>",
        type: "POST",
        data: {TYPE_PROPRIETAIRE_ID:TYPE_PROPRIETAIRE_ID,PROVINCE_ID:PROVINCE_ID,COMMUNE_ID:COMMUNE_ID,IS_ACTIVE:IS_ACTIVE},
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

 function myFunction(PROPRIETAIRE_ID) {
  // Get the checkbox
  var checkBox = document.getElementById("myCheck");
  // Get the output text

  var status=$('#status').val();
  status=2;
  var form_data = new FormData($("#myform_checked")[0]);
  $.ajax(
  {
    url:"<?=base_url()?>proprietaire/Proprietaire/active_desactive/"+status+'/'+PROPRIETAIRE_ID,

    type: 'POST',
    dataType:'JSON',
    data: form_data ,
    contentType: false,
    cache: false,
    processData: false,
    success: function(data)
    {
      window.location.href='<?=base_url('')?>proprietaire/Proprietaire/liste';

    }
  });

}

function myFunction_desactive(PROPRIETAIRE_ID) {
  // Get the checkbox
  var checkBox = document.getElementById("myCheck");
  // Get the output text

  var status=$('#status').val();

  status=1;

  var form_data = new FormData($("#myform_check")[0]);
  $.ajax(
  {
    url:"<?=base_url()?>proprietaire/Proprietaire/active_desactive/"+status+'/'+PROPRIETAIRE_ID,

    type: 'POST',
    dataType:'JSON',
    data: form_data ,
    contentType: false,
    cache: false,
    processData: false,
    success: function(data)
    {
      window.location.href='<?=base_url('')?>proprietaire/Proprietaire/liste';
    }
  });

}
</script>

</html>