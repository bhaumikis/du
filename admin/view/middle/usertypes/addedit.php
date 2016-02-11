<?php
if(isset($view->usertypedetails) and !empty($view->usertypedetails)){
		extract($view->usertypedetails);
}

if($_POST){ extract($_POST); }
?>
<script type="text/javascript">
$(document).ready( function(){
	$("#usertypeForm").validate({
		errorElement:"div",
		rules: {
		   title:"required",
		   status:"required"
		},
		messages: {
			title: "Please enter title.",
			status:"Please select status."
		}
	});
});	
</script>
<div align="center">
  <h3><?php echo ($view->usertype_id == 0) ? "Add" : "Edit";?> Usertype</h3>
</div>
<form id="usertypeForm" name="usertypeForm" action="<?php echo $module_url."/usertypes/addedit";?>" method="post" enctype="multipart/form-data">
  <input type="hidden" name="usertype_id" id="usertype_id" value="<?php echo $view->usertype_id;?>" />
  <div class="frm" style="width:500px;margin-left:32%;">
    <?php if($view->usertype_id){ ?>
    <div>
      <label for="usertype_id">Usertype ID :</label>
      <?php echo $view->usertype_id;?> </div>
    <?php } ?>
    <div>
      <label for="title">Title <span class="required">*</span> :</label>
      <input type="text" class="txtbox" name="title" id="title" value="<?php echo isset($title) ? $title : "";?>"/>
    </div>
    <div>
      <label for="description">Description :</label>
      <textarea name="description" id="description"><?php echo isset($description) ? $description : "";?></textarea>
    </div>
    <div>
      <label for="status">Status <span class="required">*</span> :</label>
      </label>
      <select name="status" id="status">
        <option value="">Select Status</option>
        <option value="1" <?php echo (isset($status) and ($status == "1")) ? "selected=\"selected\"" : "";?>>Active</option>
        <option value="0" <?php echo (isset($status) and ($status == "0")) ? "selected=\"selected\"" : "";?>>Inactive</option>
      </select>
    </div>
    <div for="submit">
      <label>&nbsp;</label>
      <input name="submit" id="submit" value="<?php echo ($usertype_id == 0) ? "Add" : "Update";?>" type="submit" class="button">
      <input name="cancel" id="cancel" type="button" value="Cancel" onclick="window.location.href = '<?php echo $module_url."/usertypes";?>'" class="button"/>
    </div>
  </div>
</form>
<div class="pad4"></div>