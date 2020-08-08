<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>index PHP for player</title>
</head>
<body>

<?php
  require_once 'checkdb.php';

  if(isset($_POST['stop'])) {
    stop_play();
  }
?>

<form action="artist.php" method="POST">
  <input type="submit" name="start" value="Artist" /><br/>
</form>
<form action="index.php" method="POST">
  <input type="submit" name="stop" value="ストップ" /><br/>
</form>


</body>
</html>

