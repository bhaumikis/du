<?php
if ($_POST) {
    extract($_POST);
}
?>
<script>
    $(document).ready(function() {

        $("#addsecurityquestion").validate({
            errorElement:"div",
            rules: {
                question:{required:true}
            },
            messages: {
                question:{required:"<?php echo _l('Error_Enter_Question', 'change_security_question'); ?>"}
            }
        });
    });
</script>
<form name="addsecurityquestion" id="addsecurityquestion" method="post" enctype="multipart/form-data">
    <div class="wrapper">
        <div class="container con-padding-tb">

            <div class="margin-right-5">
                <?php include(APPLICATION_PATH . "/application/global/message.php"); ?>
                <div id="step-1">
                    <h2 class="StepTitle"><?php echo _l('Text_Add_Question', 'change_security_question'); ?></h2>
                    <div class="col-md-6">
                        <div class="inline-form">
                            <label class="c-label"><?php echo _l('Label_Question', 'change_security_question'); ?></label>
                            <input class="input-style" type="text" placeholder="Question" name="question" id="question" value="<?php
                echo (isset($_POST['question']) and !empty($_POST['question'])) ? $_POST['question'] : "";
                ?>"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="inline-form">
                            <label class="c-label"><?php echo _l('Label_Select_Status', 'change_security_question'); ?></label>
                            <div class="sec">
                                <select class="form-control" name="status" id="status">
                                    <option value="1"><?php echo _l('Text_Active', 'common'); ?></option>
                                    <option value="0"><?php echo _l('Text_Inactive', 'common'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12 pro-btm-fix">
                        <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-left-0 padding-right-0 brd-right">
                            <button onclick="window.location.href = '<?php echo $module_url . "/security-questions"; ?>'" class="pro-btns col-sm-12 col-xs-12 padding-left-0" type="button"><?php echo _l('Button_Cancel', 'common'); ?></button>
                        </div>
                        <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-right-0 padding-left-0">
                            <input type="submit" class="pro-btns pro-btns col-sm-12 col-xs-12 padding-left-0" value="<?php echo _l('Button_Save', 'common'); ?>" name="submit">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#status").msDropdown();
    })
</script>