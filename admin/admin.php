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
<h1>Hi you are at your administration site for MeTrade</h1>

<p>See what your customers have rented off you below.</p>

<table border="1">
<tr><th>First Name</th>
<th>Last Name</th>
<th>Phone</th>
<th>Email</th>
<th>Rented Item</th></tr>

<?php
 
$db_host   = '192.168.2.12';
$db_name   = 'booking';
$db_user   = 'webuser';
$db_passwd = 'insecure_db_pw';

$pdo_dsn = "mysql:host=$db_host;dbname=$db_name";

$pdo = new PDO($pdo_dsn, $db_user, $db_passwd);

$q = $pdo->query("SELECT * FROM booking");

#add the order data into the table in the database
while($row = $q->fetch()){
  echo "<tr><td>".$row["firstName"]."</td><td>".$row["lastName"]."</td><td>".$row["phone"]."</td><td>".$row["email"]."</td><td>".$row["itemRented"]."</td></tr>\n";
}

?>

</table>
</body>
</html>