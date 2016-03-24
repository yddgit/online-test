<?php

require 'common/open_conn.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$org_name = check_input($_POST["org_name"]);
	$dept_id = check_input($_POST["dept_id"]);
	$user_name = check_input($_POST["user_name"]);
	$identity_card = check_input($_POST["identity_card"]);

	if(is_test($identity_card)) {
		$data = get_error_info(MessageType::INFO, "您已经参加过测试，可直接查看分数。");
		$data['identity_card'] = $identity_card;
		load_view("score.php", "post", true, $data);
		return;
	} else {
		if(!is_exist($identity_card)) {
			$sql = "INSERT INTO m_user (name, identity_card, org_name, dept_id, is_test)"
				." VALUES ('%s', '%s', '%s', '%d', 0)";
			exec_sql($sql, array($user_name, $identity_card, $org_name, $dept_id));
		}
		$data = array('identity_card' => $identity_card);
		load_view("test.php", "post", false, $data);
		return;
	}
} else {
	$data = get_error_info(MessageType::DANGER, "没有操作权限。", "index.php", "请重新登录");
	load_view("error.php", "post", true, $data);
	return;
}

require 'common/close_conn.inc.php';