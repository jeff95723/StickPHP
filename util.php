<?php
	function redirect_to($new_location) {
		header("Location: " . $new_location);
		exit;
	}

  function generateRandomString($length = 6){
    $string = "";
    $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    while ($length > 0) {
        $string .= $characters[mt_rand(0,strlen($characters)-1)];
        $length -= 1;
    }
    return $string;
  }

  function getRootURL(){
    $root = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://';// . $_SERVER['HTTP_HOST'] . '/';
    $url = $_SERVER['REQUEST_URI']; //returns the current URL
    $parts = explode('/',$url);
    $dir = $_SERVER['SERVER_NAME'];
    for ($i = 0; $i < count($parts) - 1; $i++) {
     $dir .= $parts[$i] . "/";
    }
    $result = $root;
    $result .= $dir;
    return rtrim($result, "/");
  }

?>
