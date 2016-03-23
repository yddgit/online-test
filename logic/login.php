<?php

require '../common/open_conn.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$org_name = test_input($_POST["org_name"]);
	$dept_id = test_input($_POST["dept_id"]);
	$user_name = test_input($_POST["user_name"]);
	$identity_card = test_input($_POST["identity_card"]);

	if(is_test($identity_card)) {
		//TODO show score
	} else {
		if(!is_exist($identity_card)) {
			//TODO insert user info
		}
		//TODO show test paper
	}
	
} else {
	header("refresh:0;url=../index.php");
	return;
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function is_test($identity_card) {
	$sql = "SELECT count(*) AS count FROM m_user AS t1 WHERE t1.identity_card = '%s' AND t1.is_test = 1";
	$result = exec_sql($sql, $identity_card);
	$count = mysql_fetch_array ( $result )['count'];
	return $count > 0 ? true : false;
}

function is_exist($identity_card) {
	$sql = "SELECT count(*) AS count FROM m_user AS t1 WHERE t1.identity_card = '%s'";
	$result = exec_sql($sql, $identity_card);
	$count = mysql_fetch_array ( $result )['count'];
	return $count > 0 ? true : false;
}

require '../common/close_conn.inc.php';