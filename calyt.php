<!DOCTYPE html>
<html>
	<head>
		<title>Calendrier</title>
	</head>
	<body>
		<?php
		require('calytdonnee.php'); #recup donnee fichier 
		$date = new Date();
		$annee=date('Y');
		$dates=$date->getAll($annee); #recup toutes les dates du tableau		
		#echo date('d/m/Y')
		?>
		#mise en page
		<div class="periodes">
			<div class="annee">
				<?php echo $annee; ?>
			</div>
			<div class="mois">
				<ul>
					<?php foreach ($date->mois as $mois); {
						#recup des mois pour les mettre sous forme voulu
					?>
						<li><?php echo $mois; ?></li> #HTML
					}
					
				</ul>
			</div>
		</div>
		<?php print_r($dates);?>
	</body>
</html>
 