<?PHP

  require_once 'checkdb.php';

  $artist = 'The Beatles';
  $album = 'The Beatles (White Album) [Disc 1] [2009 Stereo Remaster]';
  $track = 'Ob-La-Di, Ob-La-Da';
  //$track = 'The Continuing Story Of Bungalo';
  print $artist."\n";
  print strlen($artist)."\n";
  print mb_strlen($artist)."\n";
  print $album."\n";
  print strlen($album)."\n";
  print mb_strlen($album)."\n";
  print $track."\n";
  print strlen($track)."\n";
  print mb_strlen($track)."\n";

  print $artist."\n";
  str_replace('B','he',$artist);
  print $artist."\n";

  //var_dump(get_names($artist, $album));
  //start_play($artist, $album, $track);

?>
