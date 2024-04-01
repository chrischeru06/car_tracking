\<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>
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
   <div class="row page-titles mx-0">
      <div class="col-sm-10 p-md-0">
        <div class="welcome-text">
          <center>
         <table>
          <tr>
            <td>  
              <h4 class="text-dark text-center" style="margin-bottom: 1px;">Liste des motifs</h4>
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

      <a class="btn btn-outline-primary rounded-pill" onclick="popup_modal();" class="nav-link position-relative"><i class="bi bi-plus"></i> Nouveau</a>

    </div>
  </div>


  <!-- End Page Title -->
 

  <!--******** Debut Modal pour inserer motif *********-->
  <div class="modal fade" id="update_motif" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class='modal-header' style='background:cadetblue;color:white;'>      
        <h5 class="modal-title">Modification du motif</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <form id="myform1" method="post">
            <div class="row">

             <!-- <div class="col-md-12" id="div_categorie">
 -->
              <input type="hidden" name="ID_MOTIF" id="ID_MOTIF" class="form-control">
<!-- 
              <label class="text-dark">Catégorie de motif <font color="red">*</font></label>
              <select class="form-control" id="ID_CATEGORIE1" name="ID_CATEGORIE1" onchange="verif_cat()">
              </select>

              <font class="text-danger" id="error_categorie_motif1"></font>
            </div> -->

            <div class="col-md-12" id="div_type">
              <label class="text-dark">Type de motif <font color="red">*</font></label>
              <select class="form-control" id="ID_TYPE1" name="ID_TYPE1" >

              </select>

              <font class="text-danger" id="error_type_motif1"></font>
            </div>

            <div class="col-md-12" id="div_desc">
              <label class="text-dark">Motif <font color="red">*</font></label>
              <input type="text" name="DESC_MOTIF1" id="DESC_MOTIF1" class="form-control">
              <font class="text-danger" id="error_motif1"></font>
            </div>

          </div>
          <div class="modal-footer">
           
              <button type="button" class="btn btn-outline-primary rounded-pill " id="btnSave1" onclick="valider1()">  Modifier</button>
          </div>
        </form>

       </div>
    </div>
  </div>
</div>
  <!--******** fin Modal pour inserer motif  ***********-->

    <!--******** Debut Modal pour modification motif *********-->
  <div class="modal fade" id="add_motif" tabindex="-1" >
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class='modal-header' style='background:cadetblue;color:white;'>      
        <h5 class="modal-title">Ajout d'un motif </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

           <form id="myform" method="post">
              <div class="row">

              <!--  <div class="col-md-12" id="div_categorie">
                <label class="text-dark">Catégorie de motif <font color="red">*</font></label>
                <select class="form-control" id="ID_CATEGORIE" name="ID_CATEGORIE" onchange="verif_cat()">
                  <option value="">-- Sélectionner --</option>
                  <?php
                  foreach ($categorie as $key) 
                  {
                    echo "<option value=".$key['ID_CATEGORIE'].">".$key['DESC_CATEGORIE']."</option>";
                  }
                  ?>
                </select>

                <font class="text-danger" id="error_categorie_motif"></font>
              </div> -->

              <div class="col-md-12" id="div_type">
                <label class="text-dark">Type de motif <font color="red">*</font></label>
                <select class="form-control" id="ID_TYPE" name="ID_TYPE" >
                  <option value="">-- Sélectionner --</option>
                  <?php
                  foreach ($type as $key) 
                  {
                    echo "<option value=".$key['ID_TYPE'].">".$key['DESC_TYPE']."</option>";
                  }
                  ?>
                </select>

                <font class="text-danger" id="error_type_motif"></font>
              </div>

              <div class="col-md-12" id="div_desc">
                <label class="text-dark">Motif <font color="red">*</font></label>
                <textarea cols='50' rows='7' name='DESC_MOTIF' id='DESC_MOTIF' class='form-control'></textarea> <br>
                <font class="text-danger" id="error_motif"></font>
              </div>

            </div>
           

              <div class="modal-footer">
           
              <button type="button" class="btn btn-outline-primary rounded-pill " id="btnSave1" onclick="valider()">  Enregistrer</button>
           </div>
          </form>
       </div>
    </div>
  </div>
</div>
  <!--******** fin Modal pour modification motif  ***********-->

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
                        <!--  <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                          <tr>

                            <th class="text-dark">#</th>
                            <th class="text-dark">CHAUFFEUR</th>
                            <th class="text-dark">TELEPHONE</th>
                            <th class="text-dark">EMAIL</th>
                            <th class="text-dark">STATUT</th>
                            <th class="text-dark">OPTIONS</th>
                          </tr>
                        </thead> -->
                        <thead class="text-dark">
                    <tr>
                      <td>#</td>
                      <!-- <td class="text-dark"><b>CATEGORIE</b> </td> -->
                      <td class="text-dark"><b>TYPE</b> </td>
                      <td class="text-dark"><b>DESCRIPTION</b> </td>
                      <td class="text-dark"><b>Action</b></td>
                    </tr>
                  </thead >
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
        url:"<?php echo base_url('motif/Motif/listing');?>",
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
  function popup_modal()
  {
    $('#btnSave').attr('disabled',false);
    $('#add_motif').modal('show');
  }
   //fonction d'insertion
  function valider()
  {

   // var ID_CATEGORIE = $('#ID_CATEGORIE').val();
   var ID_TYPE = $('#ID_TYPE').val();
   var DESC_MOTIF = $('#DESC_MOTIF').val();

   statut = 1;

  //  if(ID_CATEGORIE=='')
  //  {
  //   $("#error_categorie_motif").text("Le champ est obligatoire !");
  //   statut = 2;
  // }else{$("#error_categorie_motif").text('');}

    // if(ID_CATEGORIE != 4) // 2 represente la carte
    // {
      if(ID_TYPE=="")
      {
        $("#error_type_motif").text("Le champ est obligatoire !");
        statut = 2;
      }else{$("#error_type_motif").text("");}
    // }
    
    if(DESC_MOTIF=="")
    {
      $("#error_motif").text("Le champ est obligatoire !");
      statut = 2;
    }else{$("#error_motif").text("");}
    if(statut==1)
    {
      $('#btnSave').text('Enregistrement Encours.....');
      $('#btnSave').attr("disabled",true);
      var url;   
      url="<?php echo base_url('motif/Motif/save')?>";
      var formData = new FormData($('#myform')[0]);
      $.ajax({
        url:url,
        type:"POST",
        data:formData,
        contentType:false,
        processData:false,
        dataType:"JSON",
        success: function(data)
        {
          if(data.status==1)
          {
            $('#myform')[0].reset();
            $('#btnSave').attr('disabled',true);
            $('#add_motif').modal('hide');
            $('#message_succ').html("<div class='alert alert-success  col-md-12'>"+data.message+"</div>");
            $('#message_succ').delay(5000).hide('slow');
            liste();
          }
          else if (data.status==2)
          {
            $('#error_motif').html(data.message);
          }
          else
          {
            for (var i = 0; i < data.inputerror.length; i++) 
            {
             $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); 
             $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); 
           }
         }
         $('#btnSave').text('Enregistrer');
         $('#btnSave').attr('disabled',false); 
       },
       error: function (jqXHR, textStatus,photo, errorThrown)
       {
         alert('Erreur s\'est produite');
         $('#btnSave').text('Enregistrer');
         $('#btnSave').attr('disabled',false);
       }
     });
    }
  }

  //fonction de mofification
  function valider1()
  {

   // var ID_CATEGORIE1 = $('#ID_CATEGORIE1').val();
   var ID_TYPE1 = $('#ID_TYPE1').val();
   var DESC_MOTIF1 = $('#DESC_MOTIF1').val();

   statut = 1;

  //  if(ID_CATEGORIE1=='')
  //  {
  //   $("#error_categorie_motif1").text("Le champ est obligatoire !");
  //   statut = 2;
  // }else{$("#error_categorie_motif1").text('');}

    // if(ID_CATEGORIE1 != 4) // 2 represente la carte
    // {
      if(ID_TYPE1=="")
      {
        $("#error_type_motif1").text("Le champ est obligatoire !");
        statut = 2;
      }else{$("#error_type_motif1").text("");}
    // }

    if(DESC_MOTIF1=="")
    {
      $("#error_motif1").text("Le champ est obligatoire !");
      statut = 2;
    }else{$("#error_motif1").text("");}


    if(statut==1)
    {
      $('#btnSave1').text('Encours...');
      $('#btnSave1').attr("disabled",true);
      var url;   
      url="<?php echo base_url('motif/Motif/update')?>";
      var formData = new FormData($('#myform1')[0]);
      $.ajax({
        url:url,
        type:"POST",
        data:formData,
        contentType:false,
        processData:false,
        dataType:"JSON",
        success: function(data)
        {
          if(data.status==1)
          {
            $('#myform1')[0].reset();
            $('#btnSave1').attr('disabled',true);
            $('#update_motif').modal('hide');
            $('#message_succ').html("<div class='alert alert-success  col-md-12'>"+data.message+"</div>");
            $('#message_succ').delay(5000).hide('slow');
            liste();
          }
          else if (data.status==2)
          {
            $('#error_motif1').html(data.message);
          }
          else
          {
            for (var i = 0; i < data.inputerror.length; i++) 
            {
             $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); 
             $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); 
           }
         }
         $('#btnSave1').text('Modifier');
         $('#btnSave1').attr('disabled',false); 
       },
       error: function (jqXHR, textStatus,photo, errorThrown)
       {
         alert('Erreur s\'est produite');
         $('#btnSave1').text('Modifier');
         $('#btnSave1').attr('disabled',false);
       }
     });
    }
  }

   function edit_motif(id)
  {

    $('#update_motif').modal('show');
    $('.form-group').removeClass('has-error');
    $('#myform1')[0].reset();
    $('.help-block').empty();
    $.ajax({
      url: "<?= base_url() ?>motif/Motif/editer/" + id,
      type: "POST",
      dataType: "JSON",
      success: function(data) {

        $('#ID_MOTIF').val(data.ID_MOTIF);
        // $('#ID_CATEGORIE1').html(data.html_cat);
        $('#ID_TYPE1').html(data.html_type);
        $('#DESC_MOTIF1').val(data.DESC_MOTIF);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log('Erreur : ' + textStatus);
      }
    });
  }

</script>


</html>