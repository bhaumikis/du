<script>
    $(document).ready(function() {        
    var oTable = $('#emailtemplate').dataTable({"aaSorting": []})
        .columnFilter({aoColumns:[
                null,
                null,
                null,
                null
            ]});        
        $('tfoot').removeClass('hidden-f');
        $('tfoot').addClass('hidden');
    });
</script>
<div class="wrapper">
    <div class="container con-padding-tb">
        <div class="streaming-table" id="flip-scroll">
            <?php include(APPLICATION_PATH . "/application/global/message.php"); ?>
            <table id="emailtemplate" class="display table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr class="table_header">
                        <th><?php echo _l('Text_Title', 'email_templates'); ?></th>
                        <th><?php echo _l('Text_Name', 'email_templates'); ?></th>
                        <th><?php echo _l('Text_Type', 'email_templates'); ?></th>
                        <th><?php echo _l('Text_Query_Template', 'email_templates'); ?></th>
                        <th><?php echo _l('Text_Created_Date', 'email_templates'); ?></th>
                        <th><?php echo _l('Text_Updated_Date', 'email_templates'); ?></th>
                        <th><?php echo _l('Text_Action', 'email_templates'); ?></th>
                    </tr>
                </thead>
                <tfoot class="hidden-f">
                    <tr class="table_header">
                        <th><?php echo _l('Text_Title', 'email_templates'); ?></th>
                        <th><?php echo _l('Text_Name', 'email_templates'); ?></th>
                        <th><?php echo _l('Text_Type', 'email_templates'); ?></th>
                        <th><?php echo _l('Text_Query_Template', 'email_templates'); ?></th>
                        <th><?php echo _l('Text_Created_Date', 'email_templates'); ?></th>
                        <th><?php echo _l('Text_Updated_Date', 'email_templates'); ?></th>
                        <th><?php echo _l('Text_Action', 'email_templates'); ?></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (isset($view->emailtemplates)) {
                        foreach ($view->emailtemplates as $template) { ?>
                            <tr>
                                <td><?php echo $template['title']; ?></td>
                                <td><?php echo $template['name']; ?></td>
                                <td><?php echo ($template['type'] == '0') ? _l('Text_Option_Default', 'email_templates') : _l('Text_Option_Promotional', 'email_templates'); ?></td>
                                <td><?php echo ($template['is_query_template'] == '0') ? _l('Text_Option_No', 'email_templates') : _l('Text_Option_Yes', 'email_templates'); ?></td>
                                <td><?php echo date(DATETIME_FORMAT, strtotime($template["created_date"])); ?></td>
                                <td><?php echo date(DATETIME_FORMAT, strtotime($template["updated_date"])); ?></td>
                                <td class="gridtd"><a title="<?php echo _l('Text_Edit', 'email_templates'); ?>" class="ancrul fa fa-edit margin-right-15 font-ic-20" href="<?php echo $module_url . "/emailtemplates/addedit/email_template_id/" . $template["email_template_id"]; ?>" ></a>
                                    <?php if ($template['type'] == '1') { ?>
                                        <a title="<?php echo _l('Text_Delete', 'email_templates'); ?>" class="ancrul fa fa-trash-o margin-right-15 font-ic-20" onclick="prompttodeletetemplate('<?php echo $module_url . "/emailtemplates/delete-template/email_template_id/" . $template['email_template_id'] . "/type/" . $template['type']; ?>')" href="javascript:void(0);"></a>
                                    <?php } ?>
                                    <a title="<?php echo _l('Text_Send_Mail', 'email_templates'); ?>" class="ancrul fa fa-envelope-o margin-right-15 font-ic-20" href="<?php echo $module_url . "/emailtemplates/send-mail/email_template_id/" . $template["email_template_id"]; ?>" ></a>
                                    <a title="<?php echo _l('Text_Sent_Mail_Log', 'email_templates'); ?>" id="<?php echo $template["email_template_id"]; ?>" data-toggle="modal" class="ancrul openLogBtn fa fa-archive font-ic-20" href="#" ></a>
                                </td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalLog" tabindex="-1" role="dialog" aria-labelledby="myModalLogLabel" aria-hidden="true" style="margin-top:30px;">
    <div class="modal-dialog col-md-8 padding-left-0 padding-right-0">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo _l('Label_Close', 'common'); ?></span></button>
                <h4 class="modal-title" id="myModalLogLabel"></h4>
            </div>
            <div class="send-mail-modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btns-bg white" data-dismiss="modal"><?php echo _l('Button_Close', 'common'); ?></button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.openLogBtn').click(function() {
            var email_template_id = this.id;
            $('#myModalLog').on('show.bs.modal', function() {
                $('#myModalLogLabel').html('<?php echo _l('Text_Sent_Mail_Log', 'email_templates'); ?>');
                $('.send-mail-modal-body').html('<iframe id="iframe_container" src="<?php echo $module_url; ?>/emailtemplates/show-sent-email-log/email_template_id/'+email_template_id+'"  height="450" width="100%" frameborder="0"></iframe>');
            });
            $('#myModalLog').modal()
        });
    });
</script>