<?php
  echo "hello!";

  // database connection
  $dbhost = "localhost";
  $dbuser = "fivxiaoo_stickPushNotifications";
  $dbpass = "TOi@XAKhm[%(";
  $dbname = "fivxiaoo_stickPushNotifications";


  $db = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

  if(mysqli_connect_errno()) {
    echo "connection failed";
    $message = "Database connection failed: " . mysqli_connect_error() .
      " (" . mysqli_connect_errno() . ")";
    die($message);
  }


  $query = "CREATE TABLE justTesting( id INT(12));";
  $result = mysqli_query($db, $query);
  if (!$result){
    die(mysqli_error($db));
  }

  mysqli_close($db);
  echo "sucess";
?>






