<?php
  require_once("util.php");
  require_once("valUtil.php");

  header('Content-Type: application/json');

  $message  = "";
  $flag = false;
  $id = -1;

  // database connection
  $dbhost = "localhost";
  $dbuser = "";
  $dbpass = "";
  $dbname = "stick";
  $db = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

  // output database errors if any

  if(mysqli_connect_errno()) {
    $message .= "Database connection failed: " . mysqli_connect_error() .
      " (" . mysqli_connect_errno() . ")";
    die($message);
  }


  $username = "";
  $password = "";

  // get the username and password
  // check if they are present
  if (!has_presence($_POST["stUsername"])){
    $message .= "No username provided;";
  } elseif(!has_presence($_POST["stPwd"])) {
    $message .= "No password provided;";
  } else {
    $username = $_POST["stUsername"];
    $password = $_POST["stPwd"];
  }

  // validate the password and username
  $query  = "SELECT * FROM users ";
  $query .= "WHERE user_name='{$username}'";
  $result = mysqli_query($db, $query);
  if (!$result){
    die("Database query failed");
  }
  $userInfo = mysqli_fetch_assoc($result);
  if ( $userInfo == []){
    $message .= "Username does not exist;";
  } elseif ( $userInfo["password"] != $password ){
    $message .= "Wrong password;";
  } else {
    $message .= "Login Successful;";
    $flag = true;
    $id = $userInfo["id"];
    mysqli_free_result($result);
    mysqli_close($db);
  }
  $finalJSON = ["flag" => $flag,
    "stUserID" => (int) $id,
    "msg" => $message];
  echo json_encode($finalJSON);
  // echo $message;

?>

