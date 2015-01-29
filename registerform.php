<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
	<head>
		<title>Register</title>
	</head>
	<body>

		<form action="register.php" method="post" enctype="multipart/form-data">
        Username: <input type="text" name="stUsername" value="" /><br />
        Password: <input type="password" name="stPassword" value="" /><br />
        Nickname: <input type="text" name="stUserNickname" value="" /><br />
        Image:    <input type="file" name="stImage" id = "file"/><br />
			<br />
		  <input type="submit" name="submit" value="Submit" />
		</form>

	</body>
</html>
