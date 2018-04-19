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
		
		<?php print_r($dates);?>
	</body>
</html>
 