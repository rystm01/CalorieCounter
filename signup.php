<?php
  $config = parse_ini_file("config.ini");   // better to hide this!
  $server = $config["host"];
  $username = $config["user"];
  $password = $config["password"];
  $database = $config["database"];

  // create the connection
  $cn = mysqli_connect($server, $username, $password, $database);

  $usrname = $_POST['username'];
  $goal = $_POST['goal'];

  $q = "INSERT INTO User VALUES (?, ?)";
  $st = $cn->stmt_init();
  $st->prepare($q);
  $st->bind_param("si", $usrname, $goal);
  $st->execute();

  $st->close();
  $cn->close();

  setcookie('user', $usrname);

  header("Location: home.php");

?>

