<h1>Calorie Count!</h1>

<?php
    $user = $_COOKIE['user'];
    echo "<h4>". $user . "'s Analytics: </h4><br>";

    $config = parse_ini_file("config.ini");   // better to hide this!
    $server = $config["host"];
    $username = $config["user"];
    $password = $config["password"];
    $database = $config["database"];

    $cn = mysqli_connect($server, $username, $password, $database);


    // finds day(s) user ate most foods
    $maxq = "SELECT SUM(calories) AS cals, date(log_date)
            FROM FoodLog
            WHERE user = ?
            GROUP BY date(log_date)
            HAVING cals >= ALL 
          (SELECT SUM(calories) AS cals
          FROM FoodLog
          WHERE user = ?
          GROUP BY date(log_date))";
        
    $st = $cn->stmt_init();
    $st->prepare($maxq);
    $st->bind_param("ss", $user, $user);

    $st->execute();
    $st->bind_result($maxcals, $maxdates);
    echo "Days with most calories from foods: <ul> ";
    while($st->fetch())
    {
        echo "<li>" . $maxdates . ",  " . $maxcals . "</li>";
    }
    echo "</ul>";

    $st->close();


    
    // finds day user ate least calories foods
    $minq = "SELECT SUM(calories) AS cals, date(log_date)
        FROM FoodLog
        WHERE user = ?
        GROUP BY date(log_date)
        HAVING cals <= ALL 
        (SELECT SUM(calories) AS cals
        FROM FoodLog
        WHERE user = ?
        GROUP BY date(log_date))";
    
    $st1 = $cn->stmt_init();
    $st1->prepare($minq);
    $st1->bind_param("ss", $user, $user);

    $st1->execute();
    $st1->bind_result($mincals, $mindates);
    echo "<br>Days with least calories from foods: <ul> ";
    while($st1->fetch())
    {
        echo "<li>" . $mindates . ",  " . $mincals . "</li>";
    }
    echo "</ul>";
    $st1->close();

    // finds average calories per day in user lifetime FROM FOODS
    $avgq = "SELECT ROUND(AVG(cals), 2) AS avg
    FROM 
    (SELECT SUM(calories) AS cals
    FROM FoodLog
    WHERE user = ?
    GROUP BY date(log_date))myTable";

    $st2 = $cn->stmt_init();
    $st2->prepare($avgq);
    $st2->bind_param("s",$user);

    $st2->execute();
    
    $st2->bind_result($avg);
    $st2->fetch();
    echo "Average amount of calories per day from foods all time: $avg <br>";
    
    $st2->close();

    $pop_foodq = "SELECT * 
                FROM 
                (SELECT f_name, food_count, DENSE_RANK()  OVER(ORDER BY food_count DESC) AS rank
                FROM
                (SELECT f_name, COUNT(*) AS food_count
                 FROM FoodLog
                WHERE user = ?
                GROUP BY f_name)table1)table2
                WHERE rank <=5";



    $st3 = $cn->stmt_init();
    $st3->prepare($pop_foodq);
    $st3->bind_param("s", $user);

    $st3->execute();
    $st3->bind_result($fnames, $fcounts, $rank);
    echo "<br>Your 5 favorite foods of all time:<br> <ul> ";
    while($st3->fetch())
    {
        echo "<li>" . $rank . ".  " . $fnames  . ": eaten " . $fcounts . " times" . "</li>";
    }
    echo "</ul>";
    $st3->close();



   

?>


<h3>Get Stats Between 2 Dates</h3>
<form method = 'GET'>
    Start Date:
    <input type = 'date' name = 'start'><br>
    End Date:&nbsp&nbsp    
    <input type = 'date' name = 'end'><br>
    <input type = 'submit' value = 'Get Stats'>
</form>

<?php
 // get stats from between 2 dates if the user wants 
 if(array_key_exists('start', $_GET))
 {   
    $start_date = $_GET['start'];
    $end_date = $_GET['end'];

    // finds total sum of calories between days
    $q4 = "SELECT SUM(calories)
            FROM FoodLog
            WHERE user = ? AND date(log_date) BETWEEN ? AND ?";
    $st4 = $cn->stmt_init();
    $st4->prepare($q4);
    $st4->bind_param('sss', $user, $start_date, $end_date);

    $st4->execute();
    $st4->bind_result($calsum);
    $st4->fetch();
    $st4->close();


    // finds average of cals each day
    $q5 = " SELECT ROUND(AVG(cals), 2) AS avg
    FROM 
    (SELECT SUM(calories) AS cals
    FROM FoodLog
    WHERE user = 'ryan1' AND date(log_date) BETWEEN '2023-12-05' AND '2023-12-15'
    GROUP BY date(log_date))myTable";
    $st5 = $cn->stmt_init();
    $st5->prepare($q5);
    $st5->bind_param('sss', $user, $start_date, $end_date);

    $st5->execute();
    $st5->bind_result($avgcals);
    $st5->fetch();
    $st5->close();


    // finds goal
    $q6 = "SELECT goal FROM User WHERE username = ?";
    $st6 = $cn->stmt_init();
    $st6->prepare($q6);
    $st6->bind_param('s', $user);

    $st6->execute();
    $st6->bind_result($goal);
    $st6->fetch();
    $st6->close();

    $goaldiff = $goal - $avgcals;
    $goaltext = "";

    if($goaldiff < 0)
    {
        $goaltext = "more";
        $goaldiff = $avgcals - $goal;
    }
    else
    {
        $goaltext = "less";
    }

    echo "Between $start_date and $end_date you: <br>
        <ul> <li>Ate $calsum calories.</li>
        <li> Thats an average of $avgcals per day </li>
        <li> Which is (on average) $goaldiff calories $goaltext than your goal of $goal. </ul>";
 }


 
?>