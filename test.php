<?php

require 'common/open_conn.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$identity_card = check_input($_POST["identity_card"]);

	if(is_test($identity_card)) {
		$data = get_error_info(MessageType::INFO, "您已经参加过测试，可直接查看分数。");
		$data['identity_card'] = $identity_card;
		load_view("score.php", "post", true, $data);
		return;
	} else {
		if(!is_exist($identity_card)) {
			$data = get_error_info(MessageType::DANGER, "您还未登录过本系统。", "index.php", "请先登录");
			load_view("error.php", "post", true, $data);
			return;
		}
	}
} else {
	$data = get_error_info(MessageType::DANGER, "没有操作权限。", "index.php", "请重新登录");
	load_view("error.php", "post", true, $data);
	return;
}

//TODO 随机查询一份试卷并显示

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
	<form action="logic/login.php" method="post" onsubmit="return formCheck(this);" class="form-horizontal" id="login-form">
		<div class="form-group">
			<h2 class="text-center test-title">试卷（类型：A卷）<?php echo $identity_card; ?></h2>
		</div>
		<div class="form-group">
			<label for="org_name" class="col-sm-2 control-label">单位名称</label>
			<div class="col-sm-10">
				<input type="text" class="form-control validate[required]" id="org_name" name="org_name" placeholder="单位名称">
			</div>
		</div>
		<div class="form-group">
			<label for="dept_id" class="col-sm-2 control-label">所在部门</label>
			<div class="col-sm-10">
				<select class="form-control validate[required]" id="dept_id" name="dept_id">
				<option></option>
				<?php
				$result = exec_sql( "SELECT t1.id, t1.`name` FROM m_dept AS t1 WHERE t1.valid_flag = '%s'", "1" );
				while ( $row = mysql_fetch_array ( $result ) ) {
					echo '<option value="' . $row ['id'] . '">' . $row ['name'] . "</option>\n";
				}
				?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="user_name" class="col-sm-2 control-label">姓名</label>
			<div class="col-sm-10">
				<input type="text" class="form-control validate[required]" id="user_name" name="user_name" placeholder="姓名">
			</div>
		</div>
		<div class="form-group">
			<label for="identity_card" class="col-sm-2 control-label">身份证号</label>
			<div class="col-sm-10">
				<input type="text" class="form-control validate[required]" id="identity_card" name="identity_card" placeholder="身份证号">
			</div>
		</div>
		<div class="form-group">
			<div class="text-center">
				<button type="submit" class="btn btn-warning">确认提交</button>
			</div>
		</div>
	</form>
	</div>
    <?php require 'common/javascript.inc.php'; ?>
</body>
</html>
<?php require 'common/close_conn.inc.php'; ?>