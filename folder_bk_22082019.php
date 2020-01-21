<?php
session_start();
require_once('includes/connect.php');
include 'includes/function.php';

if (empty($_SESSION['USER_TOR'])) {
    header('Location: login.php');
    exit;
}


getheader();

$department = findDepertment($_SESSION['USER_TOR']);
$folderName = $department->dept_code." Dept/".(date("Y")+543);
//echo $folderName;
if(!checkFolder($folderName))
{
  insertFolder ("ปี ".(date("Y")+543),$folderName,$folderName,2,0);
  mkfolder ($folderName);

}
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
				 ?>

				<div class="span12 diplay_content">
					<div class="widget">
						<div class="widget-title"><h4><i class="icon-tags"></i>ไฟล์เอกสาร : <?php echo $dept_name;?></h4></div>
						<div class="widget-body">

							<div id="docs">

								<div class="blog_folder">
								<div id="loadingmessage" style="display:none;">
								  <center><img src="includes/images/load.gif"/></center>
								</div>

					<?php if($folder != ''){

							$objScan = scandir("files/TOR/".$folder);

							foreach ($objScan as $value) {


							if ($value != "." && $value != "..") {

				                $sql = "SELECT * FROM tor.folder WHERE foldername = '".$folder."/".$value."' AND parent_id != 1 ";
				                  $query = mysql_query($sql);

								while($rs = mysql_fetch_array($query)){

								$values = $rs['text'];

					?>

						<a href="#elementID" class="links" id='<?php echo $folder; ?>/<?php echo $value;?>/<?php echo $dept_name; ?>'>
							<div class="folder">
								<img src="includes/images/folder.png" style="margin-bottom: 10px;">

								<div class="title_foder"><?php echo $values;?></div>

							</div>
						</a>

					<?php } } } }?>


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






getfooter();?>
