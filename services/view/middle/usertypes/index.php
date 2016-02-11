<div align="center">
  <h3>User types</h3>
</div>
<div align="right" style="width:95%"> <a class="crtnew" href="<?php echo $module_url."/usertypes/addedit";?>"><img src="<?php echo $module_url."/images/add.png";?>" title="Add Usertype" /></a> </div>
<div>&nbsp;</div>
<table align="center" width="90%" cellpadding="2" cellspacing="2">
  <?php include($module_path."/view/middle/miscellaneous/fields.php");?>
  <?php if(!count($view->usertypes)){ ?>
  <tr>
    <td colspan="100%" align="center"><div style="text-align: center;">No Record Found.</div></td>
  </tr>
  <?php } ?>
  <?php for($i=0;$i<count($view->usertypes);$i++){ 
			if(($i+1)%2 == 0){
				$class = "class=\"evnrw\"";
			}else{
				$class = "class=\"oddrw\"";
			}
  ?>
  <tr <?php echo $class;?>>
    <td class="gridtd"><?php echo $view->usertypes[$i]["usertype_id"];?></td>
    <td class="gridtd"><?php echo $view->usertypes[$i]["title"];?></td>
    <td class="gridtd"><?php echo $view->usertypes[$i]["description"];?></td>
	<td class="gridtd"><?php echo ($view->usertypes[$i]["updated_date"] != "0000-00-00 00:00:00") ? date(DATETIME_FORMAT,strtotime($view->usertypes[$i]["updated_date"])) : "--";?></td>
	<td class="gridtd" align="center">
		<?php if($view->usertypes[$i]["status"] == 1){ ?>
			<a href="<?php echo $module_url."/usertypes/changestatus/status/0/usertype_id/".$view->usertypes[$i]["usertype_id"];?>"><img src="<?php echo $module_url."/images"."/active.png";?>" border="0" title="Active"/></a>
		<?php }else{ ?>
			<a href="<?php echo $module_url."/usertypes/changestatus/status/1/usertype_id/".$view->usertypes[$i]["usertype_id"];?>"><img src="<?php echo $module_url."/images"."/inactive.png";?>" border="0" title="Inactive"/></a>
		<?php } ?>
	</td>
    <td class="gridtd" nowrap="nowrap"  align="center"><a class="ancrul" href="<?php echo $module_url."/usertypes/privileges/usertype_id/".$view->usertypes[$i]["usertype_id"];?>" ><img src="<?php echo $module_url."/images/privileges.png";?>" title="Privileges" /></a> <a class="ancrul" href="<?php echo $module_url."/usertypes/addedit/usertype_id/".$view->usertypes[$i]["usertype_id"];?>" ><img src="<?php echo $module_url."/images/edit_icon.png";?>" title="Edit" /></a> <a class="ancrul" href="javascript:void(0);" onclick="prompttodelete('<?php echo $module_url."/usertypes/delete/usertype_id/".$view->usertypes[$i]["usertype_id"];?>')"><img src="<?php echo $module_url."/images/delete.png";?>" title="Delete" /></a> </td>
  </tr>
  <?php } ?>
</table>
<?php include($module_path."/view/middle/miscellaneous/pagenav.php");?>
<div class="pad4"></div>
