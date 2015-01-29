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
        $query  = "SELECT * FROM friendRequests ";
        $query .= "WHERE toUserID = $userID;";
        $result = mysqli_query($db, $query);
        if (!$result){
          die(mysqli_error($db));
        }
        $allRequests = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        if ($allRequests != NULL){
          $requests = [];
          foreach ($allRequests as $req){
            $element = [];
            $requestFrom = [];
            $fromID = $req["fromUserID"];
            $element["requestID"] = $req["requestID"];

            // get username and imageURL
            $query  = "SELECT * FROM users ";
            $query .= "WHERE id = $fromID;";
            $result = mysqli_query($db, $query);
            if (!$result){
              die(mysqli_error($db));
            }
            $personInfo = mysqli_fetch_assoc($result);
            $requestFrom["stUserID"] = $fromID;
            $requestFrom["personName"] = $personInfo["user_name"];
            $requestFrom["personProfileImageURL"] = $personInfo["imageURL"];
            $aStick = [];
            mysqli_free_result($result);

            //get astick
            $query  = "SELECT * FROM pins ";
            $query .= "WHERE stickOwner = '{$fromID}' ";
            $query .= "ORDER BY stickDate DESC ";
            $query .= "LIMIT 1; ";
            $result = mysqli_query($db, $query);
            if (!$result){
              die(mysqli_error($db));
            }
            $RAWaStick = mysqli_fetch_assoc($result);
            if ($RAWaStick != NULL){
              $aStick["stickID"] = (string) $RAWaStick["stickID"];
              $aStick["stickTitle"] = $RAWaStick["stickTitle"];
              $aStick["stickSubtitle"] = $RAWaStick["stickSubtitle"];
              $aStick["stickLat"] = (string) $RAWaStick["stickLat"];
              $aStick["stickLon"] = (string) $RAWaStick["stickLon"];
              $aStick["stickPossibility"] = (string) $RAWaStick["stickPossibility"];
              $aStick["stickImageURL"] = $RAWaStick["stickImageURL"];
            }
            $requestFrom["aStick"] = $aStick;
            $element["requestFrom"] = $requestFrom;
            array_push($requests, $element);

          }
          $flag = 0;
          $message = "Success";
        }
      }
    }
    mysqli_close($db);
  }
  $finalJSON = ["flag" => $flag,
    "msg" => $message,
    "requests" => $requests];

  echo json_encode($finalJSON);
?>





