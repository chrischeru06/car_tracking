<!DOCTYPE html>
<html lang="en">
<style>
		ul {
			list-style-type: none;
			padding: 0;
		}

		li {
			padding: 5px;
			margin-bottom: 10px;
			max-width: 80%;
			list-style-type: none;
		}

		.received {
			background-color: #eaeaea;
			text-align: left;
		}

		.sent {
			background-color: #DCF8C6;
			text-align: right;
		}

		.capitalize-first {
			color:  #899bbd;
			font-weight: bold;
			text-transform: lowercase;
			font-variant: small-caps;

		}
		.capitalize-first::first-letter {
			text-transform: uppercase;
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
			<?php if ($PROFIL_ID!=1) {?>

				<h1 style="color:  #899bbd;">Discussion avec <a class="capitalize-first"> ADMIN</a> </h1>

			<?php }
			else{
				?>
				<h1 style="color:  #899bbd;">Discussion avec <a id="IDENTIFICATION" class="capitalize-first"></a> </h1>
				<?php
			}
			?>
			<nav>
				<ol class="breadcrumb">
					<!-- <li class="breadcrumb-item"><a href="index.html">Modification</a></li> -->
					<!-- <li class="breadcrumb-item active">Liste</li> -->
				</ol>
			</nav>
		</div><!-- End Page Title -->

		<section class="section">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title"></h5>       
					<div class="row">
						<div class="col-xl-12 col-xxl-12 text-dark">
							<div id="messageContainer">

								<ul>
									<?php if ($PROFIL_ID!=1) {?>
										<li class="received">Dites nous en quoi nous pouvons vous aider.</li>

										<?php
									}?>
									<?php
									foreach ($message_all as $key) {
										if($key['USER_ID_DESTINATAIRE']==$USER_ID){?>

											<li class="received"><?=$key['MESSAGE']?></li>

											<?php
										}elseif ($key['USER_ID_ENVOIE']==$USER_ID) {
											?>
											<li class="sent"><?=$key['MESSAGE']?></li>
											<?php
										}
									}
									?>

								</ul>
							</div>
							<form enctype="multipart/form-data" id="message_form" method="post">
								<input type="hidden" id="id_recepteur" name="id_recepteur" value="<?=$id_rec?>">
								<textarea id="messageInput" name="message" class="form-control" placeholder="Entrez votre message ici..."></textarea>
								<font id="error_msg" color="red"></font>

								<div class="col-lg-12" style="margin-top:31px;">
									<button type="button" style="float: right;background-color: cadetblue;border:none;" onclick="sendMessage()" class="btn btn-primary"><span class="fa fa-send-o"></span>  Envoyer</button>
								</div>
							</form>
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

		var nom='<?php echo $name['IDENTIFICATION']; ?>';
		$('#IDENTIFICATION').html(nom);

	});

	function sendMessage() {
		var statut=1;

		if($('#messageInput').val()=='')
		{
			statut=2;
			$('#error_msg').html('Veuillez remplir ce champ!');
		}
		if (statut==1) {

			var url="<?=base_url('administration/Message/save_message/')?>";
			var form_data = new FormData($("#message_form")[0]);

			var messageInput = document.getElementById("messageInput");
			var message = messageInput.value;

    	// Créer une nouvelle div pour le message envoyé
			var messageDiv = document.createElement("li");
			messageDiv.textContent = message;
			messageDiv.classList.add("message", "sent");

    	// Ajouter la nouvelle div au conteneur de messages
			document.getElementById("messageContainer").appendChild(messageDiv);

    	// Effacer le champ de saisie de message après l'envoi
			messageInput.value = "";

			$.ajax(
			{

				url: url,
				type: 'POST',
				dataType:'JSON',
				data: form_data ,
				contentType: false,
				cache: false,
				processData: false,
				success: function(data)
				{

				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					alert('Erreur');
				}
			});

		}


	}

</script>


</html>