<?php
$conn = mysql_connect("localhost:3306","root","root");
if (!$conn) { die('Could not connect: ' . mysql_error()); }
mysql_select_db("test", $conn);
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
	<form action="paper.php" method="post">
		单位名称：<input type="text" name="org_name"/>
		所在部门：<select name="dept_id">
		<?php
			$query = sprintf("SELECT t1.id, t1.`name` FROM m_dept AS t1 WHERE t1.valid_flag = '%s'", "1");
			$result = mysql_query($query);
			while($row = mysql_fetch_array($result)) {
				echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
			}
		?>
		</select>
		姓名：<input type="text" name="user_name"/>
		身份证号：<input type="text" name="identity_card"/>
		<button type="submit" name="login">登录</button>
	</form>
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-1.11.3.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
<?php
mysql_close($conn);
?>