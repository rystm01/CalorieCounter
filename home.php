<html>
<body>

<h1>Calorie Count!</h1>

<a href="logfood.php">
  <button type = "button" >Log Food</button>
</a>
<a href="logmeal.php">
  <button type = "button" >Log Meal</button>
</a>
<a href="logcustom.php">
  <button type = "button" >Log Custom Food</button>
</a>
<a href="analytics.php">
  <button type = "button" >Analytics</button>
</a>
 <a href="addmeal.php">
  <button type = "button" >Create Meal</button>
</a>
</a>
 <a href="addcustom.php">
  <button type = "button" >Create Custom Food</button>
</a>

<form action="" method="POST">
  <input type = "date" name = "date">
  <input type = "submit"  value = "Change Date">
</form>
 

 <?php
    $config = parse_ini_file("config.ini");   // better to hide this!
    $server = $config["host"];
    $username = $config["user"];
    $password = $config["password"];
    $database = $config["database"];


    setcookie('mealtable', "");
    setcookie('mealnum', 0);
    //fetch date if user entered one
    $date = $_COOKIE['date'];
    // echo "cookie date: $date <br>";
    if(!is_null($_POST['date']))
    {
      $date = $_POST['date'];
    }
  
    if(is_null($date))
    {
      $date = date("Y-m-d");
      setcookie('date', date("Y-m-d h:m:s"));
    }
    else
    {
      setcookie('date', $date);
    }
   
  
    

    // create the connection
    $cn = mysqli_connect($server, $username, $password, $database);

    $usrname = $_COOKIE['user'];

    // gets food for date entered (today by default)
    $q1 = "SELECT goal FROM User WHERE username = '$usrname'";
    $st1 = $cn->stmt_init();
    $st1->prepare($q1);
    // $st1->bind_param($usrname);
    $st1->execute();
    $st1->bind_result($goal);
    $st1->fetch();
    $st1->close();

    // welcome user
    
    echo "Welcome " . $usrname . "<br>" . $date . "<br> Todays goal: $goal". "<br> Todays Food log: ";

    // fetch all entries for the date and user
    $q = "SELECT f_name, calories, log_date
          FROM FoodLog
          WHERE user = ? and date(log_date) = date(?) 
          ORDER BY log_date";
    $st = $cn->stmt_init();
    $st->prepare($q);
    $st->bind_param("ss", $usrname, $date);
    $st->execute();
    $st->bind_result($r_fname, $r_cal, $r_date);

    $i = 1;
    $sum = 0;


    // stores values for remove and edit
    // mapped to nums
    $foodarr = array();
    $calarr = array();
    $datearr = array();
    $select_txt = "";
    $table_txt = "";
    while($st->fetch())
    {
      $select_txt = $select_txt . "<option value = $i" . "> $i. $r_fname<" . "/option>";
      //echo "<br>" . $i . ". " . $r_fname . " " . $r_cal;  
      $table_txt = $table_txt . "<tr><td>" . $i . '</td>' . "<td>" . $r_fname . '</td>' . "<td>" . $r_cal . '</td>';
      $sum += $r_cal;
      $i++;
      $foodarr[$i-1] = $r_fname;
      $calarr [$i-1] = $r_cal;
      $datearr [$i-1] = $r_date;
    }


    $st->store_result();
    $st->close();


    // fetch goal
    $q1 = "SELECT goal FROM User WHERE username = ?";
    $st1 = $cn->stmt_init();
    $st1->prepare($q1);
    
    $st1->bind_param('s', $usrname);
    $st1->execute();
    $st1->bind_result($goal);
    $st1->fetch();
    $st1->close();

    // welcome user
    
  

    // // fetch all meal entries for the date and user
    $q2 = "SELECT l.m_name, calories
          FROM MealLog l JOIN Meal m ON (m.m_name = l.m_name and user = username)
          WHERE date(log_date) = date(?) and user = ?
          ORDER BY log_date";
    $st2 = $cn->stmt_init();
    $st2->prepare($q2);
    $st2->bind_param("ss", $date, $usrname);
    $st2->execute();
    $st2->bind_result($r_mname, $r_mcal);

    $meal_table_txt = "";
    while($st2->fetch())
    {
      $meal_table_txt = $meal_table_txt . "<tr><td>$r_mname</td><td>$r_mcal</td></tr>";
      $sum += $r_mcal;
    }
    $st2->close();

    //fetch custom food entries
    $q3 = "SELECT f_name, calories
           FROM CustomLog
           WHERE user = ? and date(log_date) = date(?)";
    $st3 = $cn->stmt_init();
    $st3->prepare($q3);
    $st3->bind_param("ss", $usrname, $date);
    $st3->execute();
    $st3->bind_result($cf_name, $ccals);

    $custom_table_text = "";
    while($st3->fetch())
    {
      $custom_table_text = $custom_table_text . "<tr><td>$cf_name</td><td>$ccals</td></tr>";
      $sum += $ccals;
    }
    $st3->close();

   

    

    
?>


<table border = "1">
<tr>
    <th>Num</th>
    <th>Food</th>
    <th>Cal</th>
    <?php echo $table_txt ?>
</table>
Today's Meal Log:
<table border = '1'>
<tr>
    <th>Meal</th>
    <th>Cal</th>
    <?php echo $meal_table_txt ?>
</table>

Today's Custom Food Log:
<table border = '1'>
<tr>
    <th>Food</th>
    <th>Cal</th>
  </tr>
    <?php echo $custom_table_text ?>
</table>

<?php
    echo "<br> Total: " . $sum;
    echo "<br>" . $sum/$goal*100 . "% of goal <br>";

?>






<form onClick = "remove()" method = 'GET'>
  <b>Remove Food:</b><br>
  <select name = 'remove' >
    <?php echo $select_txt;?>
</select>
  <input type = 'submit' value = 'remove'>
  </form>

<?php 
//check if remove _GET exists then calls remove and refreshes
if(array_key_exists('remove', $_GET)) { 
  remove($_GET['remove'], $cn, $usrname, $foodarr[$_GET['remove']], $calarr[$_GET['remove']], $datearr[$_GET['remove']]);
  header('Location: home.php');
} 
?>
  <br>
  <b>Edit Food:</b><br>
  <form action = "" method = 'GET'>
  <select name = 'num' >
    <?php echo $select_txt;?>
</select>
  <br><b>New calorie value:</b><br>
  <input type = 'text' name = 'newval'>
  <input type = 'submit' value = 'edit'>
  </form>


<?php
  // remove function implemented
  // remove = input from form
  // cn = connection
  // usrname = user from cookies
  // food = food name from array
  // cal = cal from array
  // date = date from cookies/current date
  function remove($remove, $cn, $usrname, $food, $cal, $date)
  {
    echo "here, remove = $remove <br>";

    
      echo "here, remove = $remove <br>";
      $q2 = "DELETE FROM FoodLog 
            WHERE user = ? and f_name = ? and calories = ? and log_date = ?";
      $st2 = $cn->stmt_init();
      $st2->prepare($q2);
      echo $usrname . $food . $cal . $date; 
      $st2->bind_param("ssis", $usrname, $food, $cal, $date);
      $st2->execute();
      $st2->close();
      echo "here<br>";
    
    
  }
?>

<?php

 
  $num = $_GET['num'];
  $newval = $_GET['newval'];
  // checks if user is editing calories
  //updates cal value
  if($num != 0 && $newval != 0)
  {
    $q3 = "UPDATE FoodLog SET calories = ?
     WHERE user = ? and f_name = ? and calories = ? and log_date = ?";
    $st3 = $cn->stmt_init();
    $st3->prepare($q3);
    $st3->bind_param("issis", $newval, $usrname, $foodarr[$num], $calarr[$num], $datearr[$num]);
    $st3->execute();
    $st3->close();
  
    header('Location: home.php');

  }

?>




</body>
</html>