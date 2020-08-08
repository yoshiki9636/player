<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>track PHP for player</title>
</head>
<body>

<form action="play.php" method="POST">
<?php
  require_once 'checkdb.php';
  $artist = $_POST['artist'];
  $album = $_POST['album'];
  print $artist."<br/>";
  print $album."<br/>";

  $tracks = get_names($artist, $album);
  foreach($tracks as $track) { ?>
	  <input type="submit" name="track" value="<?php echo $track ?>" /><br/>
<?php
  }
  if(isset($_POST['stop'])) {
    stop_play();
  }
?>
  <input type="hidden" name="artist" value="<?php echo $artist ?>" />
  <input type="hidden" name="album" value="<?php echo $album ?>" />
</form>
<form action="track.php" method="POST">
  <input type="submit" name="stop" value="ストップ" /><br/>
  <input type="hidden" name="artist" value="<?php echo $artist ?>" />
  <input type="hidden" name="album" value="<?php echo $album ?>" />
</form>
<form action="album.php" method="POST">
  <input type="submit" name="return" value="戻る" /><br/>
  <input type="hidden" name="artist" value="<?php echo $artist ?>" />
</form>

</body>
</html>

