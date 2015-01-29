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
  $rootURL = getRootURL();
  $stUserProfileImageURL = "";

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
    $stUserNickname = "";
    $stImage = "";

    // get the username and password
    // check if they are present
    if (!(has_presence($_POST["stUsername"]) &&
          has_presence($_POST["stPassword"]) &&
          has_presence($_POST["stUserNickname"]))){
      $flag = 5;
      $message = "No userID or password or nickname provided";
    } else {
      $stUsername = $_POST["stUsername"];
      $password = $_POST["stPassword"];
      $nickname = $_POST["stUserNickname"];

      // validate the password and username
      $query  = "SELECT * FROM users ";
      $query .= "WHERE user_name ='{$stUsername}'";
      $result = mysqli_query($db, $query);
      if (!$result){
        die(mysqli_error($db));
      }
      $userInfo = mysqli_fetch_assoc($result);
      if (!($userInfo == [])){
        $flag = 9;
        $message = "Username already exists";
      } else {

        mysqli_free_result($result);
        if (!(isShorterThan($password, 30) &&
              isLongerThan($password, 3) &&
              isShorterThan($nickname, 30) &&
              isLongerThan($nickname, 3) &&
              isShorterThan($stUsername, 30) &&
              isLongerThan($stUsername, 3)))
        {
          $flag = 10;
          $message = "Wrong username/nickname/password format";

        } else {
          // image upload processing
          $allowedExts = ["gif", "jpeg", "jpg", "png"];
          $temp = explode(".", $_FILES["stImage"]["name"]);
          $extension = end($temp);
          if ((($_FILES["stImage"]["type"] == "image/gif")
            || ($_FILES["stImage"]["type"] == "image/jpeg")
            || ($_FILES["stImage"]["type"] == "image/jpg")
            || ($_FILES["stImage"]["type"] == "image/pjpeg")
            || ($_FILES["stImage"]["type"] == "image/x-png")
            || ($_FILES["stImage"]["type"] == "image/png"))
            && ($_FILES["stImage"]["size"] < 2000000)
            && in_array($extension, $allowedExts))
          {
            if ($_FILES["stImage"]["error"] > 0){
              $flag = 12;
              $message = "File error" . $_FILES["stImage"]["error"];
            } else {

              $query  = "INSERT INTO users (user_name, nickname,  password)";
              $query .= "VALUES ('{$stUsername}', '{$nickname}', '{$password}' )";

              $result1 = mysqli_query($db, $query);
              if (!$result1){
                die(mysqli_error($db));
              }

              $query  = "SELECT * FROM users ";
              $query .= "WHERE user_name='{$stUsername}'";
              $result = mysqli_query($db, $query);
              $id = mysqli_fetch_assoc($result)["id"];
              mysqli_free_result($result);

              if (!file_exists('./image/user/' . $id. "/")){
                mkdir('./image/user/' . $id. "/", 0777, true);
              }
              $date = date('Y-m-d');
              $ranString = generateRandomString(6);
              move_uploaded_file($_FILES["stImage"]["tmp_name"],
                    "./image/user/" . $id. "/" . $date . $ranString . "." . $extension);
              $stUserProfileImageURL = "/image/user/" . $id. "/" . $date . $ranString . "." . $extension;
              $query  = "UPDATE users SET imageURL = '{$stUserProfileImageURL}' ";
              $query .= "WHERE id = $id;";

              $result1 = mysqli_query($db, $query);
              if (!$result1){
                die(mysqli_error($db));
              }

              $flag = 0;
              $message = "Register sucessful";
            }

          } else {
            $flag = 11;
            $message = "invalid image file";
          }
        }
      }
    }
    mysqli_close($db);
  }
  $finalJSON = ["flag" => $flag,
    "msg" => $message,
    "stUserID" => $id,
    "rootURL" => $rootURL,
    "stUserProfileImageURL" => $stUserProfileImageURL];

  echo json_encode($finalJSON, JSON_UNESCAPED_SLASHES);
?>





