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
	if ( strpos( $query, '%' ) === false ) {
		load_view("../error.php", "post", true, $data);
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
	const SUCCESS = "success";
	const INFO = "info";
	const WARNING = "warning";
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
function get_error_info($msg_type, $msg_content, $redirect_url, $redirect_url_text) {
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

	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$data = $_GET;
	} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
 * 用户是否参加过测试
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
