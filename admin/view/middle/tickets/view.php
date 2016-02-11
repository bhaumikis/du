<?php
if (isset($view->ticketDetails) and !empty($view->ticketDetails)) {
    extract($view->ticketDetails);
}
if ($_POST) {
    extract($_POST);
}
?>
<script type="text/javascript">
    $(document).ready( function(){
        $("#viewticket").validate({
            errorElement:"div",
            rules: {
                query_template_id:"required"
            },
            messages: {
                query_template_id:"<?php echo _l('Select_Query_Template', 'tickets'); ?>"
            }
        });
        $(".btnSubmit").click(function() {            
            getMSDrowpdownValidate('viewticket','query_template_id','<?php echo _l('Select_Query_Template', 'tickets'); ?>');
        });
        $('.summernote').summernote({
            height: 200
        });
        $("#query_template_id").msDropdown();
        $("#status").msDropdown();
        $("#query_template_id").change(function() {
            $.ajax
            ({
                url: '<?php echo $module_url . "/tickets/get-template-details"; ?>',
                data: "template_id="+$(this).val(),
                type: 'post',
                success: function(response)
                {
                    var objResponse = jQuery.parseJSON(response);
                    $('.note-editable').html(objResponse.content);
                    $('#subject').val(objResponse.subject);
                }
            });
        });
    });
</script>
<form name="viewticket" id="viewticket" method="post">
    <input type="hidden" name="hid_ticket_id" id="hid_ticket_id" value="<?php echo $view->ticket_id; ?>" />
    <input type="hidden" name="hid_user_id" id="hid_user_id" value="<?php echo $view->ticketDetails['created_by']; ?>" />
    <div class="wrapper">
        <div class="container con-padding-tb">
            <div class="col-xs12 col-sm-12 col-md-12">
                <div>
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th width="20%"><?php echo _l('Label_First_Name', 'tickets'); ?></th>
                            <td><?php echo $view->ticketDetails['first_name']; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo _l('Label_Last_Name', 'tickets'); ?></th>
                            <td><?php echo $view->ticketDetails['last_name']; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo _l('Label_Add1', 'tickets'); ?></th>
                            <td><?php echo $view->ticketDetails['address_line1']; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo _l('Label_Add2', 'tickets'); ?></th>
                            <td><?php echo $view->ticketDetails['address_line1']; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo _l('Label_DOB', 'tickets'); ?></th>
                            <td><?php echo $view->ticketDetails['birth_date']; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo _l('Label_Gender', 'tickets'); ?></th>
                            <td><?php echo $view->ticketDetails['gender']; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo _l('Label_Mobile', 'tickets'); ?></th>
                            <td><?php echo $view->ticketDetails['mobile_number']; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo _l('Label_Email', 'tickets'); ?></th>
                            <td><?php echo $view->ticketDetails['email']; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo _l('Label_Country', 'tickets'); ?></th>
                            <td><?php echo $view->ticketDetails['cname']; ?></td>
                        </tr>
                    </table>
                </div>
                <h4 class="StepTitle"><?php echo _l('Label_Subject', 'tickets'); ?> : <span class="text-italic"><?php echo $view->ticketDetails['subject']; ?></span></h4>
                <div class="col-md-12"><p><?php echo $view->ticketDetails['comment']; ?></p></div>
                <div><?php echo _l('Label_Select_Query_Template', 'tickets'); ?> : <select name="query_template_id" id="query_template_id" style="width:100%;">
                        <option value=""><?php echo _l('Label_Select_Query_Template', 'tickets'); ?></option>
                        <?php if (isset($view->queryTemplates) and !empty($view->queryTemplates)) {
                            foreach ($view->queryTemplates as $template) { ?>
                                <option value="<?php echo $template['email_template_id']; ?>" <?php echo ($template['email_template_id'] == $view->ticketDetails['query_template_id']) ? 'selected="selected"' : ""; ?>><?php echo $template['title']; ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
                <div><?php echo _l('Label_Final_Solution', 'tickets'); ?> : <textarea class="summernote" name="final_solution" id="final_solution" style="width:100%;" placeholder="<?php echo _l('Placeholder_Final_Solution', 'tickets'); ?>"><?php echo (isset($view->ticketDetails['final_solution']) and !empty($view->ticketDetails['final_solution'])) ? $view->ticketDetails['final_solution'] : ""; ?></textarea></div>
                <div class="light-gray-bg padding-top-10 padding-right-10 padding-btm-10 padding-left-5 clearfix">
                    <div class=" col-xs-3">
                        <select class="margin-right-15 form-control" name="status" id="status">
                            <?php foreach ($view->status as $key => $status) { ?>
                                <option value="<?php echo $key; ?>" <?php echo ($key == $view->ticketDetails['status']) ? 'selected="selected"' : ""; ?>><?php echo $status; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="button" data-dismiss="modal" class="btn btns-bg white margin-right-15" onclick="window.location.href = '<?php echo $module_url . "/tickets"; ?>'"><?php echo _l('Button_Cancel', 'common'); ?></button>
                    <input type="submit" class="btn btns-bg white btnSubmit" value="<?php echo _l('Button_Reply', 'common'); ?>" name="replysubmit" id="replysubmit">
                </div>
            </div>
        </div>
    </div>
</form>