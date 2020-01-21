<?php
//session_start();
date_default_timezone_set("Asia/Bangkok");
include 'includes/connect.php';
include 'includes/function.php';
header('Content-Type: text/html; charset=utf-8');


//print_r($_POST);
if($_POST['form_search']){
$key=$_POST['form_search'];
$sql= "	SELECT f.fileidx,f.filetitle,f.start_date,f.filename,f.folder_name,f.filesize,f.filepath,f.version,f.filepath_encode,
		e.fname,e.lname,f.update_date FROM torTest.file_upload f
		LEFT JOIN torTest.employee e ON f.update_by = e.emref
		WHERE (f.filetitle LIKE '%{$key}%') AND f.status != 0 ORDER BY f.filetitle ASC";
$query= mysql_query($sql);
$nums = mysql_num_rows($query);

  	echo '
		<div class="span12">
					<div class="widget">
						<div class="widget-title"><h4><i class="icon-tags"></i>ค้นหาเอกสาร</h4></div>
						<div class="widget-body">

							<div class="blog_folder">';
							if($nums > 0){
								while($rs=mysql_fetch_array($query)){

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

								}
							}else{

								echo '<center><b>ไม่พบไฟล์ที่ต้องการ</b></center>';

							}

								echo '<div class="cleaner"></div>
							</div>

						</div>
					</div>
				</div>';
}


if($_GET['sticky'] == 'sticky'){
$key=$_GET['key_sticky'];

$sql= "	SELECT f.fileidx,f.filetitle,f.start_date,f.filename,f.folder_name,f.filesize,f.filepath,f.version,
		e.fname,e.lname,f.update_date,f.sticky,f.status FROM torTest.file_upload f
		LEFT JOIN torTest.employee e ON f.update_by = e.emref
		WHERE (f.filetitle LIKE '%{$key}%') AND f.status != 0 AND f.sticky = 0 ORDER BY f.filetitle ASC limit 20";
$query= mysql_query($sql);
$nums = mysql_num_rows($query);

  	echo '
		<div class="span12">
					<div class="widget">
						<div class="widget-title"><h4><i class="icon-tags"></i>ค้นหาเอกสาร</h4></div>
						<div class="widget-body">

							<div class="blog_folder">';
							if($nums > 0){
								while($rs=mysql_fetch_array($query)){

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


									echo '<a href="sticky_admin.php?action_use=confirm&fileid='.$rs['fileidx'].'">
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

								}
							}else{

								echo '<center><b>ไม่พบไฟล์ที่ต้องการ</b></center>';

							}

								echo '<div class="cleaner"></div>
							</div>

						</div>
					</div>
				</div>';
}


$post_id = explode('/',$_POST['id']);
$main = $post_id[0];
$id = $post_id[1];

if($id == 'Form'){

	$data = data_role($_COOKIE["username"]);
	$results = mysql_fetch_array($data);
	$role = $results['role_id'];

	$per = data_permiss($results['username'],$main);
	$r_per = mysql_fetch_array($per);
	$perfoldername = $r_per['foldername'];

	echo '<div class="file_content">
			<a href="javascript:;" class="link_b_form" id="เอกสาร Form">เอกสาร Form</a> > '.$_POST['id'].'

		  </div>';

		if ($role == 1){

			echo '<div style="padding-left:20px; margin-bottom: 10px;">
					<a href="addfile.php?folder=ISO9001_14001_2015&path='.$_POST['id'].'"><img src="includes/images/add.png" style="width: 15px;"> เพิ่มเอกสาร</a>

				</div>';

		}elseif($role == 2 && $perfoldername == $main){

			echo '<div style="padding-left:20px; margin-bottom: 10px;">
				<a href="addfile.php?folder=ISO9001_14001_2015&path='.$_POST['id'].'"><img src="includes/images/add.png" style="width: 15px;"> เพิ่มเอกสาร</a>

			</div>';

		}

		echo '<div class="cleaner"></div>

			<div class="blog_folder" style="padding: 15px 20px;">
			<div id="loading_form" style="display:none;">
				  <center><img src="includes/images/load.gif"/></center>
			</div>

			';
		$sql= "	SELECT f.fileidx,f.filetitle,f.start_date,f.filename,f.folder_name,f.filesize,f.filepath,f.filepath_encode,
				f.version, e.fname,e.lname,f.update_date,f.dept_code FROM torTest.file_upload f
				LEFT JOIN torTest.employee e ON f.update_by = e.emref
				WHERE f.filtorTestepath_encode = '".$_POST['id']."'  AND f.status != 0 ORDER BY f.filetitle ASC" ;
		$query= mysql_query($sql);
		while($rs=mysql_fetch_array($query)){

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
				</a>';


			if ($role == 1){

				echo '<a href="editfile.php?fileid='.$rs['fileidx'].'" title="แก้ไขเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';

			}elseif($role == 2 && $perfoldername == $main){

				echo '<a href="editfile.php?fileid='.$rs['fileidx'].'" title="แก้ไขเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';

			}

				echo '<div class="cleaner"></div>
				<div class="line_search"></div>';

			}

			echo '<div class="cleaner"></div>
		</div>';
}

if($id == 'WI'){

	$data = data_role($_COOKIE["username"]);
	$results = mysql_fetch_array($data);
	$role = $results['role_id'];

	$per = data_permiss($results['username'],$main);
	$r_per = mysql_fetch_array($per);
	$perfoldername = $r_per['foldername'];

	echo '<div class="file_content">
			<a href="javascript:;" class="link_b_wi" id="เอกสาร WI">เอกสาร WI</a> > '.$_POST['id'].'
		  </div>';

		if ($role == 1){

			echo '<div style="padding-left:20px; margin-bottom: 10px;">
					<a href="addfile.php?folder=ISO9001_14001_2015&path='.$_POST['id'].'"><img src="includes/images/add.png" style="width: 15px;"> เพิ่มเอกสาร</a>

				</div>';

		}elseif($role == 2 && $perfoldername == $main){

			echo '<div style="padding-left:20px; margin-bottom: 10px;">
				<a href="addfile.php?folder=ISO9001_14001_2015&path='.$_POST['id'].'"><img src="includes/images/add.png" style="width: 15px;"> เพิ่มเอกสาร</a>

			</div>';

		}

			echo '<div class="cleaner"></div>
			<div class="blog_folder" style="padding: 15px 20px;">
			<div id="loading_wi" style="display:none;">
				  <center><img src="includes/images/load.gif"/></center>
			</div>
			';

		$sql= "	SELECT f.fileidx,f.filetitle,f.start_date,f.filename,f.folder_name,f.filesize,f.filepath,f.filepath_encode,
				f.version,e.fname,e.lname,f.update_date FROM torTest.file_upload f
				LEFT JOIN torTest.employee e  ON f.update_by = e.emref
				WHERE f.filepath_encode = '".$_POST['id']."' AND f.status != 0 ORDER BY f.filetitle ASC" ;
		$query= mysql_query($sql);
		while($rs=mysql_fetch_array($query)){

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
				</a>';


			if ($role == 1){

				echo '<a href="editfile.php?fileid='.$rs['fileidx'].'" title="แก้ไขเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';

			}elseif($role == 2 && $perfoldername == $main){

				echo '<a href="editfile.php?fileid='.$rs['fileidx'].'" title="แก้ไขเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';

			}

				echo '<div class="cleaner"></div>
				<div class="line_search"></div>';

			}

			echo '<div class="cleaner"></div>
		</div>';
}

if($id == 'WP'){

	$data = data_role($_COOKIE["username"]);
	$results = mysql_fetch_array($data);
	$role = $results['role_id'];

	$per = data_permiss($results['username'],$main);
	$r_per = mysql_fetch_array($per);
	$perfoldername = $r_per['foldername'];

	echo '<div class="file_content">
			<a href="javascript:;" class="link_b_wp" id="เอกสาร WP">เอกสาร WP</a> > '.$_POST['id'].'
		  </div>';

			if ($role == 1){

			echo '<div style="padding-left:20px; margin-bottom: 10px;">
					<a href="addfile.php?folder=ISO9001_14001_2015&path='.$_POST['id'].'"><img src="includes/images/add.png" style="width: 15px;"> เพิ่มเอกสาร</a>

				</div>';

			}elseif($role == 2 && $perfoldername == $main){

				echo '<div style="padding-left:20px; margin-bottom: 10px;">
					<a href="addfile.php?folder=ISO9001_14001_2015&path='.$_POST['id'].'"><img src="includes/images/add.png" style="width: 15px;"> เพิ่มเอกสาร</a>

				</div>';

			}

			echo '<div class="cleaner"></div>
			<div class="blog_folder" style="padding: 15px 20px;">
			<div id="loading_wp" style="display:none;">
				  <center><img src="includes/images/load.gif"/></center>
			</div>
			';

		$sql= "	SELECT f.fileidx,f.filetitle,f.start_date,f.filename,f.folder_name,f.filesize,f.filepath,f.filepath_encode,
				f.version,e.fname,e.lname,f.update_date FROM torTest.file_upload f
				LEFT JOIN torTest.employee e ON f.update_by = e.emref
				WHERE f.filepath_encode = '".$_POST['id']."' AND f.status != 0 ORDER BY f.filetitle ASC" ;
		$query= mysql_query($sql);
		while($rs=mysql_fetch_array($query)){

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
				</a>';


			if ($role == 1){

				echo '<a href="editfile.php?fileid='.$rs['fileidx'].'" title="แก้ไขเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';

			}elseif($role == 2 && $perfoldername == $main){

				echo '<a href="editfile.php?fileid='.$rs['fileidx'].'" title="แก้ไขเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';

			}

				echo '<div class="cleaner"></div>
				<div class="line_search"></div>';

			}

			echo '<div class="cleaner"></div>
		</div>';
}


if($_GET['form'] == 'form'){

	echo '<div class="file_content">
				<a href="javascript:;" class="link_b_form" id="Form">เอกสาร Form</a>
		 </div>
		<div class="cleaner"></div>
			<div class="blog_folder">

			<div id="loading_form" style="display:none;">
				  <center><img src="includes/images/load.gif"/></center>
			</div>

			';

				$objScan = scandir("files/ISO9001_14001_2015");
					foreach ($objScan as $value) {
					  	$subvalue = substr($value,-4);
						if ($value != "." && $value != ".." && $subvalue == "Dept") {

						echo '<a href="javascript:;" class="link_form" id="'.$value.'/Form">
							<div class="folder">
								<img src="includes/images/folder.png" style="margin-bottom: 10px;">

								<div class="title_foder">'.$value.'</div>
							</div>
						</a>';

						}
					}


			echo '<div class="cleaner"></div>
		</div>';
}

if($_GET['WI'] == 'WI'){

	echo '<div style="padding-left:20px; margin-bottom: 10px;">
				<a href="javascript:;" class="link_b_wi" id="WI">เอกสาร WI</a>
		 </div>
		<div class="cleaner"></div>
			<div class="blog_folder">

				<div id="loading_wi" style="display:none;">
				  <center><img src="includes/images/load.gif"/></center>
				</div>';

				$objScan = scandir("files/ISO9001_14001_2015");
					foreach ($objScan as $value) {
					  	$subvalue = substr($value,-4);
						if ($value != "." && $value != ".." && $subvalue == "Dept") {

						echo '<a href="javascript:;" class="link_wi" id="'.$value.'/WI">
							<div class="folder">
								<img src="includes/images/folder.png" style="margin-bottom: 10px;">

								<div class="title_foder">'.$value.'</div>
							</div>
						</a>';

						}
					}


			echo '<div class="cleaner"></div>
		</div>';
}

if($_GET['WP'] == 'WP'){

	echo '<div style="padding-left:20px; margin-bottom: 10px;">
				<a href="javascript:;" class="link_b_wp" id="WP">เอกสาร WP</a>
		 </div>
		<div class="cleaner"></div>
			<div class="blog_folder">

				<div id="loading_wp" style="display:none;">
				  <center><img src="includes/images/load.gif"/></center>
				</div>';

				$objScan = scandir("files/ISO9001_14001_2015");
					foreach ($objScan as $value) {
					  	$subvalue = substr($value,-4);
						if ($value != "." && $value != ".." && $subvalue == "Dept") {

						echo '<a href="javascript:;" class="link_wp" id="'.$value.'/WP">
							<div class="folder">
								<img src="includes/images/folder.png" style="margin-bottom: 10px;">

								<div class="title_foder">'.$value.'</div>
							</div>
						</a>';

						}
					}


			echo '<div class="cleaner"></div>
		</div>';
}


if($_GET['action']=='Center'){

	$paths = $_GET['values'];
	$sql = "SELECT * FROM torTest.folder WHERE foldername = 'Center/".$paths."' ";
	$query = mysql_query($sql);
	$rs = mysql_fetch_array($query);
	$titlevalue = $rs['text'];

	$center = 'Center/'.$paths;

	$data = data_role($_COOKIE["username"]);
	$results = mysql_fetch_array($data);
	$role = $results['role_id'];

	$per = data_permiss($results['username'],'Center');
	$r_per = mysql_fetch_array($per);
	$perfoldername = $r_per['foldername'];

		echo '<div class="file_content">
			<a href="javascript:;" class="link_b_center" id="Center">Center</a> > '.$titlevalue.'
		  </div>';

		  	if ($role == 1){

			echo '<div style="padding-left:20px; margin-bottom: 10px;">
					<a href="addfile.php?folder=ISO9001_14001_2015&path='.$_POST['id'].'"><img src="includes/images/add.png" style="width: 15px;"> เพิ่มเอกสาร</a>

				</div>';

			}elseif($role == 2 && $perfoldername == 'Center'){

				echo '<div style="padding-left:20px; margin-bottom: 10px;">
					<a href="addfile.php?folder=ISO9001_14001_2015&path='.$_POST['id'].'"><img src="includes/images/add.png" style="width: 15px;"> เพิ่มเอกสาร</a>

				</div>';

			}

		  	echo '<div class="cleaner"></div>
			<div class="blog_folder" style="padding: 15px 20px;">
			<div id="loading_center" style="display:none;">
				  <center><img src="includes/images/load.gif"/></center>
			</div>';

		$sql= "	SELECT f.fileidx,f.filetitle,f.start_date,f.filename,f.folder_name,f.filesize,f.filepath,
				f.version,e.fname,e.lname,f.update_date FROM torTest.file_upload f
				LEFT JOIN torTest.employee e ON f.update_by = e.emref
				WHERE f.filepath_encode = '".$center."' AND f.status != 0 ORDER BY f.filetitle ASC" ;
		$query= mysql_query($sql);
		$nums = mysql_num_rows($query);

		if($nums <= 0){

			$objScan = scandir("files/ISO9001_14001_2015/Center/".$paths);

					foreach ($objScan as $value) {
					  	if ($value != "." && $value != "..") {

						$sql = "SELECT * FROM torTest.folder WHERE foldername = 'Center/".$paths."/".$value."' ";
						$query = mysql_query($sql);
						while($rs = mysql_fetch_array($query)){

						echo '<a href="javascript:;" class="link_sub1" id="'.$paths.'/'.$value.'">
							<div class="folder">
								<img src="includes/images/folder.png" style="margin-bottom: 10px;">

								<div class="title_foder">'.$rs['text'].'</div>
							</div>
						</a>';
						}
						}
					}

		}else{

			while($rs=mysql_fetch_array($query)){

				if(substr($rs['filename'],-3) == 'pdf'){
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
				</a>';

			if ($role == 1){

				echo '<a href="editfile.php?fileid='.$rs['fileidx'].'" title="แก้ไขเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';

			}elseif($role == 2 && $perfoldername == 'Center'){

				echo '<a href="editfile.php?fileid='.$rs['fileidx'].'" title="แก้ไขเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';

			}

				echo '<div class="cleaner"></div>
				<div class="line_search"></div>';

			}

		}

			echo '<div class="cleaner"></div>
		</div>';
}


if($_GET['action']=='sub1'){

	$paths = $_GET['values'];

	$sql = "SELECT * FROM torTest.folder WHERE foldername = 'Center/".$paths."' ";
	$query = mysql_query($sql);
	$rs = mysql_fetch_array($query);

	$sub =  explode('/',$rs['link_name']);
	$sub1 = $sub[1];
	$sub2 = $sub[2];
	$center = 'Center/'.$paths;

	$data = data_role($_COOKIE["username"]);
	$results = mysql_fetch_array($data);
	$role = $results['role_id'];

	$per = data_permiss($results['username'],'Center');
	$r_per = mysql_fetch_array($per);
	$perfoldername = $r_per['foldername'];

		echo '<div class="file_content">
			<a href="javascript:;" class="link_b_center" id="Center">Center </a> > '.$sub1.' > '.$sub2.'
		  </div>';

		  	if ($role == 1){

			echo '<div style="padding-left:20px; margin-bottom: 10px;">
					<a href="addfile.php?folder=ISO9001_14001_2015&path='.$_POST['id'].'"><img src="includes/images/add.png" style="width: 15px;"> เพิ่มเอกสาร</a>

				</div>';

			}elseif($role == 2 && $perfoldername == 'Center'){

				echo '<div style="padding-left:20px; margin-bottom: 10px;">
					<a href="addfile.php?folder=ISO9001_14001_2015&path='.$_POST['id'].'"><img src="includes/images/add.png" style="width: 15px;"> เพิ่มเอกสาร</a>

				</div>';

			}

		  	echo '<div class="cleaner"></div>
			<div class="blog_folder" style="padding: 15px 20px;">
			<div id="loading_center" style="display:none;">
				  <center><img src="includes/images/load.gif"/></center>
			</div>';

		$sql= "	SELECT f.fileidx,f.filetitle,f.start_date,f.filename,f.folder_name,f.filesize,f.filepath,
				f.version,e.fname,e.lname,f.update_date FROM torTest.file_upload f
				LEFT JOIN torTest.employee e ON f.update_by = e.emref
				WHERE f.filepath_encode = '".$center."' AND f.status != 0 ORDER BY f.filetitle ASC" ;
		$query= mysql_query($sql);
		$nums = mysql_num_rows($query);

		if($nums <= 0){


			$objScan = scandir("files/ISO9001_14001_2015/Center/".$paths);

					foreach ($objScan as $value) {
					  	if ($value != "." && $value != "..") {

						$sql = "SELECT * FROM torTest.folder WHERE foldername = 'Center/".$paths."/".$value."' ";
						$query = mysql_query($sql);
						while($rs = mysql_fetch_array($query)){

						echo '<a href="javascript:;" class="link_sub2" id="'.$paths.'/'.$value.'">
							<div class="folder">
								<img src="includes/images/folder.png" style="margin-bottom: 10px;">

								<div class="title_foder">'.$rs['text'].'</div>
							</div>
						</a>';
						}
						}
					}

		}else{

			while($rs=mysql_fetch_array($query)){

				if(substr($rs['filename'],-3) == 'pdf'){
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

				echo '<a href="geturl.php?idx_search_en='.$rs['fileidx'].'" target="_blank">
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
				</a>';

			if ($role == 1){

				echo '<a href="editfile.php?fileid='.$rs['fileidx'].'" title="แก้ไขเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';

			}elseif($role == 2 && $perfoldername == 'Center'){

				echo '<a href="editfile.php?fileid='.$rs['fileidx'].'" title="แก้ไขเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';

			}

				echo '<div class="cleaner"></div>
				<div class="line_search"></div>';

			}
		}

			echo '<div class="cleaner"></div>
		</div>';
}


if($_GET['action']=='sub2'){

	$paths = $_GET['values'];

	$sql = "SELECT * FROM torTest.folder WHERE foldername = 'Center/".$paths."' ";
	$query = mysql_query($sql);
	$rs = mysql_fetch_array($query);

	$sub =  explode('/',$rs['link_name']);
	$sub1 = $sub[1];
	$sub2 = $sub[2];
	$sub3 = $sub[3];
	$center = 'Center/'.$paths;

	$data = data_role($_SESSION["USER_TOR"]);
	$results = mysql_fetch_array($data);
	$role = $results['role_id'];

	$per = data_permiss($results['username'],'Center');
	$r_per = mysql_fetch_array($per);
	$perfoldername = $r_per['foldername'];

	echo '<div class="file_content">
			<a href="javascript:;" class="link_b_center" id="Center">Center</a> > '.$sub1.' > '.$sub2.' > '.$sub3.'
		 </div>';

		  	if ($role == 1){

			echo '<div style="padding-left:20px; margin-bottom: 10px;">
					<a href="addfile.php?folder=ISO9001_14001_2015&path='.$_POST['id'].'"><img src="includes/images/add.png" style="width: 15px;"> เพิ่มเอกสาร</a>

				</div>';

			}elseif($role == 2 && $perfoldername == 'Center'){

				echo '<div style="padding-left:20px; margin-bottom: 10px;">
					<a href="addfile.php?folder=ISO9001_14001_2015&path='.$_POST['id'].'"><img src="includes/images/add.png" style="width: 15px;"> เพิ่มเอกสาร</a>

				</div>';

			}

		  	echo '<div class="cleaner"></div>

			<div class="blog_folder" style="padding: 15px 20px;">
			<div id="loading_center" style="display:none;">
				  <center><img src="includes/images/load.gif"/></center>
			</div>';

		$sql= "	SELECT f.fileidx,f.filetitle,f.start_date,f.filename,f.folder_name,f.filesize,f.filepath,
				f.version,e.fname,e.lname,f.update_date FROM torTest.file_upload f
				LEFT JOIN torTest.employee e ON f.update_by = e.emref
				WHERE f.filepath_encode = '".$center."' AND f.status != 0 ORDER BY f.filetitle ASC" ;
		$query= mysql_query($sql);
		$nums = mysql_num_rows($query);

		if($nums <= 0){


			$objScan = scandir("files/ISO9001_14001_2015/Center/".$paths);

					foreach ($objScan as $value) {
					  	if ($value != "." && $value != "..") {

						$sql = "SELECT * FROM torTest.folder WHERE foldername = 'Center/".$paths."/".$value."' ";
						$query = mysql_query($sql);
						while($rs = mysql_fetch_array($query)){

						echo '<a href="javascript:;" class="link_sub3" id="'.$paths.'/'.$value.'">
							<div class="folder">
								<img src="includes/images/folder.png" style="margin-bottom: 10px;">

								<div class="title_foder">'.$rs['text'].'</div>
							</div>
						</a>';
						}
						}
					}

		}else{

			while($rs=mysql_fetch_array($query)){

				if(substr($rs['filename'],-3) == 'pdf'){
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

				echo '<a href="geturl.php?idx_search_en='.$rs['fileidx'].'" target="_blank">
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
				</a>';

			if ($role == 1){

				echo '<a href="editfile.php?fileid='.$rs['fileidx'].'" title="แก้ไขเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';

			}elseif($role == 2 && $perfoldername == 'Center'){

				echo '<a href="editfile.php?fileid='.$rs['fileidx'].'" title="แก้ไขเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';

			}

				echo '<div class="cleaner"></div>
				<div class="line_search"></div>';

			}
		}

			echo '<div class="cleaner"></div>
		</div>';
}

if($_GET['action']=='sub3'){

	$paths = $_GET['values'];

	$sql = "SELECT * FROM torTest.folder WHERE foldername = 'Center/".$paths."' ";
	$query = mysql_query($sql);
	$rs = mysql_fetch_array($query);

	$sub =  explode('/',$rs['link_name']);
	$sub1 = $sub[1];
	$sub2 = $sub[2];
	$sub3 = $sub[3];
	$sub4 = $sub[4];
	$center = 'Center/'.$paths;

	$data = data_role($_SESSION["USER_TOR"]);
	$results = mysql_fetch_array($data);
	$role = $results['role_id'];

	$per = data_permiss($results['username'],'Center');
	$r_per = mysql_fetch_array($per);
	$perfoldername = $r_per['foldername'];

	echo '<div class="file_content">
			<a href="javascript:;" class="link_b_center" id="Center">Center</a> > '.$sub1.' > '.$sub2.' > '.$sub3.' > '.$sub4.'
		  </div>';

		  	if ($role == 1){

			echo '<div style="padding-left:20px; margin-bottom: 10px;">
					<a href="addfile.php?folder=ISO9001_14001_2015&path='.$_POST['id'].'"><img src="includes/images/add.png" style="width: 15px;"> เพิ่มเอกสาร</a>

				</div>';

			}elseif($role == 2 && $perfoldername == 'Center'){

				echo '<div style="padding-left:20px; margin-bottom: 10px;">
					<a href="addfile.php?folder=ISO9001_14001_2015&path='.$_POST['id'].'"><img src="includes/images/add.png" style="width: 15px;"> เพิ่มเอกสาร</a>

				</div>';

			}

		  	echo '<div class="cleaner"></div>

			<div class="blog_folder" style="padding: 15px 20px;">
			<div id="loading_center" style="display:none;">
				  <center><img src="includes/images/load.gif"/></center>
			</div>';

		$sql= "	SELECT f.fileidx,f.filetitle,f.start_date,f.filename,f.folder_name,f.filesize,f.filepath,
				f.version,e.fname,e.lname,f.update_date FROM torTest.file_upload f
				LEFT JOIN torTest.employee e ON f.update_by = e.emref
				WHERE f.filepath_encode = '".$center."' AND f.status != 0 ORDER BY f.filetitle ASC" ;
		$query= mysql_query($sql);
		$nums = mysql_num_rows($query);

			while($rs=mysql_fetch_array($query)){

				if(substr($rs['filename'],-3) == 'pdf'){
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

				echo '<a href="geturl.php?idx_search_en='.$rs['fileidx'].'" target="_blank">
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
				</a>';

			if ($role == 1){

				echo '<a href="editfile.php?fileid='.$rs['fileidx'].'" title="แก้ไขเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';

			}elseif($role == 2 && $perfoldername == 'Center'){

				echo '<a href="editfile.php?fileid='.$rs['fileidx'].'" title="แก้ไขเอกสาร" style="padding-left:15px;"><img src="includes/images/edit.png" style="width: 20px; max-width: none;"> </a>';

			}

				echo '<div class="cleaner"></div>
				<div class="line_search"></div>';

			}

			echo '<div class="cleaner"></div>
		</div>';
}


if($_GET['center'] == 'center'){

	echo '<div style="padding-left:20px; margin-bottom: 10px;">
				<a href="javascript:;" class="link_b_center" id="Center">Center</a>
		 </div>
		<div class="cleaner"></div>
			<div class="blog_folder">

			<div id="loading_center" style="display:none;">
				  <center><img src="includes/images/load.gif"/></center>
			</div>

			';

				$objScan = scandir("files/ISO9001_14001_2015/Center");
					foreach ($objScan as $value) {
					  	if ($value != "." && $value != "..") {

						$sql = "SELECT * FROM torTest.folder WHERE foldername = 'Center/".$value."' ";
							$query = mysql_query($sql);
							while($rs = mysql_fetch_array($query)){
						echo '<a href="javascript:;" class="link_center" id="'.$value.'">
							<div class="folder" style="height:130px;">
								<img src="includes/images/folder.png" style="margin-bottom: 10px;">

								<div class="title_foder">'.$rs['text'].'</div>
							</div>
						</a>';
						}
						}
					}

			echo '<div class="cleaner"></div>
		</div>';
}


function data_role($username){

	$sql ="	SELECT a.username,a.role_id FROM edoc_iso.admin a
			WHERE a.username ='".$username."' AND a.user_status = '1' ";
	//echo $sql;
	return mysql_query($sql);
}

function data_permiss($username,$foldername){

	$sql ="	SELECT u.username,u.foldername
			FROM edoc_iso.user_permission u WHERE u.username ='".$username."' AND u.foldername = '".$foldername."'
			";
	//echo $sql;
	return mysql_query($sql);
}
?>

<script src="includes/js/1.111.1.jquery.min.js"></script>
<script>

	 $(function(){
            $('.link_b_form').click(function(){
				$('#loading_form').show();
                $.ajax({
			    'type': "GET",
			    'url': "get_ajax.php?form=form",
			    'data': "id=" + $(this).attr("id"),
			    'success': function(html) {
		            $('#doc_form').html(html);
					$('#loading_form').hide();
		        }

			    });
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
	/*------------------------End form------------------------*/

			$('.link_b_wi').click(function(){
				$('#loading_wi').show();
                $.ajax({
			    'type': "GET",
			    'url': "get_ajax.php?WI=WI",
			    'data': "id=" + $(this).attr("id"),
			    'success': function(html) {
		            $('#doc_wi').html(html);
					$('#loading_wi').hide();
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

	/*------------------------End wi------------------------*/

			$('.link_b_wp').click(function(){
				$('#loading_wp').show();
                $.ajax({
			    'type': "GET",
			    'url': "get_ajax.php?WP=WP",
			    'data': "id=" + $(this).attr("id"),
			    'success': function(html) {
		            $('#doc_wp').html(html);
					$('#loading_wp').hide();
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

	/*------------------------End wp------------------------*/


			$('.link_sub1').click(function(){

				$('#loading_center').show();
                $.ajax({
			    'type': "POST",
			    'url': "get_ajax.php?action=sub1&values=" + $(this).attr("id"),
			    'data': "id=" + $(this).attr("id"),
			    'success': function(html) {
		            $('#doc_center').html(html);
					$('#loading_center').hide();
		        }

			    });
            });

			$('.link_sub2').click(function(){

				$('#loading_center').show();
                $.ajax({
			    'type': "POST",
			    'url': "get_ajax.php?action=sub2&values=" + $(this).attr("id"),
			    'data': "id=" + $(this).attr("id"),
			    'success': function(html) {
		            $('#doc_center').html(html);
					$('#loading_center').hide();
		        }

			    });
            });


			$('.link_sub3').click(function(){

				$('#loading_center').show();
                $.ajax({
			    'type': "POST",
			    'url': "get_ajax.php?action=sub3&values=" + $(this).attr("id"),
			    'data': "id=" + $(this).attr("id"),
			    'success': function(html) {
		            $('#doc_center').html(html);
					$('#loading_center').hide();
		        }

			    });
            });


			$('.link_b_center').click(function(){
				$('#loading_center').show();
                $.ajax({
			    'type': "GET",
			    'url': "get_ajax.php?center=center",
			    'data': "id=" + $(this).attr("id"),
			    'success': function(html) {
		            $('#doc_center').html(html);
					$('#loading_center').hide();
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
