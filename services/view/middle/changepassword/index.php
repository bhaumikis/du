<div align="center">
  <h3>Change Password</h3>
</div>
<script type="text/javascript">
$(document).ready( function(){
	$("#changepasswordForm").validate({
		errorElement:"div",
		rules: {
		   old_password:"required",
		   password:"required",
		   rpassword:{required:true,equalTo: "#password"}
		   },
		messages: {
			old_password: "Please enter old password.",
			password: "Please enter password.",
			rpassword:{required:"Please retype password.",equalTo:"Retype password must be same as password."}
		}
	});
});	
</script>
<form id="changepasswordForm" name="changepasswordForm" action="<?php echo $module_url."/changepassword";?>" method="post" enctype="multipart/form-data">
  <div class="frm" style="width:500px;margin-left:28%;">
    <div>
      <label>Old Password :</label>
      <input type="password" class="txtbox" name="old_password" id="old_password"/>
    </div>
	<div>
      <label>Password :</label>
      <input type="password" class="txtbox" name="password" id="password"/>
    </div>
	<div>
      <label>Retype Password :</label>
      <input type="password" class="txtbox" name="rpassword" id="rpassword"/>
    </div>
    <div for="submit">
      <label>&nbsp;</label>
      <input name="submit" id="submit" value="Save" type="submit" class="button">
	  &nbsp;
	  <input name="reset" id="reset" value="Reset" type="reset" class="button">
    </div>
  </div>
</form>
<div class="pad4"></div>