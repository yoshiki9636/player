<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>album PHP for player</title>
</head>
<body>

<form action="track.php" method="POST">
<?php
  require_once 'checkdb.php';
  $artist = $_POST['artist'];
  print $artist."<br/>";

  $albums = get_albums($artist);
  foreach($albums as $album) { ?>
	  <input type="submit" name="album" value="<?php echo $album ?>" /><br/>
<?php
  }
  if(isset($_POST['stop'])) {
    stop_play();
  }
?>
  <input type="hidden" name="artist" value="<?php echo $artist ?>" />
</form>
<form action="album.php" method="POST">
  <input type="submit" name="stop" value="ストップ" /><br/>
  <input type="hidden" name="artist" value="<?php echo $artist ?>" />
</form>
<form action="artist.php" method="POST">
  <input type="submit" name="return" value="戻る" /><br/>
</form>

</body>
</html>

