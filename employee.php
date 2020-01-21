<?php
session_start();
require_once('includes/connect.php');
include 'includes/function.php';
if (empty($_SESSION["USER_TOR"])) {
    header('Location: login.php');
    exit;
}

$sql_role ="SELECT * FROM torTest.admin a WHERE a.username ='".$_SESSION["USER_TOR"]."' AND a.user_status = '1' ";
$query_role = mysql_query($sql_role,$edoc_iso);
$rs_role = mysql_fetch_array($query_role);

if ($rs_role['role_id'] != 1){

	echo '<script language="javascript">
			alert("คุณไม่ได้รับสิทธิ์ให้เข้าหน้านี้ค่ะ")
		 </script>
		 <meta http-equiv=refresh content=0;URL=index.php>';
}

if($_POST['submit'] != ''){

$user_id = $_POST['emp_id'];

//-----------------------------เก็บ Log ----------------------------------------

		$ip=@$REMOTE_ADDR;
		$th=mktime(gmdate("H")+7,gmdate("i"),gmdate("m"),gmdate("d"),gmdate("Y"));
		$format="H:i:s";
		$tm=date("Y-m-d").' '.date($format,$th);
		$query="insert into torTest.log(username,ip, log_name, log_date)
				values('".$_SESSION["USER_TOR"]."','".$ip."','เพิ่มสิทธิ์การใช้งานให้กับรหัสพนักงาน ".$user_id."','".$tm."')";
		$result_query=mysql_query($query,$edoc_iso);

//-----------------------------เก็บ Log ----------------------------------------


		$c_u = "SELECT * FROM torTest.admin WHERE username = ".$user_id." ";
		$q_u = mysql_query($c_u,$edoc_iso);
		$nums = mysql_num_rows($q_u);
		$rs= mysql_fetch_array($q_u);

		if($nums <= 0){

			$user = "INSERT INTO torTest.admin
					 VALUES('',".$user_id.",NOW(),'','".$_POST['userrole']."',1)";
			$query_user = mysql_query($user,$edoc_iso);

				for($i = 0; $i <= count($_POST['type_folder']); $i++){
					if($_POST['type_folder'][$i] != ''){
						$sql = "INSERT INTO torTest.user_permission
								VALUES('',".$user_id.",'".$_POST['type_folder'][$i]."')";
						$query = mysql_query($sql,$edoc_iso);

					}
				}

				echo '<script language="javascript">
						alert("เพิ่มข้อมูลเรียบร้อยแล้วค่ะ")
					  </script>
					<meta http-equiv=refresh content=0;URL=employee.php>';


		}elseif($rs['user_status'] == 0){

			$sql_a="UPDATE torTest.admin SET user_status='1' WHERE username = '".$user_id."' ";
			$query_a=mysql_query($sql_a,$edoc_iso);

				for($i = 0; $i <= count($_POST['type_folder']); $i++){
					if($_POST['type_folder'][$i] != ''){
						$sql = "INSERT INTO torTest.user_permission
								VALUES('',".$user_id.",'".$_POST['type_folder'][$i]."')";
						$query = mysql_query($sql,$edoc_iso);

					}
				}

				echo '<script language="javascript">
						alert("เพิ่มข้อมูลเรียบร้อยแล้วค่ะ")
					  </script>
					<meta http-equiv=refresh content=0;URL=employee.php>';

		}else{

			echo '<script language="javascript">
					alert("มีสิทธิ์พนักงานอยู่ในระบบแล้ว กรุณาตรวจสอบใหม่อีกครั้ง!!!")
				  </script>
				<meta http-equiv=refresh content=0;URL=employee.php>';
		}
}

if($_POST['edit'] != ''){

		$user_id = $_POST['emp_id'];

		$ip=@$REMOTE_ADDR;
		$th=mktime(gmdate("H")+7,gmdate("i"),gmdate("m"),gmdate("d"),gmdate("Y"));
		$format="H:i:s";
		$tm=date("Y-m-d").' '.date($format,$th);
		echo $query="insert into tor.log(username,ip, log_name, log_date)
				values('".$_SESSION["USER_TOR"]."','".$ip."','แก่ไชสิทธิ์การใช้งานให้กับรหัสพนักงาน ".$user_id."','".$tm."');";
		$result_query=mysql_query($query);

		$sql_a="UPDATE torTest.admin SET role_id='".$_POST['userrole']."' WHERE username = '".$user_id."' ";
		$query_a=mysql_query($sql_a,$edoc_iso);

		$delete = "DELETE FROM torTest.user_permission WHERE username = '".$user_id."' ";
		$query_del = mysql_query($delete,$edoc_iso);

		for($i = 0; $i <= count($_POST['type_folder']); $i++){

			if($_POST['type_folder'][$i] != ''){
				$sql = "INSERT INTO torTest.user_permission
						VALUES('',".$user_id.",'".$_POST['type_folder'][$i]."')";
				$query = mysql_query($sql,$edoc_iso);
			}
		}

		if($query){
			echo '<script language="javascript">
					alert("แก้ไขสิทธิ์พนักงานเรียบร้อยแล้วค่ะ")
				  </script>
				<meta http-equiv=refresh content=0;URL=employee.php>';
		}

}

if($_GET['action']=='del'){


		$ip=@$REMOTE_ADDR;
		$th=mktime(gmdate("H")+7,gmdate("i"),gmdate("m"),gmdate("d"),gmdate("Y"));
		$format="H:i:s";
		$tm=date("Y-m-d").' '.date($format,$th);
		$query="insert into torTest.log(username,ip, log_name, log_date)
				values('".$_SESSION["USER_TOR"]."','".$ip."','ยกเลิกสิทธิ์การใช้งาน พนักงานรหัส ".$_GET['id']."','".$tm."')";
		$result_query=mysql_query($query,$edoc_iso);

		$sql = "UPDATE torTest.admin SET user_status = 0 WHERE username ='".$_GET['id']."' ";
		$query=mysql_query($sql,$edoc_iso);

		$delete = "DELETE FROM torTest.user_permission WHERE username = '".$_GET['id']."' ";
		$query_del = mysql_query($delete,$edoc_iso);

		if($query){
			echo'<script language="javascript">
					alert("ยกเลิกข้อมูลพนักงานเรียบร้อยแล้ว!!!");
				 </script>';
			echo '<meta http-equiv=refresh content=0;URL=employee.php>';
		}
}


getheader();
?>

				</div>
			</div>

		<div id="page" class="dashboard">
			<div class="row-fluid" style="width:100%;">
				<div class="span12">
					<div class="widget">
						<div class="widget-title"><h4><i class="icon-tags"></i>จัดการสิทธิ์พนักงาน</h4></div>

						<div class="widget-body" style="padding: 5px 5px;">
							<div class="blog_folder">

<form name="form1" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" style="margin-top: 15px;margin-bottom: 30px;">
<input name="add_emp" type="submit" value="เพิ่มพนักงาน" style="cursor:pointer;"/>
<div class="cleaner_h20"></div>

<table align="center" border="0" cellpadding="0" cellspacing="0" class="admin_table">
  <tr height="35" style="background:#1ea5af; color:#ffffff;">
  	<th>ลำดับ</th>
    <th>รหัส</th>
    <th>ชื่อ</th>
	<th>แผนก</th>
	<th>ตำแหน่ง</th>
	<th></th>
  </tr>
 <?php  $sql_emp ="SELECT * FROM torTest.admin
				  LEFT JOIN torTest.employee ON employee.emref = admin.username
				  LEFT JOIN torTest.department ON department.dept_id = employee.dept_id
				  LEFT JOIN torTest.position ON position.pos_id = employee.pos_id
				  WHERE admin.user_status = '1' and position.pos_name not like 'รักษาการ%'
				  ORDER BY department.dept_code, position.pos_id, admin.username ASC";
		$query_emp = mysql_query($sql_emp,$edoc_iso);
		$i = 1;
		while($rs_emp = mysql_fetch_array($query_emp)){

		  		$a++;
				if($a%2==0){
					$bg = "#cdeef1";
				}else{
					$bg = "#f9f9f9";
				}

 ?>

  <tr height="35" style="background:<?php echo $bg;?>;">
  	<td align="center"><?php echo $i;?></td>
    <td align="center"><?php echo $rs_emp['emref'];?></td>
    <td><?php echo $rs_emp['title'].' '.$rs_emp['fname'].' '.$rs_emp['lname'];?></td>
	<td align="center"><?php echo $rs_emp['dept_code']; ?></td>
	<td align="center"><?php echo $rs_emp['pos_name'];?></td>
    <td align="center">
		<a href="<?php echo $_SERVER['SCRIPT_NAME'];?>?id=<?php echo $rs_emp['emref'];?>&action=edit"><img src="includes/images/edit.png" style="max-width: 20px;"/></a>
		<a onclick="return confirm('คุณต้องการยกเลิกสิทธิ์พนักงานหรือไม่?')" href="<?php echo $_SERVER['SCRIPT_NAME'];?>?id=<?php echo $rs_emp['emref'];?>&action=del" ><img src="includes/images/delete.png" style="max-width: 20px;"/></a>
	</td>
  </tr>

<?php $i++; }?>

</table>

</form>

<hr style="width:90%; margin:0 auto; margin-bottom: 30px; margin-top:10px;">

<?php if($_POST['add_emp'] || $_POST['search']){?>

<form name="form1" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" style="margin-top: 30px;margin-bottom: 30px;">
<center>
<b>รหัสพนักงาน : </b> <input name="emp_id" type="text" value="<?php echo $_POST['emp_id'];?>" style="width: 30%;    margin-bottom: 0;">
<input name="search" type="submit" value="ค้นหา" style="cursor:pointer;height: 30px;">
</center>

<div class="cleaner_h20"></div>

<?php

	$sql_emp ="SELECT * FROM torTest.employee LEFT JOIN torTest.position
			 ON employee.pos_id = position.pos_id LEFT JOIN edoc_iso.department
			 ON employee.dept_id = department.dept_id
			 WHERE employee.status = 1 AND employee.emref = '".$_POST['emp_id']."' ";
   $query_emp = mysql_query($sql_emp);
   $rs_emp = mysql_fetch_array($query_emp);

	if($_POST['emp_id'] == $rs_emp['emref'] && $_POST['emp_id'] != ''){

			$sql_listemp ="SELECT * FROM torTest.admin WHERE user_status = 1";
			$query_listemp = mysql_query($sql_listemp);
			while ($rs_listemp = mysql_fetch_array($query_listemp)){

				if($_POST['emp_id'] == $rs_listemp['username']){
					echo '<center style="font-size:12px; color:#ff0000;">
							พนักงานนี้มีชื่ออยู่ในระบบแล้ว กรุณาตรวจสอบใหม่อีกครั้ง
						  </center>
					<div class="cleaner_h5"></div>';
				}
			}

		echo '<div style="text-align:center; color:#0d8992;">
			<b>ชื่อพนักงาน</b> '.$rs_emp['title'].$rs_emp['fname'].'&nbsp;'.$rs_emp['lname'].'&nbsp; <b>ตำแหน่ง</b>
			'.$rs_emp['pos_name'].'&nbsp; <b>แผนก</b> '.$rs_emp['dept_name'].'</div>';
	}elseif ($_POST['emp_id'] != $rs_emp['emp_id']){
		echo '<div style="text-align:center; color:#ff0000;"> ไม่พบรายชื่อพนักงานที่ต้องการ กรุณาใส่รหัสพนักงานให้ถูกต้อง </div>';
	}

?>

<div class="cleaner_h20"></div>

<div style="text-align: center;">
	สิทธิ์การใช้งาน :
	<select name="userrole" id="showDiv">

		<option value="2">User สิทธิ์อัพโหลดเอกสาร</option>
		<option value="1">Administrator</option>

	</select>
</div>
<div class="cleaner_h10"></div>
<div id="hidden_div" style="display: block;">
<table width="95%" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="ffffff">
	<tr height="35" style="background:#8e8e8e; color:#ffffff;">
		<th width="80%">โฟลเดอร์</th>
		<th>สิทธิ์การใช้งาน</th>
  	</tr>
		  <?php
        $arr = array(0, 1, 242);
        $sql ="	SELECT * FROM torTest.folder WHERE parent_id IN ('".implode("','",$arr)."')
		  				AND foldername != 'ISO9001_14001_2015'
              AND folder_idx NOT IN (243, 244, 245,246) ";
				$query =mysql_query($sql);
				$i = 0;
				while($rs = mysql_fetch_array($query)){

							$a++;
							if($a%2==0)
							{
							$bg = "#efefef";
							}
							else
							{
							$bg = "#ffffff";
							}

		 ?>
	<tr height="30" style="background:<?php echo $bg;?>;">
		<td align="left" style="padding-left: 15px;border-right:1px #cccccc solid;"><?php echo $rs['text'];?></td>

		<td align="center">
			<input type="checkbox" value="<?php echo $rs['foldername'];?>" name="type_folder[]" />
		</td>
	</tr>

		<?php $i++; }
      if($i%2==0)
      {
      $bg = "#efefef";
      }
      else
      {
      $bg = "#ffffff";
      }
    ?>

</table>

</div>

<div class="cleaner_h30"></div>

	<center>
	<input name="submit" type="submit" value="บันทึกข้อมูล" style="cursor:pointer;"/>
	<input name="cancle" type="button" onclick="javascript:location.href='index.php'" value="ยกเลิก" style="cursor:pointer;">
	</center>
</form>

<?php }

elseif($_GET['action']=='edit' && $_POST['add_emp'] == '' && $_POST['search'] == ''){ ?>

<form name="form1" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" style="margin-top: 30px;margin-bottom: 30px;">


<?php 	 $sql_emp ="SELECT * FROM torTest.employee LEFT JOIN torTest.position
					ON employee.pos_id = position.pos_id LEFT JOIN torTest.department
					ON employee.dept_id = department.dept_id LEFT JOIN torTest.admin
					ON employee.emref = admin.username
					WHERE employee.status=1 AND admin.username = '".$_GET['id']."' ";
		$query_emp = mysql_query($sql_emp);
		$rs_emp = mysql_fetch_array($query_emp);

		echo '<center><b>ชื่อพนักงาน</b> '.$rs_emp['fname'].'&nbsp;'.$rs_emp['lname'].'&nbsp; <b>ตำแหน่ง</b> '.$rs_emp['pos_name'].'
			&nbsp; <b>แผนก</b> '.$rs_emp['dept_name'].'<center>';

?><input name="emp_id" type="hidden" value="<?php echo $rs_emp['emref'];?>" class="input_text"/>

<div class="cleaner_h20"></div>
<div style="text-align: center;">
สิทธิ์การใช้งาน :

<select name="userrole" id="showDiv">
	<option value="2"<?php echo $rs_emp['role_id'] == 2 ? 'selected="selected"' : ''; ?>>User สิทธิ์อัพโหลดเอกสาร</option>
	<option value="1"<?php echo $rs_emp['role_id'] == 1 ? 'selected="selected"' : ''; ?>>Administrator</option>

</select>
</div>

<div id="hidden_div" style="display: <?php echo $rs_emp['role_id'] == 1 ? 'none' : 'block'; ?>">
<table width="95%" align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="ffffff">
	<tr height="35" style="background:#8e8e8e; color:#ffffff;">
		<th width="80%">โฟลเดอร์</th>
		<th>สิทธิ์การใช้งาน</th>
  	</tr>
<?php
    // $sql ="	SELECT * FROM edoc_iso.folder WHERE parent_id IN (0,1)
		// 		AND foldername != 'ISO9001_14001_2015' ";
    $arr = array(0, 1, 242);
    $sql ="	SELECT * FROM torTest.folder WHERE parent_id IN ('".implode("','",$arr)."')
          AND foldername != 'ISO9001_14001_2015'
          AND folder_idx NOT IN (243, 244, 245,246) ";
		$query =mysql_query($sql);
		$i = 0;
		while($rs = mysql_fetch_array($query)){

			$a++;
			if($a%2==0)
			{
			$bg = "#efefef";
			}
			else
			{
			$bg = "#ffffff";
			}

?>
	<tr height="30" style="background:<?php echo $bg;?>;">
		<td align="left" style="padding-left: 15px;border-right:1px #cccccc solid;"><?php echo $rs['text'];?></td>

	<?php 	$sql1 ="SELECT * FROM torTest.user_permission WHERE username = '".$rs_emp['username']."'";
 			$q1 = mysql_query($sql1);
 			$keep_user_check = array();
			while( $rs1 = mysql_fetch_array($q1)){
				$keep_user_check[] = $rs1['foldername'];
			}

			 $check = '';
				 if ( in_array( $rs['foldername'], $keep_user_check ) )
			 $check = 'checked="checked"';
	?>

		<td height="30" align="center">

		<input type="checkbox" value="<?php echo $rs['foldername'];?>" name="type_folder[]" <?php echo $check; ?> />

		</td>
	</tr>
<?php $i++; }   ?>
</table>
</div>

	<div class="cleaner_h20"></div>

	<center>
	<input name="edit" type="submit" value="แก้ไขข้อมูล" style="cursor:pointer;"/>
	<input name="cancle" type="button" onclick="javascript:location.href='index.php'" value="ยกเลิก" style="cursor:pointer;">
	</center>
	<input type="hidden" name="num_a" value="<?php echo $i; ?>">
</form>

<?php }  ?>
							<div class="cleaner_h10"></div>
                            </div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php getfooter(); ?>

<script>

document.getElementById('showDiv').addEventListener('change', function () {
    var style = this.value == 2 ? 'block' : 'none';
    document.getElementById('hidden_div').style.display = style;
});
</script>
