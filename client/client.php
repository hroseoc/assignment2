<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
<head><title>Database test page</title>
<link rel="stylesheet" href="style.css">
<style>
th { text-align: left; }

table, th, td {
  border: 2px solid grey;
  border-collapse: collapse;
}

th, td {
  padding: 0.2em;
}
</style>
</head>

<body>
<h1>Welcome to MeTrade</h1>

<p>Enter in your details, and choose an item to rent...</p>

<!--https://www.w3schools.com/php/php_forms.asp?fbclid=IwAR1fFVYBlVvTBeufJkODL7Actd8Vl4P-SAwjzT7A81xHe4370VqyvgYIigU
//php code to create a form-->

<form action="client.php" method="post">
First Name: <input type="text" name="firstName"><br>
Last Name: <input type="text" name="lastName"><br>
Phone: <input type="text" name="phone"><br>
Email: <input type="text" name="email"><br>
<p>
    <label for="itemRented">Rental options:</label>
        <select name="itemRented" id="item">
        <option value="bike">Bike</option>
        <option value="Scooter">Scooter</option>
        <option value="Skateboard">Skateboard</option>
        <option value="Yellow Chair">Yellow Chair</option>
        <option value="hat">Hat</option>
        <option value="shades">Shades</option>
        </select>
</p>
<input type="submit">
</form>


<?php
 
 $db_host   = 'database-1.cjf5kyyhnqwi.us-east-1.rds.amazonaws.com';
 $db_name   = 'meTrade';
 $db_user   = 'admin';
 $db_passwd = 'password';

$pdo_dsn = "mysql:host=$db_host;dbname=$db_name";

$pdo = new PDO($pdo_dsn, $db_user, $db_passwd);

$q = $pdo->query("SELECT * FROM BOOKING");

?>

<?php

#https://www.w3schools.com/php/php_mysql_connect.asp
#Connecting to mysql database 

// Create connection
$conn = mysqli_connect($db_host, $db_user,$db_passwd, $db_name);

// Check connection
if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}

#https://www.php.net/manual/en/mysqli.real-escape-string.php 
$firstName = mysqli_real_escape_string($conn, $_REQUEST['firstName']);
$lastName = mysqli_real_escape_string($conn, $_REQUEST['lastName']);
$phone = mysqli_real_escape_string($conn, $_REQUEST['phone']);
$email = mysqli_real_escape_string($conn, $_REQUEST['email']);
$itemRented = mysqli_real_escape_string($conn, $_REQUEST['itemRented']);

#inserting the first name data into the ID
$sql = "INSERT INTO booking (firstName,lastName,phone,email,itemRented) VALUES ('$firstName','$lastName','$phone','$email','$itemRented')";

#mySQL connection query 
#https://www.w3schools.com/php/func_mysqli_query.asp 
if(mysqli_query($conn, $sql)){
  echo "You have submitted your booking.";

$query = mysqli_query($conn,"SELECT * FROM BOOKING");

$f = fopen("/var/www/client/backUp.csv","w");

fwrite($f, "\n".'firstName'.",".'lastName'.",".'phone'.",".'email'.",".'itemRented');


while($row = mysqli_fetch_assoc($query)){
  fwrite($f,"\n".$row['firstName'].",".$row['lastName'].",".$row['phone'].",".$row['email'].",".$row['itemRented']);
  
}

echo shell_exec("aws s3 mv backUp.csv s3://349-ass1-bucket");

fclose($f);

  } else{
  echo "Failed to insert to database successfully! Did not execute $sql. " . mysqli_error($conn);
  }

#Close the connection to mySQL db
mysqli_close($conn);

?>
</table>
</body>
</html>
