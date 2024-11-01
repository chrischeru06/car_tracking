<!DOCTYPE html>
<html lang="en">

<head>
  <?php include VIEWPATH . 'includes/header.php'; ?>

  <style>
    
    .dropdown-toggle{

      cursor: pointer;
      

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
          <h1><font class="fa fa-user" style="font-size:18px;"></font> <?=lang('Profils_lng')?></h1>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item active"><?=lang('title_list')?></li>
            </ol>
          </nav>
        </div>
        <div class="col-md-6">

          <div class="justify-content-sm-end d-flex">
            <a class="btn btn-outline-primary rounded-pill" href="<?=base_url('administration/Profil/add')?>"><i class="bi bi-plus h5"></i> <?=lang('btn_nouveau')?></a>
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
           <?= $this->session->flashdata('message');?>

           <div class="col-12">
            <div class="card">
              <div class="card-body">

                <br>
                <br>
                <div class="table-responsive" style=" width:100%;">
                  <table id="mytable" class="table table-hover" >
                    <thead style="font-weight:bold; background-color: rgba(0, 0, 0, 0.075);">
                      <tr>
                        <th class="text-dark">#</th>

                        <th class="text-dark"><?=lang('profil_maj')?></th>
                        <th class="text-dark"><?=lang('role_role')?></th>
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
        url: "<?php echo base_url('administration/Profil/listing'); ?>",
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

</html>