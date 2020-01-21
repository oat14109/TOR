<?php
session_start();
require_once('includes/connect.php');
include 'includes/function.php';

if (empty($_SESSION['USER_TOR'])) {
    header('Location: login.php');
    exit;
}


getheader();

//$department = findDepertment($_SESSION['USER_TOR']);
//$folderName = $department->dept_code." Dept/".(date("Y")+543);
//echo $folderName;
//if(!checkFolder($folderName))
//{
  //insertFolder ("ปี ".(date("Y")+543),$folderName,$folderName,2,0);
  //mkfolder ($folderName);

//}
//print_r($department);
?>

<script src="includes/js/1.111.1.jquery.min.js"></script>
<script>
     function showHint(str) {
        if (str.length == 0) {
           document.getElementById("txtHint").innerHTML = "";
		   $('.diplay_content').show();
           return;
        }
     }


	 $(function(){

	 		$('.myForm').submit(function() {
				$('#loading_search').show();
                $.ajax({
			    'type': "POST",
			    'url': "get_ajax.php",
				'data': {form_search: $("#form_search").val()},
			    'success': function(html) {
		            $('#txtHint').html(html);
					$('#loading_search').hide();
					$('.diplay_content').hide();
		        }

			    });

				event.preventDefault();

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

        });

</script>

					<ul class="breadcrumb">

						<li class="pull-right search-wrap">
							<form name="search" class="myForm" action="" method="post">
								<div class="search-input-area">
									<input name="form_search" id="form_search" class="form_search search-query" type="text" placeholder="Search" onkeyup="showHint(this.value)"/>
									<button class="myForm" style="height:37px;border: none; margin-left: -35px;background: none;">
									<i class='icon-search'></i>
									</button>
								</div>
							</form>
						</li>
					</ul>
				</div>
			</div>

		<div id="page" class="dashboard">
			<div class="row-fluid">

				<div id="txtHint"></div>

				<?php 	$folder = $_GET['folder'];
						$deptname = $_GET['dept_name'];
						$dept = explode(' ',$folder);
           				$dept_code = $dept[0];
				 ?>

				<div class="span12 diplay_content">
					<div class="widget">
						<div class="widget-title"><h4><i class="icon-tags"></i>ไฟล์เอกสารถูกลบ</h4></div>
						<div class="widget-body">

							<div id="docs">

<?php
$data = data_role($_SESSION['USER_TOR']);
$results = mysql_fetch_array($data);
$role = $results['role_id'];

$per = data_permiss($_SESSION['USER_TOR'],$dept_code);
$r_per = mysql_fetch_array($per);
$perfoldername = $r_per['dept_code'];


?>

		<div class="cleaner"></div>
		<div class="blog_folder" style="padding: 15px 20px;">
		<div id="loadingmessage" style="display:none;">
			  <center><img src="includes/images/load.gif"/></center>
		</div>

<?php
		$sql= "	SELECT f.fileidx,f.filetitle,f.start_date,f.filename,f.folder_name,f.filesize,f.filepath,f.filepath_encode,f.update_by,f.version,f.update_date FROM tortest.file_upload f
				WHERE  f.status != 0 AND f.trashcan = 1 ORDER BY f.start_date DESC";
		$query= mysql_query($sql);
		while($rs=mysql_fetch_array($query)){

		$sql1= "SELECT e.fname,e.lname FROM tor.employee e where e.emref ='".$rs['update_by']."';";
		$query1= mysql_query($sql1);
		if($rs1=mysql_fetch_array($query1)){

				if(substr($rs['filename'],-3) == 'pdf' || substr($rs['filename'],-3) == 'PDF' || substr($rs['filename'],-3) == 'txt' || substr($rs['filename'],-3) == 'TXT' || substr($rs['filename'],-3) == 'bem' || substr($rs['filename'],-3) == 'BEM'){
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
					<div class="title_doc_small"><u>'.$rs['filetitle'].' </u>&nbsp;&nbsp; <img src="includes/images/icon_download.png" /> </div>
					<div class="title_post_small">

						<b>วันที่บังคับใช้ </b> '.$start_date.'  &nbsp;
						<b>อัพเดทโดย</b> '.$rs1['fname'].' '.$rs1['lname'].' &nbsp;
						<b>Size </b> '.size_as_kb($rs['filesize']).'
					</div>
				</div>
				</a>';


				if ($role == 1){

					echo '<a href="recover_remove.php?fileid='.$rs['fileidx'].'" title="กู้คืนหรือลบเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';

				}//elseif($role == 2 && $perfoldername == $dept_code){

				//	echo '<a href="recover_remove.php?fileid='.$rs['fileidx'].'" title="กู้คืนหรือลบเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';
			//	}

				echo '<div class="cleaner"></div>
				<div class="line_search"></div>';
			} //if
		}// while

			echo '<div class="cleaner"></div>
		</div>';
?>

									<div class="cleaner_h40"></div>
								</div>

							</div>

						</div>
					</div>
				</div>
			</div>
		</div>


	</div>
</div>


<?php
function findDepertment ($idUser)
{
  $sql ="SELECT * FROM base_center.`transaction`  inner join base_center.department on department.dept_id = transaction.dept_id WHERE `emref`='".$idUser."'  ";
  $query = mysql_query($sql);
  return  mysql_fetch_object($query);

}

function checkFolder($nameFolder)
{
    $sql = "SELECT * FROM folder where 	foldername = '".$nameFolder."'";
    $query = mysql_query($sql);
    $num_rows = mysql_num_rows($query);
    if($num_rows > 0 )
    {
      return true;
    }
    else {
      return false;
    }

}
function insertFolder ($textFolder,$folderName,$linkName,$parentID,$orderBy)
{
  $sql = "INSERT INTO `folder` ( `text`, `foldername`, `link_name`,`parent_id` ,`order_by`)
VALUES ('".$textFolder."', '".$folderName."', '".$linkName."','".$parentID."','".$orderBy."');";
//echo $sql;
  $query = mysql_query($sql);
}

 function mkfolder ($nameFolder)
{
  $dirPath = "files/TOR/".$nameFolder."";
  if(!file_exists($dirPath)){
  //  echo $dirPath;
      mkdir($dirPath, 0777, true);
  }

}

function data_role($username){

	$sql ="	SELECT a.username,a.role_id FROM tor.admin a
			WHERE a.username ='".$_SESSION['USER_TOR']."' AND a.user_status = '1' ";
	//echo $sql;
	return mysql_query($sql);
}

function data_permiss($username,$foldername){

	$sql ="	SELECT dept_code FROM tor.employee e WHERE e.emref ='".$username."' AND e.dept_code = '".$foldername."' ";
	//echo $sql;
	return mysql_query($sql);
}




getfooter();?>
