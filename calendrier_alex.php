<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<!-- affiche image de fond  -->
<style>
body{margin:0;padding:0;background: url(fond1.jpg) no-repeat center fixed; /*image de fond*/
background-size: cover; /*taille image de fond*/
	}
</style> 

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Gestion de calendrier | Calendrier</title>
    <link rel="stylesheet" type="text/css" href="design/calendrier.css" media="screen" />
</head>
<body>
	<!--  affichage du titre -->
	<h1>Calendrier</h1>
	<h1><?php echo ("la date du jour est :").date('d/m/Y') ?></h1>
	<ul>
		<!--affichage  raccourci menu   -->
    	<h1><a href="ajoutevent.php">Ajouter un événement</a></br>
        <a href="supprevent.php">Supprimer un événement</a></h1>
    </ul>  
<?php
	#  creation de la fonction  gestion des evenements ----------------

	function getEventsDate($mois, $annee)
	{
		$result = array();
		
		include("sql_connect.php");
		
		$sql = 'SELECT DISTINCT jour_evenement, titre_evenement FROM date_evenement WHERE mois_evenement='.$mois.' AND annee_evenement='.$annee.' ORDER BY jour_evenement';
		

		$query = mysqli_query($connection,$sql) or die("Une requête a échouée.");
		
		while ($row = mysqli_fetch_array($query, MYSQLI_NUM)) {
			$result[] = $row[0];
			$result[] = $row[1];
		}
		
		mysqli_close($connection);
		
		return $result;
	}
	#  creation de la fonction  affichage des evenements ----------------
	function afficheEvent($i, $event)
	{
		$texte = ""; $suivant = false;
		
		foreach($event as $cle => $element) {
			if($suivant) {
				$texte .= $element."<br/>";
			}
			if($element == $i) {
				$suivant = true;
			} else {
				$suivant = false;
			}
		}
		
		return $texte;
	}

	#   ----------------

	if(isset($_GET['m']) && isset($_GET['y']) && is_numeric($_GET['m']) && is_numeric($_GET['y'])) {
		$timestamp = mktime(0, 0, 0, $_GET['m'], 1, $_GET['y']);
		
		$event = getEventsDate($_GET['m'], $_GET['y']); // Récupère les jour où il y a des évènements
	}
	else { // Si on ne récupère rien dans l'url, on prends la date du jour
		$timestamp = mktime(0, 0, 0, date('m'), 1, date('Y'));
		
		$event = getEventsDate(date('m'), date('Y')); // Récupère les jour où il y a des évènements
	}
	
	
	// === Si le mois correspond au mois actuel et l'année aussi, on retient le jour actuel pour le griser plus tard (sinon le jour actuel ne se situe pas dans le mois)
	if(date('m', $timestamp) == date('m') && date('Y', $timestamp) == date('Y')) $coloreNum = date('d');
	
	$m = array("01" => "Janvier", "02" => "Février", "03" => "Mars", "04" => "Avril", "05" => "Mai", "06" => "Juin", "07" => "Juillet", "08" => "Août", "09" => "Septembre", "10" => "Octobre",  "11" => "Novembre", "12" => "Décembre");
	$j = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
	
	$numero_mois = date('m', $timestamp);
	$annee = date('Y', $timestamp);
	
	if($numero_mois == 12) {
		$annee_avant = $annee;
		$annee_apres = $annee + 1;
		$mois_avant = $numero_mois - 1;
		$mois_apres = 01;
	}
	elseif($numero_mois == 01) {
		$annee_avant = $annee - 1;
		$annee_apres = $annee;
		$mois_avant = 12;
		$mois_apres = $numero_mois + 1;
	}
	else {
		$annee_avant = $annee;
		$annee_apres = $annee;
		$mois_avant = $numero_mois - 1;
		$mois_apres = $numero_mois + 1;
	}
	
	// 0 => Dimanche, 1 => Lundi, 2 = > Mardi...
	$numero_jour1er = date('w', $timestamp);
	
	// Changement du numéro du jour car l'array commence à l'indice 0
	if ($numero_jour1er == 0) $numero_jour1er = 6; // Si c'est Dimanche, on le place en 6ème position (après samedi)
	else $numero_jour1er--; // Sinon on mets lundi à 0, Mardi à 1, Mercredi à 2...
	?>
    
	<table class="calendrier">
		<!--affichage du moi en cours  est selection  des mois suivant ou precedant  -->
		<caption><h1><?php echo '<a href="?m='.$mois_avant.'&amp;y='.$annee_avant.'"><<</a>  '.$m[$numero_mois].' '.$annee.'  <a href="?m='.$mois_apres.'&amp;y='.$annee_apres.'">>></a>'; ?></h1></caption>
		
		<tr><th>Lu</th><th>Ma</th><th>Me</th><th>Je</th><th>Ve</th><th>Sa</th><th>Di</th></tr>
	<?php
		// Ecriture de la 1ère ligne
		echo '<tr>';
			// Ecriture de colones vides tant que le mois ne démarre pas
			for($i = 0 ; $i < $numero_jour1er ; $i++) {		echo '<td></td>';	}
			for($i = 1 ; $i <= 7 - $numero_jour1er; $i++) {
				// Ce jour possède un événement
				if (in_array($i, $event)) {
					echo '<td class="jourEvenement';
					
					if(isset($coloreNum) && $coloreNum == $i) echo ' lienCalendrierJour';
					
					echo '"><a href="evenement.php?d='.$i.'/'.$numero_mois.'/'.$annee.'" class="info">'.$i.'<span>'.afficheEvent($i, $event).'</span></a></div></td>';
				} else {
					echo '<td ';
					
					if(isset($coloreNum) && $coloreNum == $i) echo 'class="lienCalendrierJour"';
					
					echo '>'.$i.'</td>';
				}

			}
		echo '</tr>';
		
		$nbLignes = ceil((date('t', $timestamp) - ($i-1))/ 7); // Calcul du nombre de lignes à afficher en fonction de la 1ère (surtout pour les mois a 31 jours)
		
		for($ligne = 0 ; $ligne < $nbLignes ; $ligne++) {
			echo '<tr>';
			for($colone = 0 ; $colone < 7 ; $colone++) {
				if($i <= date('t', $timestamp))	{
					// Ce jour possède un événement
					if (in_array($i, $event)) {
						echo '<td class="jourEvenement';
						
						if(isset($coloreNum) && $coloreNum == $i) echo ' lienCalendrierJour';
						
						echo '"><a href="evenement.php?d='.$i.'/'.$numero_mois.'/'.$annee.'" class="info">'.$i.'<span>'.afficheEvent($i, $event).'</span></a></td>';
					} else {
						echo '<td ';
						
						if(isset($coloreNum) && $coloreNum == $i) echo 'class="lienCalendrierJour"';
						
						echo '>'.$i.'</td>';
					}
				} else {
					echo '<td></td>';
				}
				$i = $i +1;
			}
			echo '</tr>';
		}
		

#--------------------------suppression auto 1 mois avant 
		$ref=1;
		for($i=0; $i<$ref; $i++){ #pour le faire 1 seule fois
		$mois=date('m');
		$mois_suppr= date("m",strtotime("- 1 month")); 
		//echo $mois_suppr;
		$annee=date('Y');

		include("sql_connect.php");
		$suppr='DELETE FROM date_evenement WHERE mois_evenement<='.$mois_suppr.' AND annee_evenement<='.$annee.''; #requete de suppr
		echo $suppr;
		$query = mysqli_query($connection,$suppr) or die("Une requête a échouée.");
		mysqli_close($connection);
	}

#---------------------------------
	?>

	</table>
	
	<br/>
	
    <!-- <p class="centre"><a href="calendrier.php">Revenir à l'accueil</a></p> -->

</body>


</html>
