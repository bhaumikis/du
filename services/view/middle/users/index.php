
<div align="center">
  <h3>Users</h3>
</div>
<div>&nbsp;</div>
<div align="right" style="width:95%"> 
	<a class="crtnew" href="<?php echo $module_url."/users/addedit";?>"><img src="<?php echo $module_url."/images/add.png";?>" title="Add User" /></a> 
</div>
<div>&nbsp;</div>

<table align="center" width="90%" cellpadding="2" cellspacing="2">
  <?php include($module_path."/view/middle/miscellaneous/fields.php");?>
  <?php if(!count($view->users)){ ?>
  <tr>
    <td colspan="100%" align="center"><div style="text-align: center;">No Record Found.</div></td>
  </tr>
  <?php } ?>
  <?php for($i=0;$i<count($view->users);$i++){ 
  	if(($i+1)%2 == 0){
		$class = "class=\"evnrw\"";
	}else{
		$class = "class=\"oddrw\"";
	}
  ?>
  <tr <?php echo $class;?>>
    <td class="gridtd"><?php echo $view->users[$i]["user_id"];?></td>
    <td class="gridtd"><?php echo $view->users[$i]["first_name"]." ".$view->users[$i]["last_name"];?></td>
    <td class="gridtd"><?php echo $view->users[$i]["email"];?></td>
	<?php 
	if($view->users[$i]["usertype_id"]==2)
	{
		$myusertype='Attendee';
	}
	else if($view->users[$i]["usertype_id"]==1)
	{
		$myusertype='Super Administrator';
	}
	else if($view->users[$i]["usertype_id"]==3)
	{
		$myusertype='Manager';
	}
	?>
    <td class="gridtd"><?php echo $myusertype;?></td>
    <!--<td class="gridtd"><?php echo $view->users[$i]["designation"];?></td>-->
    <td class="gridtd"><?php echo date(DATETIME_FORMAT,strtotime($view->users[$i]["created_date"]));?></td>
    <td class="gridtd"><?php echo date(DATETIME_FORMAT,strtotime($view->users[$i]["updated_date"]));?></td>
    <td class="gridtd" align="center"><?php if($view->users[$i]["status"] == 1){ ?>
      <a href="<?php echo $module_url."/users/changestatus/status/0/user_id/".$view->users[$i]["user_id"];?>"><img src="<?php echo $module_url."/images"."/active.png";?>" border="0" title="Active"/></a>
      <?php }else{ ?>
      <a href="<?php echo $module_url."/users/changestatus/status/1/user_id/".$view->users[$i]["user_id"];?>"><img src="<?php echo $module_url."/images"."/inactive.png";?>" border="0" title="Inactive"/></a>
      <?php } ?>
    </td>
    <td class="gridtd" nowrap="nowrap" width="200"  align="center">
      <a class="ancrul" href="<?php echo $module_url."/users/addedit/user_id/".$view->users[$i]["user_id"];?>" ><img src="<?php echo $module_url."/images/edit_icon.png";?>" title="Edit" /></a> <a class="ancrul" href="javascript:void(0);" onclick="prompttodelete('<?php echo $module_url."/users/delete/user_id/".$view->users[$i]["user_id"];?>')"><img src="<?php echo $module_url."/images/delete.png";?>" title="Delete" /></a>
     
    </td>
  </tr>
  <?php } ?>
</table>
<?php include($module_path."/view/middle/miscellaneous/pagenav.php");?>
<div class="pad4"></div>
