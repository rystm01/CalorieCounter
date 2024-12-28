

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
            <input type="submit" value="Search">
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
    $select_txt = "";

    // checks if get key 'food_name' exists from the search form 
    if(array_key_exists('food_name', $_GET))
    {
        //echo "calling search <br>";
        $table = search($_GET['food_name'],  $namearr,  $per_ozarr, $select_txt, $cn);
        setcookie('names', serialize($namearr), time()+3600);
        setcookie('cals', serialize($per_ozarr), time()+3600);

        
    }

    //checks if mealtable cookie exists, sets if it doesnt.
    if(!array_key_exists('mealtable', $_COOKIE))
    {
        setcookie('mealtable', "");
    }

    // add food to meal table when selected from the search
    
    $chosen_names = array();
    $chosen_cals = array();
    $chosen_oz = array();

    // checks if 'num' get key exists from the form 
    // that adds a food to the meal
    if(array_key_exists('num', $_GET)) { 
    
        $chosen_names = unserialize($_COOKIE['chosen_names'], ["allowed_classes" => false]);
        $chosen_cals = unserialize($_COOKIE['chosen_cals'], ["allowed_classes" => false]);
        $chosen_oz = unserialize($_COOKIE['chosen_oz'], ["allowed_classes" => false]);

        $namearr = unserialize($_COOKIE['names'], ["allowed_classes" => false]);
        $per_ozarr = unserialize($_COOKIE['cals'], ["allowed_classes" => false]);

        setcookie('mealtable', $_COOKIE['mealtable'] . addtomeal($namearr[$_GET['num']], 
        $_GET['food_oz'],  $per_ozarr[$_GET['num']], $chosen_names, $chosen_cals, $chosen_oz));

        setcookie('chosen_names', serialize($chosen_names), time()+3600);
        setcookie('chosen_cals', serialize($chosen_cals), time()+3600);
        setcookie('chosen_oz', serialize($chosen_oz), time()+3600);

        header("Location: addmeal.php");
      } 

      // checks if 'meal_name' get value exists 
      // from the form that has users submit their meal and 
      // sends them back to the home screen
      if(array_key_exists('meal_name', $_GET))
      {
        $chosen_cals = unserialize($_COOKIE['chosen_cals'], ["allowed_classes" => false]);
        $chosen_names = unserialize($_COOKIE['chosen_names'], ["allowed_classes" => false]);
        $chosen_oz = unserialize($_COOKIE['chosen_oz'], ["allowed_classes" => false]);
        makemeal($_GET['meal_name'], $chosen_cals, $chosen_names, $chosen_oz, $cn );
        $cn->close();
        
        header("Location: home.php");
      }


    

?>

<table border = "1">
<tr>
    <th>Food</th>
    <th>Cal/OZ</th>
    <th>Serving Size</th>
</tr>
    <?php echo $table ?>
</table>

<form onClick = "addtomeal()" method = "GET">
    <b>Choose Food:<b><br>
    <select name = 'num' >
        <?php echo $select_txt;?>
    </select>
    <br><b>Enter OZ Eaten<b><br>
    <input type = "number" name = "food_oz"><br>
    <input type = "submit" value = "Add to Meal">
</form>

<br><br>

<table border = '1'>
    <tr>
    <th>Food</th>
    <th>OZ</th>
    <th>Cal</th>
    </tr>
    <?php echo $_COOKIE['mealtable'] ?>
</table>
    
<br><br>

<form onClick = makemeal()>
    Meal Name
    <input type = "text" name = "meal_name"><br>
    <input type = "submit" value = "Create Meal">
</form>


<?php
    // queries for foods like the search value
    function search( $food,  & $namearr, & $per_ozarr, & $select_txt, $cn)
    {
        // echo "in seach <br>";
        $q = "SELECT f_name, cal_per_oz, oz_per_serving
              FROM Food
              WHERE f_name LIKE ? LIMIT 10";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $food = $food . "%";
        $st->bind_param("s", $food);
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
// adds a food to the temporary meal foods
function addtomeal($name, $food_oz, $per_oz, &$chosen_names, &$chosen_cals, &$chosen_oz)
{
    $cal = $food_oz* $per_oz;// cal per oz from array yet to exit
    $chosen_cals[$_COOKIE['mealnum']] = $cal;
    $chosen_names[$_COOKIE['mealnum']] = $name;
    $chosen_oz[$_COOKIE['mealnum']] = $food_oz;

    setcookie('mealnum', $_COOKIE['mealnum']+1);
    return "<tr><td>$name</td><td>$food_oz</td><td>$cal</td></tr>";
    
}

// submits a meal to the meal table
// and all the foods to the MealFoods table
function makemeal($mealname, $chosen_cals, $chosen_names, $chosen_oz, $cn)
{
    
    $user = $_COOKIE['user'];
    $calsum = 0; 
    // echo   "lol <br>";
    for($i = 0; $i < $_COOKIE['mealnum']; $i++)
    {
        // echo $i ." <br>";
        $calsum = $calsum +  $chosen_cals[$i];
    }
    $q = "INSERT INTO Meal VALUES (?, ?, ?)";
    $st = $cn->stmt_init();
    $st->prepare($q);
    $st->bind_param("ssi", $mealname, $user, $calsum);
    $st->execute();
    $st->close();

    
    // 2 parameters are mealname, user, calories

    $q1 = 'INSERT INTO MealFoods VALUES (?, ?, ?, ?)';
    $st1 = $cn->stmt_init();
    $st1->prepare($q1);
    for($j = 0; $j < $_COOKIE['mealnum']; $j++)
    {
        $st1->bind_param("sssi", $mealname, $chosen_names[$j], $user, $chosen_oz[$j]);
        $st1->execute();
    }
    $st1->close();

    

    
    // sleep(10);
   


}
?>