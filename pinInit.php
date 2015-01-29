<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
	<head>
		<title>Pin Init</title>
	</head>
	<body>
<?php
  // local database connection
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

  $query  = "CREATE TABLE IF NOT EXISTS pins(";
  $query .= "stickID INT(15) NOT NULL AUTO_INCREMENT,";
  $query .= "stickTitle VARCHAR(40) NOT NULL,";
  $query .= "stickSubtitle VARCHAR(40),";
  $query .= "stickLat FLOAT(9,6) NOT NULL,";
  $query .= "stickLon FLOAT(9,6) NOT NULL,";
  $query .= "stickGeocode FLOAT(9,6) NOT NULL,";
  $query .= "stickPossibility FLOAT(9,6) NOT NULL,";
  $query .= "stickOwner VARCHAR(255) NOT NULL,";
  $query .= "stickImageURL VARCHAR(255),";
  $query .= "stickInvited VARCHAR(255) NOT NULL,";
  $query .= "stickMessage VARCHAR(140) NOT NULL,";
  $query .= "stickDate DATETIME NOT NULL,";
  $query .= "PRIMARY KEY (stickID)";
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

