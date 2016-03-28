<?php
// 数据库主机和端口
$db_host = 'localhost:3306';
// 数据库用户
$db_user = 'root';
// 数据库密码
$db_pass = 'root';
// 数据库名
$db_name = 'test';
// 连接数据库
$conn = mysql_connect($db_host, $db_user, $db_pass);
// 连接失败报错
if (!$conn) {
	die('Could not connect: ' . mysql_error());
}
// 选择数据库
mysql_select_db($db_name, $conn);

/**
 * 执行SQL语句
 * @param string $sql
 * @param array $args
 * @return mixed
 */
function exec_sql($sql, $args) {
	$query = prepare($sql, $args);
	$result = mysql_query( $query );
	return $result;
}

/**
 * 构造SQL语句
 * @param string $query
 * @param array $args
 */
function prepare( $query, $args ) {
	if ( is_null( $query ) ) {
		return;
	}

	if(count($args) === 0) {
		if ( strpos( $query, '%' ) === false ) {
			return $query;
		} else {
			$data = get_error_info(MessageType::DANGER, "数据查询语句错误(no args)。", "index.php", "点此跳转到登录页面");
			load_view("view_error.php", "post", true, $data);
		}
	}

	$escape_by_ref = function ( &$string ) {
		if (!is_float($string)){
			$string = addslashes(mysql_real_escape_string( $string ));
		}
	};

	$args = func_get_args();
	array_shift( $args );
	// If args were passed as an array (as in vsprintf), move them up
	if ( isset( $args[ 0] ) && is_array( $args[0]) )
		$args = $args [0];
	$query = str_replace( "'%s'", '%s' , $query );
	// in case someone mistakenly already singlequoted it
	$query = str_replace( '"%s"', '%s' , $query );
	// doublequote unquoting
	$query = preg_replace( '|(?<!%)%f|' , '%F' , $query );
	// Force floats to be locale unaware
	$query = preg_replace( '|(?<!%)%s|', "'%s'" , $query );
	// quote the strings, avoiding escaped strings like %%s
	array_walk( $args, $escape_by_ref );
	return @ vsprintf( $query, $args );
}

/**
 * 检查参数值
 * @param string $data
 * @return string
 */
function check_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

/**
 * 错误信息类型
 * @author yang
 */
class MessageType {
	/** 成功信息 */
	const SUCCESS = "success";
	/** 提示信息 */
	const INFO = "info";
	/** 警告信息 */
	const WARNING = "warning";
	/** 错误信息 */
	const DANGER = "danger";
}

/**
 * 构造错误信息
 * @param string $msg_type
 * @param string $msg_content
 * @param string $redirect_url
 * @param string $redirect_url_text
 * @return array
 */
function get_error_info($msg_type, $msg_content, $redirect_url = NULL, $redirect_url_text = NULL) {
	$error_msg = array();
	$error_msg['msg_type'] = $msg_type;
	$error_msg['msg_content'] = base64_encode($msg_content);

	$args = func_get_args();
	if ( isset( $args[2] ) ) {
		$error_msg['redirect_url'] = base64_encode($redirect_url);
	} else {
		$error_msg['redirect_url'] = FALSE;
	}
	if ( isset( $args[3] ) ) {
		$error_msg['redirect_url_text'] = base64_encode($redirect_url_text);
	} else {
		$error_msg['redirect_url_text'] = FALSE;
	}

	return $error_msg;
}

/**
 * 页面跳转
 * @param string $url
 * @param string $method
 * @param string $is_error
 * @param string $data
 */
function load_view($url, $method, $is_error, $data = array()) {
	echo "<form style='display:none;' id='load_view_form' name='load_view_form' method='{$method}' action='{$url}'>";
	if(isset($data) && is_array($data) && count($data) > 0) {
		foreach ($data as $key => $value) {
			echo "<input name='{$key}' id='{$key}' type='hidden' value='{$value}' />";
		}
	}
	echo "<input name='is_error' id='is_error' type='hidden' value='{$is_error}' />";
	echo "</form>";
	echo "<script type='text/javascript'>function load_submit(){document.load_view_form.submit();}load_submit();</script>";
}

/**
 * 显示错误信息
 */
function show_error_info() {

	if ($_SERVER["REQUEST_METHOD"] === "GET") {
		$data = $_GET;
	} else if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$data = $_POST;
	}

	if(!isset($data['is_error'])) {
		return;
	}

	$is_error = $data['is_error'];
	if(!$is_error) {
		return;
	}

	$msg_type = $data['msg_type'];
	$msg_content = base64_decode($data['msg_content']);
	$redirect_url = base64_decode($data['redirect_url']);
	$redirect_url_text = base64_decode($data['redirect_url_text']);

	if(!$redirect_url) {
		echo "<div class=\"alert alert-" . $msg_type . "\" role=\"alert\">" . $msg_content . "</div>";
	} else {
		if(!$redirect_url_text) {
			echo "<div class=\"alert alert-" . $msg_type . "\" role=\"alert\">" . $msg_content . "<a href=\"" . $redirect_url . "\" class=\"alert-link\">点击跳转</a></div>";
		} else {
			echo "<div class=\"alert alert-" . $msg_type . "\" role=\"alert\">" . $msg_content . "<a href=\"" . $redirect_url . "\" class=\"alert-link\">" . $redirect_url_text . "</a></div>";
		}
	}
}

/**
 * 使用身份证号查询用户信息: id, identity_card, name, dept_id, dept_name, org_name, is_test
 * @param string $identity_card
 * @return user_info
 */
function find_user_by_identity_card($identity_card) {
	$sql = "SELECT".
				" t1.id,".
				" t1.identity_card,".
				" t1.`name`,".
				" t1.dept_id,".
				" t2.`name` AS dept_name,".
				" t1.org_name,".
				" t1.is_test".
			" FROM m_user AS t1".
			" LEFT JOIN m_dept t2 ON t1.dept_id = t2.id".
			" WHERE t1.identity_card = '%s'".
			" LIMIT 1";
	$result = exec_sql($sql, $identity_card);
	$user_info = mysql_fetch_array ( $result );
	return $user_info;
}

/**
 * 用户是否参加过考试
 * @param string $identity_card
 * @return boolean
 */
function is_test($identity_card) {
	$sql = "SELECT count(*) AS count FROM m_user AS t1 WHERE t1.identity_card = '%s' AND t1.is_test = 1";
	$result = exec_sql($sql, $identity_card);
	$count = mysql_fetch_array ( $result )['count'];
	return $count > 0 ? true : false;
}

/**
 * 用户是否存在
 * @param string $identity_card
 * @return boolean
 */
function is_exist($identity_card) {
	$sql = "SELECT count(*) AS count FROM m_user AS t1 WHERE t1.identity_card = '%s'";
	$result = exec_sql($sql, $identity_card);
	$count = mysql_fetch_array ( $result )['count'];
	return $count > 0 ? true : false;
}

/**
 * HTTP请求的检查
 */
function has_auth($method) {
	if($_SERVER["REQUEST_METHOD"] !== $method) {
		$data = get_error_info(MessageType::DANGER, "没有操作权限。", "index.php", "请重新登录");
		load_view("view_error.php", "post", true, $data);
		return false;
	} else {
		return true;
	}
}

/**
 * 导出数据到Excel
 * @param PHPExcel $excel_obj
 * @param index $sheet_index
 * @param array $head
 * @param array $head_text
 * @param col_width $col_width
 * @param col_date $col_date
 * @param data $rows
 * @param sheet_name $sheet_name
 */
function export_excel($excel_obj, $sheet_index, $head, $head_text, $col_width, $col_date, $rows, $sheet_name) {
	if(mysql_num_rows($rows) > 0) {
		// 创建工作表
		if($excel_obj->getSheetCount() > $sheet_index) {
			$worksheet = $excel_obj->getSheet($sheet_index);
		} else {
			$worksheet = $excel_obj->createSheet($sheet_index);
		}

		// 添加数据
		
		// 数据
		$worksheet->setCellValueByColumnAndRow(0, 1, "序号");
		// 表头文字加粗
		$worksheet->getStyleByColumnAndRow(0, 1)->getFont()->setBold(true);
		// 列宽
		$worksheet->getColumnDimensionByColumn(0)->setWidth(10);
		foreach ($head_text as $i => $value) {
			// 数据
			$worksheet->setCellValueByColumnAndRow($i + 1, 1, $value);
			// 表头文字加粗
			$worksheet->getStyleByColumnAndRow($i + 1, 1)->getFont()->setBold(true);
			// 列宽
			$worksheet->getColumnDimensionByColumn($i + 1)->setWidth($col_width[$i]);
		}

		$row_num = 1;
		while($row = mysql_fetch_array( $rows )) {
			$row_num++;
			$worksheet->setCellValueByColumnAndRow(0, $row_num, $row_num - 1);
			foreach ($head as $i => $value) {
				$cell_value = $row["{$value}"];
				if(in_array($value, $col_date)) {
					$cell_value = date("Y-m-d H:i:s", strtotime($cell_value));
				}
				// 数据
				$worksheet->setCellValueByColumnAndRow($i + 1, $row_num, $cell_value);
				// 设置数据类型为文本
				$worksheet->getCellByColumnAndRow($i + 1, $row_num)->setDataType(PHPExcel_Cell_DataType::TYPE_STRING);
			}
		}
		// 设置sheet名
		$worksheet->setTitle($sheet_name);
	}
}