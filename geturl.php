<?php
ob_start();
session_start();
date_default_timezone_set("Asia/Bangkok");
include 'includes/connect.php';
include 'includes/function.php';

if (empty($_SESSION['USER_TOR'])) {
  header('Location: login.php');
  exit;
}
header('Content-Type: text/html; charset=utf-8');

$sqls= "SELECT * FROM torTest.file_upload WHERE fileidx = '".$_GET['idx_search']."' ";
$querys= mysql_query($sqls);
$rs=mysql_fetch_array($querys);

$sql_emp = "select emref,status from tor.employee a where a.status=1 and a.emref='".$_SESSION["USER_TOR"]."' ;";
$query_emp = mysql_query($sql_emp);
$rs_emp = mysql_fetch_array($query_emp);


if($_GET['idx_search'] !=''){

	$s_s = "SELECT * FROM torTest.file_upload WHERE fileidx = '".$_GET['idx_search']."' ";
	$q_s = mysql_query($s_s);
	$rs_s = mysql_fetch_array($q_s);

	if($rs_emp['emref'] != $_SESSION["USER_TOR"] && $rs_emp['status'] != 1){
		exit("<script>alert('คุณไม่มีสิทธิ์ในการดาวน์โหลดเอกสารค่ะ');setTimeout('window.close();', 100);</script>");
	}else{

		$sqls= "SELECT * FROM torTest.file_upload WHERE fileidx = '".$_GET['idx_search']."' ";
		$querys= mysql_query($sqls);
		$rs=mysql_fetch_array($querys);

		$filetype = substr($rs['filename'],-3);
echo $filetype;
		$ip=@$REMOTE_ADDR;
		$tm=date("Y-m-d H:i:s");
		$query="insert into tor.log(username,ip, log_name, log_date)
				values('".$_SESSION["USER_TOR"]."','".$ip."','ดาวน์โหลดเอกสาร : ".$rs['filetitle']." ','".$tm."')";
		$result_query=mysql_query($query);

		if($filetype == 'pdf' || $filetype == 'PDF'){

		$filename = 'files$/'.$rs['folder_name'].'/'.$rs['filepath_encode'].'';
		$fileinfo = pathinfo($filename);
		$sendname = $fileinfo['filename'] . '.' . $fileinfo['extension'];



		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="' . $rs['filename'] . '"');
		header('Content-Transfer-Encoding: binary');

		ob_clean();
		flush();
		@readfile($filename.'/'.iconv("UTF-8", "TIS-620",$rs['filename']));

		}
    elseif($filetype == 'txt' || $filetype == 'TXT' || $filetype == 'bem'|| $filetype == 'BEM'){

    $filename = 'files$/'.$rs['folder_name'].'/'.$rs['filepath_encode'].'';
    $fileinfo = pathinfo($filename);
    $sendname = $fileinfo['filename'] . '.' . $fileinfo['extension'];


    $dataFile =  decryptBase64File ($filename.'/'.iconv("UTF-8", "TIS-620",$rs['filename']));
    header('Content-Type: application/pdf');
    echo $dataFile;

    //header('Content-type: application/pdf');
    //header('Content-Disposition: inline; filename="' . $rs['filename'] . '"');
    //header('Content-Transfer-Encoding: binary');

    //ob_clean();
    //flush();
    //@readfile($filename.'/'.iconv("UTF-8", "TIS-620",$rs['filename']));

    }else{
//$filename = "\\\\".gethostbyaddr("192.168.44.3")."\\files$\\".$rs['folder_name']."\\".$rs['filepath_encode'];
		$filename = 'files$/'.$rs['folder_name'].'/'.$rs['filepath_encode'].'';

		echo '<meta http-equiv="refresh"  content="0; url='.$filename.'"/>';

		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.$rs['filename']);
    //header('X-Sendfile: '.$filename);
		header('Cache-Control: must-revalidate, cache,post-check=0, pre-check=0');
    //echo  $filename;
    //print_r($rs);

		ob_clean();
		flush();
		@readfile($filename.'/'.iconv("UTF-8", "TIS-620",$rs['filename']));

		}
	}
}


function decryptBase64File ($pathFile)
{
  $pdf_base64 = $pathFile;
  //Get File content from txt file
  $pdf_base64_handler = fopen($pdf_base64,'r');
  $pdf_content = fread ($pdf_base64_handler,filesize($pdf_base64));
  fclose ($pdf_base64_handler);
  $pdf_decoded = base64_decode ($pdf_content);
  //Write data back to pdf file
  //$pdf = fopen ('test.pdf','w');
  //fwrite ($pdf,$pdf_decoded);
  //close output file
  //fclose ($pdf);
  return $pdf_decoded;
}

?>
