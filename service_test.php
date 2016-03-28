<?php

require 'common/open_conn.inc.php';

if(!has_auth("POST")) { return; }

$paper_id = check_input($_POST["paper_id"]);
$identity_card = check_input($_POST["identity_card"]);

if(is_test($identity_card)) {
	$data = get_error_info(MessageType::INFO, "您已经参加过考试，可直接查看分数。");
	$data['identity_card'] = $identity_card;
	load_view("view_score.php", "post", true, $data);
	return;
} else {
	if(!is_exist($identity_card)) {
		$data = get_error_info(MessageType::DANGER, "您还未登录过本系统。", "index.php", "请先登录");
		load_view("view_error.php", "post", true, $data);
		return;
	}
}

//用户信息
$user_info = find_user_by_identity_card($identity_card);
$user_id = $user_info['id'];

$sql = "SELECT".
		" t1.question_id,".
		" t1.question_score,".
		" t2.correct_option_id".
		" FROM m_test_question AS t1".
		" LEFT JOIN m_question AS t2 ON t1.question_id = t2.id".
		" WHERE t1.test_paper_id = %d AND t2.valid_flag = 1".
		" ORDER BY t1.question_order";
// 问题列表
$questions = exec_sql($sql, $paper_id);

$total_score = 0;
$insert_answer_sql = "INSERT INTO t_user_answer (user_id, question_id, user_option_id, test_date) VALUES ";
while ( $question = mysql_fetch_array( $questions ) ) {
	$question_id = $question['question_id'];
	$user_option = $_POST[$question_id];
	$user_option = is_array($user_option) ? $user_option : array($user_option);
	$question_score = $question['question_score'];
	$question_correct_option_id = $question['correct_option_id'];
	$option_num = count(explode(",", $question_correct_option_id));
	if(count($user_option) !== $option_num) {
		$total_score = $total_score + 0;
	} else {
		$is_all_true = true;
		foreach ($user_option as $option) {
			if(strpos($question_correct_option_id, $option) === false) {
				$is_all_true = false;
				break;
			}
		}
		if($is_all_true) {
			$total_score = $total_score + $question_score;
		} else {
			$total_score = $total_score + 0;
		}
	}
	$insert_answer_sql = $insert_answer_sql . prepare(
		"('%s', '%s', '%s', CURRENT_TIMESTAMP),",
		array($user_id, $question_id, implode(",", $user_option)));
}

// 保存详细答题记录SQL
$insert_answer_sql = substr($insert_answer_sql, 0, strlen($insert_answer_sql) - 1);
// 保存得分SQL
$insert_score_sql = "INSERT INTO t_score (user_id, test_paper_id, score, test_date) VALUES ('%s', '%s', '%s', CURRENT_TIMESTAMP)";
$insert_score_sql_param = array($user_id, $paper_id, $total_score);

exec_sql("BEGIN", array());
$result1 = exec_sql($insert_answer_sql, array());
$result2 = exec_sql($insert_score_sql, $insert_score_sql_param);
$result3 = exec_sql("UPDATE m_user SET is_test = 1 WHERE id = '%s';", $user_id);

if($result1 && $result2 && $result3) {
	exec_sql("COMMIT", array());
} else {
	exec_sql("ROLLBACK", array());
	$data = get_error_info(MessageType::DANGER, "提交答案出错。", "index.php", "请重新登录答题");
	load_view("view_error.php", "post", true, $data);
	return;
}

$data['identity_card'] = $identity_card;
load_view("view_score.php", "post", false, $data);

require 'common/close_conn.inc.php';