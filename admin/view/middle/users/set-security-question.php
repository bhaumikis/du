<?php
if ($_POST) {
    extract($_POST);
}
?>
<script type="text/javascript">
    $(document).ready( function() {
        $("#setsecurityquestion").validate({
            rules: {
                security_question_id:{required:true},
                security_answer:{required:true}
            },
            messages: {
                security_question_id:{required:"<?php echo _l('Select_Security_Question', 'administrators'); ?>"},
                security_answer:{required:"<?php echo _l('Enter_Security_Answer', 'administrators'); ?>"}
            }
        });
        $(".btnSubmit").click(function() {            
            getMSDrowpdownValidate('setsecurityquestion','security_question_id','<?php echo _l('Select_Security_Question', 'administrators'); ?>');
        });
    });
</script>
<div class="wrapper">

    <div class="container con-padding-tb">
        <form name="setsecurityquestion" id="setsecurityquestion" method="post" enctype="multipart/form-data">
            <div class="col-md-2 grids hidden-sm"></div>
            <div class="col-md-8 grids col-sm-12">
                <div class="profile-sec-head pink">
                    <div class="col-md-12 col-sm-12"> <span class="pic-profile"> </span> </div>
                </div>
                <div class="registration">
                    <?php include(APPLICATION_PATH . "/application/global/message.php"); ?>
                    <div class="custom-form">
                        <div class="col-md-6 col-sm-12">
                            <div class="inline-form">
                                <label class="c-label"><?php echo _l('Label_Question', 'administrators'); ?>*</label>
                                <select class="form-control" name="security_question_id" id="security_question_id">
                                    <option value=""><?php echo _l('Label_Select_Question', 'administrators'); ?></option>
                                    <?php if (isset($view->securityquestions)) {
                                        foreach ($view->securityquestions as $questions) { ?>
                                            <option value="<?php echo $questions['security_question_id']; ?>" <?php echo ($_POST['security_question_id'] == $questions['security_question_id']) ? 'selected="selected"' : ""; ?>><?php echo $questions['question']; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="inline-form">
                                <label class="c-label"><?php echo _l('Label_Answer', 'administrators'); ?></label>
                                <input type="password" placeholder="<?php echo _l('Placeholder_Answer', 'administrators'); ?>" name="security_answer" id="security_answer" value="<?php echo ($_POST['security_answer'] != "") ? $_POST['security_answer'] : ""; ?>"/>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-xs-12 pro-btm-fix">
                            <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-left-0 padding-right-0 brd-right">
                                <button type="button" class="pro-btns col-sm-12 col-xs-12 padding-left-0" onclick="window.location.href = '<?php echo $module_url . "/users/set-security-question"; ?>'"><?php echo _l('Button_Cancel', 'common'); ?></button>
                            </div>
                            <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-right-0 padding-left-0">
                                <input type="submit" name="submit" value="<?php echo _l('Button_Submit', 'common'); ?>" class="pro-btns pro-btns col-sm-12 col-xs-12 padding-left-0 btnSubmit" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 grids hidden-sm">&nbsp;</div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#security_question_id").msDropdown();
    })
</script>