<?php
class Date{
	function getAll($annee){
		$tableau=array();
		$date=strtotime($annee.'-01-01');
		while(date('Y',$date)<=$annee){
			#Stocke les parametres du jour 
			$annee=date('Y',$date); 
			$mois=date('n',$date); 
			$jour=date('j',$date);#j'utilise j plutot que d pour enlever les 0 initiaux
			$jour_semaine=str_replace('0','7',date('w',$date));#stocke le jour dans la semaine mais commence par dimanche donc care
			$tableau[$annee][$mois][$jour]=$jour_semaine;
			$date=strtotime(date('Y-m-d',$date).'+1 DAY');
			#$date=$date+24*3600; methode pour afficher un jour + un jour mais pas pratique
		}

		return $tableau;
	}
}