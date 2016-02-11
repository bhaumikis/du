<?php
if(isset($view->userdetails) and !empty($view->userdetails)){
		extract($view->userdetails);
}

if($_POST){ extract($_POST); }
?>
<script type="text/javascript">
$(document).ready( function(){
	$("#userForm").validate({
		errorElement:"div",
		rules: {
		   type:"required",
		   email:{required: true,email: true},
		   first_name:"required",
		   last_name:"required",
		   <?php if($view->user_id == 0){ ?>
				password:"required",
				cpassword: {required: true,equalTo: "#password" },	
		   <?php }else{ ?>
			   cpassword: { equalTo: "#password" },
		   <?php } ?>
		   status:"required"
		},
		messages: {
			type:"Please select type.",
		    email:{required: "Please enter email.",email : "Please enter a valid email."},
			first_name:"Please enter firstname.",
			last_name:"Please enter lastname.",
			 <?php if($view->user_id == 0){ ?>
				password:"Please enter password",
				cpassword: {required: "Please enter confirm password",equalTo: "Please enter the same password as above." },	
		   <?php }else{ ?>
			   cpassword: { equalTo: "Please enter the same password as above." },
		   <?php } ?>
			
		 	status:"Please select status."
		}
	});
});	
$('.reset').click(function() {

	window.location.href = '<?php echo $module_url."/users/resetsearch";?>';
	});
</script>

<div align="center">
  <h3><?php echo ($view->user_id == 0) ? "Add" : "Edit";?> User</h3>
</div>
<form id="userForm" name="userForm" action="<?php echo $module_url."/users/addedit";?>" method="post" enctype="multipart/form-data">
  <input type="hidden" name="user_id" id="user_id" value="<?php echo $view->user_id;?>" />
  <div class="frm" style="width:450px;margin-left:30%;">
    <?php if($view->user_id){ ?>
    <div>
      <label for="user_id">User ID :</label>
      <?php echo $view->user_id;?> </div>
    <?php } ?>
	<div>
      <label for="type">Type <span class="required">*</span> :</label>
      </label>
	  <select name="usertype_id" id="usertype_id">
        <option value="">Select Usertype</option>
        <?php foreach($view->usertypes as $k => $v){ ?>
        <option value="<?php echo $k;?>" <?php echo ($usertype_id == $k) ? "selected=\"selected\"" : ""; ?>><?php echo $v;?></option>
        <?php } ?>
      </select>
    </div>
    <div>
      <label for="email">Email <span class="required">*</span> :</label>
      <input type="text" class="txtbox" name="email" id="email" value="<?php echo isset($email) ? $email : "";?>"/>
    </div>
    <div>
      <label for="password">Password :</label>
      <input type="password" name="password" id="password" value="" class="txtbox"/>
    </div>
    <div>
      <label for="cpassword">Confirm Password :</label>
      <input type="password" name="cpassword" id="cpassword" value="" class="txtbox"/>
    </div>
    <div>
      <label for="first_name">Firstname <span class="required">*</span> :</label>
      <input type="text" class="txtbox" name="first_name" id="first_name" value="<?php echo isset($first_name) ? $first_name : "";?>"/>
    </div>
    <div>
      <label for="last_name">Lastname <span class="required">*</span> :</label>
      <input type="text" class="txtbox" name="last_name" id="last_name" value="<?php echo isset($last_name) ? $last_name : "";?>"/>
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
    <div>
      <label>&nbsp;</label>
      <input name="submit" id="submit" value="Submit" type="submit" class="button">
      <input name="cancel" id="cancel" type="button" value="Cancel" onclick="window.location.href = '<?php echo $module_url."/users";?>'" class="button"/>
    </div>
  </div>
</form>
<div class="pad4"></div>
