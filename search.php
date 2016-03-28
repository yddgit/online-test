<?php

require 'common/open_conn.inc.php';

$sql = "SELECT".
			" t.id AS dept_id,".
			" t.`name` AS dept_name,".
			" IFNULL(t3.user_num, 0) AS user_num".
		" FROM m_dept AS t".
		" LEFT JOIN (".
		"  SELECT".
			"  t2.dept_id,".
			"  count(t1.user_id) AS user_num".
		"  FROM t_score AS t1".
		"  LEFT JOIN m_user AS t2 ON t1.user_id = t2.id".
		"  GROUP BY t2.dept_id".
		" ) AS t3 ON t.id = t3.dept_id";
$result = exec_sql($sql, array());
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>考试成绩统计</title>
<?php require 'common/header.inc.php'; ?>
</head>
<body>
	<?php show_error_info(); ?>
	<div class="center-panel search-panel">
	<h2 class="text-center search-title">各部门考试人数统计</h2>
	<div class="text-right export-btn">
		<a class="btn btn-primary btn-sm" href="export.php" role="button">导出</a>
	</div>
	<table class="table table-hover table-bordered">
		<thead>
			<tr>
				<th class="info" style="width: 100px;">序号</th>
				<th class="info" style="width: auto;">部门名称</th>
				<th class="info" style="width: 120px;">参加考试人数</th>
				<th class="info" style="width: 100px;">详细</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$count = 0;
			while ( $row = mysql_fetch_array( $result )) {
				$count++;
				echo "<tr>";
				echo "<th scope=\"row\">" . $count . "</th>";
				echo "<td>" . $row['dept_name'] . "</td>";
				echo "<td>" . $row['user_num'] . "</td>";
				echo "<td><a href=\"view_detail.php?dept_id=" . $row['dept_id'] . "\" target=\"_blank\">查看详细</a></td>";
				echo "</tr>\n";
			}
			?>
		</tbody>
	</table>
	</div>
    <?php require 'common/javascript.inc.php'; ?>
</body>
</html>
<?php require 'common/close_conn.inc.php'; ?>