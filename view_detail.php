<?php require 'common/open_conn.inc.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>部门考试成绩</title>
<?php require 'common/header.inc.php'; ?>
</head>
<body>
	<?php show_error_info(); ?>
	<div class="center-panel search-panel">
	<h2 class="text-center search-title">部门考试成绩</h2>
	<div class="text-right export-btn">
		<a class="btn btn-primary btn-sm" href="service_export.php" role="button">导出</a>
	</div>
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
			<th class="info" style="width: 100px">考试得分</th>
			<td class="text-center"><?php echo isset($row['score']) ? $row['score'] : "无成绩"; ?></td>
		</tr>
		<tr>
			<th class="info" style="width: 100px">考试时间</th>
			<td class="text-center"><?php echo isset($row['test_date']) ? date("Y-m-d H:i:s", strtotime($row['test_date'])) : "无"; ?></td>
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