<?php

require_once 'common/common.inc.php';

// 连接数据库
$conn = create_conn();

$dept_id = check_input($_GET["dept_id"]);
$dept_info = find_dept_by_id($dept_id);

$dept_sql = "SELECT".
		" t.`level`,".
		" t.user_num,".
		" concat(ifnull(convert(round(100 * t.user_num/t.total_user, 2), decimal), 0), '%s') AS user_percent,".
		" t.avg_score FROM (".
		" SELECT".
		"  '0-60' AS level,".
		"  count(*) AS user_num,".
		"  (SELECT count(*) FROM t_score LEFT JOIN m_user ON t_score.user_id = m_user.id WHERE m_user.dept_id = '%s') AS total_user,".
		"  ifnull(round(avg(t1.score), 2), 0) AS avg_score".
		"  FROM t_score AS t1".
		"  LEFT JOIN m_user AS t2 ON t1.user_id = t2.id".
		"  WHERE t2.dept_id = '%s' AND t1.score >= 0  AND t1.score < 60".
		" UNION".
		" SELECT".
		"  '60-70' AS level,".
		"  count(*) AS user_num,".
		"  (SELECT count(*) FROM t_score LEFT JOIN m_user ON t_score.user_id = m_user.id WHERE m_user.dept_id = '%s') AS total_user,".
		"  ifnull(round(avg(t1.score), 2), 0) AS avg_score".
		"  FROM t_score AS t1".
		"  LEFT JOIN m_user AS t2 ON t1.user_id = t2.id".
		"  WHERE t2.dept_id = '%s' AND t1.score >= 60 AND t1.score < 70".
		" UNION".
		" SELECT".
		"  '70-80' AS level,".
		"  count(*) AS user_num,".
		"  (SELECT count(*) FROM t_score LEFT JOIN m_user ON t_score.user_id = m_user.id WHERE m_user.dept_id = '%s') AS total_user,".
		"  ifnull(round(avg(t1.score), 2), 0) AS avg_score".
		"  FROM t_score AS t1".
		"  LEFT JOIN m_user AS t2 ON t1.user_id = t2.id".
		"  WHERE t2.dept_id = '%s' AND t1.score >= 70 AND t1.score < 80".
		" UNION".
		" SELECT".
		"  '80-90' AS level,".
		"  count(*) AS user_num,".
		"  (SELECT count(*) FROM t_score LEFT JOIN m_user ON t_score.user_id = m_user.id WHERE m_user.dept_id = '%s') AS total_user,".
		"  ifnull(round(avg(t1.score), 2), 0) AS avg_score".
		"  FROM t_score AS t1".
		"  LEFT JOIN m_user AS t2 ON t1.user_id = t2.id".
		"  WHERE t2.dept_id = '%s' AND t1.score >= 80 AND t1.score < 90".
		" UNION".
		" SELECT".
		"  '90-100' AS level,".
		"  count(*) AS user_num,".
		"  (SELECT count(*) FROM t_score LEFT JOIN m_user ON t_score.user_id = m_user.id WHERE m_user.dept_id = '%s') AS total_user,".
		"  ifnull(round(avg(t1.score), 2), 0) AS avg_score".
		"  FROM t_score AS t1".
		"  LEFT JOIN m_user AS t2 ON t1.user_id = t2.id".
		"  WHERE t2.dept_id = '%s' AND t1.score >= 90 AND t1.score <= 100".
		" ) AS t";
$dept_result = exec_sql($dept_sql, array('%', $dept_id, $dept_id, $dept_id, $dept_id, $dept_id, $dept_id, $dept_id, $dept_id, $dept_id, $dept_id));

$user_sql = "SELECT".
		" t1.user_id,".
		" t2.name,".
		" t2.identity_card,".
		" t2.org_name,".
		" t4.`name` AS dept_name,".
		" t3.paper_name,".
		" t1.score,".
		" t1.test_date".
		" FROM t_score AS t1".
		" LEFT JOIN m_user AS t2 ON t1.user_id = t2.id".
		" LEFT JOIN m_test_paper t3 ON t1.test_paper_id = t3.id".
		" LEFT JOIN m_dept AS t4 ON t2.dept_id = t4.id".
		" WHERE t2.dept_id = '%s'";
$user_result = exec_sql($user_sql, $dept_id);

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>【<?php echo $dept_info['dept_name']; ?>】考试成绩</title>
<?php require 'common/header.inc.php'; ?>
</head>
<body>
	<?php show_error_info(); ?>
	<div class="center-panel dept-panel">
	<h2 class="text-center search-title">【<?php echo $dept_info['dept_name']; ?>】考试成绩</h2>
	<div class="text-right export-btn">
		<a class="btn btn-primary btn-sm" href="export.php" role="button">导出</a>
	</div>
	<table class="table table-hover table-bordered">
		<thead>
			<tr>
				<th class="info" style="width: 100px;">序号</th>
				<th class="info" style="width: auto;">成绩区间</th>
				<th class="info" style="width: 100px;">人数</th>
				<th class="info" style="width: 100px;">所占比例</th>
				<th class="info" style="width: 100px;">平均分</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$count = 0;
			while ( $row = mysql_fetch_array( $dept_result )) {
				$count++;
				echo "<tr>";
				echo "<th scope=\"row\">" . $count . "</th>";
				echo "<td>" . $row['level'] . "</td>";
				echo "<td>" . $row['user_num'] . "</td>";
				echo "<td>" . $row['user_percent'] . "</td>";
				echo "<td>" . $row['avg_score'] . "</td>";
				echo "</tr>\n";
			}
			?>
		</tbody>
	</table>
	<h3>成绩表</h3>
	<?php if(mysql_num_rows($user_result) > 0) { ?>
	<table class="table table-hover table-bordered table-condensed">
		<thead>
			<tr>
				<th class="success" style="width: 100px;">序号</th>
				<th class="success" style="width: 100px;">姓名</th>
				<th class="success" style="width: 150px;">身份证号</th>
				<th class="success" style="width: auto;">部门名称</th>
				<th class="success" style="width: 100px;">试卷类型</th>
				<th class="success" style="width: 100px;">分数</th>
				<th class="success" style="width: 100px;">测试时间</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$count = 0;
			while ( $row = mysql_fetch_array( $user_result )) {
				$count++;
				echo "<tr>";
				echo "<th scope=\"row\">" . $count . "</th>";
				echo "<td>" . $row['name'] . "</td>";
				echo "<td>" . $row['identity_card'] . "</td>";
				echo "<td>" . $row['dept_name'] . "</td>";
				echo "<td>" . $row['paper_name'] . "</td>";
				echo "<td>" . $row['score'] . "</td>";
				echo "<td>" . (isset($row['test_date']) ? date("Y-m-d", strtotime($row['test_date'])) : "") . "</td>";
				echo "</tr>\n";
			}
			?>
		</tbody>
	</table>
	<?php } else { ?>
	<h4 class="text-danger">本部门暂无人参加考试。</h4>
	<?php }
	// 关闭数据库连接
	close_conn($conn);
	?>
	</div>
    <?php require 'common/javascript.inc.php'; ?>
</body>
</html>