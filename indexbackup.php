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



$sql_role ="SELECT e.fname,e.lname,role_id,e.emref,u.foldername
			FROM torTest.employee e LEFT JOIN torTest.admin  ON e.emref = admin.username
			LEFT JOIN torTest.user_permission u ON admin.username = u.username
			WHERE admin.username ='".$_SESSION['USER_TOR']."' AND  admin.user_status = '1' ";

$query_role = mysql_query($sql_role);
$rs_role = mysql_fetch_array($query_role);
$role = $rs_role['role_id'];
$username = $_SESSION['USER_TOR'];

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


							<?php   $data_lastupdate = data_lastupdate();
									while($rs_last = mysql_fetch_array($data_lastupdate)){

									$path = explode('/', $rs_last['filepath_encode']);
									$filepath = $path[1];

									$img_file = 'includes/images/pdf.jpg';



							?>

								<a href="geturl.php?idx_search=<?php echo $rs_last['fileidx'];?>" target="_blank">

								<div class="blog_use">
								<div style="float:left; margin-right:10px;">
                                    <img src="<?php echo $img_file;?>" />
                                </div>
                                <div style="line-height: 15px;font-size: 12px;">
                                	<u><?php echo $rs_last['filetitle'];?></u>
                                    <br />

									<?php if($rs_last['dept_code'] == ''){ ?>
										<b> <?php echo $rs_last['text'];?> </b>

									<?php }else{ ?>

										<b> <?php echo $rs_last['dept_code'];?> <?php echo $rs_last['dept_name'];?> </b>
									<?php } ?>
                                </div>

                                </div>
                                </a>
                            <?php }   ?>

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


function data_lastupdate(){

	$sql= "	SELECT * FROM torTest.file_upload f WHERE f.status != 0 ORDER BY f.update_date DESC  LIMIT 12 ";
	//echo $sql;
	return mysql_query($sql);
}

?>
