<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>

	<?php
		$service = new AuthService();


		if(isset($_POST['btn_login'])) {
			$post = $_POST;
			$retVal = $service->authenticate($post['username'], $post['password']);

			dump($retVal);
		}

		if(isset($_POST['btn_new_user'])) {
			$post = $_POST;
			$retVal = $service->getDatabaseHelper()->store($post);

			
		}
	?>
	<form method="post">
		<table>
			<tr>
				<td>Username</td>
				<td><input type="text" name="username"></td>
			</tr>

			<tr>
				<td>Password</td>
				<td><input type="password" name="password"></td>
			</tr>

			<tr>
				<td><input type="submit" name="btn_login" value="LOGIN"></td>
				<td><input type="submit" name="btn_new_user" value="CREATE NEW USER"></td>
			</tr>
		</table>
	</form>
</body>
</html>