<?php
ob_start();
session_start();
require_once('includes/connect.php');
include 'includes/function.php';

if (empty($_SESSION['USER_TOR'])) {
  header('Location: login.php');
  exit;
}
header('Content-Type: text/html; charset=utf-8');

//echo $_POST["share"];
//print_r($_POST);
//print_r($_SESSION);
if($_POST['save_add']){

$departments = $_POST['department'];
$foldername = $_POST['foldername'];
$code = explode('/',$_POST['dept_code']);
$start_date = $_POST['start_date'];


$dcode = $code[0];
$dname = $code[1];

$path = $_POST['filepath'];

$s_p = "SELECT * FROM torTest.folder WHERE foldername = '".$path."' ";
$s_q = mysql_query($s_p);
$rs = mysql_fetch_assoc($s_q);

$filepath = $rs['link_name'];
$filepath_encode = $rs['foldername'];
$filename = $_FILES["filename"]["name"];
$filename_c = $foldername.'_'.date('Ymd_hms');
$fileInfo = pathinfo($_FILES["filename"]["name"]);
$fileType = $fileInfo['extension'];
//$fileText = $rs['text'];
//print_r($rs);
//print_r($_POST);
$password =  $_POST['passwordPDF'];

$target_file = 'files$/'.$foldername.'/'.$filepath_encode.'/'.iconv("UTF-8", "TIS-620",$_FILES["filename"]["name"]);
//$target_file = 'files/'.$foldername.'/'.$filepath_encode.'/'.$filename_c.'.'.$fileType;
//$filename = $filename_c.$fileType;


if ($_FILES["filename"]["name"] == '') {
	echo'<script language="javascript">
			alert("ไม่พบเอกสารดังกล่าว กรุณาอัพโหลดเอกสารด้วยค่ะ");
			window.history.back();
		</script>';
}else {

   if (move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {
     $target_BUfile = 'backupfiles$/'.$foldername.'/'.$filepath_encode.'/'.iconv("UTF-8", "TIS-620",$_FILES["filename"]["name"]);
  copy($target_file, $target_BUfile);
  if(substr($_FILES["filename"]["name"],-3) == 'pdf' || substr($_FILES["filename"]["name"],-3) == 'PDF'){
     if($_POST["share"] == "แผนกที่ต้องการ" || $_POST["share"] == "ไม่เปิดเผย") {
       PDFPassword($target_file,$password);
     }

     $filename =  encryptBase64File('files$/'.$foldername.'/'.$filepath_encode,$_FILES["filename"]["name"]);
    }
		$sql =	"INSERT INTO torTest.file_upload VALUES('','".$_POST['filedep'].$_POST['filetitle']."','".$start_date."',
				 '".$dcode."','".$dname."','".$filename."','".$foldername."','".$_FILES["filename"]["size"]."','".$_POST['version']."','".$filepath."','".$filepath_encode."','','',
				 '1','0','".base64_encode($password)."','".$_SESSION["USER_TOR"]."',NOW() )";
    $query = mysql_query($sql);

    $last_id = mysql_insert_id();


    $sql = "INSERT INTO sharefile (idFileUpload, idDepartment)
            VALUES ('".$last_id."', '".$_SESSION["dept_id"]."');";
    $query = mysql_query($sql);

    $folderDept =  explode("/",$_POST['dept_code']);

    if ($_POST["share"] == "เปิดเผย") {
      // code...

      $departments = getDepartment($_SESSION["dept_id"]);
      while ($row = mysql_fetch_object($departments)) {
        $dep[] = $row;

        // code...
      }

      for ($i=0; $i < count($dep); $i++) {
        // code...
        $sql = "INSERT INTO sharefile (idFileUpload, idDepartment)
                VALUES ('".$last_id."', '".$dep[$i]->dept_id."');";
        $query = mysql_query($sql);


      }


    }
    elseif ($_POST["share"] == "แผนกที่ต้องการ") {
      // code...

      for ($i=0; $i < count($_POST["department"]) ; $i++) {
        // code...
        $dep =   explode(" ",$_POST["department"][$i]);
        //print_r($dep);
        if($i ==  0)
        {
            $stringDep .= "'".$dep[0]."'";
        }
        else {
          $stringDep .= ",'".$dep[0]."'";
        }
      }


      $departments = getDepartmentSome($stringDep);
      while ($row = mysql_fetch_object($departments)) {
        $depart[] = $row;

        // code...
      }
      //print_r($depart);
      for ($i=0; $i < count($depart); $i++) {
        // code...
        $sql = "INSERT INTO sharefile (idFileUpload, idDepartment)
                VALUES ('".$last_id."', '".$depart[$i]->dept_id."');";
        $query = mysql_query($sql);


      }

    }

    echo'<script language="javascript">
         alert("บันทึกข้อมูลเรียบร้อยแล้ว!!!");
     </script>';
      echo '<meta http-equiv=refresh content=0;URL=folder.php?folder='.$folderDept[0].'%20Dept&dept_name='.$folderDept[0].'%20'.$folderDept[1].'>';



//	echo'<script language="javascript">
//			alert("บันทึกข้อมูลเรียบร้อยแล้ว!!!");
//		</script>';
//  echo '<meta http-equiv=refresh content=0;URL=folder.php?folder='.$folderDept[0].'%20Dept&dept_name='.$folderDept[0].'%20'.$folderDept[1].'>';
		//echo '<meta http-equiv=refresh content=0;URL=uploadfile.php?user='.$_SESSION["USER_TOR"].'>';
    }
}

//-----------------------------------เก็บ LOG-----------------------------------
$ip=@$REMOTE_ADDR;
$tm=date("Y-m-d H:i:s");
$sql_log="insert into torTest.log(username,ip, log_name, log_date)
	values('".$_SESSION["USER_TOR"]."','".$ip."','เพิ่มเอกสาร : ".$filename." หัวข้อ : ".$_POST['filedep'].$_POST['filetitle']."','".$tm."')";
$rs_log=mysql_query($sql_log);
//-----------------------------------END เก็บ LOG-----------------------------------

}


$sql_role ="SELECT admin.role_id FROM torTest.admin
			WHERE admin.username ='".$_SESSION["USER_TOR"]."' AND admin.user_status = '1' ";
$query_role = mysql_query($sql_role,$edoc_iso);
$rs_role = mysql_fetch_array($query_role);
$role = $rs_role['role_id'];

getheader();



$path = $_GET['path'];

$c = explode('/',$_GET['path']);
$deptcode= substr($c[0],0,2);
$folder=$_GET['folder'];

$data = data_files($path,$edoc_iso);
$rs_p=mysql_fetch_array($data);

$s_d = "SELECT * FROM torTest.department WHERE dept_code = '".$deptcode."' ";
$q_d = mysql_query($s_d);
$r_dept = mysql_fetch_array($q_d);

?>
<link rel="stylesheet" href="includes/css/smoothness.css">
<style>
input[type="text"]{
	margin-bottom:0;
	width:40%;
}
select{
	width:42%;
}
input[type="number"]{
	width:40px;
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
						<div class="widget-title"><h4><i class="icon-tags"></i>เพิ่มเอกสาร</h4></div>

						<div class="widget-body">


							<div class="blog_folder">

      <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" name="form_add" enctype="multipart/form-data">

        <table width="100%" >
            <tr style="background:#f7f7f7;border-bottom:1px solid #cccccc; height:45px;">
                <td class="title_td">หัวข้อ</td>
                <td style="padding-left: 10px;"><input  type ="text"  name="filedep" value="TOR <?php echo $r_dept['dept_code'];?> :" readonly="readonly" style="width:12%"><input type="text" name="filetitle" value=""/></td>
            </tr>
            <tr style="background:#ffffff;border-bottom:1px solid #cccccc; height:45px;">
                <td class="title_td">ไฟล์</td>
                <td style="padding-left: 10px;">
                	<input type="file" name="filename"  class="fileupload-new">
                </td>
            </tr>
            <tr style="background:#f7f7f7;border-bottom:1px solid #cccccc; height:45px;">
                <td class="title_td">แก้ไขครั้งที่</td>
                <td style="padding-left: 10px;">
                <input type="text" maxlength='3' style='width:30px;' name="version" id="version" value="0" onkeyup='intonly(this.value)'/>
                </td>
            </tr>
            <tr style="background:#ffffff;border-bottom:1px solid #cccccc; height:45px;">
                <td class="title_td">วันที่บังคับใช้</td>
                <td style="padding-left: 10px;">
                	<input type="text" id="datepicker1" name="start_date" value="<?php echo date('Y-m-d');?>" style="width: 36.5%;"/>
                </td>
            </tr>
			<?php if ($deptcode != 0){ ?>
           	<tr style="background:#f7f7f7;border-bottom:1px solid #cccccc; height:45px;">
                <td class="title_td">ไฟล์ของแผนก</td>
                <td style="padding-left: 10px;">
				<input type="hidden" name="dept_code" value="<?php echo $r_dept['dept_code'].'/'.$r_dept['dept_name'];?>" />
				<?php echo $r_dept['dept_code'].' '.$r_dept['dept_name'];?>
                </td>
            </tr>
			<?php } ?>
           <tr style="background:#f7f7f7;border-bottom:1px solid #cccccc; height:45px;">
              <td class="title_td">การแชร์</td>
                <td style="padding-left: 10px; ">
                  <input name="share" type="radio" id="hide" value="ไม่เปิดเผย" checked="checked" > ไม่เปิดเผย<br>
                  <input name="share" type="radio" id="hidee" value="เปิดเผย"> เปิดเผย<br>
                  <input name="share" type="radio" id="show" value="แผนกที่ต้องการ"> แผนกที่ต้องการ<br>
                </td>
            </tr>
            <tr id="popup" style="background:#f7f7f7;border-bottom:1px solid #cccccc; height:45px;">
                <td class="title_td">แผนกที่ต้องการแชร์</td>
                <td style="padding-left: 10px; ">
					<?php //echo $rs_p['link_name'];?>
          <input type="hidden" name="filepath"  value="<?php echo $rs_p['foldername'];?>" />
					<input type="hidden" name="foldername" value="<?php echo $folder;?>" />
					<input type="hidden" name="status" value="1" />
            <div id="department"></div>
                </td>
            </tr>
            <tr id="passwordPDF" style="background:#f7f7f7;border-bottom:1px solid #cccccc; height:45px;" >
              <td class="title_td">รหัสPDF</td>
              <td style="padding-left: 10px; ">
                  <input type="text" name="passwordPDF" value=""/>
            </tr>
        </table>
        	<div class="cleaner_h20"></div>
            	<center>
        		<input type="submit" name="save_add" value="บันทึกข้อมูล" />

				<input type="button" onclick="window.location='folder.php?folder=<?php echo $deptcode." Dept" ;  ?>&dept_name=<?php echo $r_dept['dept_code'].' '.$r_dept['dept_name'];?>#elementID'" value="ย้อนกลับ"/>


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

function PDFPassword($pathfile,$password)
{
  require "includes/mpdf/mpdf.php";
$mpdf = new mPDF('utf-8');
$mpdf = new mPDF('th', 'A4', '0', 'THSaraban');
$mpdf->SetImportUse();
$pagecount = $mpdf->SetSourceFile($pathfile);

// Import the last page of the source PDF file
for ($i = 1 ;$i<= $pagecount  ;$i++)
{
$tplId = $mpdf->ImportPage($i);
$mpdf->UseTemplate($tplId);
if ($i < $pagecount)
  $mpdf->AddPage();
}

//$mpdf->SetProtection(array(), 'UserPassword', 'MyPassword');
$mpdf->SetProtection(array('copy','print'), $password, $password);
//$mpdf->SetProtection(array('copy','print'), '', 'MyPassword');
$mpdf->Output($pathfile);
}


function encryptBase64File ($pathfile,$filename)
{
$pdf_base64 = $pathfile."/".iconv("UTF-8", "TIS-620",$filename);
$pdf_content = file_get_contents($pdf_base64);
$pdf_encoded = chunk_split(base64_encode ($pdf_content));
$name = explode(".",$filename);
$pdf = fopen ($pathfile."/".iconv("UTF-8", "TIS-620",$name[0].'.bem'),'w');
fwrite ($pdf,$pdf_encoded);
fclose ($pdf);
unlink($pdf_base64) or die("Couldn't delete file");

return $name[0].'.bem' ;
}

function data_files($path,$edoc_iso){

	$sql= "	SELECT * FROM torTest.folder WHERE foldername = '".$path."' ";
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

function getDepartmentSome($deptIDString)
{
  $sql = "SELECT *
FROM  `department`
WHERE short_name =  '' AND dept_code IN (".$deptIDString.")
ORDER BY  `department`.`dept_code` ,  `department`.`dept_id` ASC ";

//echo $sql;
  return mysql_query($sql);

}


function insertShareFile ($idFileUpload,$idDepartment)
{
  $sql = "INSERT INTO sharefile ( `idFileUpload`,`idDepartment`)
VALUES ('".$idFileUpload."','".$idDepartment."');";
mysql_query($sql);

}
?>

<script src="includes/js/jquery.min.js"></script><!--ซ้ำ-->
<script src="includes/js/date.js"></script>
<script src="includes/js/jquery.multiSelect.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
  $("#popup").hide();

  $("#hide").click(function(){
    $("#popup").hide();
      $("#passwordPDF").show();
  });
  $("#hidee").click(function(){
    $("#popup").hide();
    $("#passwordPDF").hide();
  });
  $("#show").click(function(){
    $("#popup").show();
    $("#passwordPDF").show();
  });
});
</script>
<script type="text/javascript">
      $("#department").multiSelect({
          title: "แผนก",
          elements: [
              <?php
                $departments = getDepartment(	$_SESSION['dept_id']);

              while ($row =  mysql_fetch_object($departments)) {
                echo '"'.$row->dept_code.' '.$row->dept_name.'",';
                // code...
              }?>

          ],
          elementsDefauts:[]
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
