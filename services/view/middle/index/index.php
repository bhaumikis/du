<script type="text/javascript">
    $(document).ready(function() {
        $("#loginForm").validate({
            errorElement : "div",
            rules:{
                email : {
                    required :true
                },		  
                password:{
                    required:true
                }
            },
	
            messages : {
                email : {
                    required : "Please enter username."
                },
                password:{
                    required : "Please enter password."
                }
            }
        });
    });
</script>

<form id="loginForm" name="loginForm" action="<?php echo $module_url . "/index"; ?>" method="post">
    <div class="frm" id="myBox" style="width:331px;margin:10% 36%;">
        <div>
            <label for="email">Email :</label>
            <input type="text" name="email" id="email" class="txtbox">
        </div>
        <div>
            <label for="password">Password :</label>
            <input type="password" name="password" id="password" class="txtbox"/>
        </div>
        <div for="submit">
            <label>&nbsp;</label>
            <span class="lgnbtn"><input type="submit" name="submit" id="submit" class="button" value="Login" border="0"/></span>
        </div>
    </div>
</form>
