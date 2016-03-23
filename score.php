<?php

require 'common/open_conn.inc.php';

$is_get_score = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$identity_card = check_input($_POST["identity_card"]);
	if($identity_card) {
		$sql = "SELECT"
					." t1.id AS id,"
					." t2.name AS user_name,"
					." t4.paper_name AS paper_name,"
					." t1.score AS score,"
					." DATE_FORMAT(t1.test_date,'%Y-%m-%d %H:%i:%s') AS test_date,"
					." t2.org_name AS org_name,"
					." t3.name AS dept_name"
				." FROM t_score AS t1"
				." LEFT JOIN m_user AS t2 ON t1.user_id = t2.id"
				." LEFT JOIN m_dept AS t3 ON t2.dept_id = t3.id"
				." LEFT JOIN m_test_paper AS t4 ON t1.test_paper_id = t4.id"
				." WHERE t2.identity_card = '%s'";
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
	<div class="alert alert-danger" role="alert"><a href="#" class="alert-link">...</a></div>
	<div class="center-panel score-panel">
		<h2 class="text-center score-title">测试成绩</h2>
		<!-- 显示成绩 -->
		<dl class="dl-horizontal">
			<dt>姓名</dt><dd><?php echo $row['user_name'] ?></dd>
			<dt>试卷类型</dt><dd><?php echo $row['paper_name'] ?></dd>
			<dt>测试得分</dt><dd><?php echo $row['score'] ?></dd>
			<dt>测试时间</dt><dd><?php echo $row['test_date'] ?></dd>
			<dt>单位名称</dt><dd><?php echo $row['org_name'] ?></dd>
			<dt>部门名称</dt><dd><?php echo $row['dept_name'] ?></dd>
		</dl>
	</div>
    <?php require 'common/javascript.inc.php'; ?>
</body>
</html>
<?php require 'common/close_conn.inc.php'; ?>