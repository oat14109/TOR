<?php
session_start();
require_once('includes/connect.php');
include 'includes/function.php';

if (empty($_SESSION['USER_TOR'])) {
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

getheader();
?>
<link rel="stylesheet" type="text/css" href="includes/css/jquery.dataTables.css">
<script type="text/javascript" language="javascript" src="includes/js/jquery.dataTables.js"></script>


<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
	$('#example').DataTable( {
		"order": [[ 0, "asc" ]],
		"pageLength": 20
	} );
} );
</script>

			</div>

		<div id="page" class="dashboard">
			<div class="row-fluid" style="width:100%;">
				<div class="span12">
					<div class="widget">
						<div class="widget-title"><h4><i class="icon-tags"></i>ประวัติเข้าใช้งานระบบ</h4></div>

						<div class="widget-body">


							<div class="blog_folder">

    <?php $sql = "SELECT * FROM torTest.log
				  LEFT JOIN torTest.employee ON log.username = employee.emref
				  ORDER BY log_date DESC";
		   $query = mysql_query($sql,$edoc_iso);
		   $i = 1;
	?>
	<table align="center" border="0" cellpadding="0" cellspacing="0" class="admin_table">
		<thead>
		<tr height="35" style="background:#1ea5af; color:#ffffff;">
			<th scope="col" align="center" style="width:5%;">ลำดับ</th>
			<th scope="col" align="center" style="width:15%;">วันที่-เวลา</th>
			<th scope="col" align="center" style="width:27%;">พนักงานผู้ใช้ระบบ</th>
			<th scope="col" align="center">รายละเอียด</th>
		</tr>
		</thead>
		<tbody>
		<?php while($rs = mysql_fetch_array($query)){
				$log_date = explode(' ',$rs['log_date']);
				$logdate = $log_date[0];
				$logtime = $log_date[1];
				$date = explode('-',$logdate);
				$y = $date[0]+543;
				$m = $date[1];
				$d = $date[2];
				$log_date ="".$d."/".$m."/".$y."";
				$time = explode(':',$logtime);
				$h = $time[0];
				$m = $time[1];
				$log_time = "".$h.":".$m."";

				$a++;
				if($a%2==0){
					$bg = "#cdeef1";
				}else{
					$bg = "#f9f9f9";
				}


		?>


			<tr height="35" style="background:<?php echo $bg;?>;">
				<td align="center"><?php echo $i;?></td>
				<td align="center"><?php echo $log_date.' '.$log_time;?></td>
				<td style="padding-left:10px;" ><?php echo $rs['emref'].' '.$rs['title'].''.$rs['fname'].' '.$rs['lname'] ;?></td>
				<td style="padding-left:10px;"><?php echo $rs['log_name'];?></td>
			</tr>


		<?php $i++;  } ?>
		</tbody>
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

<?php getfooter(); ?>
