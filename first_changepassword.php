<?php
include('includes/connect_basecenter.php'); 
include('includes/connect.php');


if($_POST['submit'] != ''){

$password = $_POST['password']; 
$new_password = $_POST['new_password'];

	if($_POST['password'] == ''){
		echo "<script language='javascript'>
						alert('กรุณาใส่รหัสผ่าน!!');
			  </script>";
		echo "<meta http-equiv=refresh content=0;URL=first_changepassword.php?user=".$_GET['user'].">";
	}else{
		if($password == $new_password){
			$sql = "UPDATE base_center.login set password='".$password."',status_pwd=1,
					update_date = NOW() WHERE username = '".$_GET["user"]."' ";
			$query=mysql_query($sql,$base_center);
			
			if($query){
				echo "<script language='javascript'>
						alert('เปลี่ยนรหัสผ่านเรียบร้อยแล้ว!!!');
					 </script>";
				echo "<meta http-equiv=refresh content=0;URL=index.php>";
			}else{
				echo "<script language='javascript'>
						alert('เปลี่ยนรหัสผ่านไม่สำเร็จ กรุณาติดต่อแผนก 01!!!');
					 </script>";
				echo "<meta http-equiv=refresh content=0;URL=login.php>";
			}
		}else{
			echo "<script language='javascript'>
						alert('รหัสผ่านไม่ตรงกัน กรุณาใส่รหัสผ่านใหม่อีกครั้ง');
				 </script>";
				 echo "<meta http-equiv=refresh content=0;URL=first_changepassword.php?user=".$_GET['user'].">";
		}
	}
	
$ip=@$REMOTE_ADDR; 
$tm=date("Y-m-d H:i:s");
$query="insert into edoc_iso.log(username,ip, log_name, log_date) 
		values('".$_GET["user"]."','".$ip."','เปลี่ยนรหัสผ่าน','".$tm."')";
$result_query=mysql_query($query,$edoc_iso);	

}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if IE 8]><html lang="en" class="ie8"></html><![endif]--><!--[if IE 9]><html lang="en" class="ie9"></html><![endif]--><!--[if !IE]><!-->
<html xmlns="http://www.w3.org/1999/xhtml">
<!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>E-Folder</title>
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
<link href="includes/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
<link href="includes/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href="includes/css/style.min.css" rel="stylesheet" />
<link href="includes/css/style_responsive.css" rel="stylesheet" />
<link href="includes/css/style_default.css" rel="stylesheet" id="style_color" />
</head>
<script type="text/javascript" src="includes/js/jquery-1.9.1.min.js"></script>


<body id="login-body">

<div class="login-header">
	<a class="brand" href="login.php"><img src="includes/images/logo.png" alt="BEM" class="logo_login"></a>
	<div class="title_login">E-Folder (QM & SHE)</div>
</div>

<div id="login">

<form name="changepass" class="form-vertical no-padding no-margin" action="" method="post" onSubmit="return chk_form()">

<div class="lock"><i class="icon-lock"></i></div>
<div class="control-wrap">
<h4>เปลี่ยนรหัสผ่าน</h4>
<div class="control-group">
	<div class="controls">
		<div class="input-prepend">
			<span class="add-on"><i class="icon-key"></i></span>
				<input name="password" type="password" placeholder="รหัสผ่านใหม่" value=""/>
		</div>
	</div>
</div>

<div class="control-group">
	<div class="controls">
		<div class="input-prepend">
			<span class="add-on"><i class="icon-key"></i></span>
			<input name="new_password" type="password" placeholder="ยืนยันรหัสผ่าน" value=""/>
		</div>
		<div class="mtop10"></div>
		<div class="clearfix space5"></div>
		</div>
	</div>
</div>

<input name="submit" type="submit" id="login-btn" class="btn btn-block login-btn" value="เปลี่ยนรหัสผ่าน"  /></form>

</div>

<div id="login-copyright"> Copyright ©2017 Bangkok Expressway and Metro Public Company Limited - All rights reserved.</div>

</body>
</html>