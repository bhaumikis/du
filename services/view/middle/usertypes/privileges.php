<div align="center">
  <h3>Privileges - <?php echo $view->usertype["title"];?></h3>
</div>
<style>
.frm label{
	width:45%;
}
</style>
<?php 
if($_GET["usertype_id"] == 1) { ?>
<div style="width:650px;margin-left:19%;"> &nbsp; </div>
<div class="gridtd" style="margin-left:42%;">Super Administrator has all privileges. </div>
<div style="clear:both;"></div>
<div style="width:650px;margin-left:19%;"> &nbsp; </div>
<div style="width:630px; text-align:right;">
  <input name="cancel" id="cancel" type="button" value="Cancel" onclick="window.location.href = '<?php echo $module_url."/usertypes";?>'" class="button"/>
</div>
<?php } else { ?>
<div style="width:650px;margin-left:19%;"> &nbsp; </div>
<div style="width:100%;">
  <form id="privilegeForm" name="privilegeForm" action="<?php echo $module_url."/usertypes/privileges";?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="usertype_id" value="<?php echo $_GET["usertype_id"];?>" />
    
   
    <div style="float:left; width:100%;">
      <div style="width:500px;"> &nbsp; </div>
      <table width="90%" cellpadding="2"  align="center" cellspacing="2">
    <?php include($module_path . "/view/middle/miscellaneous/fields.php"); ?>
    <?php if (!count($view->resources)) { ?>
        <tr>
            <td colspan="100%" align="center"><div style="text-align: center;">No Record Found.</div></td>
        </tr>
    <?php } ?>
    <?php
    for ($i = 0; $i < count($view->resources); $i++) {
        if (($i + 1) % 2 == 0) {
            $class = "class=\"evnrw\"";
        } else {
            $class = "class=\"oddrw\"";
        }
        ?>
        <tr <?php echo $class; ?>>
            <td class="gridtd" align="center"><input type="checkbox" name="chk[]" id="chk" value="<?php echo $view->resources[$i]["resource_id"]; ?>" <?php echo (isset($view->tmp_selected_resources_admin) and in_array($view->resources[$i]["resource_id"],$view->tmp_selected_resources_admin)) ? "checked=\"checked\"" : "";?>/></td>
            <td class="gridtd"><?php echo $view->resources[$i]["title"]; ?></td>
            <td class="gridtd"><?php echo $view->resources[$i]["module"]; ?></td>
            <td class="gridtd"><?php echo $view->resources[$i]["option"]; ?></td>
            <td class="gridtd"><?php echo $view->resources[$i]["action"]; ?></td>
            
        </tr>
<?php } ?>
</table>
      <div style="width:650px;"> &nbsp; </div>
      <div style="clear:both;"></div>
      <div style="width:390px; text-align:right;">
        <input name="submit" id="submit" value="Save" type="submit" class="button">
        <input name="cancel" id="cancel" type="button" value="Cancel" onclick="window.location.href = '<?php echo $module_url."/usertypes";?>'" class="button"/>
      </div>
      <div style="width:650px;"> &nbsp; </div>
    </div>

  </form>
</div>
<?php } ?>
<div class="pad4"></div>
