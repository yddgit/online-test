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

function exec_sql($sql, $args) {
	$query = prepare($sql, $args);
	$result = mysql_query( $query );
	return $result;
}

function prepare( $query, $args ) {
	if ( is_null( $query ) ) {
		return;
	}

	// This is not meant to be foolproof -- but it will catch obviously incorrect usage.
	if ( strpos( $query, '%' ) === false ) {
		//TODO error handler;
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

function check_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}


class ErrorMessage {
	
	const SUCCESS = "success";
	const INFO = "info";
	const WARNING = "warning";
	const DANGER = "danger";

	private $type; //success, info, warning, danger
	private $message;
	private $redirect_url;
	private $redirect_url_text;

	private function __get($property_name) {
		if (isset ( $this->$property_name )) {
			return ($this->$property_name);
		} else {
			return (NULL);
		}
	}

	private function __set($property_name, $value) {
		$this->$property_name = $value;
	}

	function &getInstance() {
		static $me;
		if (is_object($me) == true) {
			return $me;
		}
		$me = new ErrorMessage;
		return $me;
	}
}

function set_error_info($type, $message, $redirect_url, $redirect_url_text) {
	$error_msg = $GLOBALS['error_msg'];
	$error_msg->type = $type;
	$error_msg->message = $message;

	$args = func_get_args(); //0->type
	array_shift( $args ); //0->message
	array_shift( $args ); //0->redirect_url
	if ( isset( $args[0] ) ) {
		$error_msg->redirect_url = $redirect_url;
	} else {
		$error_msg->redirect_url = false;
	}

	array_shift( $args ); //0->redirect_url_text
	if ( isset( $args[0] ) ) {
		$error_msg->redirect_url_text = $redirect_url_text;
	} else {
		$error_msg->redirect_url_text = false;
	}

	return;
}

function forward_page($url, $is_error, $type, $message, $redirect_url, $redirect_url_text) {
	if($is_error) {
		set_error_info($type, $message, $redirect_url, $redirect_url_text);
	}
	header("refresh:0;url={$url}");
}

function get_error_info() {

	$error_msg = & ErrorMessage::getInstance();

	$error_text = "";
	if(!$error_msg->redirect_url) {
		$error_text = "<div class=\"alert alert-" . $error_msg->type . "\" role=\"alert\">" . $error_msg->message . "</div>";
	} else {
		if(!$error_msg->redirect_url_text) {
			$error_text = "<div class=\"alert alert-" . $error_msg->type . "\" role=\"alert\">" . $error_msg->message . "<a href=\"" . $error_msg->redirect_url . "\" class=\"alert-link\">" . $error_msg->redirect_url . "</a></div>";
		} else {
			$error_text = "<div class=\"alert alert-" . $error_msg->type . "\" role=\"alert\">" . $error_msg->message . "<a href=\"" . $error_msg->redirect_url . "\" class=\"alert-link\">" . $error_msg->redirect_url_text . "</a></div>";
		}
	}

	return $error_text;
}