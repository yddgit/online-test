<?php

require 'common/open_conn.inc.php';

if(!has_auth("POST")) { return; }

$identity_card = check_input($_POST["identity_card"]);

if(is_test($identity_card)) {
	$data = get_error_info(MessageType::INFO, "您已经参加过测试，可直接查看分数。");
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

$sql = "SELECT t1.id, t1.paper_name FROM m_test_paper AS t1 ORDER BY rand() LIMIT %d";
$test_paper = exec_sql($sql, 1);
$test_paper_row = mysql_fetch_array($test_paper);
// 试卷ID
$paper_id = $test_paper_row['id'];
// 试卷名
$paper_name = $test_paper_row['paper_name'];

$sql = "SELECT".
			" t1.question_id,".
			" t1.question_order,".
			" t1.question_score,".
			" t2.question,".
			" t2.desc,".
			" t2.correct_option_id,".
			" t2.explain".
		" FROM m_test_question AS t1".
		" LEFT JOIN m_question AS t2 ON t1.question_id = t2.id".
		" WHERE t1.test_paper_id = %d AND t2.valid_flag = 1".
		" ORDER BY t1.question_order";
// 问题列表
$questions = exec_sql($sql, $paper_id);

$sql = "SELECT".
			" t1.id, t1.option, t1.`order`,".
			" t1.question_id".
		" FROM m_option AS t1".
		" WHERE EXISTS (".
			" SELECT 1 FROM m_test_question AS t2".
			" WHERE t2.test_paper_id = %d AND t2.question_id = t1.question_id".
		" )".
		" ORDER BY t1.question_id, t1.`order`";
$options_row = exec_sql($sql, $paper_id);
// 选项列表
$options = array();
while ( $option = mysql_fetch_array( $options_row ) ) {
	$key = $option['question_id'];
	$value = isset($options[$key]) ? $options[$key] : array();
	$value[$option['id']] = $option;
	$options[$key] = $value;
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>正在测试</title>
<?php require 'common/header.inc.php'; ?>
</head>
<body>
	<?php show_error_info(); ?>
	<div class="center-panel test-form ">
		<form action="service_login.php" method="post" onsubmit="return formCheck(this);" class="form-horizontal" id="testForm">
			<div class="form-group">
				<h2 class="text-center test-title">正在考试（试卷类型：<?php echo $paper_name; ?>）</h2>
				<input type="hidden" name="paper_id" value="<?php echo $paper_id; ?>" />
				<input type="hidden" name="identity_card" value="<?php echo $identity_card; ?>" />
			</div>
			<div class="form-group">
				<ol class="decimal-list">
					<?php
					$question_ids = "";
					while ( $question = mysql_fetch_array( $questions ) ) {
						$question_id = $question['question_id'];
						$question_order = $question['question_order'];
						$question_score = $question['question_score'];
						$question_title = $question['question'];
						$question_desc = $question['desc'];
						$question_correct_option_id = $question['correct_option_id'];
						$question_explain = $question['explain'];
						if(count(explode(",", $question_correct_option_id)) > 1) {
							$question_type = "checkbox";
						} else {
							$question_type = "radio";
						}
						$question_ids = $question_ids . "," . $question_id;
					?>
					<li>
						<pre class="question-title"><?php echo $question_title ?>【<?php echo $question_score ?>分】
<?php echo $question_desc ?></pre>
						<div class="question-options">
							<ol class="upper-alpha-list">
								<?php
								$question_options = $options[$question_id];
								foreach ($question_options as $question_option) {
								?>
								<li>
									<label class="<?php echo $question_type; ?> option-<?php echo $question_type; ?>">
										<input type="<?php echo $question_type; ?>" name="<?php echo $question_option['question_id'] ?>" value="<?php echo $question_option['id'] ?>" class="validate[required]" />
										<span><?php echo $question_option['option'] ?></span>
									</label>
								</li>
								<?php } ?>
							</ol>
						</div>
					</li>
					<?php } ?>
				</ol>
				<input type="hidden" name="question_ids" value="<?php echo $question_ids; ?>" />
			</div>
			<div class="form-group">
				<div class="text-center">
					<button type="button" onclick="showConfirm()" class="btn btn-warning">确认提交</button>
				</div>
			</div>
		</form>
	</div>
	<div class="modal fade" id="testFormConfirm" tabindex="-1" role="dialog" aria-labelledby="formConfirm">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="formConfirm">确认</h4>
				</div>
				<div class="modal-body">您确认要提交答卷吗？</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					<button type="button" class="btn btn-primary" onclick="commitAnswer()">确认</button>
				</div>
			</div>
		</div>
	</div>
	<a id="toTop" href="#">回到顶部</a>
    <?php require 'common/javascript.inc.php'; ?>
</body>
</html>
<?php require 'common/close_conn.inc.php'; ?>