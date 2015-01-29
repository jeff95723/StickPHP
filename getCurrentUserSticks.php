<?php
  // flags:
  // 1: database connection failed
  // 2: missing field in form
  // 3: insert failed
  // 4: stickID does not exist
  // 5: no username or password provided
  // 6: Bad login
  // 7: Invalid pullScope
  require_once("util.php");
  require_once("valUtil.php");

  header('Content-Type: application/json');

  $message  = "";
  $flag = -1;
  $id = -1;
  $sticks = [];
  $stDictLength = 0;

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


        if(!(has_presence($_POST["stPullScope"]) ||
             $_POST["stPullScope"] == "global"   ||
             $_POST["stPullScope"] == "user"))
        {
          $flag = 7;
          $message = "Invalid pullscope";
        } else {

          $stPullScope = $_POST["stPullScope"];

          if(!(has_presence($_POST["stPullCount"])) ||
              $_POST["stPullCount"] <= 0){
            $flag = 8;
            $message = "Invalid pullCount";

          } else {

            $stPullCount = (int) $_POST["stPullCount"];

            if ($stPullScope == "global"){
              $queryAddon = " ";
            } else if ($stPullScope == "user"){
              $queryAddon = "WHERE stickOwner = '{$userID}' ";
            }

            $query = "SELECT * FROM pins ";
            $query .= $queryAddon;
            $query .= "ORDER BY stickDate DESC LIMIT {$stPullCount};";

            $result = mysqli_query($db, $query);
            if (!$result){
              die(mysqli_error($db));
            }
            while ($pinInfo = $result->fetch_assoc()){
              if (!$pinInfo == NULL){
                $stDictLength += 1;
                $aStick = [ "stickID" => $pinInfo["stickID"],
                            "stickTitle" => $pinInfo["stickTitle"],
                            "stickSubtitle" => $pinInfo["stickSubtitle"],
                            "stickLat" => $pinInfo["stickLat"],
                            "stickLon" => $pinInfo["stickLon"],
                            "stickPossibility" => $pinInfo["stickPossibility"],
                            "stickOwner" => $pinInfo["stickOwner"],
                            "stickMessage" => $pinInfo["stickMessage"],
                            "stickDate" => $pinInfo["stickDate"],
                            "stickImageURL" => $pinInfo["stickImageURL"]
                          ];
                array_push($sticks,$aStick);
              }
            }

            $message = "Get Successful;";
            $flag = 0;

          }
        }
      }
    }
    mysqli_close($db);
  }
  $finalJSON = ["flag" => $flag,
    "msg" => $message,
    "sticks" => $sticks,
    "stDictLength" => $stDictLength];

  echo json_encode($finalJSON);
?>





