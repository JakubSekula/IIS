<?php

/******************************************************************************
 * Projekt: Hotel: rezervace a správa ubytování                               *
 * Předmět: IIS - Informační systémy - FIT VUT v Brně                         *
 * Rok:     2020/2021                                                         *
 * Tým:     xsekul01, varianta 2                                              *
 * Autoři:                                                                    *
 *          Jakub Sekula   (xsekul01) - xsekul01@stud.fit.vutbr.cz            *
 *          Lukáš Perina   (xperin11) - xperin11@stud.fit.vutbr.cz            *
 *			Martin Fekete  (xfeket00) - xfeket00@stud.fit.vutbr.cz            *
 ******************************************************************************/

?>

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
			$_SESSION['timestamp'] = time();
			header('Location: index.php');
		} else {
			echo '<script type="text/javascript">'; 
			echo 'alert("Wrong password");'; 
			echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'";';
			echo '</script>';
		}
	} else {
		echo '<script type="text/javascript">'; 
		echo 'alert("Wrong email");'; 
		echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'";';
		echo '</script>';
	}
	$stmt->close();
}
?>