<div align="center">
  <h3>Privileges</h3>
</div>
<style>
.frm label{
	width:45%;
}
</style>
<?php if($_GET["usertype_id"] == 1) { ?>
<div style="width:650px;margin-left:19%;"> &nbsp; </div>
<div class="gridtd" style="margin-left:42%;">Super Administrator has all privileges. </div>
<?php } else { ?> 
<div style="width:650px;margin-left:19%;"> &nbsp; </div>
<div style="width:650px; margin-left:19%;">
  <form id="privilegeForm" name="privilegeForm" action="<?php echo $module_url."/privileges";?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="usertype_id" value="<?php echo $_GET["usertype_id"];?>" />
    <fieldset>
    <legend><strong><?php echo $usertype["title"];?></strong></legend>
	<?php
		$selected_resources_admin = $database->selectData("privileges","usertype_id = '".$_GET["usertype_id"]."'");
		
		$tmp_selected_resources_admin = array();
		foreach($selected_resources_admin as $k => $v){
			$tmp_selected_resources_admin[] = $v["resource_id"];
		}
	?>
      <div style="float:left; width:100%;">
	  	<div style="width:500px;"> &nbsp; </div>
        <?php for($i=0;$i<count($resources);$i++) {?>
			<div style="padding:3px;float:left;width:30px; text-align:right ">
				<input type="checkbox" name="resource_id[<?php echo $i;?>]" id="resource_id[<?php echo $i;?>]" value="<?php echo $resources[$i]["resource_id"];?>" <?php echo (in_array($resources[$i]["resource_id"],$tmp_selected_resources_admin)) ? "checked=\"checked\"" : "";?>/>
			</div>
			<div style="padding:3px;float:left;width:275px;">
				<label for="resource_id[<?php echo $i;?>]" style="font-weight:normal;"><?php echo $resources[$i]["title"];?></label>
			</div>
        <?php if(($i+1) % 2 == 0) {?>
      	</div>
		<div style="clear:both;"></div>
      	<div style="float:left;">
        <?php } ?>
        <?php } ?>
		<div style="width:650px;"> &nbsp; </div>
		<div style="width:355px; text-align:right;"> <input name="submit" id="submit" value="Save" type="submit" class="button"></div>
		<div style="width:650px;"> &nbsp; </div>
		</div>
    </fieldset>
  </form>
</div>
<?php } ?>
<div class="pad4"></div>
