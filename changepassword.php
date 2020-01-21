<?php
session_start();
include 'includes/connect_basecenter.php';
include 'includes/connect.php';
include 'includes/function.php';

if (empty($_SESSION["USER_TOR"])) {
    header('Location: login.php');
    exit;
}
if($_POST['submit'] != ''){


$password = $_POST['password'];
$new_password = $_POST['new_password'];

 if($_POST['password'] == ''){
 	echo "<script language='javascript'>
					alert('กรุณาใส่รหัสผ่าน!!');
		  </script>";
	echo "<meta http-equiv=refresh content=0;URL=changepassword.php>";
 }else{
	if($password == $new_password){
		$sql = "UPDATE base_center.login set password='".$password."',
				update_date = NOW() WHERE username = '".$_SESSION["USER_TOR"]."' ";
		$query=mysql_query($sql,$base_center);


		$ip=@$REMOTE_ADDR;
		$tm=date("Y-m-d H:i:s");
		$query="insert into tor.log(username,ip, log_name, log_date)
				values('".$_SESSION["USER_TOR"]."','".$ip."','เปลี่ยนรหัสผ่าน','".$tm."')";
		$result_query=mysql_query($query,$edoc_iso);

			echo "<script language='javascript'>
					alert('เปลี่ยนรหัสผ่านเรียบร้อยแล้ว!!!');
				 </script>";
			echo "<meta http-equiv=refresh content=0;URL=profile.php>";
	}else{
		echo "<script language='javascript'>
					alert('รหัสผ่านไม่ตรงกัน กรุณาใส่รหัสผ่านใหม่อีกครั้ง');
			 </script>";
			 echo "<meta http-equiv=refresh content=0;URL=changepassword.php>";
	}
}
}
getheader();
?>

<div id="main-content">
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">
					<a href="index.php" class="title_content"><h3 class="page-title" style="margin-top: 20px !important;">E-Folder (QM & SHE)</h3></a>
				</div>
			</div>

		<div id="page" class="dashboard">
			<div class="row-fluid" style="width:100%;">
				<div class="span12">
					<div class="widget">
						<div class="widget-title"><h4><i class="icon-tags"></i>เปลี่ยนรหัสผ่าน</h4></div>

						<div class="widget-body" style="padding: 5px 5px;">
							<div class="blog_folder">

	<div class="cleaner_h30"></div>
	<div id="login" style="margin:0 auto;">

	<form name="changpass" class="form-vertical no-padding no-margin" action="" method="post">
		<div class="lock"><i class="icon-lock"></i></div>
		<div class="control-wrap">
		<h4>เปลี่ยนรหัสผ่าน</h4>
		<div class="control-group">
			<div class="controls">
				<div class="input-prepend">
					<span class="add-on"><i class="icon-key"></i></span>
						<input name="password" type="password" placeholder="รหัสผ่านใหม่" style="width:85%">
				</div>
			</div>
		</div>

		<div class="control-group">
			<div class="controls">
				<div class="input-prepend">
					<span class="add-on"><i class="icon-key"></i></span>
					<input name="new_password" type="password" placeholder="ยืนยันรหัสผ่าน" style="width:85%">
				</div>
				<div class="mtop10"></div>
				<div class="clearfix space5"></div>
				</div>
			</div>
		</div>
		<input name="submit" type="submit" id="login-btn" class="btn btn-block login-btn" value="เปลี่ยนรหัสผ่าน"  /></form>

	</div>


							<div class="cleaner_h30"></div>
                            </div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<footer>

<?php getfooter(); ?>
