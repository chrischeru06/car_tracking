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
        </center><i class="bi bi-bell"></i>
      </div>-->
      <h1><i class="bi bi-bell" style="font-size:18px;"></i> <font>Notifications</font></h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Notifications</a></li>
          <li class="breadcrumb-item "><?=lang('title_list')?></li>
        </ol>
      </nav>
    </div>
    <div class="col-md-2">

    </div>

  </div>

</div>

<section class="section dashboard">
  <div class="row">
    <div class="row">
      <!-- Reports -->
      <div class="col-12">
        <ul class="nav nav-tabs nav-tabs-bordered">

          <li class="nav-item">
            <button class="nav-link active btn-sm" data-bs-toggle="tab" data-bs-target="#all_devices"><label class=""><img src="<?= base_url()?>/upload/icon_total1.png" style="width: 15px;"> Toutes les notifications (<font id="nbr_notif">0</font>) </label></button>
          </li>

          <li class="nav-item">
            <button class="nav-link btn-sm" data-bs-toggle="tab" data-bs-target="#forfait_proche_exp"><label class=""><i class="fa fa-rotate-right text-warning"></i> Proches de l'expiration (<font id="nbr_forfait_proche_exp">0</font>)</label></button>
          </li>

          <li class="nav-item">
            <button class="nav-link btn-sm" data-bs-toggle="tab" data-bs-target="#forfaits_expires"><label class=""><i class="fa fa-ban text-danger"></i> Forfaits expirés (<font id="nbr_forfait_expire">0</font>)</label></button>
          </li>

        </ul>
      </div>
    </div>
  </div>
</section><br>


<section class="section">
 <div class="">
  <div class="row">
    <div class="col-sm-12">
      <div class="card" style="border-radius: 10px;">

        <br>

        <div class="card-body">

         <!-- begin tab content -->
         <div class="tab-content pt-2"> 

          <div class="tab-pane fade show active" id="all_devices">

            <div class="row">

              <div class="col-md-3 text-sm">
                <label class="text-dark"><b>Statut lecture</b>&nbsp;<span class="badge bg-primary rounded-pill nbr_notif_selected" style="font-size:10px;">0</span></label>
                <select class="form-control text-sm" id="STATUT" name="STATUT" onchange="liste();get_nbr_notif_selected();">
                  <option value=""> <?=lang('selectionner')?></option>
                  <option value="1"> Notifications non lus </option>
                  <option value="2">  Notifications lus</option>

                </select>

              </div>

              <div class="col-md-3 text-sm">
                <label class="text-dark"><b>catégorie</b>&nbsp;<span class="badge bg-primary rounded-pill nbr_notif_selected_cat" style="font-size:10px;">0</span></label>
                <select class="form-control text-sm" id="CATEGORIE" name="CATEGORIE" onchange="liste();get_nbr_notif_selected();">
                  <option value=""> <?=lang('selectionner')?></option>
                  <?php
                  foreach($categorie as $key)
                  {
                   echo "<option value=".$key['ID_CAT_NOTIF'].">".$key['DESC_CATEGORIE']."</option>";
                 }
                 ?>

               </select>

             </div>

           </div><br>

           <div class="row table-responsive">

            <?= $this->session->flashdata('message'); ?>

            <table id="mytable" class="table table-hover" style="width: 100%;margin: 0;">
              <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                <tr>
                  <th class="text-dark">#</th>

                  <th class="text-dark">CATEGORIE</th>
                  <th class="text-dark">MESSAGE</th>
                  <th class="text-dark">STATUT</th>
                  <th class="text-dark">DATE</th>
                  <th class="text-dark"></th>
                </tr>
              </thead>
              <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
              </tbody>
            </table>

          </div>

        </div>


        <div class="tab-pane fade " id="forfait_proche_exp">

          <div class="row">

              <div class="col-md-3 text-sm">
                <label class="text-dark"><b>Statut lecture</b>&nbsp;<span class="badge bg-primary rounded-pill nbr_notif_select_proche" style="font-size:10px;">0</span></label>
                <select class="form-control text-sm" id="STATUT_PROCHE" name="STATUT_PROCHE" onchange="forfait_proch_exp();get_nbr_not_select_proche();">
                  <option value=""> <?=lang('selectionner')?></option>
                  <option value="1"> Notifications non lus </option>
                  <option value="2">  Notifications lus</option>

                </select>

              </div>
            </div><br>

          <div class="row table-responsive">

            <table id="tableFprocheExp" class="table table-hover" style="width: 100%;margin: 0;" >
              <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                <tr>
                  <th class="text-dark">#</th>

                  <th class="text-dark">CATEGORIE</th>
                  <th class="text-dark">MESSAGE</th>
                  <th class="text-dark">STATUT</th>
                  <th class="text-dark">DATE</th>
                  <th class="text-dark"></th>
                </tr>
              </thead>
              <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
              </tbody>
            </table>

          </div>

        </div>

        <div class="tab-pane fade " id="forfaits_expires">

          <div class="row">

              <div class="col-md-3 text-sm">
                <label class="text-dark"><b>Statut lecture</b>&nbsp;<span class="badge bg-primary rounded-pill nbr_notif_select_expire" style="font-size:10px;">0</span></label>
                <select class="form-control text-sm" id="STATUT_EXPIRE" name="STATUT_EXPIRE" onchange="forfait_expire();get_nbr_not_select_expire();">
                  <option value=""> <?=lang('selectionner')?></option>
                  <option value="1"> Notifications non lus </option>
                  <option value="2">  Notifications lus</option>

                </select>

              </div>
            </div><br>

          <div class="row table-responsive">

            <table id="tableFexpire" class="table table-hover" style="width: 100%;margin: 0;" >
              <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                <tr>
                  <th class="text-dark">#</th>

                  <th class="text-dark">CATEGORIE</th>
                  <th class="text-dark">MESSAGE</th>
                  <th class="text-dark">STATUT</th>
                  <th class="text-dark">DATE</th>
                  <th class="text-dark"></th>
                </tr>
              </thead>
              <tbody class="text-dark" style="overflow-x: auto; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
              </tbody>
            </table>

          </div>

        </div>


      </div>
      <!-- end tab content -->

    </div>

    <!-- </div> -->

  </div>
</div>
</div>
</div>
<!-- </div> -->
</section>



</main><!-- End #main -->

<?php include VIEWPATH . 'includes/footer.php'; ?>

</body>


<script>
  // Fonction pour le chargement de donnees par defaut
  $(document).ready( function ()
  {
    liste();
    forfait_proch_exp();
    forfait_expire();

    get_nbr_notif();
    get_nbr_device_proche_exp();
    get_nbr_notif_selected();
    get_nbr_device_expire();

    get_nbr_not_select_proche();
    get_nbr_not_select_expire();
    

  });
  
</script>


<script>
  function liste()
  {
    var STATUT = $('#STATUT').val();
    var CATEGORIE = $('#CATEGORIE').val();

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
        url: "<?php echo base_url('notification/Notification/listing'); ?>",
        type: "POST",
        data: 
        {
          STATUT:STATUT,
          CATEGORIE:CATEGORIE,
        },
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
  setInterval(liste(), 60000);

</script>

<script>

  function forfait_proch_exp()
  {
    var CATEGORIE = 1;
    var STATUT_PROCHE = $('#STATUT_PROCHE').val();

    $('#message').delay('slow').fadeOut(10000);

    $("#tableFprocheExp").DataTable({
      "processing":true,
      "destroy" : true,
      "serverSide":true,
      "oreder":[[ 0, 'desc' ]],
      "ajax":{
        url: "<?php echo base_url('notification/Notification/listing2'); ?>",
        type:"POST",
        data : {
          CATEGORIE:CATEGORIE,
          STATUT_PROCHE:STATUT_PROCHE,
        },
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
      buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'  ],

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

  function forfait_expire()
  {
    var CATEGORIE = 2;
    var STATUT_EXPIRE = $('#STATUT_EXPIRE').val();

    $('#message').delay('slow').fadeOut(10000);

    $("#tableFexpire").DataTable({
      "processing":true,
      "destroy" : true,
      "serverSide":true,
      "oreder":[[ 0, 'desc' ]],
      "ajax":{
        url: "<?php echo base_url('notification/Notification/listing3'); ?>",
        type:"POST",
        data : {
          CATEGORIE:CATEGORIE,
          STATUT_EXPIRE:STATUT_EXPIRE,
        },
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
      buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'  ],

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
 function get_nbr_notif()
 {

  $.ajax({
    url: "<?= base_url() ?>notification/Notification/get_nbr_notif/",
    type: "POST",
    dataType: "JSON",
    success: function(data) {
     $('#nbr_notif').text(data);
   },

 });
}
</script>

<script>
 function get_nbr_device_proche_exp()
 {
  var CATEGORIE = 1; // Forfaits proche à expirer

  $.ajax({
    url: "<?= base_url() ?>notification/Notification/get_nbr_proche/" + CATEGORIE,
    type: "POST",
    dataType: "JSON",
    success: function(data) {
     $('#nbr_forfait_proche_exp').text(data);
   },

 });
}
</script>

<script>
 function get_nbr_device_expire()
 {
  var CATEGORIE = 2; // Forfaits expires

  $.ajax({
    url: "<?= base_url() ?>notification/Notification/get_nbr_exipire/" + CATEGORIE,
    type: "POST",
    dataType: "JSON",
    success: function(data) {
     $('#nbr_forfait_expire').text(data);
   },

 });
}
</script>

<script>
 function get_nbr_notif_selected()
 {
  var STATUT = $('#STATUT').val();
  var CATEGORIE = $('#CATEGORIE').val();

  $.ajax({
    url: "<?= base_url() ?>notification/Notification/get_nbr_notif/" + STATUT+"/"+CATEGORIE,
    type: "POST",
    dataType: "JSON",
    success: function(data) {
     $('.nbr_notif_selected').text(data);
     $('.nbr_notif_selected_cat').text(data);
   },

 });
}
</script>


<script>
 function get_nbr_not_select_proche()
 {
  var STATUT_PROCHE = $('#STATUT_PROCHE').val();

  $.ajax({
    url: "<?= base_url() ?>notification/Notification/get_nbr_notif_proche/" + STATUT_PROCHE,
    type: "POST",
    dataType: "JSON",
    success: function(data) {
     $('.nbr_notif_select_proche').text(data);
   },

 });
}
</script>

<script>
 function get_nbr_not_select_expire()
 {
  var STATUT_EXPIRE = $('#STATUT_EXPIRE').val();

  $.ajax({
    url: "<?= base_url() ?>notification/Notification/get_nbr_notif_expire/" + STATUT_EXPIRE,
    type: "POST",
    dataType: "JSON",
    success: function(data) {
     $('.nbr_notif_select_expire').text(data);
   },

 });
}
</script>


</html>