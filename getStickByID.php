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
    $stickID = -1;
    $stickTitle = "";
    $stickSubtitle = "";
    $stickLat = -1;
    $stickLon = -1;
    $stickAddress = "";
    $stickPossibility = -1;
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


        if(!(has_presence($_POST["stickID"])))
        {
          $flag = 2;
          $message = "Missing field in form";
        } else {

          $stickID = (int) $_POST["stickID"];

          $query = "SELECT * FROM pins WHERE stickID = $stickID;";
          $ver = mysqli_query($db, $query);
          $pinInfo = mysqli_fetch_assoc($ver);
          if ($pinInfo == NULL){
            $flag = 4;
            $message = "stickID does not exist";
            mysqli_free_result($ver);
          } else {

            $message = "Get Successful;";
            $flag = 0;

            $stickID = $pinInfo["stickID"];
            $stickTitle = $pinInfo["stickTitle"];
            $stickSubtitle = $pinInfo["stickSubtitle"];
            $stickLat = $pinInfo["stickLat"];
            $stickLon = $pinInfo["stickLon"];
            $stickAddress = $pinInfo["stickAddress"];
            $stickPossibility = $pinInfo["stickPossibility"];
            $stickOwner = $pinInfo["stickOwner"];
            $stickImageURL = $pinInfo["stickImageURL"];
            $stickInvited = $pinInfo["stickInvited"];
            $stickMessage = $pinInfo["stickMessage"];
            $stickDate = $pinInfo["stickDate"];
          }
        }
      }
    }
    mysqli_close($db);
  }
  $finalJSON = ["flag" => $flag,
    "msg" => $message,
    "stickID" => $stickID,
    "stickTitle" => $stickTitle,
    "stickSubtitle" => $stickSubtitle,
    "stickLat" => $stickLat,
    "stickLon" => $stickLon,
    "stickAddress" => $stickAddress,
    "stickPossibility" => $stickPossibility,
    "stickOwner" => $stickOwner,
    "stickImageURL" => $stickImageURL,
    "stickInvited" => $stickInvited,
    "stickMessage" => $stickMessage,
    "stickDate" => $stickDate];

  echo json_encode($finalJSON);
?>




