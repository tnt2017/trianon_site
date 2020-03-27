<?php
session_start ();
if (isset ( $_GET ['logout'] )) {
	unset ( $_SESSION ['OraLogin'] );
	unset ( $_SESSION ['OraPwd'] );
	unset ( $_SESSION ['OraName'] );
	
	// $cookie_name = 'OraPar';
	// setcookie ( $cookie_name, '', time () - 1000 );
	// setcookie ( $cookie_name, '', time () - 1000, '/' );
}

if(!defined('__NO_SESSION_START__')) {
	$db = new CMyDb ();
	if (isset ($_SESSION ['OraLogin'])) {
		try {
			$db->connect($_SESSION ['OraLogin'], $_SESSION ['OraPwd'], "trn");

			$arlg = explode("$", $_SESSION ['OraLogin']);
			$_SESSION ['DB_SCHEME'] = $arlg [1];

			$_SESSION ['CUR_USER_ID'] = ssec_auth_path_get_cur_user_katsotr_id($db);
			$_SESSION ['CUR_USER_FIO'] = ssec_auth_path_get_cur_user_fio($db);
		} catch (Exception $e) {
			$url = 'login.php';
			echo "<script>window.location.href = '$url';</script>";
			exit ();
		}
	} /* else if (isset ( $_COOKIE ['OraPar'] ) && ! isset ( $_GET ['logout'] )) {
	$db->connect ( $_SESSION ['OraLogin'], $_SESSION ['OraPwd'], "trn" );

	$arlg = explode ( "$", $_SESSION ['OraLogin'] );
	$_SESSION ['DB_SCHEME'] = $arlg [1];

	$_SESSION ['CUR_USER_ID'] = ssec_auth_path_get_cur_user_katsotr_id ( $db );
	$_SESSION ['CUR_USER_FIO'] = ssec_auth_path_get_cur_user_fio ( $db );
}*/ else {
		$url = 'login.php';
		echo "<script>window.location.href = '$url';</script>";
		exit ();
	}
}

?>