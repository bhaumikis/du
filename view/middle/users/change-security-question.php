<?php
if (isset($view->userDetails)) {
    extract($view->userDetails);
}
if ($_POST) {
    extract($_POST);
}
?>
<script type="text/javascript">
    $(document).ready( function(){

        $("#btn_smt").click(function() {
            $('.alert').hide();
        });

        $("#changesecurityquestion").validate({
            rules: {
                old_security_answer:{required:true},
                security_question_id:{required:true},
                security_answer:{required:true}
            },
            messages: {
                old_security_answer:{required:"<?php echo _l('Enter_Old_Security_Answer', 'change_security_question'); ?>"},
                security_question_id:{required:"<?php echo _l('Select_New_Security_Question', 'change_security_question'); ?>"},
                security_answer:{required:"<?php echo _l('Enter_New_Security_Answer', 'change_security_question'); ?>"}
            }
        });
    });
</script>
<div class="wrapper">

    <div class="container con-padding-tb">
        <form name="changesecurityquestion" id="changesecurityquestion" method="post" enctype="multipart/form-data">
            <div class="col-md-2 grids hidden-sm"></div>
            <div class="col-md-8 grids col-sm-12">
                <div class="profile-sec-head pink">
                    <div class="col-md-12 col-sm-12">
                        <span class="profile-sec-head profile-head-bg">
                            <?php if ($view->userDetails['user_image'] != "") { ?>
                                <img src="<?php echo $module_url . '/images/user_images/' . $view->userDetails['user_image']; ?>" height="120px" width="120px" alt="" />
                            <?php } else { ?>
                                <img src="" height="120px" width="120px" alt="" />
                            <?php } ?>
                        </span>
                    </div>
                </div>
                <div class="registration">
                    <?php include($module_path . "/application/global/message.php"); ?>
                    <div class="custom-form">
                        <div class="col-md-6 col-sm-12">
                            <div class="inline-form">
                                <input type="hidden" name="hid_old_sec_que" value="<?php echo $view->userDetails['security_question_id']; ?>">
                                <label class="c-label"><?php echo _l('Label_Old_Question', 'change_security_question'); ?></label>
                                <select class="form-control" name="old_security_question_id" disabled="disabled">
                                    <option value="">Select Security Question</option>
                                    <?php if (isset($view->securityquestions)) {
                                        foreach ($view->securityquestions as $questions) { ?>
                                            <option value="<?php echo $questions['security_question_id']; ?>" <?php echo ($view->userDetails['security_question_id'] == $questions['security_question_id']) ? 'selected="selected"' : ""; ?>><?php echo $questions['question']; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="inline-form">
                                <label class="c-label"><?php echo _l('Label_Old_Answer', 'change_security_question'); ?><span class="error">*</span></label>
                                <input type="password" placeholder="<?php echo _l('Placeholder_Old_Answer', 'change_security_question'); ?>" name="old_security_answer" id="old_security_answer" value="<?php echo ($_POST['old_security_answer'] != "") ? $_POST['old_security_answer'] : ""; ?>"/>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="inline-form">
                                <label class="c-label"><?php echo _l('Label_New_Question', 'change_security_question'); ?><span class="error">*</span></label>
                                <select class="form-control" name="security_question_id">
                                    <option value=""><?php echo _l('Select_Question', 'change_security_question'); ?></option>
                                    <?php if (isset($view->securityquestions)) {
                                        foreach ($view->securityquestions as $questions) { if($view->userDetails['security_question_id'] == $questions['security_question_id']) { continue;}?>
                                            <option value="<?php echo $questions['security_question_id']; ?>" <?php echo ($_POST['security_question_id'] == $questions['security_question_id']) ? 'selected="selected"' : ""; ?>><?php echo $questions['question']; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="inline-form">
                                <label class="c-label"><?php echo _l('Label_New_Answer', 'change_security_question'); ?><span class="error">*</span></label>
                                <input type="password" placeholder="<?php echo _l('Placeholder_New_Answer', 'change_security_question'); ?>" name="security_answer" id="security_answer" value="<?php echo ($_POST['security_answer'] != "") ? $_POST['security_answer'] : ""; ?>"/>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-xs-12 pro-btm-fix">
                            <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-left-0 padding-right-0 brd-right">
                                <button type="button" class="pro-btns col-sm-12 col-xs-12 padding-left-0" onclick="window.location.href = '<?php echo $module_url . "/users/my-profile"; ?>'"><?php echo _l('Button_Cancel', 'common'); ?></button>
                            </div>
                            <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-right-0 padding-left-0">
                                <input id="btn_smt" type="submit" name="submit" value="<?php echo _l('Button_Submit', 'common'); ?>" class="pro-btns pro-btns col-sm-12 col-xs-12 padding-left-0" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 grids hidden-sm">&nbsp;</div>
            </div>
        </form>
    </div>
</div>
