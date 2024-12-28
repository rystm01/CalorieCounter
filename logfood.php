

<h1>Calorie Count!</h1>

<?php echo $_COOKIE['date'] . " <br>";?>
<form>
    <form onClick ="search()" method="GET">
        <table>
        <tr>
        <td>Food Name:</td>
        <td>
            <input type="text" name = "food_name">
            </input>
        </td>
            </tr>
            <tr>
            <td>Min Calories Per OZ:</td>
            <td><input type="number" name="min_calories"></td>
        </tr>
        </td>
            </tr>
            <tr>
            <td>Max Calories Per OZ:</td>
            <td><input type="number" name="max_calories"></td>
        </tr>
        </td>
            </tr>
        <td><input type="submit" value="Search"/></td>
    </table>
    </form>
</form>
<?php
    $config = parse_ini_file("config.ini");   // better to hide this!
    $server = $config["host"];
    $username = $config["user"];
    $password = $config["password"];
    $database = $config["database"];
    $cn = mysqli_connect($server, $username, $password, $database);
  
    $usrname = $_COOKIE['user'];
    $table = "";

    $namearr = array();
    $per_ozarr = array();
    //search call if search input there
    $select_txt = "";
    if(array_key_exists('food_name', $_GET))
    {
        //echo "calling search <br>";
        $table = search($_GET['min_calories'], $_GET['max_calories'], $_GET['food_name'],  $namearr,  $per_ozarr, $select_txt, $cn);
        setcookie('names', serialize($namearr), time()+3600);
        setcookie('cals', serialize($per_ozarr), time()+3600);

        
    }

    // log food call if input there
    if(array_key_exists('num', $_GET)) { 
        // echo "about to call log food";
        // sleep(5);
        $namearr = unserialize($_COOKIE['names'], ["allowed_classes" => false]);
        $per_ozarr = unserialize($_COOKIE['cals'], ["allowed_classes" => false]);


        
        logfood($cn, $usrname, $namearr[$_GET['num']], $_GET['food_oz'],  $per_ozarr[$_GET['num']]);
        // echo "header next! <br>";
        // $cn->close();
        header("Location: home.php");
      } 


    

?>

<table border = "1">
<tr>
    <th>Num</th>
    <th>Food</th>
    <th>Cal/OZ</th>
    <th>Serving Size</th>
    <?php echo $table ?>
  
</table>
<form onClick = "logfood()" method = "GET">
    <b>Choose Food:<b><br>
    <select name = 'num' >
        <?php echo $select_txt;?>
    </select>
    <br><b>Enter OZ Eaten<b><br>
    <input type = "number" name = "food_oz"><br>
    <input type = "submit" value = "Log Food">
</form>

<?php
    // searches for food like the users input.
    // puts it into select_txt
    function search($mincal, $maxcal, $food,  & $namearr, & $per_ozarr, & $select_txt, $cn)
    {
        // echo "in seach <br>";
        $q = "SELECT f_name, cal_per_oz, oz_per_serving
              FROM Food
              WHERE cal_per_oz >= ? and cal_per_oz <= ? and f_name LIKE ? 
              LIMIT 10";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $food = $food . "%";
        $st->bind_param("iis", $mincal, $maxcal, $food);
        $st->execute();
        
        $st->bind_result($f_name, $cal_per, $ser_size);

        //echo "food    calories per oz     serving size <br>";
        $i = 1;
        $table_text = "</tr>";
        while($st->fetch() && $i < 11)
        {
            $namearr[$i] = $f_name;
            $per_ozarr[$i] = $cal_per;

            $table_text = $table_text . "<tr>";
            $table_text = $table_text . "<td>" . $i . "</td>";
            $table_text = $table_text . "<td>" . $f_name . "</td>";
            $table_text = $table_text .  "<td>" . $cal_per . "</td>";
            $table_text = $table_text .  "<td>" . $ser_size . " OZ </td>";
            $select_txt = $select_txt .  "<option value = $i" . "> $i. $f_name<" . "/option>";
            $i++;
        }
        $table_text = $table_text .  "</tr>";
        // echo "$f_name<br>";
    
        $st->close();
        $cn->close();

        return $table_text;
    }

    
   
?>

<?php
function logfood($cn, $user, $name, $food_oz, $per_oz)
{


    $cal = $food_oz* $per_oz;;// cal per oz from array yet to exit
    $date = $_COOKIE['date'];
    echo "foodname: "  . $name . "food oz: $food_oz  per_oz: $per_oz cal: " . $cal . " user: $user  date: $date <br>"; 
    
    $q1 = "INSERT INTO FoodLog VALUES (?,?,?,?)";
    $st1 = $cn->stmt_init();
    $st1->prepare($q1);
    $st1->bind_param("ssis", $user, $name, $cal, $date);
    $st1->execute();
    $st1->close();
    

}
?>