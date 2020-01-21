<?php
session_start();
include('includes/connect_basecenter.php');
include('includes/connect.php');

header('Content-Type: text/html; charset=utf-8');

$username = $_SESSION['USER_TOR'];
$pass = $_SESSION['pass'];
$token = $_SESSION['CK_token'].'|'.$_SESSION['USER_TOR'];
 //print_r($_SESSION);

$q= "SELECT password FROM base_center.login WHERE username='".$username."' AND password='".$pass."'
	 AND status_pwd = 1";
$qu = mysql_query($q,$base_center);
$num = mysql_num_rows($qu);

if ($username && $token && $num == 1) {
    header('Location: index.php');
    exit;
}

if($_POST['submit'] != ''){

$username = $_POST['username'];
$password = $_POST['pass'];


$sql_pwd="SELECT * FROM base_center.login WHERE username='".$username."' AND password='".$password."'
		  AND status_pwd = 0 ";
$query_pwd=mysql_query($sql_pwd,$base_center);

if(mysql_num_rows($query_pwd) >0){

		echo "<script>alert('การเข้าระบบครั้งแรกจำเป็นต้องเปลี่ยนรหัสผ่านก่อนค่ะ !!!');</script>";
		echo "<meta http-equiv=refresh content=0;URL=first_changepassword.php?user=".$username.">";
		exit;
}
else{
//Create query

$qry="	SELECT l.username,l.password,l.dept_id FROM base_center.login l LEFT JOIN base_center.employee e
 		ON l.emref = e.emref WHERE l.username='".$username."'
		AND e.status = 1 AND e.status_pwd = 1 ";
$result=mysql_query($qry,$base_center);

	if($result) {
	//Login Successful

	$user = mysql_fetch_assoc($result);
	//print_r($user);
	$date_created = explode(" ",$user['update_date']);
	$date = $date_created[0];
	$date_created = date('Y-m-d', strtotime($date. ' + 90 days'));

		if($user['username'] != $_POST['username']){

				echo "<script language='javascript'>
						alert('รหัสพนักงานไม่ถูกต้อง กรุณาตรวจสอบอีกครั้งค่ะ');
					  </script>";
				echo "<meta http-equiv=refresh content=0;URL=login.php>";

		}elseif($user['password'] != $_POST['pass']){
				echo "<script language='javascript'>
						alert('รหัสผ่านไม่ถูกต้อง กรุณาตรวจสอบอีกครั้งค่ะ');
					  </script>";
				echo "<meta http-equiv=refresh content=0;URL=login.php>";

		}elseif(!$user){

				echo "<script language='javascript'>
						alert('ไม่มีสิทธิ์เข้าใช้งานระบบ');
					  </script>";
				echo "<meta http-equiv=refresh content=0;URL=login.php>";

		}else{

					$username = $user['username'];

					//setcookie('username', $username, time()+60*60*24*365);

					$ip=@$REMOTE_ADDR;
					$tm=date("Y-m-d H:i:s");
					$query="insert into tor.log(username,ip, log_name, log_date)
							values('".$username."','".$ip."','ล็อคอินเข้าสู่ระบบ','".$tm."')";
					$result_query=mysql_query($query);

					$sql = "UPDATE base_center.login SET token = '".$token."' ,
							token_expire = CURDATE() + INTERVAL 90 DAY WHERE username = '".$username."' ";
					$query = mysql_query($sql,$base_center);

					$_SESSION['USER_TOR'] = $user['username'];
					$_SESSION['dept_id'] = $user['dept_id'];

					echo "<meta http-equiv=refresh content=0;URL=index.php>";

		}
	}
}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if IE 8]><html lang="en" class="ie8"></html><![endif]--><!--[if IE 9]><html lang="en" class="ie9"></html><![endif]--><!--[if !IE]><!-->
<html xmlns="http://www.w3.org/1999/xhtml">
<!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>สนับสนุนการเก็บข้อมูลพิจารณาร่าง TOR</title>
<!----------------icon mobile RESPONSIVE----------------->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="apple-touch-fullscreen" content="yes" />
	<link rel="apple-touch-icon" href="includes/images/iconmobile.png" />
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="includes/images/iconmobile.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="includes/images/iconmobile.png" />
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="includes/images/iconmobile.png" />
	<link rel="apple-touch-icon-precomposed" sizes="57x57" href="includes/images/iconmobile.png" />
	<link href="includes/images/iconmobile.png" rel="icon" sizes="192x192" />
	<link href="includes/images/iconmobile.png" rel="icon" sizes="128x128" />


<!----------------END RESPONSIVE----------------->
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />
<link rel="stylesheet" href="includes/css/supersized.css">
<link rel="stylesheet" href="includes/css/style_login.css">
<link rel="stylesheet" href="includes/css/style_default.css">
</head>
<script type="text/javascript" src="includes/js/jquery-1.9.1.min.js"></script>
<style>
body {
  background: url(includes/images/bg.jpeg) no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
</style>

<body>

<div style="margin-top: 95px;">
	<img src="includes/images/logo.png" alt="BEM">
	<br /><br />
	<h1 class="h1_login" style="color: #000000;">ระบบจัดเก็บเอกสาร TOR</h1><br>
</div>

<div id="login">

<form name="loginform" class="form-vertical no-padding no-margin" action="" method="post">

	<div class="page-container" style="margin-top:20px;">


               <input name="username" type="text" placeholder="รหัสพนักงาน" value="<?php echo $DATA['username'];?>"/>
                <input name="pass" type="password" placeholder="รหัสผ่าน" />
				<input name="token" type="hidden" id="token" value="<?php echo md5('BEM@2018');?>" />
				<input name="submit" type="submit" class="button" value="เข้าสู่ระบบ"  /></form>

       </div>

</form>

</div>
<div class="cleaner_h60"></div>
<div style="font-size:12px; color:#000000;"> Copyright ©2019 Bangkok Expressway and Metro Public Company Limited - All rights reserved.<br>
Developed by Expressway System Development Department - Unit 1</div>

</body>
</html>
