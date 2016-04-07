<?php

require_once 'common/common.inc.php';

/** 引入PHPExcel文件 */
require_once "phpexcel/PHPExcel.php";

// 连接数据库
$conn = create_conn();

// 将数据导出为Excel
$objPHPExcel = export_dept_user_num();

// 关闭数据库连接
close_conn($conn);

// 将创建好的excel文件返回给浏览器
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . urlencode("各部门考试情况统计") . '.xls"');
header('Cache-Control: max-age=0');
// IE9兼容设置项
header('Cache-Control: max-age=1');
// IE SSL兼容设置项
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header ('Cache-Control: cache, must-revalidate');
header ('Pragma: public');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

/**
 * 导出各部门考试人数到Excel
 * @return PHPExcel
 */
function export_dept_user_num() {

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

	// 创建PHPExcel对象
	$excel_obj = new PHPExcel();
	
	// 设置文档属性
	$excel_obj->getProperties()
		->setTitle("Online Test Result")
		->setSubject("Online Test Result")
		->setCreator("online-test")
		->setLastModifiedBy("online-test");
	
	// 整个Excel文档中工作表的索引
	$sheet_index = 0;
	$worksheet = $excel_obj->setActiveSheetIndex($sheet_index++);

	// 数据
	$worksheet->setCellValueByColumnAndRow(0, 1, "序号");
	// 表头文字加粗
	$worksheet->getStyleByColumnAndRow(0, 1)->getFont()->setBold(true);
	// 列宽
	$worksheet->getColumnDimensionByColumn(0)->setWidth(10);
	// 数据
	$worksheet->setCellValueByColumnAndRow(1, 1, "部门名称");
	// 表头文字加粗
	$worksheet->getStyleByColumnAndRow(1, 1)->getFont()->setBold(true);
	// 列宽
	$worksheet->getColumnDimensionByColumn(1)->setWidth(30);
	// 数据
	$worksheet->setCellValueByColumnAndRow(2, 1, "参加考试人数");
	// 表头文字加粗
	$worksheet->getStyleByColumnAndRow(2, 1)->getFont()->setBold(true);
	// 列宽
	$worksheet->getColumnDimensionByColumn(2)->setWidth(18);
	
	$row_num = 1;
	while ( $row = mysql_fetch_array( $result )) {
		$row_num++;
		$worksheet->setCellValueByColumnAndRow(0, $row_num, $row_num - 1);
		// 数据
		$worksheet->setCellValueByColumnAndRow(1, $row_num, $row['dept_name']);
		// 设置数据类型为文本
		$worksheet->getCellByColumnAndRow(1, $row_num)->setDataType(PHPExcel_Cell_DataType::TYPE_STRING);
		// 数据
		$worksheet->setCellValueByColumnAndRow(2, $row_num, $row['user_num']);
		// 设置数据类型为文本
		$worksheet->getCellByColumnAndRow(2, $row_num)->setDataType(PHPExcel_Cell_DataType::TYPE_STRING);
	
		// 部门ID
		$dept_id = $row['dept_id'];

		// 导出部门成绩统计表
		export_dept_score($excel_obj, $sheet_index++, $dept_id, "{$row['dept_name']}-统计表");
		// 导出用户得分详细
		export_user_score($excel_obj, $sheet_index++, $dept_id, "{$row['dept_name']}-成绩表");
	}
	
	// 设置sheet名
	$worksheet->setTitle('各部门考试人数统计');
	
	// 设置Excel打开默认显示的sheet
	$excel_obj->setActiveSheetIndex(0);
	
	return $excel_obj;
}

/**
 * 导出用户得分详细到Excel
 * @param PHPExcel $excel_obj
 * @param index $sheet_index
 * @param dept_id $dept_id
 * @param sheet_name $sheet_name
 */
function export_user_score($excel_obj, $sheet_index, $dept_id, $sheet_name) {
	$sql = "SELECT".
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
	$result = exec_sql($sql, $dept_id);
	
	export_excel($excel_obj, $sheet_index,
			array("name","identity_card","dept_name","paper_name","score", "test_date"),
			array("姓名","身份证号","部门名称","试卷类型","分数","测试时间"),
			array(10, 24, 30, 15, 10, 20),
			array("test_date"), $result, $sheet_name);
}

/**
 * 导出部门成绩统计表到Excel
 * @param PHPExcel $excel_obj
 * @param index $sheet_index
 * @param dept_id $dept_id
 * @param sheet_name $sheet_name
 */
function export_dept_score($excel_obj, $sheet_index, $dept_id, $sheet_name) {
	$sql = "SELECT".
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
	$result = exec_sql($sql, array('%', $dept_id, $dept_id, $dept_id, $dept_id, $dept_id, $dept_id, $dept_id, $dept_id, $dept_id, $dept_id));
	
	export_excel($excel_obj, $sheet_index,
			array("level","user_num","user_percent","avg_score"),
			array("成绩区间","人数","所占比例","平均分"),
			array(20, 10, 15, 10),
			array(), $result, $sheet_name);
}
