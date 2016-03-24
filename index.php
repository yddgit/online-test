<?php require 'common/open_conn.inc.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>登录考试系统</title>
<?php require 'common/header.inc.php'; ?>
</head>
<body>
	<?php show_error_info(); ?>
	<div class="center-panel login-form">
	<form action="service_login.php" method="post" onsubmit="return formCheck(this);" class="form-horizontal" id="login-form">
		<div class="form-group">
			<h2 class="text-center form-title">考试系统</h2>
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
				while ( $row = mysql_fetch_array( $result ) ) {
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
				<button type="submit" class="btn btn-primary">开始考试</button>
			</div>
		</div>
	</form>
	</div>
    <?php require 'common/javascript.inc.php'; ?>
</body>
</html>
<?php require 'common/close_conn.inc.php'; ?>