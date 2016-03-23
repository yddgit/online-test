<?php

require '../common/open_conn.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$org_name = check_input($_POST["org_name"]);
	$dept_id = check_input($_POST["dept_id"]);
	$user_name = check_input($_POST["user_name"]);
	$identity_card = check_input($_POST["identity_card"]);

	if(is_test($identity_card)) {
		forward_page("../score.php?identity_card={$identity_card}", true, ErrorMessage::INFO, "已经参加过测试，可直接查看分数");
		return;
	} else {
		if(!is_exist($identity_card)) {
			$sql = "INSERT INTO m_user (name, identity_card, org_name, dept_id, is_test)"
				." VALUES ('%s', '%s', '%s', '%d', 0)";
			$result = exec_sql($sql, array($user_name, $identity_card, $org_name, $dept_id));
		}
		forward_page("../test.php?identity_card={$identity_card}", false);
		return;
	}
} else {
	forward_page("../error.php", true, ErrorMessage::DANGER, "没有操作权限。", "index.php", "请重新登录");
	return;
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