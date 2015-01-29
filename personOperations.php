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


        if(!(has_presence($_POST["stPersonID"]) &&
             has_presence($_POST["command"]))){
          $flag = 2;
          $message = "Missing field in form";
        } else {

          $stPersonID = (int) $_POST["stPersonID"];
          $command = $_POST["command"];

          $query = "SELECT * FROM users WHERE id = {$stPersonID};";
          $ver = mysqli_query($db, $query);
          $userInfo = mysqli_fetch_assoc($ver);
          if ($userInfo == NULL){
            $flag = 8;
            $message = "stPersonID does not exist";
            mysqli_free_result($ver);
          } else {
            if ($command == "sendFriendReq"){
              $query  = "SELECT * FROM relations ";
              $query .= "WHERE stUserID = $userID AND friendWithUserID = $stPersonID;";
              $result = mysqli_query($db, $query);
              if (!$result){
                die(mysqli_error($db));
              }
              $relInfo = mysqli_fetch_assoc($result);
              mysqli_free_result($result);
              if ($relInfo != NULL || $stPersonID == $userID){

                $flag = 15;
                $message = "Invalid friend request target";

              } else {
                $query  = "SELECT * FROM friendRequests ";
                $query .= "WHERE fromUserID = $userID AND toUserID = $stPersonID;";
                $result = mysqli_query($db, $query);
                if (!$result){
                  die(mysqli_error($db));
                }
                $reqInfo = mysqli_fetch_assoc($result);
                mysqli_free_result($result);

                if ($reqInfo != NULL){
                  $flag = 16;
                  $message = "Friend request already sent";
                } else {
                  $query  = "INSERT INTO friendRequests (fromUserID, toUserID) ";
                  $query .= "VALUES($userID, $stPersonID);";
                  $result = mysqli_query($db, $query);
                  if (!$result){
                    die(mysqli_error($db));
                  }

                  $flag = 0;
                  $message = "Send Sucessful";
                }
              }
            } else if ($command == "deleteFriend"){

              $query  = "SELECT * FROM relations ";
              $query .= "WHERE stUserID = $userID AND friendWithUserID = $stPersonID;";
              $result = mysqli_query($db, $query);
              if (!$result){
                die(mysqli_error($db));
              }

              $relInfo = mysqli_fetch_assoc($result);
              mysqli_free_result($result);

              if ($relInfo == NULL){
                $flag = 17;
                $message = "Invalid delete friend target";
              } else {

                $query  = "DELETE from relations";
                $query .=" WHERE stUserID = $userID AND friendWithUserID = $stPersonID;";

                $result = mysqli_query($db, $query);
                if (!$result){
                  die(mysqli_error($db));
                }

                $query  = "DELETE from relations";
                $query .=" WHERE friendWithUserID = $userID AND stUserID = $stPersonID;";

                $result = mysqli_query($db, $query);
                if (!$result){
                  die(mysqli_error($db));
                }

                $flag = 0;
                $message = "Delete Successful";

              }


            } else {
              $flag = 13;
              $message = "Invalid command";
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






