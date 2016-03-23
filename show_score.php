<?php require 'common/open_conn.inc.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>登录考试系统</title>
<?php require 'common/header.inc.php'; ?>
</head>
<body>
	<!-- 显示试卷内容 -->
	单位名称：<?php echo $org_name . "<br/>" ?>
	部门ID:<?php echo $dept_id . "<br/>" ?>
	用户姓名：<?php echo $user_name . "<br/>" ?>
	身份证号：<?php echo $identity_card . "<br/>" ?>
    <?php require 'common/javascript.inc.php'; ?>
  </body>
</html>
<?php require 'common/close_conn.inc.php'; ?>