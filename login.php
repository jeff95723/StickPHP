<?php
  // flags:
  // 1: database connection failed
  // 2: missing field in form
  // 3: insert failed
  // 5: no username or password provided
  // 6: Bad login
  require_once("util.php");
  require_once("valUtil.php");

  header('Content-Type: application/json');

  $message  = "";
  $flag = -1;
  $stUserID = -1;
  $imageURL = "";

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


    $stUsername = "";
    $stPassword = "";

    // get the username and password
    // check if they are present
    if (!(has_presence($_POST["stUsername"]) && has_presence($_POST["stPassword"]))){
      $flag = 5;
      $message = "No username or password provided";
    } else {
      $username = $_POST["stUsername"];
      $password = $_POST["stPassword"];

      // validate the password and username
      $query  = "SELECT * FROM users ";
      $query .= "WHERE user_name='{$username}';";
      $result = mysqli_query($db, $query);
      if (!$result){
        die(mysqli_error($db));
      }
      $userInfo = mysqli_fetch_assoc($result);
      if (($userInfo == []) || ($userInfo["password"] != $password)){
        $flag = 6;
        $message = "Bad login";
      } else {
        // Login sucessful

        $imageURL = $userInfo["imageURL"];
        mysqli_free_result($result);
        $stUserID = $userInfo["id"];
        $flag = 0;
        $message = "Login Sucessful";

      }
    }
    mysqli_close($db);
  }
  $finalJSON = ["flag" => $flag,
    "msg" => $message,
    "stUserID" => $stUserID,
    "imageURL" => $imageURL];
  echo json_encode($finalJSON, JSON_UNESCAPED_SLASHES);
?>



