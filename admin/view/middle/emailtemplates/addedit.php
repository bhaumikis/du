<?php
if (isset($view->emailtemplatedetails) and !empty($view->emailtemplatedetails)) {
    extract($view->emailtemplatedetails);
}
if ($_POST) {
    extract($_POST);
}
?>
<script type="text/javascript">

    $(document).ready( function(){
        $("#emailtemplatesForm").validate({
            errorElement:"div",
            rules: {
                title:{required:true},
                subject:{required:true},
                to_email:{email:true},
                from_email:{email:true}
            },
            messages: {
                title:{required:"<?php echo _l('Error_Enter_Title', 'email_templates'); ?>"},
                subject:{required:"<?php echo _l('Error_Enter_Subject', 'email_templates'); ?>"},
                to_email:{email:"<?php echo _l('Error_Enter_To_Email', 'email_templates'); ?>"},
                from_email:{email:"<?php echo _l('Error_Enter_From_Email', 'email_templates'); ?>"}
            }
        });
        $('.summernote').summernote({
            height: 200
        });
    });
</script>
<div class="wrapper">
    <div class="container con-padding-tb">

        <h3 class="StepTitle"><?php echo ($view->email_template_id == 0) ? "Add" : "Edit"; ?> <?php echo _l('Label_Email_Template', 'email_templates'); ?></h3>

        <form id="emailtemplatesForm" name="emailtemplatesForm" action="<?php echo $module_url . "/emailtemplates/addedit"; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="email_template_id" id="email_template_id" value="<?php echo $view->email_template_id; ?>" />
            <div class="">
                <div class="frm">
                    <?php if ($view->email_template_id) { ?>
                        <div>
                            <label for="email_template_id"><?php echo _l('Label_Email_Template_ID', 'email_templates'); ?> :</label>
                            <?php echo $view->email_template_id; ?> </div>
                    <?php } ?>

                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="inline-form">
                            <label for="title" class="c-label"><?php echo _l('Label_Title', 'email_templates'); ?> <span class="required">*</span>:</label>
                            <input type="text" class="txtbox input-style" name="title" id="title" value="<?php echo isset($title) ? $title : ""; ?>"/>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="inline-form">
                            <label for="name" class="c-label"><?php echo _l('Label_Name', 'email_templates'); ?> :</label>
                            <input type="text" class="txtbox" name="name" id="name" value="<?php echo isset($name) ? $name : ""; ?>" <?php
                    if (isset($view->email_template_id) and !empty($view->email_template_id)) {
                        echo "disabled=\"disabled\"";
                    }
                    ?>/>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="inline-form">
                            <label for="type" class="c-label"><?php echo _l('Label_Type', 'email_templates'); ?> :</label>
                            <select name="type" id="type">
                                <option value="0" <?php echo (isset($type) and ($type == "0")) ? "selected=\"selected\"" : ""; ?>><?php echo _l('Text_Option_Default', 'email_templates');?></option>
                                <option value="1" <?php echo (isset($type) and ($type == "1")) ? "selected=\"selected\"" : ""; ?>><?php echo _l('Text_Option_Promotional', 'email_templates');?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="inline-form">
                            <label for="type" class="c-label"><?php echo _l('Label_Query_Template', 'email_templates'); ?> :</label>
                            <select name="is_query_template" id="is_query_template">
                                <option value="0" <?php echo (isset($is_query_template) and ($is_query_template == "0")) ? "selected=\"selected\"" : ""; ?>><?php echo _l('Text_Option_No', 'email_templates');?></option>
                                <option value="1" <?php echo (isset($is_query_template) and ($is_query_template == "1")) ? "selected=\"selected\"" : ""; ?>><?php echo _l('Text_Option_Yes', 'email_templates');?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6 clearfix">
                        <div class="inline-form">
                            <label for="format" class="c-label"><?php echo _l('Label_Format', 'email_templates'); ?> :</label>
                            <select name="format" id="format">
                                <option value="text" <?php echo (isset($format) and ($format == "text")) ? "selected=\"selected\"" : ""; ?>><?php echo _l('Text_Option_Text', 'email_templates');?></option>
                                <option value="html" <?php echo (isset($format) and ($format == "html")) ? "selected=\"selected\"" : ""; ?>><?php echo _l('Text_Option_Html', 'email_templates');?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="inline-form">
                            <label for="htmltext" class="c-label padding-right-10"><?php echo _l('Label_Variables', 'email_templates'); ?> :</label>
                            <div class="padding-top-5"><?php echo isset($variables) ? $variables : ""; ?></div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="">
                            <label for="htmltext" class="c-label"><?php echo _l('Label_Html_Content', 'email_templates'); ?> :</label>
                            <div class="col-xs-12 padding-left-0 padding-right-0"><textarea class="summernote" name="htmltext" id="htmltext"><?php echo (isset($htmltext) and !empty($htmltext)) ? $htmltext : ""; ?></textarea></div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="inline-form">
                            <label for="text" class="c-label"><?php echo _l('Label_Text_Content', 'email_templates'); ?> :</label>
                            <textarea name="text" id="text" class="pull-left" style="width:100%;height:150px;"><?php echo isset($text) ? $text : ""; ?></textarea>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="inline-form">
                            <label for="to_email" class="c-label"><?php echo _l('Label_To_Email', 'email_templates'); ?> :</label>
                            <input type="text" class="txtbox" name="to_email" id="to_email" value="<?php echo isset($to_email) ? $to_email : ""; ?>"/>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="inline-form">
                            <label for="to_name" class="c-label"><?php echo _l('Label_To_Name', 'email_templates'); ?> :</label>
                            <input type="text" class="txtbox" name="to_name" id="to_name" value="<?php echo isset($to_name) ? $to_name : ""; ?>"/>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="inline-form">
                            <label for="from_email" class="c-label"><?php echo _l('Label_From_Email', 'email_templates'); ?> :</label>
                            <input type="text" class="txtbox" name="from_email" id="from_email" value="<?php echo isset($from_email) ? $from_email : ""; ?>"/>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="inline-form">
                            <label for="from_name" class="c-label"><?php echo _l('Label_From_Name', 'email_templates'); ?> :</label>
                            <input type="text" class="txtbox" name="from_name" id="from_name" value="<?php echo isset($from_name) ? $from_name : ""; ?>"/>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="inline-form">
                            <label for="subject" class="c-label"><?php echo _l('Label_Email_Subject', 'email_templates'); ?> :</label>
                            <input type="text" class="txtbox" name="subject" id="subject" value="<?php echo isset($subject) ? $subject : ""; ?>"/>
                        </div>
                    </div>
                    <div class="col-xs-12 pro-btm-fix">

                        <label class="c-label">&nbsp;</label>
                        <input name="cancel" id="cancel" type="button" value="<?php echo _l('Button_Cancel', 'common'); ?>" onclick="window.location.href = '<?php echo $module_url . "/emailtemplates"; ?>'" class="btns margin-right-10 green  sml-btn flat-btn"/>
                        <input name="submit" id="submit" value="<?php echo _l('Button_Submit', 'common'); ?>" type="submit" class="btns  green  sml-btn flat-btn" />

                    </div>
                </div>
            </div>
        </form>
        <div class="pad4"></div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#type").msDropdown();
        $("#format").msDropdown();
    })
</script>