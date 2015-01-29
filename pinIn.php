<?php
  // flags:
  // 1: database connection failed
  // 2: missing field in form
  // 3: insert failed
  require_once("util.php");
  require_once("valUtil.php");

  // header('Content-Type: application/json');

  $message  = "";
  $flag = -1;
  $id = -1;

  // database connection
  $dbhost = "localhost";
  $dbuser = "";
  $dbpass = "";
  $dbname = "stick";
  /*
  $dbhost = "localhost";
  $dbuser = "fivxiaoo_jf";
  $dbpass = "Ltq-9n!PS*7F";
  $dbname = "fivxiaoo_stick";
   */
  $db = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

  // output database errors if any

  if(mysqli_connect_errno()) {
    $flag = 1;
    $message = "Database connection failed: " . mysqli_connect_error() .
      " (" . mysqli_connect_errno() . ")";
    die($message);
  } else {


    $pinTitle = "";
    $pinOwnerID = "";
    $pinDate = "";
    $pinComment = "";
    $possibility = "";
    $invited = "";
    $pinType = "";
    $pinLat = "";
    $pinLong = "";
    $isActive = "";


    // get the username and password
    // check if they are present
    if(!(has_presence($_POST["pinTitle"]) &&
         has_presence($_POST["pinOwnerID"]) &&
         has_presence($_POST["pinDate"]) &&
         has_presence($_POST["pinComment"]) &&
         has_presence($_POST["possibility"]) &&
         has_presence($_POST["invited"]) &&
         has_presence($_POST["pinType"]) &&
         has_presence($_POST["pinLat"]) &&
         has_presence($_POST["pinLong"]) &&
         has_presence($_POST["isActive"])))
    {
      $flag = 2;
      $message = "Missing field in form";
    } else {
      $pinTitle = $_POST["pinTitle"];
      $pinOwnerID =(int) $_POST["pinOwnerID"];
      $pinDate = $_POST["pinDate"];
      $pinComment = $_POST["pinComment"];
      $possibility = $_POST["possibility"];
      $invited = $_POST["invited"];
      $pinType = (int) $_POST["pinType"];
      $pinLat = (float) $_POST["pinLat"];
      $pinLong = (float) $_POST["pinLong"];
      $isActive = (BOOL) $_POST["isActive"];

    }
    $query  = "INSERT INTO pins (pinTitle, pinOwnerID, pinDate, pinComment, possibility, pinType, pinLat, pinLong, isActive)";
    $query .= "VALUES ('{$pinTitle}', {$pinOwnerID}, '{$pinDate}', '{$pinComment}', {$possibility}, {$pinType},{$pinLat},{$pinLong},{$isActive});";
    $result1 = mysqli_query($db, $query);
    if (!$result1){
      $flag = 3;
      $message = "insert failed";
      die(mysqli_error($db));
    } else {
      $message = "Insert Successful;";
      $flag = 0;
      $query  = "SELECT pinID FROM pins ";
      $query .= "WHERE pinOwnerID = $pinOwnerID ";
      $query .= "ORDER BY pinID DESC;";
      $result = mysqli_query($db, $query);
      if (!$result){
        die(mysqli_error($db));
      }
      $id = mysqli_fetch_array($result)[0];
      mysqli_free_result($result);
    }
    mysqli_close($db);
  }
  $finalJSON = ["flag" => $flag,
    "pinID" => (int) $id,
    "msg" => $message];
  echo json_encode($finalJSON);
?>

