<?php

require 'common/open_conn.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$data = $_GET;
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$data = $_POST;
}

if(!isset($data['is_error'])) {
	load_view("index.php", "post", false);
	return;
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>出错了！</title>
<?php require 'common/header.inc.php'; ?>
</head>
<body>
	<?php show_error_info(); ?>
    <?php require 'common/javascript.inc.php'; ?>
</body>
</html>
<?php require 'common/close_conn.inc.php'; ?>