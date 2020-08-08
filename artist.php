<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>artist PHP for player</title>
</head>
<body>

<form action="album.php" method="POST">
<?php
  require_once 'checkdb.php';

  $artists = get_artists();
  foreach($artists as $artist) { ?>
	  <input type="submit" name="artist" value="<?php echo $artist ?>" /><br/>
<?php
  }
  if(isset($_POST['stop'])) {
    stop_play();
  }
?>
</form>
<form action="artist.php" method="POST">
  <input type="submit" name="stop" value="ストップ" /><br/>
</form>
<form action="index.php" method="POST">
  <input type="submit" name="return" value="戻る" /><br/>
</form>


</body>
</html>

