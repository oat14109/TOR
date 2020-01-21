<?php
session_start();
require_once('includes/connect.php');
include 'includes/function.php';

if (empty($_SESSION['USER_TOR'])) {
  header('Location: login.php');
  exit;
}

$ip=@$REMOTE_ADDR;
$tm=date("Y-m-d H:i:s");
$query="insert into edoc_iso.log(username,ip, log_name, log_date)
		values('".$_SESSION['USER_TOR']."','".$ip."','ล็อคอินเข้าสู่ระบบ','".$tm."')";
$result_query=mysql_query($query);



$sql_role ="SELECT e.fname,e.lname,role_id,e.emref,u.foldername,e.dept_code
			FROM torTest.employee e LEFT JOIN torTest.admin  ON e.emref = admin.username
			LEFT JOIN torTest.user_permission u ON admin.username = u.username
			WHERE admin.username ='".$_SESSION['USER_TOR']."' AND  admin.user_status = '1' ";

$query_role = mysql_query($sql_role);
$rs_role = mysql_fetch_array($query_role);
$role = $rs_role['role_id'];
$username = $_SESSION['USER_TOR'];
$deptCode   = $rs_role['dept_code'];
getheader();

?>
<script src="includes/js/1.111.1.jquery.min.js"></script>
<script>
     function showHint(str) {

        if (str.length == 0) {
           document.getElementById("txtHint").innerHTML = "";
		   $('.diplay_content').show();
           return;
        }/*else {

		  var xmlhttp = new XMLHttpRequest();

           xmlhttp.onreadystatechange = function() {
			  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                 document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
				$('.diplay_content').hide();
              }

           }
           xmlhttp.open("GET", "get_ajax.php?key=" + str, true);
           xmlhttp.send();

        }*/

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


        $('.link_form').click(function(){

				$('#loading_form').show();
                $.ajax({
			    'type': "POST",
			    'url': "get_ajax.php",
			    'data': "id=" + $(this).attr("id"),
			    'success': function(html) {
		            $('#doc_form').html(html);
					$('#loading_form').hide();
		        }

			    });
            });

            $('.link_wi').click(function(){

				$('#loading_wi').show();
                $.ajax({
			    'type': "POST",
			    'url': "get_ajax.php",
			    'data': "id=" + $(this).attr("id"),
			    'success': function(html) {
		            $('#doc_wi').html(html);
					$('#loading_wi').hide();
		        }

			    });
            });

            $('.link_wp').click(function(){

				$('#loading_wp').show();
                $.ajax({
			    'type': "POST",
			    'url': "get_ajax.php",
			    'data': "id=" + $(this).attr("id"),
			    'success': function(html) {
		            $('#doc_wp').html(html);
					$('#loading_wp').hide();
		        }

			    });
            });

			$('.link_center').click(function(){

				$('#loading_center').show();
                $.ajax({
			    'type': "POST",
			    'url': "get_ajax.php?action=Center&values=" + $(this).attr("id"),
			    'data': "id=" + $(this).attr("id"),
			    'success': function(html) {
		            $('#doc_center').html(html);
					$('#loading_center').hide();
		        }

			    });
            });

        });
</script>

<script type="text/javascript">
$(function(){
    $("ul#navi_containTab > li").click(function(event){
            var menuIndex=$(this).index();

            $("ul#detail_containTab > li:visible").hide();
            $("ul#detail_containTab > li").eq(menuIndex).show();

    });

	$('li.tabs').click(function(e) {
        e.preventDefault();
        $('li.tabs').removeClass('active');
        $(this).addClass('active');
    });
});
</script>

<style>
ul.tabs > li.active {
    background:#20aeb9;
}
</style>

					<ul class="breadcrumb">

						<li class="pull-right search-wrap">
							<form name="search" class="myForm" action="" method="post">
								<div class="search-input-area">
									<!--<input name="form_search" class="form_search search-query" type="text" placeholder="Search" onkeyup="showHint(this.value)"/>-->
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

				<div class="span12 diplay_content">
					<div class="widget">
						<div class="widget-title"><h4><i class="icon-tags"></i>เอกสารอัพเดทล่าสุุด</h4></div>
						<div class="widget-body">

							<div class="blog_folder">

							<div id="loading_search" style="display:none">
								  <center><img src='includes/images/load.gif'/></center>
								</div>

            <?php
            //f.dept_code = '".$deptCode."'

            $sql_emp ="SELECT * FROM employee LEFT JOIN position ON position.pos_id = employee.pos_id
                 LEFT JOIN department ON department.dept_id = employee.dept_id
                 WHERE employee.emref = ".$_SESSION["USER_TOR"]." ";
            $query_emp = mysql_query($sql_emp);
            $rs_emp = mysql_fetch_array($query_emp);
            if($rs_emp['degree_id'] > 10)
            {

              $sql= "	SELECT f.fileidx,f.filetitle,f.start_date,f.filename,f.folder_name,f.filesize,f.filepath,f.filepath_encode,f.update_by,f.version,f.update_date,f.passswordPDF FROM torTest.file_upload f
                  WHERE  f.status != 0 AND f.trashcan = 0 GROUP BY f.fileidx ORDER BY f.start_date DESC LIMIT 12";
            }
            elseif ($rs_emp['degree_id'] == 9 || $rs_emp['degree_id'] == 10 )
            {

              $allDep = findDepertmentGM($_SESSION["dept_id"]);
              $sql= "	SELECT f.fileidx,f.filetitle,f.start_date,f.filename,f.folder_name,f.filesize,f.filepath,f.filepath_encode,f.update_by,f.version,f.update_date,f.passswordPDF FROM torTest.file_upload f
              INNER JOIN  torTest.sharefile ON  torTest.sharefile.idFileUpload	= f.fileidx
                  WHERE  f.status != 0 AND f.trashcan = 0  AND torTest.sharefile.idDepartment in (".$allDep.") GROUP BY f.fileidx  ORDER BY f.start_date DESC LIMIT 12";

            }
            else {

            $sql= "	SELECT f.fileidx,f.filetitle,f.start_date,f.filename,f.folder_name,f.filesize,f.filepath,f.filepath_encode,f.update_by,f.version,f.update_date,f.passswordPDF FROM torTest.file_upload f
            INNER JOIN  torTest.sharefile ON  torTest.sharefile.idFileUpload	= f.fileidx
                WHERE  f.status != 0 AND f.trashcan = 0  AND torTest.sharefile.idDepartment = '".$_SESSION["dept_id"]."'  ORDER BY f.start_date DESC LIMIT 12";
            }

            $query= mysql_query($sql);
            while($rs=mysql_fetch_array($query)){

              $sql1= "SELECT e.fname,e.lname FROM torTest.employee e where e.emref ='".$rs['update_by']."';	" ;
              $query1= mysql_query($sql1);
              if($rs1=mysql_fetch_array($query1)){

                if(substr($rs['filename'],-3) == 'pdf' || substr($rs['filename'],-3) == 'PDF' || substr($rs['filename'],-3) == 'txt'|| substr($rs['filename'],-3) == 'TXT' || substr($rs['filename'],-3) == 'bem' || substr($rs['filename'],-3) == 'BEM'){
                  $icon = 'includes/images/icon_pdf.png';
                }elseif(substr($rs['filename'],-4) == 'xlsx' || substr($rs['filename'],-3) == 'xls'){
                  $icon = 'includes/images/icon_excel.jpg';
                }elseif(substr($rs['filename'],-4) == 'docx' || substr($rs['filename'],-3) == 'doc'){
                  $icon = 'includes/images/icon_word.jpg';
                }

                if(empty($rs['passswordPDF']))
                {
                    $iconkey = '';
                }
                else {
                  // code...
                  $iconkey = 'includes/images/key.png';
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
                  <div class="title_doc_small"><u>'.$rs['filetitle'].'</u>&nbsp;&nbsp; <img src="includes/images/icon_download.png" /><img src="'.$iconkey.'" width="15" height="13" title="'.base64_decode($rs['passswordPDF']).'"/> </div>
                  <div class="title_post_small">

                    <b>วันที่บังคับใช้ </b> '.$start_date.'  &nbsp;
                    <b>อัพเดทโดย</b> '.$rs1['fname'].' '.$rs1['lname'].' &nbsp;
                    <b>Size </b> '.size_as_kb($rs['filesize']).'
                  </div>
                </div>
                </a>';
                echo '<div class="cleaner"></div>
        				<div class="line_search"></div>';
        			} //if
        		}// while?>


								<div class="cleaner"></div>
							</div>
					</div>
				</div>

			</div>

			</div>
		</div>

	</div>

</div>

<?php getfooter();

function findDepertmentGM($idDep)
{
  $sql ="SELECT *
FROM  `department` AS dep1, (
SELECT  `div_id`
FROM  `department`
WHERE  `dept_id` ='".$idDep."'
) AS dep2
WHERE dep1.`div_id` = dep2.`div_id`
AND dep1.`short_name` =  ''  ";
  $query = mysql_query($sql);
  $checkfirst = true;
  $deptall = '';
  while ($res =  mysql_fetch_object($query)) {
    // code...

    if($checkfirst)
    {
      $deptall = "'".$res->dept_id."'";
      $checkfirst = false;
    }
    else {
      $deptall .=",'".$res->dept_id."'";
    }

  }
  return $deptall;
}
function data_lastupdate(){

	$sql= "	SELECT * FROM torTest.file_upload f WHERE f.status != 0 ORDER BY f.update_date DESC  LIMIT 12 ";
	//echo $sql;
	return mysql_query($sql);
}

?>
