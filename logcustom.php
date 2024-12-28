<h1>Calorie Count!</h1>

<?php
    echo $_COOKIE['date'] . "<br> ";

    $config = parse_ini_file("config.ini");   // better to hide this!
    $server = $config["host"];
    $username = $config["user"];
    $password = $config["password"];
    $database = $config["database"];

    $cn = mysqli_connect($server, $username, $password, $database);

    // fetches all the custom foods a user has created
    $q = "SELECT f_name, cal_per_oz, oz_per_serving FROM CustomFood WHERE username = ?";
    $st = $cn->stmt_init();
    $st->prepare($q);
    $st->bind_param('s', $_COOKIE['user']);
    $st->execute();
    $st->bind_result($foodname, $cal_per_oz, $s_size);
    $per_ozarr = array();
    $s_sizearr = array();
    $foodname_arr = array();
    $select_text = "";
    $i = 1;
    while($st->fetch())
    {
        $per_ozarr[$i] = $cal_per_oz;
        $foodname_arr[$i] = $foodname;
        $select_text = $select_text . "<option value = $i" . ">$foodname<" . "/option>";
        $i++;
    }

    // checks if get value 'foodname' from the form exists
    // calls log food and sends user home if it does.
    if(array_key_exists('foodname', $_GET))
    {
        logfood($_GET['foodname'], $_GET['ozs'], $foodname_arr, $per_ozarr, $cn);
        header("Location: home.php");
    }

?>

<form onClick = "logfood()">
Food:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
    <select name = "foodname">
        <?php echo $select_text; ?>
</select>
<br>
Ozs Eaten:
<input type = 'number' name = "ozs">
<br>
<input type = 'submit' value = "Log Food">
</form>

<?php
    // gets info from querying CustomFood and sends the one
    // the user chose into FoodLog
    function logfood($num, $ozs, $foodnamearr, $per_ozarr, $cn) 
    {
        $user = $_COOKIE['user'];
        $date = $_COOKIE['date'];
        $cals = $per_ozarr[$num] * $ozs;
        $q1 = "INSERT INTO CustomLog VALUES (?,?,?,?)";


        $st1 = $cn->stmt_init();
        $st1->prepare($q1);
        $st1->bind_param("ssis", $foodnamearr[$num], $user, $cals, $date);

        echo $foodnamearr[$num] . " " . $user . " " . $cals . " " . $date;

        $st1->execute();
        $st1->close();
    }
?>