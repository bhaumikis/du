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
        $("#sendmail").validate({
            errorElement:"div",
            rules: {
                sendto:"required",
                subject:"required"
            },
            messages: {
                sendto:"<?php echo _l('Enter_Sendto_Email', 'email_templates'); ?>",
                subject:"<?php echo _l('Enter_Send_Subject', 'email_templates'); ?>"
            },
            submitHandler: function() {
                $("input[type=submit]").attr("disabled", "disabled");
                $("input[type=submit]").css("cursor", "default");
                $("#sendmail").submit();
            },
            invalidHandler: function() {
                $("input[type=submit]").removeAttr("disabled");
            }
        });
        $('.summernote').summernote({
            height: 200
        });
    });
</script>

<form name="sendmail" id="sendmail" method="post">
    <input type="hidden" name="hid_email_template_id" id="hid_email_template_id" value="<?php echo $view->email_template_id;?>" />
    <div class="wrapper">
        <div class="container con-padding-tb">
            <!--add user start-->
            <div class="col-xs-12">
                <div class="wizard-form-h">
                    <div id="wizard" class="swMain">
                        <div id="step-1">
                            <h2 class="StepTitle"><?php echo _l('Label_Send_Mail', 'email_templates'); ?></h2>
                            <div class="col-xs-12">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_To_List', 'email_templates'); ?></label>
                                    <div class="input-group clearfix">
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><?php echo _l('Label_To', 'email_templates'); ?> <span class="caret"></span></button>
                                            <ul class="dropdown-menu" role="menu">
                                                <?php if($obj->checkLoggedInAsSuperAdmin()) { ?>
                                                <li><a href="#" data-toggle="modal" id="3" usertype="admin" class="openBtn"><?php echo _l('Label_Admin', 'email_templates'); ?></a></li>
                                                <li class="divider"></li>
                                                <?php } ?>
                                                <li><a href="#" data-toggle="modal" id="2" usertype="user" class="openBtn"><?php echo _l('Label_User', 'email_templates'); ?></a></li>
                                            </ul>
                                        </div>
                                        <input type="text" name="sendto" id="sendto" class="form-control" readonly="readonly">
                                        <input type="hidden" name="sendtoids" id="sendtoids" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Subject', 'email_templates'); ?></label>
                                    <input class="input-style" type="text" name="subject" id="subject" placeholder="<?php echo _l('Label_Subject', 'email_templates'); ?>" value="<?php echo $subject; ?>" />
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="">
                                    <label class="c-label"><?php echo _l('Label_Message', 'email_templates'); ?></label>
                                    <div class="col-xs-12"><textarea class="summernote" name="htmltext" id="htmltext"><?php echo (isset($htmltext) and !empty($htmltext)) ? $htmltext : "";?></textarea></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 pro-btm-fix">
                        <div class="margin-top-15 padding-left-0 margin-right-10 pull-left">
                            <input type="submit" name="submit" class="btns  green  sml-btn flat-btn" value="<?php echo _l('Button_Send_Mail', 'common'); ?>" />
                        </div>
                        <div class="margin-top-15 padding-left-0 margin-right-10 pull-left">
                            <input name="cancel" id="cancel" type="button" value="<?php echo _l('Button_Cancel', 'common'); ?>" onclick="window.location.href = '<?php echo $module_url . "/emailtemplates"; ?>'" class="btns  green  sml-btn flat-btn" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalSendMail" tabindex="-1" role="dialog" aria-labelledby="myModalSendMailLabel" aria-hidden="true">
        <div class="modal-dialog col-md-8 padding-left-0 padding-right-0">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo _l('Label_Close', 'common'); ?></span></button>
                    <h4 class="modal-title" id="myModalSendMailLabel"></h4>
                </div>
                <div class="send-mail-modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btns-bg white" data-dismiss="modal"><?php echo _l('Button_Close', 'common'); ?></button>
                    <button type="button" class="btn btns-bg white" id="btnsave"><?php echo _l('Button_Select', 'common'); ?></button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('.openBtn').click(function() {
            var usertypeid = this.id;
            $('#myModalSendMail').on('show.bs.modal', function() {
                $('#myModalSendMailLabel').html('<?php echo _l('Text_Select_User', 'email_templates'); ?>');
                $('.send-mail-modal-body').html('<iframe id="iframe_container" src="<?php echo $module_url; ?>/emailtemplates/user-list/usertypeid/'+usertypeid+'"  height="350" width="100%" frameborder="0"></iframe>');
            });
            $('#myModalSendMail').modal()
        });
        $("#btnsave").click(function() {
            var myArray = [];
            var myArrayIds = [];
            //alert($("iframe[id='iframe_container']").contents().find(".uemails").size());
            $($("iframe[id='iframe_container']").contents().find(".uemails")).each(function(){
                if($(this).is(":checked")) {
                    if($(this).val() !== "") {
                        var res = $(this).val().split("~~~");
                        myArray.push(res[0]);
                        myArrayIds.push(res[1]);
                    }
                }
            });
            $("#sendto").val(myArray);
            $("#sendtoids").val(myArrayIds);
            $("#myModalSendMail").modal('hide');
        });
    });
</script>