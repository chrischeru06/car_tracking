 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<?php include VIEWPATH .'includes/header.php';?>
 </head>
 <body>
 	<!--******************* Preloader start ********************-->
 	<?php include VIEWPATH .'includes/preloader.php';?>
 	<!--******************* Preloader end ********************-->

 	<!--********************************** Main wrapper start ***********************************-->
 	<div id="main-wrapper">
 		<!--********************************** Header start ***********************************-->
 		<?php include VIEWPATH .'includes/menu_top.php';?>
 		<!--********************************** Header end ***********************************-->

 		<!--********************************** Sidebar start ***********************************-->
 		<?php include VIEWPATH .'includes/menu_left.php';?>
 		<!--********************************** Sidebar end ***********************************-->

 		<!--********************************** Content body start ***********************************-->
 		<div class="content-body">
 			<div class="container-fluid">
 				<div class="row page-titles mx-0">
 					<div class="col-sm-6 p-md-0">
 						<div class="welcome-text">
 							Société Burundaise de la Gardinnage 
 						</div>
 					</div>
 					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
 						<ol class="breadcrumb">
 							<li class="breadcrumb-item"><a href="javascript:void(0)">Liste</a></li>
 							<li class="breadcrumb-item active"><a href="javascript:void(0)">Agents</a></li>
 						</ol>
 					</div>
 				</div>

 				<!-- row -->
 				<div class="row text-dark">
 					<div class="col-xl-12 col-xxl-12">
 						<div class="card">
 							<div class="card-header">
 								<h4>MODIFIER LE MOT DE PASSE</h4>
 							</div>
 							<div class="card-body">
 								<div class="basic-form">
 									<form method="POST" id="myform" action="#">
 										<div class="form-row">
 											<div class="col-md-4">
 												<label>Nouveau mot de passe<span style="color: red;">*</span></label>
 												<input type="password" autofocus="" name="NEW_PASSWORD" autocomplete="off" id="NEW_PASSWORD" class="form-control">
 												<span class="help-block text-danger"></span>
 											</div>

 											<div class="col-md-4">
 												<label>Confirmer le mot de passe<span style="color: red;"></span></label>
 												<input type="password" name="CONFIRMER_PASSWORD" id="CONFIRMER_PASSWORD"  class="form-control" autocomplete="off">
 												<span class="help-block text-danger"></span>
 											</div>

 											<div class="col-md-4">
 												<label>Ancien mot de passe<span style="color: red;"></span></label>
 												<input type="password" name="ANCIEN_PASSWORD" id="ANCIEN_PASSWORD"   class="form-control" autocomplete="off">
 												<span class="help-block text-danger"></span>
 											</div>
 										</div>
 										<br>
 										<div class="form-group row">
 											<div class="col-md-12">
 												<button type="button" onclick="enr()" style="float: right;clear: both;" class="btn btn-primary" id="btnSave">Appliquer</button>
 											</div>
 										</div>
 									</form>
 								</div>
 							</div>
 						</div>
 					</div>
 				</div>
 			</div>
 		</div>
 	</div>

 	<!--********************************** Content body end ***********************************-->

 	<!--********************************** Footer start ***********************************-->
 	<?php include VIEWPATH . 'includes/footer.php'; ?>
 	<!--********************************** Footer end ***********************************-->
 </div>
 <!--********************************** Main wrapper end ***********************************-->

 <!--********************************** Scripts start ***********************************-->
 <?php include VIEWPATH . 'includes/footer_scripts.php'; ?>
 <!--********************************** Scripts End ***********************************-->

</body>
</html>


<script>
	$(document).ready(function() {
		$("input").change(function(){
			$(this).parent().parent().removeClass('has-error');
			$(this).next().empty();
		});


	});
</script>



<script>
	function enr()
	{

		$('#btnSave').text('Enregistrement.....');
		$('#btnSave').attr("disabled",true);

		var url;


		url="<?php echo base_url('Change_pwd/changer')?>";

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
				if(data.status) 
				{
				
					$('#myform')[0].reset();
					window.location="<?=base_url('')?>";
						    alert_notify('success','Validation','Opération effectuée avec succès','1');
				}
				else
				{
					for (var i = 0; i < data.inputerror.length; i++) 
					{
						$('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); 
						$('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); 
					}
				}

				$('#btnSave').text('Sauvegarder');
				$('#btnSave').attr('disabled',false); 


			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				alert('Erreur s\'est produite');
				$('#btnSave').text('Sauvegarder');
				$('#btnSave').attr('disabled',false);

			}


		});

	}

</script>
