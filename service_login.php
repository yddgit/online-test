<?php

require_once 'common/common.inc.php';

if(!has_auth("POST")) { return; }

// 连接数据库
$conn = create_conn();

$org_name = check_input($_POST["org_name"]);
$dept_id = check_input($_POST["dept_id"]);
$user_name = check_input($_POST["user_name"]);
$identity_card = check_input($_POST["identity_card"]);

if(is_test($identity_card)) {
	// 关闭数据库连接
	close_conn($conn);

	$data = get_error_info(MessageType::INFO, "您已经参加过考试，可直接查看分数。");
	$data['identity_card'] = $identity_card;
	load_view("view_score.php", "post", true, $data);
	return;
} else {
	if(!is_exist($identity_card)) {
		$sql = "INSERT INTO m_user (name, identity_card, org_name, dept_id, is_test)"
			." VALUES ('%s', '%s', '%s', '%d', 0)";
		exec_sql($sql, array($user_name, $identity_card, $org_name, $dept_id));
	}

	// 关闭数据库连接
	close_conn($conn);

	$data = array('identity_card' => $identity_card);
	load_view("view_test.php", "post", false, $data);
	return;
}
