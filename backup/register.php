<?php
  require_once("util.php");
  require_once("valUtil.php");

  header('Content-Type: application/json');

  $message  = "";
  $flag = false;
  $id = -1;

  // database connection
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


  $username = "";
  $password = "";


  // get the username and password
  // check if they are present
  if (!has_presence($_POST["stUsername"])){
    $message .= "No username provided;";
  } elseif(!has_presence($_POST["stPwd"])) {
    $message .= "No password provided;";
  } elseif(!has_presence($_POST["stUserNickName"])) {
    $message .= "No Nickname provided;";
  } else {
    $username = $_POST["stUsername"];
    $nickname = $_POST["stUserNickName"];
    $password = $_POST["stPwd"];
  }

  // validate the password, nickname and username
  $query  = "SELECT id FROM users ";
  $query .= "WHERE user_name='{$username}'";
  $result = mysqli_query($db, $query);
  if (!$result){
    die(mysqli_error($db));
  }
  $userInfo = mysqli_fetch_assoc($result);
  if (!$userInfo == []){
    $message .= "Username already exists;";
  } elseif ( !isShorterThan($password, 30)){
    $message .= "Password too long;";
  } elseif ( !isLongerThan($password, 3 )){
    $message .= "Password too short;";
  } elseif ( !isShorterThan($nickname, 30 )){
    $message .= "Nickname too long;";
  } elseif ( !isLongerThan($nickname, 3 )){
    $message .= "Nickname too short;";
  } elseif ( !isShorterThan($username, 30 )){
    $message .= "Username too long;";
  } elseif ( !isLongerThan($username, 3 )){
    $message .= "Username too short;";
  } else {
    $query  = "INSERT INTO users (user_name, password, nickname)";
    $query .= "VALUES ('{$username}', '{$password}', '{$nickname}')";
    $message .= "Register Successful;";
    $result1 = mysqli_query($db, $query);
    if (!$result1){
      die(mysqli_error($db));
    }
    $flag = true;
    mysqli_free_result($result);
    $query  = "SELECT * FROM users ";
    $query .= "WHERE user_name='{$username}'";
    $result = mysqli_query($db, $query);
    $id = mysqli_fetch_assoc($result)["id"];
    mysqli_close($db);
  }
  $finalJSON = ["flag" => $flag,
    "stUserID" => (int) $id,
    "msg" => $message];
  echo json_encode($finalJSON);
?>
