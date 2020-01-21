<?php
session_start();
include 'includes/connect.php';
include 'includes/function.php';

if (empty($_SESSION["USER_TOR"])) {
    header('Location: login.php');
    exit;
}
getheader();
?>




		<div id="page" class="dashboard">
			<div class="row-fluid" style="width:100%;">
				<div class="span12">
					<div class="widget">
						<div class="widget-title"><h4><i class="icon-tags"></i>โปรไฟล์</h4></div>

						<div class="widget-body" style="padding: 5px 5px;">
							<div class="blog_folder">

	<?php

	 	  $sql_emp ="SELECT * FROM employee LEFT JOIN position ON position.pos_id = employee.pos_id
					 LEFT JOIN department ON department.dept_id = employee.dept_id
					 WHERE employee.emref = ".$_SESSION["USER_TOR"]." ";
		  $query_emp = mysql_query($sql_emp,$edoc_iso);
		  $rs_emp = mysql_fetch_array($query_emp);
		  $bg = "#cdeef1";
		  $bg2 = "#f9f9f9";
	?>
	<table align="center" border="0" cellpadding="0" cellspacing="0" class="admin_table">
		<tr height="35" style="background:<?php echo $bg;?>; color:#868686;">
			<td align="left" class="admin_td">รหัสพนักงาน</td>
			<td style="padding-left:10px;"><?php echo  $rs_emp['emref'];?></td>
		</tr>
		<tr height="35" style="background:<?php echo $bg2;?>; color:#868686;">
			<td class="admin_td">ชื่อ - นามสกุล</td>
			<td style="padding-left:10px;"><?php echo $rs_emp['fname'];?> <?php echo  $rs_emp['lname'];?>
			</td>
		</tr>
		<tr height="35" style="background:<?php echo $bg;?>; color:#868686;">
			<td class="admin_td">ตำแหน่ง</td>
			<td style="padding-left:10px;"><?php echo  $rs_emp['pos_name'];?></td>
		</tr>
		<tr height="35" style="background:<?php echo $bg2;?>; color:#868686;">
			<td class="admin_td">แผนก</td>
			<td style="padding-left:10px;"><?php echo  $rs_emp['dept_name'];?> (<?php echo  $rs_emp['dept_code'];?>)</td>
		</tr>
		<tr height="35" style="background:<?php echo $bg;?>; color:#868686;">
			<td class="admin_td">Username</td>
			<td style="padding-left:10px;"><?php echo  $_COOKIE["username"];?></td>
		</tr>
		<tr height="35" style="background:<?php echo $bg2;?>; color:#868686;">
			<td class="admin_td">Password</td>
			<td style="padding-left:10px;"><a href="changepassword.php"><u>เปลี่ยนรหัสผ่าน</u></a></td>
		</tr>
	</table>

							<div class="cleaner_h10"></div>
                            </div>
	             </div>
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
