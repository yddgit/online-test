<?php

require_once 'common/common.inc.php';

if(!has_auth("POST")) { return; }

if(!isset($_POST['is_error'])) {
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