<?php
session_start();
require_once('includes/connect.php');
include 'includes/function.php';
if (empty($_SESSION['USER_TOR'])) {
  header('Location: login.php');
  exit;
}
header('Content-Type: text/html; charset=utf-8');

if($_POST['save_edit']){

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

$s_p = "SELECT * FROM torTest.folder WHERE foldername = '".$path."' ";
$s_q = mysql_query($s_p,$edoc_iso);
$rs = mysql_fetch_assoc($s_q);

$s_f = "SELECT * FROM torTest.file_upload WHERE fileidx = '".$_GET['fileid']."' ";
$q_f = mysql_query($s_f,$edoc_iso);
$rf = mysql_fetch_assoc($q_f);


$password =  $rf['passswordPDF'];
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
    $password =  $_POST['passwordPDF'];

      if (move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {
        copy($target_file, $target_BUfile);
        if(substr($_FILES["filename"]["name"],-3) == 'pdf' || substr($_FILES["filename"]["name"],-3) == 'PDF'){

        if($_POST["share"] == "แผนกที่ต้องการ" || $_POST["share"] == "ไม่เปิดเผย") {
          PDFPassword($target_file,$password);
      }
         $filename =  encryptBase64File('files$/'.$foldername.'/'.$filepath_encode,$_FILES["filename"]["name"]);

  	}
  }
  $password = base64_encode($password);
}
  	//move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file);

		$sql =	"UPDATE torTest.file_upload SET
					filetitle = '".$_POST['filedep'].$_POST['filetitle']."',
					 start_date = '".$start_date."',
					 dept_code = '".$dcode."',
					 dept_name = '".$dname."',
					 filename = '".$filename."',
					 folder_name = '".$foldername."',
					 filesize = '".$filesize."',
					 version = '".$version."',
					 filepath = '".$filepath."',
					 filepath_encode = '".$filepath_encode."',
					 status = 1,
           passswordPDF = '".$password."',
					 update_by = '".$_SESSION["USER_TOR"]."',
					 update_date = NOW()
					 WHERE fileidx = '".$_POST['fileidx']."' ";

		$query = mysql_query($sql);

    deleteShareFile ($_POST['fileidx'],$_SESSION["dept_id"]);
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
                VALUES ('".$_POST['fileidx']."', '".$dep[$i]->dept_id."');";
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
                VALUES ('".$_POST['fileidx']."', '".$depart[$i]->dept_id."');";
        $query = mysql_query($sql);


      }

    }
		echo'<script language="javascript">
			alert("บันทึกข้อมูลเรียบร้อยแล้ว!!!");
		</script>';
		//echo '<meta http-equiv=refresh content=0;URL=uploadfile.php?user='.$_SESSION['USER_TOR'].'>';
echo '<meta http-equiv=refresh content=0;URL=folder.php?folder='.$_POST['dept_code'].'%20Dept&dept_name='.$_POST['dept_code'].'%20'.$_POST['dept_name'].'>';
//-----------------------------------เก็บ LOG-----------------------------------
$ip=@$REMOTE_ADDR;
$tm=date("Y-m-d H:i:s");
$sql_log="insert into tor.log(username,ip, log_name, log_date)
	values('".$_SESSION['USER_TOR']."','".$ip."','แก้ไขเอกสาร : ".$filename." ครั้งที่ ".$version." หัวข้อ : ".$_POST['filedep'].$_POST['filetitle']."','".$tm."')";
$rs_log=mysql_query($sql_log);
//-----------------------------------END เก็บ LOG-----------------------------------

}

if($_POST['delete']){

	$s_p = "SELECT * FROM torTest.file_upload WHERE fileidx = '".$_GET['fileid']."' ";
	$s_q = mysql_query($s_p,$edoc_iso);
	$rs = mysql_fetch_assoc($s_q);

	if($rs['folder_name'] == $rs['filepath_encode']){

		$target_file = 'files$/'.$rs['folder_name'].'/'.iconv("UTF-8", "TIS-620",$rs['filename']);
	}else{

		$target_file = 'files$/'.$rs['folder_name'].'/'.$rs['filepath_encode'].'/'.iconv("UTF-8", "TIS-620",$rs['filename']);
	}

	//$delete = "DELETE FROM torTest.file_upload WHERE fileidx = '".$rs['fileidx']."' ";
  $delete = "UPDATE torTest.file_upload SET
         trashcan = 1,
         update_by = '".$_SESSION["USER_TOR"]."',
         update_date = NOW()
         WHERE fileidx = '".$_POST['fileidx']."' ;";
	$query_del = mysql_query($delete);

	@unlink($target_file);

		echo '<script>alert("ลบข้อมูลเรียบร้อยแล้วค่ะ")</script>';
		//echo '<meta http-equiv=refresh content=0;URL=uploadfile.php?user='.$_SESSION["USER_TOR"].'>';
		echo '<meta http-equiv=refresh content=0;URL=folder.php?folder='.$_POST['dept_code'].'%20Dept&dept_name='.$_POST['dept_code'].'%20'.$_POST['dept_name'].'>';

//-----------------------------------เก็บ LOG-----------------------------------
$ip=@$REMOTE_ADDR;
$tm=date("Y-m-d H:i:s");
$sql_log="insert into torTest.log(username,ip, log_name, log_date)
		values('".$_SESSION["USER_TOR"]."','".$ip."','ลบเอกสาร : ".$rs['filename']." หัวข้อ : ".$rs['filetitle']."','".$tm."')";
$rs_log=mysql_query($sql_log);
//-----------------------------------END เก็บ LOG-----------------------------------


}

$update_by = $_SESSION["USER_TOR"];
$fileid = $_GET['fileid'];


$sql_role ="SELECT admin.role_id FROM torTest.admin
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
                	<input  type ="text"  name="filedep" value="<?php echo $title[0];?>:" readonly="readonly" style="width:12%"><input type="text" name="filetitle" value="<?php echo $title[1];?>"/>
                </td>
            </tr>
            <tr style="background:#ffffff;border-bottom:1px solid #cccccc; height:45px;">
                <td class="title_td">ไฟล์</td>
                <td style="padding-left: 10px;">
                	<input id="fileInput" type="file" name="filename"> <br /><?php echo $rs['filename'];?>
					<input type="hidden" name="file_textbox" value="<?php echo $rs['filename'];?>" />
                </td>
            </tr>
            <tr style="background:#f7f7f7;border-bottom:1px solid #cccccc; height:45px;">
                <td class="title_td">แก้ไขครั้งที่</td>
                <td style="padding-left: 10px;">
                <input type="text"  maxlength='3' style='width:30px;' name="version" id="version" value="<?php echo $rs['version'];?>" onkeyup='intonly(this.value)' />
				<?php //echo $rs['version'];?>
                </td>
            </tr>
            <tr style="background:#ffffff;border-bottom:1px solid #cccccc; height:45px;">
                <td class="title_td">วันที่บังคับใช้</td>
                <td style="padding-left: 10px;">
                	<input type="text" id="datepicker1" name="start_date" value="<?php echo $rs['start_date'];?>" style="width: 36.5%;"/>
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
            <tr style="background:#f7f7f7;border-bottom:1px solid #cccccc; height:45px;">
               <td class="title_td">การแชร์</td>
                 <td style="padding-left: 10px; ">
                   <?php

                    $countDep = getDepartmentAll();
                    $countshare = getShareFile($rs['fileidx']);
                    if($countDep == $countshare)
                    {?>
                      <input name="share" type="radio" id="hide" value="ไม่เปิดเผย" > ไม่เปิดเผย<br>
                      <input name="share" type="radio" id="hidee" value="เปิดเผย" checked="checked"> เปิดเผย<br>
                      <input name="share" type="radio" id="show" value="แผนกที่ต้องการ"> แผนกที่ต้องการ<br>
                  <?php  }
                    elseif ($countshare == 1)
                    {?>
                      <input name="share" type="radio" id="hide" value="ไม่เปิดเผย" checked="checked" > ไม่เปิดเผย<br>
                      <input name="share" type="radio" id="hidee" value="เปิดเผย"> เปิดเผย<br>
                      <input name="share" type="radio" id="show" value="แผนกที่ต้องการ"> แผนกที่ต้องการ<br>
                  <?php  }
                    else { ?>
                      <input name="share" type="radio" id="hide" value="ไม่เปิดเผย" > ไม่เปิดเผย<br>
                      <input name="share" type="radio" id="hidee" value="เปิดเผย"> เปิดเผย<br>
                      <input name="share" type="radio" id="show" value="แผนกที่ต้องการ" checked="checked" > แผนกที่ต้องการ<br>
                  <?php  }
                   ?>

                 </td>
             </tr>
		<?php } ?>
            <tr id="popup" style="background:#f7f7f7;border-bottom:1px solid #cccccc; height:45px;">
               <td class="title_td">แผนกที่ต้องการแชร์</td>
                <td style="padding-left: 10px; padding-top: 10px;">

        <div id="department"></div>
					<input type="hidden" name="filepath1"  value="<?php echo $rs['foldername'];?>" />
					<input type="hidden" name="foldername" value="<?php echo $rs['folder_name'];?>" />
					<input type="hidden" name="status" value="<?php echo $rs['status'];?>" />
					<input type="hidden" name="per_foldername" value="<?php echo $_GET['per_foldername'];?>" />
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
        		<input type="submit" name="save_edit" value="บันทึกข้อมูล" />

				<input type="button" value="ย้อนกลับ" onClick="window.location='folder.php?folder=<?php echo $rs['dept_code']." Dept" ;  ?>&dept_name=<?php echo $rs['dept_code'].' '.$rs['dept_name'];?>#elementID'"/>

				<input type="submit" name="delete" value="ลบเอกสาร" onclick="return confirm('เอกสารจะลบทันที!!! คุณต้องการลบเอกสารหรือไม่?')">


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


function data_files($fileidx,$update_by,$edoc_iso){

	$sql= "	SELECT * FROM torTest.file_upload f LEFT JOIN tor.folder fo ON f.filepath_encode = fo.foldername
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
function getDepartmentAll()
{
  $sql = "SELECT *
FROM  `department`
WHERE short_name =  ''
AND  `dept_code` !=  '-'
ORDER BY  `department`.`dept_code` ,  `department`.`dept_id` ASC ";
  $res = mysql_query($sql);
  return  mysql_num_rows($res);
}
function getShareFile($fileidx)
{
  $sql = "SELECT * FROM `sharefile` WHERE  `sharefile`.idFileUpload = '".$fileidx."'";
  $res =  mysql_query($sql);
  return  mysql_num_rows($res);

}
function getDepartmentSelect($deptId,$fileidx)
{
    $sql = "SELECT *
  FROM  `department` INNER JOIN `sharefile`
  ON `sharefile`.idDepartment = `department`.dept_id
  WHERE `department`.short_name =  ''
  AND  `department`.`dept_code` !=  '-'
  AND 	`department`.dept_id != '".$deptId."'
  AND   `sharefile`.idFileUpload = '".$fileidx."'
  ORDER BY  `department`.`dept_code` ,  `department`.`dept_id` ASC ";

  return mysql_query($sql);

}
function getDepartmentDontSelect($deptId,$fileidx)
{
    $sql = "SELECT *
    FROM  `department`
    WHERE `department`.dept_id NOT IN
    (SELECT `department`.dept_id
    FROM  `department` INNER JOIN `sharefile`
    ON `sharefile`.idDepartment = `department`.dept_id
    WHERE    `sharefile`.idFileUpload = '".$fileidx."')
    AND  `department`.short_name =  ''
    AND  `department`.`dept_code` !=  '-'
    AND  `department`.dept_id != '".$deptId."'
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
function deleteShareFile ($idFileUpload,$idDepartment)
{
  $sql = "DELETE FROM sharefile WHERE idFileUpload = '".$idFileUpload."' and idDepartment != '".$idDepartment."' ;";
  mysql_query($sql);

}
?>
<script src="includes/js/jquery.min.js"></script>
<script src="includes/js/date.js"></script>
<script src="includes/js/jquery.multiSelect.js" type="text/javascript"></script>
<script>
$(document).ready(function(){

  $("#passwordPDF").hide();
  if ($("#hide").is(":checked")) {
    $("#popup").hide();
  }
  else if ($("#show").is(":checked")) {
    $("#popup").show();
  }
  else if ($("#hidee").is(":checked")) {
    $("#popup").hide();
  }
  else {
    $("#popup").hide();

  }

  $('input[type="file"]').change(function(){
    if ($("#hide").is(":checked")) {
      $("#passwordPDF").show();
    }
    else if ($("#show").is(":checked")) {
      $("#passwordPDF").show();
    }
    else {
        $("#passwordPDF").hide();
    }


  });
  $("#hide").click(function(){
    $("#popup").hide();
    file = $("#fileInput").prop('files')[0];
    if(file)
    $("#passwordPDF").show();
  });
  $("#hidee").click(function(){
    $("#popup").hide();
    $("#passwordPDF").hide();
  });
  $("#show").click(function(){
    $("#popup").show();
    file = $("#fileInput").prop('files')[0];
    if(file)
    $("#passwordPDF").show();
  });
});
</script>

<script type="text/javascript">
      $("#department").multiSelect({
          title: "แผนก",
          elements: [
              <?php
                $departments = getDepartmentDontSelect($_SESSION['dept_id'],$rs['fileidx']);
                $countDepartments = mysql_num_rows($departments);
              while ($row =  mysql_fetch_object($departments)) {
                echo'"'.$row->dept_code.' '.$row->dept_name.'",';
                // code...
              }?>

          ],
          elementsDefauts: [
            <?php
              $departments = getDepartmentSelect(	$_SESSION['dept_id'],$rs['fileidx']);
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
