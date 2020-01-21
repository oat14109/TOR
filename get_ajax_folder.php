<?php
session_start();
require_once('includes/connect.php');
include 'includes/function.php';

if (empty($_SESSION['USER_TOR'])) {
  header('Location: login.php');
  exit;
}

date_default_timezone_set("Asia/Bangkok");

if($_POST['form_search']){
$key=$_POST['form_search'];

$sql= "	SELECT f.fileidx,f.filetitle,f.start_date,f.filename,f.folder_name,f.filesize,f.filepath,f.version,
		f.update_by,f.update_date FROM torTest.file_upload f
		WHERE (f.filetitle LIKE '%{$key}%') AND f.status != 0 ORDER BY f.filetitle ASC limit 10";
$query= mysql_query($sql);
$nums = mysql_num_rows($query);

  	echo '<div class="span12">
					<div class="widget">
						<div class="widget-title"><h4><i class="icon-tags"></i>ค้นหาเอกสาร</h4></div>
						<div class="widget-body">

							<div class="blog_folder">';

							if($nums > 0){

								while($rs=mysql_fetch_array($query)){

								$sql2 = "	SELECT e.fname,e.lname FROM torTest.employee e
											WHERE e.emref = '".$rs['update_by']."' ;";
								$query2= mysql_query($sql2);
								if($rs2=mysql_fetch_array($query2)){

									if(substr($rs['filename'],-3) == 'pdf' || substr($rs['filename'],-3) == 'PDF'){
										$icon = 'includes/images/icon_pdf.png';
									}elseif(substr($rs['filename'],-4) == 'xlsx' || substr($rs['filename'],-3) == 'xls'){
										$icon = 'includes/images/icon_excel.jpg';
									}elseif(substr($rs['filename'],-4) == 'docx' || substr($rs['filename'],-3) == 'doc'){
										$icon = 'includes/images/icon_word.jpg';
									}

									if($rs['start_date'] == '0000-00-00'){
										$start_date = '-';
									}else{
										$start_date = DateThai(strtotime(date($rs['start_date'])));
									}

									echo '<a href="geturl.php?idx_search='.$rs['fileidx'].'" target="_blank">
									<div style="width:100%;">
										<div style="float:left; margin-right:10px;">
											<img src="'.$icon.'" />
										</div>
										<div class="title_doc_small"><u>'.$rs['filetitle'].' แก้ไขครั้งที่ '.$rs['version'].' วันที่บังคับใช้ '.$start_date. ' </u>&nbsp;&nbsp; <img src="includes/images/icon_download.png" /> </div>
										<div class="title_post_small">

											<b>วันที่บังคับใช้ </b> '.$start_date.'  &nbsp;
											<b>อัพเดทโดย</b> '.$rs['fname'].' '.$rs['lname'].' &nbsp;
											<b>Size </b> '.size_as_kb($rs['filesize']).'
										</div>
									</div>
									</a>
									<div class="cleaner"></div>
									<div class="line_search"></div>';
									}	//IF
								} // while
							}else{

								echo '<center><b>ไม่พบไฟล์ที่ต้องการ</b></center>';

							}

								echo '<div class="cleaner"></div>
							</div>

						</div>
					</div>
				</div>';
}

if($_GET['docs']){

	$docs = explode('/',$_GET['docs']);
	$d1 = $docs[0];
	$d2 = $docs[1];
	$d3 = $docs[2];
	$v_docs = $d1.'/'.$d2;

	$data = data_role($_SESSION['USER_TOR']);
	$results = mysql_fetch_array($data);
	$role = $results['role_id'];

	$per = data_permiss($_SESSION['USER_TOR'],$d1);
	$r_per = mysql_fetch_array($per);
	$perfoldername = $r_per['foldername'];

	echo '<div class="file_content">
			<a href="javascript:;" class="link_b_docs" id="'.$v_docs.'/'.$d3.'"> '.$d3.' </a> > '.$d2.'
		 </div>';

		if ($role == 1){

			echo '<div style="padding-left:20px; margin-bottom: 10px;">
					<a href="addfile.php?folder=TOR&path='.$v_docs.'"><img src="includes/images/add.png" style="width: 15px;"> เพิ่มเอกสาร</a>

				</div>';

		}elseif($role == 2 && $perfoldername == $d1){

			echo '<div style="padding-left:20px; margin-bottom: 10px;">
				<a href="addfile.php?folder=ISO9001_14001_2015&path='.$v_docs.'"><img src="includes/images/add.png" style="width: 15px;"> เพิ่มเอกสาร</a>

			</div>';

		}

		echo '<div class="cleaner"></div>
			<div class="blog_folder" style="padding: 15px 20px;">
			<div id="loadingmessage" style="display:none;">
				  <center><img src="includes/images/load.gif"/></center>
			</div>
			';

		$sql= "	SELECT f.fileidx,f.filetitle,f.start_date,f.filename,f.folder_name,f.filesize,f.filepath,
				f.filepath_encode,f.update_by,f.version,f.update_date FROM torTest.file_upload f
				WHERE f.filepath_encode = '".$v_docs."' AND f.status != 0 ORDER BY f.filetitle ASC" ;
		$query= mysql_query($sql);
		while($rs=mysql_fetch_array($query)){

			$sql1= "SELECT e.fname,e.lname FROM torTest.employee e where e.emref ='".$rs['update_by']."';	" ;
			$query1= mysql_query($sql1);
			if($rs1=mysql_fetch_array($query1)){

				if(substr($rs['filename'],-3) == 'pdf' || substr($rs['filename'],-3) == 'PDF'){
					$icon = 'includes/images/icon_pdf.png';
				}elseif(substr($rs['filename'],-4) == 'xlsx' || substr($rs['filename'],-3) == 'xls'){
					$icon = 'includes/images/icon_excel.jpg';
				}elseif(substr($rs['filename'],-4) == 'docx' || substr($rs['filename'],-3) == 'doc'){
					$icon = 'includes/images/icon_word.jpg';
				}

				if($rs['start_date'] == '0000-00-00'){
					$start_date = '-';
				}else{
					$start_date = DateThai(strtotime(date($rs['start_date'])));
				}

				echo '<a href="geturl.php?idx_search='.$rs['fileidx'].'" target="_blank">
				<div style="width:100%;">
					<div style="float:left; margin-right:10px;">
						<img src="'.$icon.'" />
					</div>
					<div class="title_doc_small"><u>'.$rs['filetitle'].' แก้ไขครั้งที่ '.$rs['version'].' วันที่บังคับใช้ '.$start_date. ' </u>&nbsp;&nbsp; <img src="includes/images/icon_download.png" /> </div>
					<div class="title_post_small">

						<b>วันที่บังคับใช้ </b> '.$start_date.'  &nbsp;
						<b>อัพเดทโดย</b> '.$rs1['fname'].' '.$rs1['lname'].' &nbsp;
						<b>Size </b> '.size_as_kb($rs['filesize']).'
					</div>
				</div>
				</a>';


				if ($role == 1){

					echo '<a href="editfile.php?fileid='.$rs['fileidx'].'" title="แก้ไขเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';

				}elseif($role == 2 && $perfoldername == $d1){

					echo '<a href="editfile.php?fileid='.$rs['fileidx'].'" title="แก้ไขเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';
				}

				echo '<div class="cleaner"></div>
				<div class="line_search"></div>';
			} //if
		}// while

			echo '<div class="cleaner"></div>
		</div>';
}



if($_GET['docs_black']){

	$d = explode('/',$_GET['docs_black']);
	$dept_code = $d[0];
	$dept_name = $d[2];

	$id_user = $_SESSION['USER_TOR'];
	$concession ="SELECT * FROM torTest.employee  WHERE employee.emref ='$id_user'  AND employee.status = '1' ";
	$query_part = mysql_query($concession);
	$rs_part = mysql_fetch_array($query_part);
	$access = $rs_part['pos_id'];

	echo '<div class="blog_folder">

			<div id="loadingmessage" style="display:none;">
			  <center><img src="includes/images/load.gif"/></center>
			</div>';

				$objScan = scandir("files$/TOR/".$dept_code);

					foreach ($objScan as $value) {

					  	if ($value != "." && $value != "..") {

							$sql = "SELECT * FROM torTest.folder
									WHERE foldername = '".$dept_code."/".$value."' ORDER BY folder_idx ASC";
							$query = mysql_query($sql);
							while($rs = mysql_fetch_array($query)){

						echo '<a href="javascript:;" class="links" id="'.$dept_code.'/'.$value.'/'.$dept_name.'">
							<div class="folder">
								<img src="includes/images/folder.png" style="margin-bottom: 10px;">

								<div class="title_foder">'.$rs['text'].'</div>
							</div>
						</a>';

						} }
					}
			echo '<div class="cleaner"></div>
		</div>';
}






function data_role($username){

	$sql ="	SELECT a.username,a.role_id FROM torTest.admin a
			WHERE a.username ='".$_SESSION['USER_TOR']."' AND a.user_status = '1' ";
	//echo $sql;
	return mysql_query($sql);
}

function data_permiss($username,$foldername){

	$sql ="	SELECT u.username,u.foldername
			FROM torTest.user_permission u WHERE u.username ='".$username."' AND u.foldername = '".$foldername."'
			";
	//echo $sql;
	return mysql_query($sql);
}

?>

<script src="includes/js/1.111.1.jquery.min.js"></script>
<script>

	 $(function(){

			$('.link_b_docs').click(function(){
				$('#loadingmessage').show();
                $.ajax({
			    'type': "GET",
			    'url': "get_ajax_folder.php?docs_black=" + $(this).attr("id"),
			    'data': "id=" + $(this).attr("id"),
			    'success': function(html) {
		            $('#docs').html(html);
					$('#loadingmessage').hide();
		        }

			    });
            });


			$('.links').click(function(){

				$('#loadingmessage').show();
                $.ajax({
			    'type': "GET",
			    'url': "get_ajax_folder.php?docs="+ $(this).attr("id"),
			    'data': "id=" + $(this).attr("id"),
			    'success': function(html) {
		            $('#docs').html(html);
					$('#loadingmessage').hide();
		        }

			    });
            });
;


	/*------------------------End Docs Dept------------------------*/

        });
</script>


<script type="text/javascript">
    $(document).ready(function() {
        $('html, body').hide();

        if (window.location.hash) {
            setTimeout(function() {
                $('html, body').scrollTop(0).show();
                $('html, body').animate({
                    scrollTop: $(window.location.hash).offset().top
                    }, 1000)
            }, 0);
        }
        else {
            $('html, body').show();
        }
    });
</script>
