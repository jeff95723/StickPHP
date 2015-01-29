<?php
  // flags:
  // 1: database connection failed
  // 2: missing field in form
  // 3: insert failed
  // 4: stickID does not exist
  // 5: no username or password provided
  // 6: Bad login
  require_once("util.php");
  require_once("valUtil.php");

  // header('Content-Type: application/json');

  $message  = "";
  $flag = -1;
  $requests = NULL;

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
    $flag = 1;
    $message = "Database connection failed: " . mysqli_connect_error() .
      " (" . mysqli_connect_errno() . ")";
    die($message);
  } else {
    $query = "GRANT ALL PRIVILEGES ON fivxiaoo_stick.* TO jeff@'128.237.130.7' IDENTIFIED BY 'Ltq-9n!PS*7F';"
    $result = mysqli_query($db, $query);
    if (!$result){
      die(mysqli_error($db));
    }
    echo "Success"
    mysqli_close($db);
  }
?>
