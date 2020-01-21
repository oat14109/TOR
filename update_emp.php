<?php
include('includes/connect_basecenter.php'); 
include('includes/connect.php');

$delete = "DELETE FROM edoc_iso.employee_test ;";
$q_del = mysql_query($delete, $edoc_iso);

$select = "SELECT * FROM base_center.employee";
$q_select = mysql_query($select, $base_center);
while($rs = mysql_fetch_array($q_select)){
	$emref = $rs["emref"];
	$title = $rs["title"];
	$fname = $rs["fname"];
	$lname = $rs["lname"];
	$dept_id = $rs["dept_id"];
	$dept_code = $rs["dept_code"];
	$pos_id = $rs["pos_id"];
	$update_date = $rs["update_date"];
	$status = $rs["status"];


$insert = "INSERT INTO edoc_iso.employee_test (emref, title, fname, lname, update_date, status) VALUES ('".$emref."', '".$title."', '".$fname."', '".$lname."', '".$update_date."', '".$status."');";
$q_insert =  mysql_query($insert, $edoc_iso);



}

//*************** update Dept_id, Dept_code, Pos_id *************//
$select_trans = "SELECT a.emref, a.dept_id, a.pos_id, b.dept_code FROM base_center.transaction a inner join base_center.department b on a.dept_id=b.dept_id where a.status=1 and a.pos_id not in ('8','12');";
$q_trans = mysql_query($select_trans, $base_center);

while($rs = mysql_fetch_array($q_trans)){
	$emref = $rs["emref"];
	$dept_id = $rs["dept_id"];
	$dept_code = $rs["dept_code"];
	$pos_id = $rs["pos_id"];
	
$update =	"update edoc_iso.employee a set dept_id =  '".$dept_id."', pos_id =  '".$pos_id."', dept_code = '".$dept_code."'
where a.emref = '".$emref."' and a.status=1;";
$q_update =  mysql_query($update, $edoc_iso);


	
}	

/*$update1 =	"update edoc_iso.employee a set dept_id = (select dept_id from transaction b where a.emref=b.emref and b.status=1 and pos_id not in ('8','12')),
pos_id = (select pos_id from transaction b where a.emref=b.emref and b.status=1  and pos_id not in ('8','12'))
where a.emref in (select distinct b.emref from transaction b where b.status=1 and pos_id not in ('8','12'))
and a.status=1;";


"update edoc_iso.employee a set dept_code = (select dept_code from department b where a.dept_id=b.dept_id )
where a.dept_id in (select distinct b.dept_id from department b )
and a.status=1 and dept_id is not null;";
*/
?>

