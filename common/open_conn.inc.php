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
		_doing_it_wrong( 'wpdb::prepare' ,
				sprintf ( __( 'The query argument of %s
                 must have a placeholder.' ), 'wpdb::prepare()' ), '3.9' );
	}

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
	array_walk( $args, array( $this, 'escape_by_ref' ) );
	return @ vsprintf( $query, $args );
}