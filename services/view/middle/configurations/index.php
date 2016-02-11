<div align="center">
  <h3>Configurations</h3>
</div>
<style>
input{
 width:280px;
}
.frm label{
	width:45%;
}
.frm div.error {
	padding-left:46%;
}
</style>
<script type="text/javascript">
$(document).ready( function(){
	$("#configurationForm").validate({
		errorElement:"div",
		rules: {
		   <?php for($i=0;$i<count($view->configurationdetails);$i++){?>
		   'configurations[<?php echo $view->configurationdetails[$i]["configuration_id"];?>]':"required"<?php if((count($view->configurationdetails)-1) != $i){?>,<?php }?>
		   <?php } ?>
		},
		messages: {
			<?php for($i=0;$i<count($view->configurationdetails);$i++){?>
			'configurations[<?php echo $view->configurationdetails[$i]["configuration_id"];?>]': "Please enter required value."<?php if((count($view->configurationdetails)-1) != $i){?>,<?php }?>
			 <?php } ?>
		}
	});
});	
</script>
<form id="configurationForm" name="configurationForm" action="<?php echo $module_url."/configurations";?>" method="post" enctype="multipart/form-data">
  <table align="center" width="90%" cellpadding="2" cellspacing="2">
    <?php include($module_path."/view/middle/miscellaneous/fields.php");?>
    <?php if(!count($view->configurationdetails)){ ?>
    <tr>
      <td colspan="100%" align="center"><div style="text-align: center;">No Record Found.</div></td>
    </tr>
    <?php } ?>
    <?php for($i=0;$i<count($view->configurationdetails);$i++){ 
  	if(($i+1)%2 == 0){
                $class = "class=\"oddrw\"";
                 $hrclass="class=\"evnrw\"";
		
	}else{
		$class = "class=\"evnrw\"";
                $hrclass="class=\"oddrw\"";
	}
    
       
	?>
		<?php if($view->configurationdetails[$i]["type"] == "combo") {?>
		<?php $opts = unserialize($view->configurationdetails[$i]["list"]); ?>
			<tr <?php echo $class;?>>
			  <td class="gridtd" align="right"><?php echo $view->configurationdetails[$i]["title"];?></td>
			  <td class="gridtd" align="center">
			  	<select  style="width:188px;" name="configurations[<?php echo $view->configurationdetails[$i]["configuration_id"];?>]" id="configurations[<?php echo $view->configurationdetails[$i]["configuration_id"];?>]">
					<?php foreach($opts as $k => $v) {?>
						<option value="<?php echo $k;?>" <?php echo ($view->configurationdetails[$i]["value"] == $k) ? "selected=\"selected\"" : "";?>><?php echo $v;?></option>
					<?php } ?>
				</select>
			  </td>
			  <td class="gridtd"><?php echo $view->configurationdetails[$i]["parameter"];?></td>
			</tr>
		<?php }else{ ?>
			<tr <?php echo $class;?>>
			  <td class="gridtd" align="right"><?php echo $view->configurationdetails[$i]["title"];?></td>
			  <td class="gridtd" align="center"><input type="text" class="txtbox" name="configurations[<?php echo $view->configurationdetails[$i]["configuration_id"];?>]" id="configurations[<?php echo $view->configurationdetails[$i]["configuration_id"];?>]" value="<?php echo $view->configurationdetails[$i]["value"];?>" title="<?php echo $view->configurationdetails[$i]["comment"];?>"/></td>
			  <td class="gridtd"><?php echo $view->configurationdetails[$i]["parameter"];?></td>
			</tr>
		<?php } if($view->configurationdetails[$i+1]["group"]!=$view->configurationdetails[$i]["group"])
        {
        ?>
    <tr><td colspan="3"><hr></hr></td></tr>
    <?php
        }
       
	?>
    <?php } ?>
  </table>
  <table align="center" width="90%" cellpadding="2" cellspacing="2">
  	<tr>
		<td align="right"><input name="submit" id="submit" value="Save" type="submit" class="button"></td>
		<td align="left"><input name="reset" id="reset" value="Reset" type="reset" class="button"></td>
	</tr>
  </table>
 
</form>
<div class="pad4"></div>
