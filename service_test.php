<?php

require 'common/open_conn.inc.php';

if(!has_auth("POST")) { return; }

$paper_id = check_input($_POST["paper_id"]);
$identity_card = check_input($_POST["identity_card"]);
$question_ids = check_input($_POST["question_ids"]);

if(is_test($identity_card)) {
	$data = get_error_info(MessageType::INFO, "您已经参加过测试，可直接查看分数。");
	$data['identity_card'] = $identity_card;
	load_view("view_score.php", "post", true, $data);
	return;
} else {
	if(!is_exist($identity_card)) {
		$sql = "INSERT INTO m_user (name, identity_card, org_name, dept_id, is_test)"
			." VALUES ('%s', '%s', '%s', '%d', 0)";
		exec_sql($sql, array($user_name, $identity_card, $org_name, $dept_id));
	}
	$data = array('identity_card' => $identity_card);
	load_view("view_test.php", "post", false, $data);
	return;
}

require 'common/close_conn.inc.php';