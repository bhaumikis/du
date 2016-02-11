<div align="center">
  <h3>Email Templates</h3>
</div>
<div>&nbsp;</div>
<table width="90%" cellpadding="2"  align="center" cellspacing="2">
  <?php include($module_path."/view/middle/miscellaneous/fields.php");?>
  <?php if(!count($view->emailtemplates)){ ?>
  <tr>
    <td colspan="100%" align="center"><div style="text-align: center;">No Record Found.</div></td>
  </tr>
  <?php } ?>
  <?php for($i=0;$i<count($view->emailtemplates);$i++){ 
  if(($i+1)%2 == 0){
		$class = "class=\"evnrw\"";
	}else{
		$class = "class=\"oddrw\"";
	}
  ?>
  <tr <?php echo $class;?>>
    <td class="gridtd"><?php echo $view->emailtemplates[$i]["email_template_id"];?></td>
	<td class="gridtd"><?php echo $view->emailtemplates[$i]["title"];?></td>
	<td class="gridtd"><?php echo $view->emailtemplates[$i]["name"];?></td>
	<td class="gridtd"><?php echo date(DATETIME_FORMAT,strtotime($view->emailtemplates[$i]["created_date"]));?></td>
	<td class="gridtd"><?php echo date(DATETIME_FORMAT,strtotime($view->emailtemplates[$i]["updated_date"]));?></td>
	<td class="gridtd"><a class="ancrul" href="<?php echo $module_url."/emailtemplates/addedit/email_template_id/".$view->emailtemplates[$i]["email_template_id"];?>" ><img src="<?php echo $module_url."/images/edit_icon.png";?>" title="Edit" /></a></td>
  </tr>
  <?php } ?>
</table>
<?php include($module_path."/view/middle/miscellaneous/pagenav.php");?>
<div class="pad4"></div>