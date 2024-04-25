<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>

  <style type="text/css">
.btn-md:hover{
      background-color: rgba(210, 232, 249,100);
      border-radius: 5px;
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
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
<link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
<link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
<link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
<link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
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
          <i class="bi bi-user-plus"></i> <h1>Propriétaires</h1>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.html">Propriétaire</a></li>
              <li class="breadcrumb-item ">Liste</li>
            </ol>
          </nav>
        </div>
        
        <div class="col-md-6">

          <div class="justify-content-sm-end d-flex">
            <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('proprietaire/Proprietaire/index')?>"><i class="bi bi-plus"></i> Nouveau</a>
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
                        <label class="text-dark" style="font-weight: 1000; color:#454545">Propriétaire</label>
                        <select class="form-control" id="TYPE_PROPRIETAIRE_ID" name="TYPE_PROPRIETAIRE_ID" onchange="change_type_personne();get_nbr_proprio();">
                          <option value="0">Sélectionner</option>
                          <option value="1">Personne morale</option>
                          <option value="2">Personne physique</option>
                        </select>
                      </div>
                      <div class="col-md-1">
                        <span class="badge bg-primary rounded-pill nbr_proprio" style="font-size:10px;position:relative;top:6px;left:-23px;"></span>
                      </div>
                      <div class="col-md-3">
                        <label class="text-dark" style="font-weight: 1000; color:#454545">Statut</label>
                        <select class="form-control" id="IS_ACTIVE" name="IS_ACTIVE" onchange="change_activation();get_nbr_proprio();">
                          <option value="0">Sélectionner</option>
                          <option value="2">Désactivé</option>
                          <option value="1">Activé</option>
                        </select>
                      </div>

                      <div class="col-md-3">
                        <label style="font-weight: 1000; color:#454545">Pays</label>
                        <div class="input-group has-validation">

                          <select onchange="localisation();get_nbr_proprio();"  class="form-control" name="COUNTRY_ID" id="COUNTRY_ID">
                           <option value="0">Sélectionner</option>

                           <?php
                           foreach($pays as $key) { 
                            if ($key['COUNTRY_ID']==set_value('COUNTRY_ID')) { 
                             echo "<option value='".$key['COUNTRY_ID']."' selected>".$key['CommonName']."</option>";
                           }  else{
                             echo "<option value='".$key['COUNTRY_ID']."' >".$key['CommonName']."</option>"; 
                           } }?>
                         </select>

                         <div class="valid-feedback">
                         </div>
                       </div>
                       <span class="text-danger" id="errorcountry"></span>
                       <?php echo form_error('COUNTRY_ID','<div class="text-danger">', '</div>'); ?>


                     </div>

                     <div class="col-md-3" id="div_prov">
                      <label class="text-dark" style="font-weight: 1000; color:#454545">Province</label>
                      <select onchange="change_province();get_nbr_proprio();" class="form-control" id="PROVINCE_ID" name="PROVINCE_ID">
                        <option value="0">Sélectionner</option>
                       
                         <?php
                           foreach($provinces as $key) { 
                            if ($key['PROVINCE_ID']==set_value('PROVINCE_ID')) { 
                             echo "<option value='".$key['PROVINCE_ID']."' selected>".$key['PROVINCE_NAME']."</option>";
                           }  else{
                             echo "<option value='".$key['PROVINCE_ID']."' >".$key['PROVINCE_NAME']."</option>"; 
                           } }?>
                      </select>
                    </div>

                    <div class="col-md-3" id="div_com">
                      <label class="text-dark" style="font-weight: 1000; color:#454545">Commune</label>
                      <select class="form-control" id="COMMUNE_ID" name="COMMUNE_ID" onchange="change_commune();get_nbr_proprio();">
                        <option value="0">Sélectionner</option>
                      </select>
                    </div>

                  </div>

                </div>

              </div>
              <br>
              <div class="table-responsive" style="padding-top: 20px;">
                <table id="mytable" class="table table-hover" style="min-width: 100%">
                  <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                    <tr>
                      <th class="text-dark">#</th>
                      <th class="text-dark">PROPRIETAIRE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                      <th class="text-dark">TYPE&nbsp;PROPRIETAIRE</th>
                      <th class="text-dark">EMAIL</th>
                      <th class="text-dark">TELEPHONE</th>
                      <th>STATUT</th>
                      <th>NBR&nbsp;VEHICULE</th>
                      <th class="text-dark">OPTIONS</th>
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
      <div class='modal-header' style='background:cadetblue;color:white;'>            
        <h5 class="modal-title">Détails</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <div class="row">
            <div class='col-md-6' id="div_info">
            </div>
            <div class='col-md-6'>
              <table class="table table-borderless">
                <tr>            
                  <td><span class="fa fa-user"></span> &nbsp;&nbsp; Type</td>
                  <td><a id="DESC_TYPE_PROPRIETAIRE"></a></td>
                </tr>
                <tr>
                  <td><span class="fa fa-user-plus"></span> &nbsp;&nbsp; Personne de référence</td>
                  <td><a id="PERSONNE_REFERENCE"></a></td>
                </tr>
                
                
                <a id="cni_physique"></a>

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

                <div class="icon">
                  <tr>
                    <td><i class="bi bi-file-earmark-pdf-fill"></i> &nbsp;&nbsp; <a id="label_doc"></a></td>
                    <td><button onclick="affiche_doc();"><span class="bi bi-eye"></span></button></td>
                  </tr>
                </div>

              </table>
            </div>
          </div>


          <!-- <div id="CNI"></div> -->



        </div>

      </div>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div> -->
    </div>
  </div>
</div><!-- End Modal-->

<!------------------------ Modal detail proprietaire type moral' ------------------------>

<div class="modal fade" id="myModal_Modal" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'>      
        <h5 class="modal-title">Détails</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <div class="row">
            <div class="col-md-12">

              <table class="table table-borderless">
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
                <div class="icon">
                  <tr>
                    <td><i class="bi bi-file-earmark-pdf-fill"></i> &nbsp;&nbsp; <a id="label_doc"></a></td>
                    <td><button onclick="affiche_doc();"><span class="bi bi-eye"></span></button></td>
                  </tr>
                </div>

              </table> 
            </div>         
          </div>
        </div>
      </div>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div> -->
    </div>
  </div>
</div>

<!------------------------ Modal documents proprietaire type moral' ------------------------>

<div class="modal fade" id="myModal_Modal_doc" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class='modal-header' style='background:cadetblue;color:white;'>      
        <h5 class="modal-title">Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <div class="row">
            <div id="div_info_doc_mor">


            </div>         
          </div>
        </div>
      </div>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div> -->
    </div>
  </div>
</div><!-- End Modal-->


<!--******** Debut Modal pour motif_activation *********-->
<?php
$desc_button='';
?>


<div class="modal fade" id="Modal_active" tabindex="-1" >
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
              <input type="hidden" name="PROPRIETAIRE_ID" id="PROPRIETAIRE_ID">

               <!--  <div class = 'col-md-8'>
                <label style='color:black'>Motif</label>
                <textarea class='form-control' name ="MOTIF_DESACT_ACTIVATION" id="MOTIF_DESACT_ACTIVATION"></textarea>
                <span id="errorMOTIF_DESACT_ACTIVATION" class="text-danger"></span>
              </div> -->
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
<?php
$desc_button='';
?>


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
              <input type="hidden" name="PROPRIETAIRE_ID_des" id="PROPRIETAIRE_ID_des">

               <!--  <div class = 'col-md-8'>
                <label style='color:black'>Motif</label>
                <textarea class='form-control' name ='MOTIF_DESACT_ACTIVATION_des' id="MOTIF_DESACT_ACTIVATION_des"></textarea>
                <span id="errorMOTIF_DESACT_ACTIVATION_des" class="text-danger"></span>
              </div> -->
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
<script>
  // Fonction pour le chargement de donnees par defaut
  $(document).ready( function ()
  {
    liste($('#TYPE_PROPRIETAIRE_ID').val(),0,0,$('#IS_ACTIVE').val(),$('#COUNTRY_ID').val());
    $('#div_prov').attr('hidden',true); 
    $('#div_com').attr('hidden',true);  
    get_nbr_proprio();

  });


  function localisation(){
    var COUNTRY_ID=$('#COUNTRY_ID').val();
    
    liste($('#TYPE_PROPRIETAIRE_ID').val(),$('#PROVINCE_ID').val(),$('#COMMUNE_ID').val(),$('#IS_ACTIVE').val(),$('#COUNTRY_ID').val());

    if (COUNTRY_ID==28) {
     $('#div_prov').attr('hidden',false);
     $('#div_com').attr('hidden',false);


   }else{
    $('#div_prov').attr('hidden',true); 
    $('#div_com').attr('hidden',true);  


  }
}

  //Fonction de filtrage de clients selon le type de personne
function change_type_personne()
{



  liste($('#TYPE_PROPRIETAIRE_ID').val(),$('#PROVINCE_ID').val(),$('#COMMUNE_ID').val(),$('#IS_ACTIVE').val(),$('#COUNTRY_ID').val());
}

   //Fonction de filtrage de clients selon le statut
function change_activation()
{

  liste($('#TYPE_PROPRIETAIRE_ID').val(),$('#PROVINCE_ID').val(),$('#COMMUNE_ID').val(),$('#IS_ACTIVE').val(),$('#COUNTRY_ID').val());
}

// function get_commune(PROVINCE_ID)
// {

// }

function change_province()
{
  // get_commune($('#PROVINCE_ID').val());
  liste($('#TYPE_PROPRIETAIRE_ID').val(),$('#PROVINCE_ID').val(),0,$('#IS_ACTIVE').val(),$('#COUNTRY_ID').val());

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
  liste($('#TYPE_PROPRIETAIRE_ID').val(),$('#PROVINCE_ID').val(),$('#COMMUNE_ID').val(),$('#IS_ACTIVE').val(),$('#COUNTRY_ID').val());
}

function liste(TYPE_PROPRIETAIRE_ID,PROVINCE_ID,COMMUNE_ID,IS_ACTIVE,COUNTRY_ID)
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
      data: {TYPE_PROPRIETAIRE_ID:TYPE_PROPRIETAIRE_ID,PROVINCE_ID:PROVINCE_ID,COMMUNE_ID:COMMUNE_ID,IS_ACTIVE:IS_ACTIVE,COUNTRY_ID:COUNTRY_ID},
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
    $('#div_info_doc_mor').html(data.fichier_cni);
    $('#label_doc').html(data.label_doc);
    $('#ADRESSE').html(data.ADRESSE);
    $('#cni_physique').html(data.cni_physique);


  },

});


}

//detail personne moral
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
    $('#div_info_doc_mor').html(data.fichier_cni);
    $('#ADRESSE_MORAL').html(data.ADRESSE);
    $('#label_doc').html(data.label_doc);
    






  },

});

}

function myFunction(PROPRIETAIRE_ID) 
{
  var PROPRIETAIRE_ID=$('#PROPRIETAIRE_ID').val(PROPRIETAIRE_ID);

  $('#Modal_active').modal('show'); 
}

function save_motif_active()
{
  var statut=1;
  $('#errorID_MOTIF').html('');

  if($('#ID_MOTIF').val()=='')
  {
    $('#errorID_MOTIF').html('Le champ est obligatoire');
    statut=2;
  }
  if (statut==1) {

    var checkBox = document.getElementById("myCheck");
  // Get the output text
    var PROPRIETAIRE_ID=$('#PROPRIETAIRE_ID').val();

    var status=$('#status').val();
    status=1;
    var form_data = new FormData($("#active_form")[0]);
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
           window.location.href='<?=base_url('')?>proprietaire/Proprietaire/liste';
         });
        }
      }
    });
  }
  

}

function myFunction_desactive(PROPRIETAIRE_ID) 
{

 var PROPRIETAIRE_ID_des=$('#PROPRIETAIRE_ID_des').val(PROPRIETAIRE_ID);

 $('#Modal_desactivation').modal('show'); 
}


function save_motif_desactive()
{
  var statut=1;
  $('#errorID_MOTIF_des').html('');

  if($('#ID_MOTIF_des').val()=='')
  {
    $('#errorID_MOTIF_des').html('Le champ est obligatoire');
    statut=2;
  }

  if (statut==1) {

   var checkBox = document.getElementById("myCheck");
  // Get the output text
   var PROPRIETAIRE_ID_des=$('#PROPRIETAIRE_ID_des').val();

   var status=$('#status').val();
   status=2;
   var form_data = new FormData($("#desactive_form")[0]);
   $.ajax(
   {
    url:"<?=base_url()?>proprietaire/Proprietaire/active_desactive/"+status+'/'+PROPRIETAIRE_ID_des,

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
         window.location.href='<?=base_url('')?>proprietaire/Proprietaire/liste';
       });
      }
    }
  });
 }
 

}

//   var checkBox = document.getElementById("myCheck");
//   // Get the output text

//   var status=$('#status').val();

//   status=1;

//   var form_data = new FormData($("#myform_check")[0]);
//   $.ajax(
//   {
//     url:"<?=base_url()?>proprietaire/Proprietaire/active_desactive/"+status+'/'+PROPRIETAIRE_ID,

//     type: 'POST',
//     dataType:'JSON',
//     data: form_data ,
//     contentType: false,
//     cache: false,
//     processData: false,
//     success: function(data)
//     {
//       window.location.href='<?=base_url('')?>proprietaire/Proprietaire/liste';
//     }
//   });

// }

function affiche_doc(){

 $("#myModal_Modal_doc").modal("show");



}

 function get_nbr_proprio()
 {
  var TYPE_PROPRIETAIRE_ID = $('#TYPE_PROPRIETAIRE_ID').val();
  var IS_ACTIVE = $('#IS_ACTIVE').val();
  var COUNTRY_ID = $('#COUNTRY_ID').val();
  var PROVINCE_IDD = $('#PROVINCE_ID').val();
  var COMMUNE_IDS = $('#COMMUNE_ID').val();
  $.ajax({
    url: "<?= base_url() ?>proprietaire/Proprietaire/get_nbr_proprio/"+TYPE_PROPRIETAIRE_ID+'/'+IS_ACTIVE+'/'+COUNTRY_ID+'/'+PROVINCE_IDD+'/'+COMMUNE_IDS,
    type: "POST",
    dataType: "JSON",
    success: function(data) {
     $('.nbr_proprio').text(data);
   },

 });
}


</script>

</html>