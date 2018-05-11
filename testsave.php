     <?php
    // création d’une fonction comportant les identifiants de connexion au FTP :

    function mysql_structure() {
    $host = ‘mon_serveur’;
    $user = ‘mon_login’;
    $pass = ‘mot_de_passe’;
    $base = ‘nom_de_la_base’;

    // création d’un fichier affichant en boucle le contenu des tuples de la base :

    mysql_connect($host, $user, $pass);
    mysql_select_db($base);
    $tables = mysql_list_tables($base);
    while ($donnees = mysql_fetch_array($tables))
    {
    $table = $donnees[0];
    $res = mysql_query("SHOW CREATE TABLE $table");
    if ($res)
    {
    $insertions = "";
    $tableau = mysql_fetch_array($res);
    $tableau[1] .= ";";
    $dumpsql[] = str_replace(" ", "", $tableau[1]);
    $req_table = mysql_query("SELECT * FROM $table");
    $nbr_champs = mysql_num_fields($req_table);
    while ($ligne = mysql_fetch_array($req_table))
    {
    $insertions .= "INSERT INTO $table VALUES(";
    for ($i=0; $i<=$nbr_champs-1; $i++)
    {
    $insertions .= "’" . mysql_real_escape_string($ligne[$i]) . "’, ";
    }
    $insertions = substr($insertions, 0, -2);
    $insertions .= "); ";
    }
    if ($insertions != "")
    {
    $dumpsql[] = $insertions;
    }
    }
    }
    return implode("
    ", $dumpsql);
    }

    // creation d’une fonction file_put_content si le script est en PHP4 :

    if(!function_exists(‘file_put_contents’)) {
    function file_put_contents($filename, $data, $file_append = false) {
    $fp = fopen($filename, (!$file_append ? ‘w+’ : ‘a+’));
    if(!$fp) {
    trigger_error(‘file_put_contents ne peut pas écrire dans le fichier.’, E_USER_ERROR);
    return;
    }
    fputs($fp, $data);
    fclose($fp);
    }
    }

    // création du fichier de dump sur le même niveau que ce fichier dump.php

    file_put_contents("sqldump_".date("d-n-Y").".sql", mysql_structure());

    // effacement du fichier precedant (créé 7 jours plus tot)
    $time_old = getdate(mktime()-(7*24*3600));
    $an = $time_old[‘year’];
    $mois = $time_old[‘mon’];
    $jour = $time_old[‘mday’];

    // formatage des jours à 1 chiffre

    for($k=1; $k<10; $k++)
    {
    if ($jour==$k)
    {
    $jour=’0′.$jour;
    }
    }

    $date_old=$jour.’-‘.$mois.’-‘.$an;
    $file_old="sqldump_".$date_old.".sql";
    unlink($file_old);
    ?> 