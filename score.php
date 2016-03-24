<?php

require 'common/open_conn.inc.php';

$is_get_score = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$identity_card = check_input($_POST["identity_card"]);
	if($identity_card) {
		$sql = "SELECT".
					" t1.id AS id,".
					" t2.name AS user_name,".
					" t4.paper_name AS paper_name,".
					" t1.score AS score,".
					" DATE_FORMAT(t1.test_date,'%Y-%m-%d %H:%i:%s') AS test_date,".
					" t2.org_name AS org_name,".
					" t3.name AS dept_name".
				" FROM t_score AS t1".
				" LEFT JOIN m_user AS t2 ON t1.user_id = t2.id".
				" LEFT JOIN m_dept AS t3 ON t2.dept_id = t3.id".
				" LEFT JOIN m_test_paper AS t4 ON t1.test_paper_id = t4.id".
				" WHERE t2.identity_card = '%s'";
		$result = exec_sql($sql, $identity_card);
		$row = mysql_fetch_array($result);
		
		if(!empty($row)) {
			$is_get_score = true;
		}
	}
}

if(!$is_get_score) {
	$data = get_error_info(MessageType::DANGER, "暂未没有找到成绩记录。", "index.php", "返回登录页面");
	load_view("error.php", "post", true, $data);
	return;
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>测试成绩</title>
<?php require 'common/header.inc.php'; ?>
</head>
<body>
	<?php show_error_info(); ?>
	<div class="center-panel score-panel">
		<h2 class="text-center score-title">测试成绩</h2>
		<!-- 显示成绩 -->
		<table class="table table-hover table-bordered">
			<tr>
				<th class="info" style="width: 100px">姓名</th>
				<td class="text-center"><?php echo isset($row['user_name']) ? $row['user_name'] : "未知"; ?></td>
			</tr>
			<tr>
				<th class="info" style="width: 100px">试卷类型</th>
				<td class="text-center"><?php echo isset($row['paper_name']) ? $row['paper_name'] : "未知"; ?></td>
			</tr>
			<tr>
				<th class="info" style="width: 100px">测试得分</th>
				<td class="text-center"><?php echo isset($row['score']) ? $row['score'] : "无成绩"; ?></td>
			</tr>
			<tr>
				<th class="info" style="width: 100px">测试时间</th>
				<td class="text-center"><?php echo isset($row['test_date']) ? $row['test_date'] : "无"; ?></td>
			</tr>
			<tr>
				<th class="info" style="width: 100px">单位名称</th>
				<td class="text-center"><?php echo isset($row['org_name']) ? $row['org_name'] : "未知"; ?></td>
			</tr>
			<tr>
				<th class="info" style="width: 100px">部门名称</th>
				<td class="text-center"><?php echo isset($row['dept_name']) ? $row['dept_name'] : "未知"; ?></td>
			</tr>
		</table>
	</div>
    <?php require 'common/javascript.inc.php'; ?>
</body>
</html>
<?php require 'common/close_conn.inc.php'; ?>