<!DOCTYPE html>
<html lang="en">

<head>
    <?php include VIEWPATH . 'includes/header.php'; ?>

  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/highcharts-more.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
  <script src="https://code.highcharts.com/modules/lollipop.js"></script>

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
      <h1>Rapport</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Rapport</a></li>
          <!-- <li class="breadcrumb-item active">Rapport véhicules par propriétaire</li> -->
        </ol>
      </nav>
    </div><!-- End Page Title -->

     <!--***************************** Modal datail start ***********************************-->
    
    <!--***************************** Modal datail end ***********************************-->

    <section class="section dashboard">

    
    </section>


  </main><!-- End #main -->

<?php include VIEWPATH . 'includes/footer.php'; ?>

</body>

</html>



<script type="text/javascript">
wpchart({
         extract( shortcode_atts(
            array(
                'type'             => 'pie',
                'title'            => 'chart',
                'canvaswidth'      => '625',
                'canvasheight'     => '625',
                'width'        => '48%',
                'height'       => 'auto',
                'margin'       => '5px',
                'relativewidth'    => '1',
                'align'            => '',
                'class'        => '',
                'labels'           => '',
                'data'             => '30,50,100',
                'datasets'         => '30,50,100 next 20,90,75',
                'colors'           => '#69D2E7,#E0E4CC,#F38630,#96CE7F,#CEBC17,#CE4264',
                'fillopacity'      => '0.7',
                'pointstrokecolor' => '#FFFFFF',
                'animation'      => 'true',
                'scalefontsize'    => '12',
                'scalefontcolor'   => '#666',
                'scaleoverride'    => 'false',
                'scalesteps'     => 'null',
                'scalestepwidth'   => 'null',
                'scalestartvalue'  => 'null'
            ), $data_acte )
    );


                });
    </script>

