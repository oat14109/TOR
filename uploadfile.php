<?php

session_start();
include 'includes/connect.php';
include 'includes/function.php';
if (empty($_SESSION['USER_TOR'])) {
  header('Location: login.php');
  exit;
}
if($_GET['action'] == 'del'){

	$s_p = "SELECT * FROM torTest.file_upload WHERE fileidx = '".$_GET['fileid']."' ";
	$s_q = mysql_query($s_p);
	$rs = mysql_fetch_assoc($s_q);

	if($rs['folder_name'] == $rs['filepath_encode']){

		$target_file = 'files/'.$rs['folder_name'].'/'.iconv("UTF-8", "TIS-620",$rs['filename']);
	}else{

		$target_file = 'files/'.$rs['folder_name'].'/'.$rs['filepath_encode'].'/'.iconv("UTF-8", "TIS-620",$rs['filename']);
	}

	$delete = "DELETE FROM torTest.file_upload WHERE fileidx = '".$rs['fileidx']."' ";
	$query_del = mysql_query($delete);

	@unlink($target_file);

	echo '<script>alert("บันทึกข้อมูลเรียบร้อยแล้วค่ะ")</script>';
	echo '<meta http-equiv=refresh content=0;URL=uploadfile.php?user='.$_SESSION["USER_TOR"].'>';


//-----------------------------------เก็บ LOG-----------------------------------
$ip=@$REMOTE_ADDR;
$tm=date("Y-m-d H:i:s");
$sql_log="insert into torTest.log(username,ip, log_name, log_date)
		values('".$_SESSION["USER_TOR"]."','".$ip."','ลบเอกสาร : ".$rs['filename']."','".$tm."')";
$rs_log=mysql_query($sql_log);
//-----------------------------------END เก็บ LOG-----------------------------------

}

$sql_role ="SELECT admin.role_id FROM torTest.admin
			WHERE admin.username ='".$_SESSION["USER_TOR"]."' AND admin.user_status = '1' ";
$query_role = mysql_query($sql_role);
$rs_role = mysql_fetch_array($query_role);
$role = $rs_role['role_id'];

if($role == 1){
	$update_by = '';

}else{
	$update_by = $_SESSION["USER_TOR"];
}

$per_foldername = $_GET['per_foldername'];

getheader();

?>

</div>
</div>

		<div id="page" class="dashboard">
			<div class="row-fluid" style="width:100%;">
				<div class="span12">
					<div class="widget">
						<div class="widget-title"><h4><i class="icon-tags"></i>รายละเอียดไฟล์</h4>

                            <div style="padding-top: 9px;padding-right: 15px;float: right; text-decoration:underline;">

                            </div>
                        </div>

						<div class="widget-body">


							<div class="blog_folder">
                            <table class="admin_table">
                              <tr style="background:#e0e0e0;border-bottom:1px solid #cccccc; height:35px;">
                                <th style="width: 4%;">ลำดับ</th>
                                <th style="width: 21%;">หัวข้อ</th>
                                <th style="width: 19%;" class="hide_upload">โฟลเดอร์</th>
                                <th style="width: 5%;" class="hide_upload">เวอร์ชั่น</th>
                                <th style="width: 8%;" class="hide_upload">สถานะ</th>
                                <!--<th style="width: 10%;"class="hide_upload">วันที่อัพเดท</th>-->
                                <th style="width: 4%;"></th>
                              </tr>
                              <?php $data = data_files($update_by);
							  		$i=1;
							  		while($rs=mysql_fetch_array($data)){

									if($i%2==0){
										$bg = "#f7f7f7";
									}else{
										$bg = "#FFFFFF";
									}
							  ?>
                              <tr style="background:<?php echo $bg;?>;border-bottom:1px solid #cccccc;height:35px;">
                                <td align="center"><?php echo $i;?></td>
                                <td>
									<a href="geturl.php?idx_search=<?php echo $rs['fileidx'];?>" target="_blank">
										<?php echo $rs['filetitle'];?>
									</a>
								</td>
                                <td align="center" class="hide_upload"><?php echo $rs['filepath'];?></td>
                                <td align="center" class="hide_upload"><?php echo $rs['version'];?></td>
                                <td align="center" class="hide_upload">

								<?php 	if($rs['status'] == 1){
											echo 'ดูได้ทุกคน';
										}elseif($rs['status'] == 2){
											echo 'MGR ขึ้นไป';
										}elseif($rs['status'] == 3){
											echo 'GM ขึ้นไป';
										}elseif($rs['status'] == 4){
											echo 'AMD ขึ้นไป';
										}else{
											echo '<span style="color:#ff0000;">ยกเลิก</span>';
										}
								?>
                                </td>
                                <!--<td align="center" class="hide_upload"><?php //echo $rs['update_date'];?></td>-->
                                <td align="center">
                                	<a href="editfile.php?fileid=<?php echo $rs['fileidx'];?>"><img src="includes/images/edit.png" style="max-width: 20px;"> </a>

								<?php if ($_GET['user'] == ''){ ?>

                                    <a href="<?php echo $_SERVER['REQUEST_URI']; ?>?action=del&fileid=<?php echo $rs['fileidx'];?>" onclick="return confirm('เอกสารจะลบทันที!!! คุณต้องการลบเอกสารหรือไม่?')"><img src="includes/images/delete.png" style="max-width: 20px;"></a>

								<?php }else{ ?>

									<a href="<?php echo $_SERVER['REQUEST_URI']; ?>?user=<?php echo $_SESSION["USER_TOR"];?>&action=del&fileid=<?php echo $rs['fileidx'];?>" onclick="return confirm('เอกสารจะลบทันที!!! คุณต้องการลบเอกสารหรือไม่?')"><img src="includes/images/delete.png" style="max-width: 20px;"></a>

								<?php } ?>
                                </td>
                              </tr>
                              <?php $i++; } ?>
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

<?php getfooter();

function data_files($user){

	if($user != ''){

	$sql= "	SELECT * FROM torTest.file_upload f LEFT JOIN torTest.user_permission u
			ON u.foldername = SUBSTRING_INDEX(f.filepath_encode, '/', 1)
			WHERE u.username = '".$user."' ORDER BY update_date DESC";
	}else{

	$sql= "	SELECT * FROM torTest.file_upload ORDER BY update_date DESC";
	}

	//echo $sql;
	return mysql_query($sql);
}

?>
