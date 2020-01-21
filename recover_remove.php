<?php
session_start();
require_once('includes/connect.php');
include 'includes/function.php';
if (empty($_SESSION['USER_TOR'])) {
  header('Location: login.php');
  exit;
}
header('Content-Type: text/html; charset=utf-8');

if($_POST['recover']){

$foldername = $_POST['foldername'];
//$code = explode('/',$_POST['dept_code']);
$start_date = $_POST['start_date'];

$dcode = $_POST['dept_code'];
$dname = $_POST['dept_name'];

if ($_POST['filepath'] == ''){
	$path = $_POST['filepath1'];
}else{
	$p = explode('(',$_POST['filepath']);
	$p2 =  explode(')',$p[1]);
	$path = $p2[0];
}

$s_p = "SELECT * FROM tortest.folder WHERE foldername = '".$path."' ";
$s_q = mysql_query($s_p,$edoc_iso);
$rs = mysql_fetch_assoc($s_q);

$s_f = "SELECT * FROM tortest.file_upload WHERE fileidx = '".$_GET['fileid']."' ";
$q_f = mysql_query($s_f,$edoc_iso);
$rf = mysql_fetch_assoc($q_f);

$filepath = $rs['link_name'];
$filepath_encode = $rs['foldername'];

if($_FILES["filename"]["name"] != ''){
	$filename = $_FILES["filename"]["name"];
	//$filename_c = $foldername.'_'.date('Ymd_hms');
	$filesize = $_FILES["filename"]["size"];
	$version = $_POST['version']+1;
}else{
	$filename = $_POST["file_textbox"];
	//$filename_c = $_POST["file_textbox"];
	$filesize = $rf['filesize'];
	$version = $rf['version'];
}
$version = $_POST['version']; //--add--//
$fileInfo = pathinfo($_FILES["filename"]["name"]);
$fileType = $fileInfo['extension'];
$target_file = 'files$/'.$foldername.'/'.$filepath_encode.'/'.iconv("UTF-8", "TIS-620",$filename);

	if($_FILES["filename"]["name"] != ''){
		@unlink($target_file);
	}
  	move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file);

		$sql =	"UPDATE tortest.file_upload SET
          trashcan = 0,
          update_by = '".$_SESSION["USER_TOR"]."',
          update_date = NOW()
          WHERE fileidx = '".$_POST['fileidx']."' ;";

		$query = mysql_query($sql,$edoc_iso);


		echo'<script language="javascript">
			alert("บันทึกข้อมูลเรียบร้อยแล้ว!!!");
		</script>';
		//echo '<meta http-equiv=refresh content=0;URL=uploadfile.php?user='.$_SESSION['USER_TOR'].'>';
echo '<meta http-equiv=refresh content=0;URL=folder.php?folder='.$_POST['dept_code'].'%20Dept&dept_name='.$_POST['dept_code'].'%20'.$_POST['dept_name'].'>';
//-----------------------------------เก็บ LOG-----------------------------------
$ip=@$REMOTE_ADDR;
$tm=date("Y-m-d H:i:s");
$sql_log="insert into tortest.log(username,ip, log_name, log_date)
		values('".$_SESSION['USER_TOR']."','".$ip."','กู้คืนเอกสาร : ".$filename." ครั้งที่ ".$version." หัวข้อ : ".$_POST['filedep'].$_POST['filetitle']."','".$tm."')";
$rs_log=mysql_query($sql_log);
//-----------------------------------END เก็บ LOG-----------------------------------

}

if($_POST['delete']){

	$s_p = "SELECT * FROM tortest.file_upload WHERE fileidx = '".$_GET['fileid']."' ";
	$s_q = mysql_query($s_p,$edoc_iso);
	$rs = mysql_fetch_assoc($s_q);

	if($rs['folder_name'] == $rs['filepath_encode']){

		$target_file = 'files$/'.$rs['folder_name'].'/'.iconv("UTF-8", "TIS-620",$rs['filename']);
	}else{

		$target_file = 'files$/'.$rs['folder_name'].'/'.$rs['filepath_encode'].'/'.iconv("UTF-8", "TIS-620",$rs['filename']);
	}

  $delete = "DELETE FROM tortest.sharefile WHERE 	idFileUpload  ='".$rs['fileidx']."' ";
  $query_del = mysql_query($delete);
	$delete = "DELETE FROM tortest.file_upload WHERE fileidx = '".$rs['fileidx']."' ";
  //$delete = "UPDATE tor.file_upload SET
  //       trashcan = 1,
  //       update_by = '".$_SESSION["USER_TOR"]."',
  //       update_date = NOW()
  //       WHERE fileidx = '".$_POST['fileidx']."' ;";
	$query_del = mysql_query($delete);

	@unlink($target_file);

		echo '<script>alert("ลบข้อมูลเรียบร้อยแล้วค่ะ")</script>';
		//echo '<meta http-equiv=refresh content=0;URL=uploadfile.php?user='.$_SESSION["USER_TOR"].'>';
		echo '<meta http-equiv=refresh content=0;URL=folder.php?folder='.$_POST['dept_code'].'%20Dept&dept_name='.$_POST['dept_code'].'%20'.$_POST['dept_name'].'>';

//-----------------------------------เก็บ LOG-----------------------------------
$ip=@$REMOTE_ADDR;
$tm=date("Y-m-d H:i:s");
$sql_log="insert into tortest.log(username,ip, log_name, log_date)
		values('".$_SESSION["USER_TOR"]."','".$ip."','ลบเอกสารถาวร : ".$rs['filename']." หัวข้อ : ".$rs['filetitle']."','".$tm."')";
$rs_log=mysql_query($sql_log);
//-----------------------------------END เก็บ LOG-----------------------------------


}

$update_by = $_SESSION["USER_TOR"];
$fileid = $_GET['fileid'];


$sql_role ="SELECT admin.role_id FROM tortest.admin
			WHERE admin.username ='".$_SESSION["USER_TOR"]."' AND admin.user_status = '1' ";
$query_role = mysql_query($sql_role,$edoc_iso);
$rs_role = mysql_fetch_array($query_role);
$role = $rs_role['role_id'];

getheader();

?>
<link rel="stylesheet" type="text/css" href="includes/css/jquery.datetimepicker.css">
<link rel="stylesheet" href="includes/css/smoothness.css">

<style>
input[type="text"]{
	margin-bottom:0;
	width:40%;
}
select{
	width:42%;
	margin-bottom:0;
}
input[type="number"]{
	width:40px;
	margin-bottom:0;
}
.title_td{
	width:20%; padding-left: 10px;border-right: 1px solid #cccccc; font-size:14px;
}
.ui-datepicker-trigger{
	width:28px;
}

</style>

				</div>
			</div>

		<div id="page" class="dashboard">
			<div class="row-fluid" style="width:100%;">
				<div class="span12">
					<div class="widget">
						<div class="widget-title"><h4><i class="icon-tags"></i>แก้ไขเอกสาร</h4>

                            <div style="padding-top: 9px;padding-right: 15px;float: right; text-decoration:underline;">
                            </div>
                        </div>

						<div class="widget-body">


							<div class="blog_folder">

      <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" name="form_add" enctype="multipart/form-data">


      <?php $data = data_files($fileid,$update_by,$edoc_iso);
	  		$rs=mysql_fetch_array($data);

			$path = explode('/',$rs['filepath']);
      $title = explode(':',$rs['filetitle']);
			$filepath = $path[1];

	  ?>
      <input type="hidden" name="fileidx" value="<?php echo $rs['fileidx'];?>"/>
        <table width="100%" >
            <tr style="background:#f7f7f7;border-bottom:1px solid #cccccc; height:45px;">
                <td class="title_td">หัวข้อ</td>
                <td style="padding-left: 10px;">
                	<input  type ="text"  name="filedep" value="<?php echo $title[0];?>:" readonly="readonly" style="width:12%"> <input type="text" readonly="readonly" name="filetitle" value="<?php echo $title[1];?>"/>
                </td>
            </tr>
            <tr style="background:#ffffff;border-bottom:1px solid #cccccc; height:45px;">
                <td class="title_td">ไฟล์</td>
                <td style="padding-left: 10px;">
                	 <?php echo $rs['filename'];?>
					<input type="hidden" name="file_textbox" value="<?php echo $rs['filename'];?>" />
                </td>
            </tr>
            <tr style="background:#f7f7f7;border-bottom:1px solid #cccccc; height:45px;">
                <td class="title_td">แก้ไขครั้งที่</td>
                <td style="padding-left: 10px;">
                <input type="text" readonly="readonly"  maxlength='3' style='width:30px;' name="version" id="version" value="<?php echo $rs['version'];?>" onkeyup='intonly(this.value)' />
				<?php //echo $rs['version'];?>
                </td>
            </tr>
            <tr style="background:#ffffff;border-bottom:1px solid #cccccc; height:45px;">
                <td class="title_td">วันที่บังคับใช้</td>
                <td style="padding-left: 10px;">
                	<input type="text" readonly="readonly" id="datepicker1" name="start_date" value="<?php echo $rs['start_date'];?>" style="width: 36.5%;"/>
                </td>
            </tr>
            </tr>
		<?php if ($rs['dept_code'] != ''){ ?>
           	<tr style="background:#f7f7f7;border-bottom:1px solid #cccccc; height:45px;">
                <td class="title_td">ไฟล์ของแผนก</td>
                <td style="padding-left: 10px;">
				<input type="hidden" name="dept_code" value="<?php echo $rs['dept_code'];?>" />
				<input type="hidden" name="dept_name" value="<?php echo $rs['dept_name'];?>" />
				<?php echo $rs['dept_code'].' '.$rs['dept_name'];?>
                </td>
            </tr>
		<?php } ?>
            <tr style="background:#f7f7f7;border-bottom:1px solid #cccccc; height:45px;">
              <!--  <td class="title_td">แผนกที่ต้องการแชร์</td> --
                <td style="padding-left: 10px; padding-top: 10px;">
                <?php
					$sql = "SELECT * FROM tortest.folder ORDER BY folder_idx ASC";
					$result = mysql_query($sql);

					$menus = array(
						'items' => array(),
						'parents' => array()
					);

					while ($items = mysql_fetch_assoc($result)) {

						$menus['items'][$items['folder_idx']] = $items;

						$menus['parents'][$items['parent_id']][] = $items['folder_idx'];
					}


				//	echo $rs['folder_name'].'/'.$rs['link_name'];

				?>
        <div id="department"></div>-->
					<input type="hidden" name="filepath1"  value="<?php echo $rs['foldername'];?>" />
					<input type="hidden" name="foldername" value="<?php echo $rs['folder_name'];?>" />
					<input type="hidden" name="status" value="<?php echo $rs['status'];?>" />
					<input type="hidden" name="per_foldername" value="<?php echo $_GET['per_foldername'];?>" />
                </td>
            </tr>
        </table>

        	<div class="cleaner_h20"></div>
            	<center>
        		<input type="submit" name="recover" value="กู้คืนเอกสาร" />

				<input type="button" value="ย้อนกลับ" onClick="window.location='trashcan.php'"/>

				<input type="submit" name="delete" value="ลบเอกสารถาวร" onclick="return confirm('เอกสารจะลบทันที!!! คุณต้องการลบเอกสารหรือไม่?')">


                </center>

    </form>

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

function data_files($fileidx,$update_by,$edoc_iso){

	$sql= "	SELECT * FROM tortest.file_upload f LEFT JOIN tortest.folder fo ON f.filepath_encode = fo.foldername
			LEFT JOIN department d ON f.dept_code = d.dept_code WHERE f.fileidx = ".$fileidx." ";
	//echo $sql;
	return mysql_query($sql);
}

function getDepartment($deptId)
{
  $sql = "SELECT *
FROM  `department`
WHERE short_name =  ''
AND  `dept_code` !=  '-'
AND 	dept_id != '".$deptId."'
ORDER BY  `department`.`dept_code` ,  `department`.`dept_id` ASC ";

return mysql_query($sql);

}
?>
<script src="includes/js/jquery.min.js"></script>
<script src="includes/js/date.js"></script>
<script src="includes/js/jquery.multiSelect.js" type="text/javascript"></script>
<script type="text/javascript">
      $("#department").multiSelect({
          title: "แผนก",
          elements: [
              "ไม่ระบุ",
              "ทั้งหมด",
              <?php
                $departments = getDepartment(	$_SESSION['dept_id']);
                $countDepartments = mysql_num_rows($departments);
              while ($row =  mysql_fetch_object($departments)) {
                echo'"'.$row->dept_code.' '.$row->dept_name.'",';
                // code...
              }?>

          ]
      });
  </script>


<script>
 $.datepicker.regional['th'] ={
        changeMonth: true,
        changeYear: true,
        yearOffSet: '',
        showOn: "button",
        buttonImage: 'includes/images/icon-calendar.png',
		height: "50px",
        buttonImageOnly: true,
        dateFormat: 'yy-mm-dd',
        dayNames: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
        dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
        monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
        monthNamesShort: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
        constrainInput: true,
    };
$.datepicker.setDefaults($.datepicker.regional['th']);

$(function() {
    $("#datepicker1").datepicker( $.datepicker.regional["th"] ); // Set ภาษาที่เรานิยามไว้ด้านบน
    $("#datepicker1").datepicker('setDate', $("#datepicker1").val());
  });
function intonly(val){
	if(val.charCodeAt(val.length-1) < 48 || val.charCodeAt(val.length-1) > 57){
		val=val.substr(0,(val.length-1));
	}
	$("#version").val(val);
}
</script>
