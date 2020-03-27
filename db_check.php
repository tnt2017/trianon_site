<?php
session_start ();
require_once 'extras/utils.php';
require_once 'extras/orasql.php';

if (empty ( $_POST ['id'] ) || empty ( $_POST ['password'] )) {
	// http_response_code(404);
	echo 'Ошибка имени или пароля.';
} else {
	// Define $username and $password
	$id = $_POST ['id'];
	$password = esc_decode ( $_POST ['password'] );
	$remember_me = isset ( $_POST ['remember_me'] ) ? $_POST ['remember_me'] : false;
	
	$db = new CMyDb ();
	$login = "NNN";
	$pwd = "registr";
	$db->connect ( $login, $pwd, "trn" );
	$db->parse ( "begin SSEC.AUTH_PATH.LIST_USERBASE(:ret); end;" );
	$db->bind ( ":ret", $cur, OCI_B_CURSOR );
	$db->execute ();
	$db->execute_cursor ( $cur );
	
	while ( $row = $db->fetch_cursor ( $cur ) ) {
		if ($id == $row ['ID']) {
			$_SESSION ['OraLogin'] = $row ['BASENAME'];
			$_SESSION ['OraPwd'] = $password;
			$_SESSION ['OraName'] = $row ['USERDESCR'];
			if ($remember_me) {
				$ora = sencode ( $_SESSION ['OraLogin'] ) . 'g0' . sencode ( $_SESSION ['OraPwd'] );
				$cookie_name = 'OraPar';
				$expire = time () + (365 * 24 * 60 * 60); // set cookie expire to one year
				setcookie ( $cookie_name, $ora, $expire );
			}
			break;
		}
	}
	
	try {
		$db->connect ( $_SESSION ['OraLogin'], $_SESSION ['OraPwd'], "trn" );
		// http_response_code(200);
	} catch ( Exception $e ) {
		unset ( $_SESSION ['OraLogin'] );
		unset ( $_SESSION ['OraPwd'] );
		unset ( $_SESSION ['OraName'] );
		// http_response_code(200);
		exit ();
	}
}

?>