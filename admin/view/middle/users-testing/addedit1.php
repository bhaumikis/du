<script type="text/javascript">

jQuery.validator.addMethod("token", function(token, element) {
	return this.optional(element) || token.match(<?php echo REGEXP_DEVICE_TOKEN;?>);
}, "Please enter valid device token");

$(document).ready( function(){
	$("#userForm").validate({
		errorElement:"div",
		rules: {
		   name:"required",
		   email:{required: true,email: true},
		   rpassword:{equalTo: "#password"},
		   address : {maxlength:300},
		   token : {token:true},
		   status:"required"
		},
		messages: {
			name: "Please enter name.",
		    email:{required: "Please enter email.",email : "Please enter a valid email."},
			rpassword:{equalTo: "Password mis-match."},
			address :{maxlength:"Maximum 300 characters allow."},
			token : {token:"Invalid device token. <br> e.g. c9d4c07c fbbc26d6 ef87a44d 53e16983 1096a5d5 fd825475 56659ddd f715defc"},
			status:"Please select status."
		}
	});
});	
</script>
<div align="center">
  <h3><?php echo ($user_id == 0) ? "Add" : "Edit";?> User</h3>
</div>
<form id="userForm" name="userForm" action="<?php echo $module_url."/user/addedit";?>" method="post" enctype="multipart/form-data">
  <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>" />
  <div class="frm" style="width:600px;margin-left:25%;">
    <?php if($user_id){ ?>
    <div>
      <label for="user_id">User ID :</label>
      <?php echo $user_id;?> </div>
    <?php } ?>
    <div>
      <label for="name">Name <span class="required">*</span> :</label>
      <input type="text" class="txtbox" name="name" id="name" value="<?php echo isset($name) ? $name : "";?>"/>
    </div>
    <div>
      <label for="email">Email <span class="required">*</span> :</label>
      <input type="text" class="txtbox" name="email" id="email" value="<?php echo isset($email) ? $email : "";?>"/>
    </div>
	<div>
      <label for="password">Password :</label>
      <input type="password" class="txtbox" name="password" id="password" value=""/>
    </div>
	<div>
      <label for="rpassword">Retype-Password :</label>
      <input type="password" class="txtbox" name="rpassword" id="rpassword" value=""/>
    </div>
	<div>
      <label for="address">Address :</label>
	  <textarea name="address" id="address"><?php echo isset($address) ? $address : "";?></textarea>
    </div>
    <div>
      <label for="town">Town :</label>
      <input type="text" class="txtbox" name="town" id="town" value="<?php echo isset($town) ? $town : "";?>"/>
    </div>
	<div>
      <label for="state">State :</label>
      <input type="text" class="txtbox" name="state" id="state" value="<?php echo isset($state) ? $state : "";?>"/>
    </div>
	<div>
      <label for="token">Device Token :</label>
      <input type="text" class="txtbox" name="token" id="token" value="<?php echo isset($token) ? $token : "";?>" title="Example : c9d4c07c fbbc26d6 ef87a44d 53e16983 1096a5d5 fd825475 56659ddd f715defc"/>
    </div>
   <div>
      <label for="flag1">&nbsp;</label>
      <input type="checkbox" name="flag1" id="flag1"  value="1" <?php if($flag1 == 1) { ?> checked="checked" <?php } ?> /> <span class="smltxt">To receive messages from the person who shared TenToTeachTen with you (immediate upline).</span>
    </div>
	<div>
      <label for="flag2">&nbsp;</label>
      <input type="checkbox" name="flag2" id="flag2" value="1" <?php if($flag2 == 1) { ?> checked="checked" <?php } ?>/> <span class="smltxt">To receive messages from your upline's upline (upline).</span>
    </div>
	<div>
      <label for="flag3">&nbsp;</label>
      <input type="checkbox" name="flag3" id="flag3" value="1" <?php if($flag3 == 1) { ?> checked="checked" <?php } ?>/> <span class="smltxt">To receive messages from TenToTeachTen.com</span>
    </div>
	<div>
      <label for="flag4">&nbsp;</label>
      <input type="checkbox" name="flag4" id="flag4" value="1" <?php if($flag4 == 1) { ?> checked="checked" <?php } ?>/> <span class="smltxt">People upline to see your name in their impact page (upline).</span>
    </div>
	<div>
      <label for="flag5">&nbsp;</label>
      <input type="checkbox" name="flag5" id="flag5" value="1" <?php if($flag5 == 1) { ?> checked="checked" <?php } ?>/> <span class="smltxt">Your name visible to people you share TenToTeachTen with on their impact page (immediate downline)</span>
    </div>
	<div>
      <label for="flag6">&nbsp;</label>
      <input type="checkbox" name="flag6" id="flag6" value="1" <?php if($flag6 == 1) { ?> checked="checked" <?php } ?>/> <span class="smltxt">Your name visible to people your downline shares with (downline).</span>
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
      <input name="submit" id="submit" value="<?php echo ($user_id == 0) ? "Add" : "Update";?>" type="submit" class="button">
      <input name="cancel" id="cancel" type="button" value="Cancel" onclick="window.location.href = '<?php echo $module_url."/user";?>'" class="button"/>
    </div>
  </div>
</form>
<div class="pad4"></div>