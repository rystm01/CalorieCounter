<h1>Calorie Count!</h1>

<?php
    echo $_COOKIE['date'] . "<br> ";

    $config = parse_ini_file("config.ini");   // better to hide this!
    $server = $config["host"];
    $username = $config["user"];
    $password = $config["password"];
    $database = $config["database"];

    $cn = mysqli_connect($server, $username, $password, $database);

    $q = "SELECT * FROM Meal WHERE username = ?";
    $st = $cn->stmt_init();
    $st->prepare($q);
    $st->bind_param('s', $_COOKIE['user']);
    $st->execute();
    $st->bind_result($mealname, $user, $cal);

    $select_text = "";
    while($st->fetch())
    {
        $select_text = $select_text . "<option value = $mealname" . ">$mealname<" . "/option>";
    }

    // checks if the user submitted the form 
    // and meal_name exists. logs and sends user home
    if(array_key_exists('meal_name', $_GET))
    {
        logmeal($_GET['meal_name'], $cn);
        header("Location: home.php");
    }

?>

<form onClick = "logmeal()">
    <select name = "meal_name">
        <?php echo $select_text; ?>
</select>
<input type = 'submit' value = "Log Meal">
</form>

<?php
    // puts values into mealLog
    function logmeal($name, $cn) 
    {
        $user = $_COOKIE['user'];
        $date = $_COOKIE['date'];
        $q1 = "INSERT INTO MealLog VALUES (?,?,?)";
        $st1 = $cn->stmt_init();
        $st1->prepare($q1);
        $st1->bind_param("sss", $name, $user, $date);
        $st1->execute();
        $st1->close();
    }
?>