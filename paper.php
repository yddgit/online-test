<?php
$conn = mysql_connect("localhost:3306","root","root");
if (!$conn) { die('Could not connect: ' . mysql_error()); }
mysql_select_db("test", $conn);

$org_name = test_input($_POST["org_name"]);
$dept_id = test_input($_POST["dept_id"]);
$user_name = test_input($_POST["user_name"]);
$identity_card = test_input($_POST["identity_card"]);

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>登录考试系统</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
	<!-- 显示试卷内容 -->
	单位名称：<?php echo $org_name . "<br/>" ?>
	部门ID:<?php echo $dept_id . "<br/>" ?>
	用户姓名：<?php echo $user_name . "<br/>" ?>
	身份证号：<?php echo $identity_card . "<br/>" ?>
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.3.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
<?php
mysql_close($conn);
?>