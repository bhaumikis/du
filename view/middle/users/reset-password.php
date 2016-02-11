<script type="text/javascript">
    $(document).ready( function(){

        $("#resetpassword").validate({
            rules: {
                password:{required:true,regex:true},
                cpassword:{required:true,equalTo: "#password"}
            },
            messages: {
                password:{required:"<?php echo _l('Enter_Password', 'reset_password'); ?>"},
                cpassword:{required:"<?php echo _l('Confirm_Password', 'reset_password'); ?>",equalTo:"<?php echo _l('Password_Confirm_Password_Same', 'reset_password'); ?>"}
            }
        });
        $.validator.addMethod('regex', function (value) {
            return /\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\W])(?=\S*[\d])\S*/.test(value);
        }, '<?php echo _l('Invalid_Password_String', 'reset_password'); ?>');


    });
</script>
<div class="pass-reset-head black">
    <div class="sign-in-details">
        <h1><?php echo _l('Label_Reset_Password', 'reset_password'); ?><i class="fa fa-key"></i></h1>
    </div>
    <div class="log-in-thumb"> <img src="<?php echo $module_url; ?>/images/sign-in.jpg" alt="" /> </div>
</div>
<div class="f-pass-form">
    <div class="custom-form"> <span class="sq"><?php echo _l('Label_New_Password', 'reset_password'); ?></span>
        <form name="resetpassword" id="resetpassword" method="post">
            <input type="password" placeholder="<?php echo _l('Placeholder_Password', 'reset_password'); ?>" name="password" id="password"/>

            <input type="password" placeholder="<?php echo _l('Placeholder_CPassword', 'reset_password'); ?>" name="cpassword" id="cpassword" class="margin-top-15"/>

            <input type="submit" name="submit" value="<?php echo _l('Button_Submit', 'common'); ?>" class="f-pass-btn margin-top-15">

            <button class="f-cancel-btn" onclick="window.location.href = '<?php echo $module_url . "/index/logout"; ?>'"><?php echo _l('Button_Cancel', 'common'); ?></button>
        </form>
    </div>
</div>