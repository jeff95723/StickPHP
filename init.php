<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
	<head>
		<title>Register</title>
	</head>
	<body>
<?php
  // database connection
  /*
  $dbhost = "localhost";
  $dbuser = "";
  $dbpass = "";
  $dbname = "stick";
   */
  $dbhost = "localhost";
  $dbuser = "fivxiaoo_jf";
  $dbpass = "Ltq-9n!PS*7F";
  $dbname = "fivxiaoo_stick";
  $db = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

  // output database errors if any

  if(mysqli_connect_errno()) {
    $message .= "Database connection failed: " . mysqli_connect_error() .
      " (" . mysqli_connect_errno() . ")";
    die($message);
  }

  $query  = "CREATE TABLE IF NOT EXISTS users(";
  $query .= "id INT(11) NOT NULL AUTO_INCREMENT,";
  $query .= "user_name VARCHAR(32) NOT NULL,";
  $query .= "nickname VARCHAR(32) NOT NULL,";
  $query .= "imageURL VARCHAR(255),";
  $query .= "password VARCHAR(32) NOT NULL,";
  $query .= "PRIMARY KEY (id)";
  $query .= ");";
  $result = mysqli_query($db, $query);
  if (!$result){
    die(mysqli_error($db));
  } else {
    echo "Init Sucessful!";
    mysqli_close($db);
  }

?>
	</body>
</html>
