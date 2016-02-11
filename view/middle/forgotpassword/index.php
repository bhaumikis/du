<?php
if ($_POST) {
    extract($_POST);
}
?>

<div class="sign-in-head black">
    <div class="logo"></div>


</div>
<div class="f-pass-form">
    <div class="custom-form"> <span class="sq"><?php echo _l('Label_Security_Question', 'forgot_password'); ?></span>
        <form name="forgotpassword" id="forgotpassword" method="post">
            <div class="sec">
                <select class="form-control" name="security_question_id" id="security_question_id">
                    <option value=""><?php echo _l('Select_Security_Question', 'forgot_password'); ?></option>
                    <?php if (isset($view->securityquestions)) {
                        foreach ($view->securityquestions as $questions) { ?>
                            <option value="<?php echo $questions['security_question_id']; ?>" ><?php echo $questions['question']; ?></option>
                        <?php }
                    } ?>
                </select>
            </div>
            <input type="password" placeholder="<?php echo _l('Placeholder_Answer', 'forgot_password'); ?>" name="security_answer" id="security_answer" value=""/>
            <span class="f-pass-text"><?php echo _l('Text_Help', 'forgot_password'); ?></span>
            <input class="mygroup" type="text" placeholder="<?php echo _l('Placeholder_Mobile', 'forgot_password'); ?>" name="mobile_number" id="mobile_number" value=""/>

            <span class="or-divider"><span class="or-text"><?php echo _l('Text_OR', 'forgot_password'); ?></span></span>

            <input class="mygroup" type="text" placeholder="<?php echo _l('Placeholder_Email', 'forgot_password'); ?>" name="email" id="email" value=""/>

            <input type="submit" name="submit" value="<?php echo _l('Button_Send', 'forgot_password'); ?>" class="f-pass-btn btnSubmit">

            <button type="button" class="btns f-pass-btn lrg-btn" onclick="window.location.href = '<?php echo $module_url; ?>'"><?php echo _l('Button_Cancel', 'forgot_password'); ?></button>

        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready( function(){
        $("#security_question_id").msDropdown();
        $("#forgotpassword").validate({
            rules: {
                mobile_number: {
                    require_from_group: [1, '.mygroup'],digits: true
                },
                email: {
                    require_from_group: [1, '.mygroup'],email: true
                },
                security_question_id:{required:true},
                security_answer:{required:true}
            },
            messages: {
                security_question_id:{required:"<?php echo _l('Select_Question', 'forgot_password'); ?>"},
                security_answer:{required:"<?php echo _l('Enter_Answer', 'forgot_password'); ?>"},
                mobile_number: {digits: "<?php echo _l("Please enter a valid mobile number.", "users"); ?>"},
                email: {email: "<?php echo _l("Please enter a valid email.", "users"); ?>"}
            }
        });
        $(".btnSubmit").click(function() {
            getMSDrowpdownValidate('forgotpassword','security_question_id','<?php echo _l('Select_Question', 'forgot_password'); ?>');
        });

    });
</script>
<script type="text/javascript">
    $('#mobile_number').on('blur',function() {

        if($('#email').val().trim() === "") {
            $('#email').prop("readonly", true);
        } else {
            $('#mobile_number').prop("readonly", true);
        }

        if($('#mobile_number').val().trim() === "") {
            $('#email').prop("readonly", false);
        }
    });

    $('#email').on('blur',function() {

        if($('#mobile_number').val().trim() === "") {
            $('#mobile_number').prop("readonly", true);
        } else {
            $('#email').prop("readonly", true);
        }

        if($('#email').val().trim() === "") {
            $('#mobile_number').prop("readonly", false);
        }
    });
</script>