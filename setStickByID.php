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
    $stickID = "";
    $stickTitle = "";
    $stickSubtitle = "";
    $stickLat = "";
    $stickLon = "";
    $stickAddress = "";
    $stickPossibility = "";
    $stickOwner = "";
    $stickImageURL = "";
    $stickInvited = "";
    $stickMessage = "";
    $stickDate = "";

    // get the username and password
    // check if they are present
    if (!(has_presence($_POST["stUserID"]) && has_presence($_POST["stPassword"]))){
      $flag = 4;
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
        $flag = 5;
        $message = "Bad login";
      } else {
        // Login sucessful

        mysqli_free_result($result);


        if(!(has_presence($_POST["stickTitle"]) &&
             has_presence($_POST["stickID"]) &&
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

          $stickID = (int) $_POST["stickID"];
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


          $query = "SELECT * FROM pins WHERE stickID = $stickID;";
          $ver = mysqli_query($db, $query);
          $pinInfo = mysqli_fetch_assoc($ver);
          if ($pinInfo == NULL){
            $flag = 4;
            $message = "stickID does not exist";
            mysqli_free_result($ver);
          } else {

            $query  = "UPDATE pins
              SET stickTitle = '{$stickTitle}',
              stickSubtitle = {$stickSubtitle},
              stickLat = $stickLat,
              stickLon = $stickLon,
              stickAddress = '{$stickAddress}',
              stickPossibility = $stickPossibility,
              stickOwner = '{$stickOwner}',
              stickImageURL = {$stickImageURL},
              stickInvited = '{$stickInvited}',
              stickMessage = {$stickMessage},
              stickDate = '{$stickDate}'
              WHERE stickID = {$stickID};";
            $result1 = mysqli_query($db, $query);
            if (!$result1){
              $flag = 3;
              $message = "query failed";
              die(mysqli_error($db));
            } else {
              $message = "Set Successful;";
              $flag = 0;
            }
          }
        }
      }
    }
    mysqli_close($db);
  }
  $finalJSON = ["flag" => $flag,
    "msg" => $message];
  echo json_encode($finalJSON);
?>



