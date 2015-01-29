<?php
  // flags:
  // 1: database connection failed
  // 2: missing field in form
  // 3: insert failed
  // 4: stickID does not exist
  // 5: no username or password provided
  // 6: Bad login
  // 8: stPersonID does not exist
  require_once("util.php");
  require_once("valUtil.php");

  header('Content-Type: application/json');

  $message  = "";
  $flag = -1;
  $isFriend = -1;
  $ReturnedStUserID = -1;
  $personName = "";
  $personProfileImageURL = "";
  $aStick = NULL;
  $sticks = NULL;
  $rootURL = getRootURL();

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

        $ReturnedStUserID = $userID;


        if(!(has_presence($_POST["stPersonID"])))
        {
          $flag = 2;
          $message = "Missing field in form";
        } else {

          $stPersonID = (int) $_POST["stPersonID"];

          $query = "SELECT * FROM users WHERE id = {$stPersonID};";
          $ver = mysqli_query($db, $query);
          $userInfo = mysqli_fetch_assoc($ver);
          if ($userInfo == NULL){
            $flag = 8;
            $message = "stPersonID does not exist";
            mysqli_free_result($ver);
          } else {

            $personName = $userInfo["user_name"];
            $personProfileImageURL = $userInfo["imageURL"];

            $query  = "SELECT * FROM relations ";
            $query .= "WHERE stUserID = $userID AND friendWithUserID = $stPersonID;";
            $result = mysqli_query($db, $query);
            if (!$result){
              die(mysqli_error($db));
            }
            $relInfo = mysqli_fetch_assoc($result);
            mysqli_free_result($result);

            if ($relInfo == NULL){
              $isFriend = 0;
            } else {
              $isFriend = 1;
            }

            if ($isFriend){
              // is friend

              $stFriendID = $stPersonID;
              $sticks = [];
              $stDictLength = 0;

              $query  = "SELECT * FROM pins WHERE stickOwner = '{$stFriendID}' ";
              $query .= "ORDER BY stickDate DESC LIMIT 10;";

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
                              "stickImageURL" => $pinInfo["stickImageURL"]
                            ];
                  array_push($sticks,$aStick);
                }
              }
              $message = "Get Successful";
              $flag = 0;
            } else {

              // not friend
              $query = "SELECT * FROM pins WHERE stickOwner = '{$stPersonID}';";
              $foo = mysqli_query($db, $query);
              $pinInfo = mysqli_fetch_assoc($foo);

              if (!$pinInfo == NULL){

                $aStick = [ "stickID" => $pinInfo["stickID"],
                            "stickTitle" => $pinInfo["stickTitle"],
                            "stickSubtitle" => $pinInfo["stickSubtitle"],
                            "stickLat" => $pinInfo["stickLat"],
                            "stickLon" => $pinInfo["stickLon"],
                            "stickPossibility" => $pinInfo["stickPossibility"],
                            "stickImageURL" => $pinInfo["stickImageURL"]
                          ];

              }

              $message = "Success";
              $flag = 0;

            }
          }
        }
      }
    }
    mysqli_close($db);
  }
  if ($isFriend){
    $finalJSON = ["isFriend" => $isFriend,
      "stUserID" => $ReturnedStUserID,
      "flag" => $flag,
      "msg" => $message,
      "friendName" => $personName,
      "friendProfileImageURL" => $personProfileImageURL,
      "sticks" => $sticks,
      "stDictLength" => $stDictLength,
      "rootURL" => $rootURL];
  } else {
    $finalJSON = ["isFriend" => $isFriend,
      "stUserID" => $ReturnedStUserID,
      "flag" => $flag,
      "msg" => $message,
      "personName" => $personName,
      "personProfileImageURL" => $personProfileImageURL,
      "aStick" => $aStick,
      "rootURL" => $rootURL];
  }

  echo json_encode($finalJSON, JSON_UNESCAPED_SLASHES);
?>





