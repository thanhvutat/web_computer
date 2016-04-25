<?php // Config
$codeSelect = $multiLanguage == 0 ? "code='".$_lang."_advright_top'" : "code='vn_advright_top' or code='en_advright_top'";
$tableCategoryConfig = 'tbl_adv';
$tableConfig         = 'tbl_adv';
$actConfig           = 'adv';
$arraySourceCombo    = getArrayCombo($tableCategoryConfig,'id','name',$codeSelect);
?>
<?php $errMsg =''?>
<?php switch ($_GET['action']){
	case 'del' :
		$id = $_GET['id'];
		$r = getRecord($tableConfig,"id=".$id);
		@$result = mysql_query('delete from '.$tableConfig.' where id="'.$id.'"',$conn);
		if ($result){
			if(file_exists('../'.$r['image'])) @unlink('../'.$r['image']);
			if(file_exists('../'.$r['image_large'])) @unlink('../'.$r['image_large']);
			$errMsg = 'Đã xóa thành công.';
		}else $errMsg = 'Không thể xóa dữ liệu !';
		break;
}

if (isset($_POST['btnDel'])){
	$cntDel=0;
	$cntNotDel=0;
	if($_POST['chk']!=''){
		foreach ($_POST['chk'] as $id){
			$r = getRecord($tableConfig,"id=".$id);
			@$result = mysql_query('delete from '.$tableConfig.' where id="'.$id.'"',$conn);
			if ($result){
				if(file_exists('../'.$r['image'])) @unlink('../'.$r['image']);
				if(file_exists('../'.$r['image_large'])) @unlink('../'.$r['image_large']);
				$cntDel++;
			}else $cntNotDel++;
		}
		$errMsg = 'Đã xóa '.$cntDel.' phần tử.<br><br>';
		$errMsg .= $cntNotDel>0 ? 'Không thể xóa '.$cntNotDel.' phần tử.<br>' : '';
	}else{
		$errMsg = 'Hãy chọn trước khi xóa !';
	}
}

$page = $_GET['page'];
$p=0;
if ($page!='') $p=$page;
$where = $firstWhere;
if ($_REQUEST['cat']!='') $where='parent='.$_REQUEST['cat']?>
<form method="POST" action="./" name="frmForm" enctype="multipart/form-data">
<input type="hidden" name="page" value="<?php echo $page?>">
<input type="hidden" name="act" value="<?php echo $actConfig?>">
<?php $pageindex = createPage(countRecord($tableConfig,$where),'./?act='.$actConfig.'&cat='.$_REQUEST['cat'].'&page=',$MAXPAGE,$page)?>

<?php if ($_REQUEST['code']==1) $errMsg = 'Cập nhật thành công.'?>

<table cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td height="30" class="smallfont">Trang : <?php echo $pageindex?></td>
		<td align="right" class="smallfont">
			<?php echo comboCategory('ddCat',$arraySourceCombo,'smallfont',$_REQUEST['cat'],1)?>
			<input type="button" value="Chuyển" class="button" 
				onClick="window.location='./?act=<?php echo $actConfig ?>&cat='+ddCat.value">
		</td>
	</tr>
</table>

<table border="1" cellpadding="2" bordercolor="#C9C9C9" width="100%">
	<tr>
		<th width="20" class="title"><input type="checkbox" name="chkall" onClick="chkallClick(this);"></th>
		<th class="title" colspan="2" nowrap></th>
		<th width="29" class="title"><a class="title" href="<?php echo getLinkSort(1)?>">ID</a></th>
		<th width="115" class="title"><a class="title" href="<?php echo getLinkSort(2)?>">Tên quảng cáo</a></th>
		<th width="115" class="title"><a class="title" href="<?php echo getLinkSort(2)?>">Địa chỉ trang web</a></th>
		<th width="115" class="title"><a class="title" href="<?php echo getLinkSort(2)?>">Ngày bắt đầu</a></th>
		<th width="115" class="title"><a class="title" href="<?php echo getLinkSort(2)?>">Ngày kết thúc</a></th>
		<th width="32" class="title"><a class="title" href="<?php echo getLinkSort(8)?>">Hình</a></th>
		<th width="50" class="title"><a class="title" href="<?php echo getLinkSort(11)?>">Không hiển thị</a></th>
	</tr>
  
<?php $sortby = 'order by date_added';
if ($_REQUEST['sortby']!='') $sortby='order by '.(int)$_REQUEST['sortby'];
$direction=($_REQUEST['direction']==''||$_REQUEST['direction']=='0'?'desc':'');

$sql="select * from tbl_adv limit ".($p*$MAXPAGE).",".$MAXPAGE;
$result=mysql_query($sql,$conn);
$i=0;
while($row=mysql_fetch_array($result)){
	$parent = getRecord($tableCategoryConfig,'id = '.$row['parent']);
	$color = $i++%2 ? '#d5d5d5' : '#e5e5e5'?>
  
	<tr>
		<td align="center" bgcolor="<?php echo $color?>" class="smallfont">
			<input type="checkbox" name="chk[]" value="<?php echo $row['id']?>"></td>
		<td width="25" align="center" bgcolor="<?php echo $color?>" class="smallfont">
			<a href="./?act=<?php echo $actConfig ?>_m&cat=<?php echo $_REQUEST['cat']?>&page=<?php echo $_REQUEST['page']?>&id=<?php echo $row['id']?>">Sửa</a>		</td>
		<td width="30" align="center" bgcolor="<?php echo $color?>" class="smallfont">
			<a 
				onClick="return confirm('Bạn có chắc chắn muốn xóa ?');" 
				href="./?act=<?php echo $actConfig ?>&action=del&page=<?php echo $_REQUEST['page']?>&id=<?php echo $row['id']?>"
			>Xóa</a>		</td>
		<td bgcolor="<?php echo $color?>" class="smallfont" align="center"><?php echo $row['id']?></td>
		<td bgcolor="<?php echo $color?>" class="smallfont"><?php echo $row['name']?></td>
		<td bgcolor="<?php echo $color?>" class="smallfont"><?php echo $row['webadd']?></td>
		<td bgcolor="<?php echo $color?>" class="smallfont"><?php echo $row['date_start']?></td>
		<td bgcolor="<?php echo $color?>" class="smallfont"><?php echo $row['date_stop']?></td>
		<td bgcolor="<?php echo $color?>" class="smallfont" align="center"> <img align="center" border="0" src="../<?php echo $row['image']?>"></td>
		<td bgcolor="<?php echo $color?>" class="smallfont" align="center">
			<input type="checkbox" disabled <?php echo $row['status']>0?'checked':''?>>
		</td>
	</tr>
<?php }
?>
</table>
<input type="submit" value="Xóa chọn" name="btnDel" onClick="return confirm('Bạn có chắc chắn muốn xóa ?');" class="button">
<input type="button" value="Thêm mới" name="btnNew" onClick="window.location='./?act=<?php echo $actConfig?>_m&page=<?php echo $_REQUEST['page']?>&cat=<?php echo $_REQUEST['cat']?>'" class="button">
</form>
<script language="JavaScript">
function chkallClick(o) {
  	var form = document.frmForm;
	for (var i = 0; i < form.elements.length; i++) {
		if (form.elements[i].type == "checkbox" && form.elements[i].name!="chkall") {
			form.elements[i].checked = document.frmForm.chkall.checked;
		}
	}
}
</script>
<?php if($errMsg!=''){echo '<p align=center class="err">'.$errMsg.'<br></p>';}?>

<table width="100%">
	<tr><td height="10"></td></tr>
	<tr><td class="smallfont"><?php echo 'Tổng số hàng : <b>'.countRecord($tableConfig,$firstWhere).'</b>'?></td></tr>
</table>