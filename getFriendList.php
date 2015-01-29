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
  $friends = [];
  $stDictLength = 0;
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
        $query = "SELECT friendWithUserID FROM relations WHERE stUserID = $userID;";
        $result = mysqli_query($db, $query);
        if (!$result){
          die(mysqli_error($db));
        }
        // $friendIDList = mysqli_fetch_assoc($result);
        while ($row = $result->fetch_assoc()){
          $element = [];
          $friendID = (int) $row["friendWithUserID"];
          $query  = "SELECT * FROM users WHERE id = $friendID;";
          $result1 = mysqli_query($db, $query);
          if (!$result){
            die(mysqli_error($db));
          }
          $friendInfo = mysqli_fetch_array($result1);
          mysqli_free_result($result1);
          $element["stFriendID"] = $friendID;
          $element["stFriendName"] = $friendInfo["user_name"];
          $element["stFriendProfileImageURL"] = $friendInfo["imageURL"];
          array_push($friends, $element);
          $stDictLength += 1;

        }
        mysqli_free_result($result);
        $flag = 0;
        $message = "Get Sucessful";
      }
    }
    mysqli_close($db);
  }
  $finalJSON = ["flag" => $flag,
    "msg" => $message,
    "friends" => $friends,
    "stDictLength" => $stDictLength,
    "rootURL" => $rootURL];

  echo json_encode($finalJSON, JSON_UNESCAPED_SLASHES);
?>





