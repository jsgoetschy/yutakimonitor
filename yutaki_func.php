<?php

$sqlite = '/home/yutaki/yutaki.db';
$tbname = 'yutakidata';

  //
  //  recupere les donnees de puissance des $nb_days derniers jours et les met en forme pour les affichers sur le graphique
  //
function getDataYutaki ($nb_days,$type) {
    global $sqlite;
    global $tbname;
    $months    = array('01' => 'janv', '02' => 'fev', '03' => 'mars', '04' => 'avril', '05' => 'mai', '06' => 'juin', '07' => 'juil', '08' => 'aout', '09' => 'sept', '10' => 'oct', '11' => 'nov', '12' => 'dec');
    $now  = time();
    $past = strtotime("-$nb_days day", $now);

    $db = new SQLite3($sqlite);
    $results = $db->query("SELECT timestamp,$type FROM $tbname WHERE timestamp > $past ORDER BY timestamp ASC;");

    $datas = array();

    while($row = $results->fetchArray(SQLITE3_ASSOC)){
      $timestamp=$row['timestamp']*1000+3600*1000;
      $datas[] = "[$timestamp, ".$row[$type]."]";
    };

    return implode(', ', $datas);
}

  //
  //  recupere la première date et la dernière date des $nb_days derniers jours
  //
function getDataMinMax ($nb_days,$type) {
    global $sqlite;
    global $tbname;
    $months    = array('01' => 'janv', '02' => 'fev', '03' => 'mars', '04' => 'avril', '05' => 'mai', '06' => 'juin', '07' => 'juil', '08' => 'aout', '09' => 'sept', '10' => 'oct', '11' => 'nov', '12' => 'dec');
    $now  = time();
    $past = strtotime("-$nb_days day", $now);

    $db = new SQLite3($sqlite);
    $results = $db->query("SELECT timestamp,$type FROM $tbname WHERE timestamp > $past ORDER BY timestamp ASC LIMIT 1;");
    $results2 = $db->query("SELECT timestamp,$type FROM $tbname ORDER BY timestamp DESC LIMIT 1;");

    $datas = array();

    $row = $results->fetchArray(SQLITE3_ASSOC);
    $timestamp=$row['timestamp']*1000+3600*1000;
    $datas[0] = "[$timestamp, ".$row[$type]."]";

    $row = $results2->fetchArray(SQLITE3_ASSOC);
    $timestamp=$row['timestamp']*1000+3600*1000;
    $datas[1] = "[$timestamp, ".$row[$type]."]";

    return implode(', ', $datas);
}



?>
