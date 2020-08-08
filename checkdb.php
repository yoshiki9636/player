<?php
  $dbport = 5432;
  $dbuser = 'XXXXX';
  $dbpasswd = 'XXXXX';
  $dbname = 'trackdb';
  $ip_address = 'XX.XX.XX.XX';
  $ghnport = 8080;
  $txtpath = '/var/www/html/player/tmp/crontab.txt';
  $gap = 3;

  function get_artists() {
    global $dbport, $dbuser, $dbpasswd, $dbname;
    $dbconn = pg_connect("port=$dbport user=$dbuser password=$dbpasswd dbname=$dbname");
    $sql = "SELECT DISTINCT artist, compilation FROM track_table WHERE artist IS NOT NULL ORDER BY artist";

    $result = pg_query($dbconn, $sql );
    $cnt = pg_numrows($result);

    $artists = array();
  
    for ($i = 0; $i < $cnt; $i++) {
      $row = pg_fetch_row($result, $i);
      if($row[1] == 't') continue;
      $artists[] = $row[0];
    }
    $artists[] = 'Compilations';

    pg_close($dbconn);
    $artists = str_replace('"','_',$artists);
    return $artists;
  }

  function get_albums($artist) {
    global $dbport, $dbuser, $dbpasswd, $dbname;
    $dbconn = pg_connect("port=$dbport user=$dbuser password=$dbpasswd dbname=$dbname");
    if($artist == 'Compilations') {
      $sql = "SELECT DISTINCT album, compilation FROM track_table WHERE compilation='t' ORDER BY album";
      $result = pg_query($dbconn, $sql );
      $cnt = pg_numrows($result);
      $albums = array();
      for ($i = 0; $i < $cnt; $i++) {
        $row = pg_fetch_row($result, $i);
        $albums[] = $row[0];
      }
    }
    else {
      $sql = "SELECT DISTINCT album, compilation FROM track_table WHERE artist='".str_replace("'","''",$artist)."' ORDER BY album";
      $result = pg_query($dbconn, $sql );
      $cnt = pg_numrows($result);
  
      $albums = array();
      for ($i = 0; $i < $cnt; $i++) {
        $row = pg_fetch_row($result, $i);
        if($row[1] == 't') continue;
        $albums[] = $row[0];
      }
    }

    pg_close($dbconn);
    $albums = str_replace('"','_',$albums);
    return $albums;
  }

  function get_names($artist, $album) {
    global $dbport, $dbuser, $dbpasswd, $dbname;
    $dbconn = pg_connect("port=$dbport user=$dbuser password=$dbpasswd dbname=$dbname");
    if($artist == 'Compilations') {
       $sql = "SELECT DISTINCT name, track_number FROM track_table WHERE album = '".str_replace("'","''",$album)."' ORDER BY track_number";
    }
    else {
       $sql = "SELECT DISTINCT name, track_number FROM track_table WHERE artist = '".str_replace("'","''",$artist)."' AND album = '".str_replace("'","''",$album)."' ORDER BY track_number";
    }
    $result = pg_query($dbconn, $sql);
    $cnt = pg_numrows($result);

    $names = array(); 
    for ($i = 0; $i < $cnt; $i++) {
      $row = pg_fetch_row($result, $i);
      $names[] = $row[0];
    }
    pg_close($dbconn);
    $names = str_replace('"','_',$names);
    return $names;
  }

  function start_play($artist, $album, $track_name) {
    global $dbport, $dbuser, $dbpasswd, $dbname, $ip_address, $ghnport, $txtpath, $gap;
    $dbconn = pg_connect("port=$dbport user=$dbuser password=$dbpasswd dbname=$dbname");
    if($artist == 'Compilations') {
      $sql = "SELECT DISTINCT name, track_number, total_time FROM track_table WHERE album = '".str_replace("'","''",$album)."' ORDER BY track_number";
    }
    else {
      $sql = "SELECT DISTINCT name, track_number, total_time FROM track_table WHERE artist = '".str_replace("'","''",$artist)."' AND album = '".str_replace("'","''",$album)."' ORDER BY track_number";
    }
    $result = pg_query($dbconn, $sql );
    $cnt = pg_numrows($result);

    $start_track = 1;
    for ($i = 0; $i < $cnt; $i++) {
      $row = pg_fetch_row($result, $i);
      $name = str_replace('"', '_', $row[0]);
      if($name == $track_name) {
        $start_track = $row[1];
        break;
      }
    }
    
    if(preg_match('/^.*[\[\(]Disc ([0-9])[\]\)].*$/i', $album, $matches)) {
      $disc_num = $matches[1];
    }
    else {
      $disc_num = '0';
    }

    $fp = fopen($txtpath, "w");

    $date = explode(" ",date("s i H d m"));
    $sec =(int)$date[0];
    $min =(int)$date[1];
    $hou =(int)$date[2];
    $day =(int)$date[3];
    $mon =(int)$date[4];

    $prev = 0;

    $artist = str_replace('/', '_', $artist);
    $album = str_replace('/', '_', $album);

    for ($i = $start_track - 1; $i < $cnt; $i++) {
      $row = pg_fetch_row($result, $i);
      $name = str_replace('/', '_', $row[0]);
      if($disc_num == '0') { 
        $track_num = sprintf("%'.02d", $row[1]);
      }
      else {
        $track_num = $disc_num.'-'.sprintf("%'.02d", $row[1]);
      }

      $artist = (mb_strlen($artist) > 40) ? mb_substr($artist, 0, 40)  : $artist;
      $artist = preg_replace('/ *$/', '', $artist);
      $album = (mb_strlen($album) > 40) ? mb_substr($album, 0, 40)  : $album;
      $album = preg_replace('/ *$/', '', $album);
      $track_filename = $track_num.' '.$name;
      $track_filename = (mb_strlen($track_filename) > 36) ? mb_substr($track_filename, 0, 36)  : $track_filename;
      $track_filename = preg_replace('/ +$/', '', $track_filename);
      $path = "/itunes/Music/$artist/$album/$track_filename.m4a" ;
      $path = preg_replace('/["<>\?]/', '_', $path);
      $path = str_replace('&','%26',$path);
      $path = str_replace("'","%27",$path);
      $path = str_replace(' ','%20',$path);
      if ($i == $start_track -1) {
        exec("curl -X POST 'http://localhost:".$ghnport."/google-home-notifier' -d \"text=http://".$ip_address.$path."\"");
      }
      else {
        $path = str_replace('%','\%',$path);
        $sec += $prev + $gap;
        $omi = (int)($sec / 60);
        $sec = $sec % 60;
        $min += $omi;
        $oho = (int)($min / 60);
        $min = $min % 60;
        $hou += $oho;
        $oda = (int)($hou / 24);
        $hou = $hou % 24;
        $day += $oda;
        $cmd = "$min $hou $day $mon * sleep $sec ; "."curl -X POST 'http://localhost:".$ghnport."/google-home-notifier' -d \"text=http://".$ip_address.$path."\"";
        fwrite($fp, $cmd."\n");
      }
      $prev = (int)($row[2] / 1000);
    }
    fclose($fp);
    exec( "crontab ".$txtpath);
    pg_close($dbconn);
  }

  function stop_play() {
    global $ip_address, $ghnport;
     exec("curl -X POST 'http://localhost:".$ghnport."/google-home-notifier' -d \"text=http://".$ip_address."/itunes/none.wav\"");
    exec( "crontab -r");
  }

?>

