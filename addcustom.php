

<h1>Calorie Count!</h1>
<td>Make Custom Food:</td>
<form>
    <form onClick ="add()" method="GET">
        <table>
        <tr>
        <td>Food Name:</td>
        <td>
            <input type="text" name = "food">
            </input>
        </td>
            </tr>
            <tr>
            <td>Calories Per OZ:</td>
            <td><input type="number" name="calories"></td>
        </tr>
        </td>
            </tr>
            <tr>
            <td>Serving Size:</td>
            <td><input type="number" name="size"></td>
            </tr>
        <td><input type="submit" value="Create"/></td>
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
  
    // checks if get key 'food' exists from  the form 
    // that a user enters custom food info into
    if(array_key_exists('food', $_GET))
    {
        add($_GET['food'], $_GET['calories'], $_GET['size'], $cn);
        $cn->close();
        header('Location: home.php');
    }
?>

<?php 
    // inserts info into customfood table
    function add($food, $cal, $size, $cn)
    {
        echo "here<br>";
        $q = "INSERT INTO CustomFood VALUES (?, ?, ?, ?)";
        $st = $cn->stmt_init();
        $st->prepare($q);
        $u = $_COOKIE['user'];
        //echo "user: $u, food: $food, cal: $cal, size: $size <br>";
        $st->bind_param("ssii", $u, $food, $cal, $size);
        $st->execute();
        
        $st->close();
   
    }
?>
