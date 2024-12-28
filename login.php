<html>
<body>

<h1>Calorie Count!</h1>

<b> Log in:</b>
<br/>
    <form action="login.php" method="GET">
        <table>
        <tr>
        <td>Username:</td>
        <td>
            <input type="text" name = "username">
            </input>
        </td>
            </tr>
        <td><input type="submit" value="Log In"/></td>
        </tr>
        <tr>
    </form>

 <?php
    $config = parse_ini_file("config.ini");   // better to hide this!
    $server = $config["host"];
    $username = $config["user"];
    $password = $config["password"];
    $database = $config["database"];

    // create the connection
    $cn = mysqli_connect($server, $username, $password, $database);

    $usrn = $_GET['username'];
    // echo $usrn;

    $q = "SELECT username FROM User WHERE username = ?";
    $st = $cn->stmt_init();
    $st->prepare($q);
    $st->bind_param("s", $usrn);
    $st->execute();
    $st->bind_result($name);
    $st->fetch();
    // echo "name: " .$name . "<br>" . "usrn: " . $usrn;
    if(strcmp($name, $usrn) == 0 && strlen($usrn) > 0)
    {
        $st->close();
        $cn->close();
        setcookie('user', $usrn);
        header("Location: home.php");
    }
    else if(strlen($usrn) > 0)
    {
        echo "<br>" . "Not a valid username";
    }

    $st->close();
    $cn->close();
  


 ?>


</body>
</html>