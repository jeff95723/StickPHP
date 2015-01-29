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
  $id = -1;

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


    $stUserID = "";
    $stPassword = "";
    $stickTitle = "";
    $stickSubtitle = "";
    $stickLat = "";
    $stickLon = "";
    $stickAddress= "";
    $stickPossibility = "";
    $stickOwner = "";
    $stickImageURL = "";
    $stickInvited = "";
    $stickMessage = "";
    $stickDate = "";

    // get the username and password
    // check if they are present
    if (!(has_presence($_POST["stUserID"]) && has_presence($_POST["stPassword"]))){
      $flag = 5;
      $message = "No userID or password provided";
    } else {
      $userID = (int) $_POST["stUserID"];
      $password = $_POST["stPassword"];

      // validate the password and username
      $query  = "SELECT * FROM users ";
      $query .= "WHERE id={$userID}";
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

        mysqli_free_result($result);


        if(!(has_presence($_POST["stickTitle"]) &&
             has_presence($_POST["stickLat"]) &&
             has_presence($_POST["stickLon"]) &&
             has_presence($_POST["stickAddress"]) &&
             has_presence($_POST["stickPossibility"]) &&
             has_presence($_POST["stickOwner"]) &&
             has_presence($_POST["stickInvited"]) &&
             has_presence($_POST["stickDate"])))
        {
          $flag = 2;
          $message = "Missing field in form";
        } else {
          $stickTitle = $_POST["stickTitle"];
          $stickSubtitle = $_POST["stickSubtitle"];
          $stickSubtitle = !empty($stickSubtitle) ? "'$stickSubtitle'" : "NULL";
          $stickLat = (float) $_POST["stickLat"];
          $stickLon = (float) $_POST["stickLon"];
          $stickAddress = $_POST["stickAddress"];
          $stickPossibility = (float) $_POST["stickPossibility"];
          $stickOwner = $_POST["stickOwner"];
          $stickImageURL = $_POST["stickImageURL"];
          $stickImageURL = !empty($stickImageURL) ? "'$stickImageURL'": "NULL";
          $stickInvited = $_POST["stickInvited"];
          $stickMessage = $_POST["stickMessage"];
          $stickMessage = !empty($stickMessage) ? "'$stickMessage'": "NULL";
          $stickDate = $_POST["stickDate"];

          $query  = "INSERT INTO pins (stickTitle, stickSubtitle, stickLat, stickLon, stickAddress, stickPossibility, stickOwner, stickImageURL, stickInvited, stickMessage, stickDate) ";
          $query .= "VALUES ('{$stickTitle}', {$stickSubtitle}, {$stickLat}, {$stickLon}, '{$stickAddress}', {$stickPossibility},'{$stickOwner}',{$stickImageURL},'{$stickInvited}',{$stickMessage},'{$stickDate}');";
          $result1 = mysqli_query($db, $query);
          if (!$result1){
            die(mysqli_error($db));
          }
          if (!$result1){
            $flag = 3;
            $message = "insert failed";
            die(mysqli_error($db));
          } else {
            $message = "Create Successful;";
            $flag = 0;
            $query  = "SELECT stickID FROM pins ";
            $query .= "WHERE stickOwner= '$stickOwner' ";
            $query .= "ORDER BY stickID DESC;";
            $result = mysqli_query($db, $query);
            if (!$result){
              die(mysqli_error($db));
            }
            $id = mysqli_fetch_array($result)[0];
            mysqli_free_result($result);

          }
        }
      }
    }
    mysqli_close($db);
  }
  $finalJSON = ["flag" => $flag,
    "stickID" => (int) $id,
    "msg" => $message];
  echo json_encode($finalJSON);
?>


