<?php
include "config.php";

if ( !isset($_POST['email'], $_POST['password']) ) {
	exit('Please fill both the username and password fields!');
}

// login
if ($stmt = $connection->prepare('SELECT id, email, password, rights FROM User WHERE email = ?')) {
	
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute();
	$stmt->store_result();

	if ($stmt->num_rows > 0) {
		$stmt->bind_result($id, $email, $password, $rights);
		$stmt->fetch();
		if (password_verify($_POST['password'], $password)) {
			session_regenerate_id();
			$_SESSION['loggedin'] = TRUE;
			$_SESSION['email'] = $email;
			$_SESSION['id'] = $id;
			$_SESSION[ 'rights' ] = $rights;
			header('Location: index.php');
		} else {
			echo '<script type="text/javascript">'; 
			echo 'alert("Wrong password");'; 
			echo 'window.location.href = "index.php";';
			echo '</script>';
		}
	} else {
		echo '<script type="text/javascript">'; 
		echo 'alert("Wrong email");'; 
		echo 'window.location.href = "index.php";';
		echo '</script>';
	}
	$stmt->close();
}
?>