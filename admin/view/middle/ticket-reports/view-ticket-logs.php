<script>
    $(document).ready(function() {
        var oTable = $('#ticketreports').dataTable({"aaSorting": []})
        .columnFilter({aoColumns:[
                null,
                null,
                null,
                null,
                null,
                null
            ]});
        $('#clearFilter').on('click', function(e) {
            $(".text_filter").val("");
            oTable.fnDraw(true);
        });
        $('tfoot').removeClass('hidden-f');
        $('tfoot').addClass('hidden');
    });
</script>
<div class="wrapper">
    <div class="container con-padding-tb">
        <div class="streaming-table" id="flip-scroll">
            <?php include(APPLICATION_PATH . "/application/global/message.php"); ?>
            <table cellspacing="0" cellpadding="0" border="0" class="display margin-bottom-10" ID="Table1" width="100%">
                <tbody>
                    <tr>
                        <td class="admin-ticket-header">
                            <?php echo _l('Text_Ticket_Logs', 'tickets'); ?>
                            <a class="float-right padding-right-10" href="<?php echo $module_url."/ticket-reports/view-ticket-assignments/tdi/".$view->ticket_detail_id; ?>"><?php echo _l('Text_Back', 'common'); ?></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table id="ticketreports" class="display table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr class="table_header">
                        <th><?php echo _l('Text_Ticket_Id', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Logged_By', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Status', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Log_Date', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Action', 'tickets'); ?></th>
                    </tr>
                </thead>
                <tfoot class="hidden-f">
                    <tr class="table_header">
                        <th><?php echo _l('Text_Ticket_Id', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Logged_By', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Status', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Log_Date', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Action', 'tickets'); ?></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (isset($view->ticketLogDetails)) {
                        foreach ($view->ticketLogDetails as $admin) { ?>
                            <tr>
                                <td><?php echo $admin['ticket_id']; ?></td>
                                <td><?php echo $admin['loggedby']; ?></td>
                                <td>
                                    <?php
                                    foreach ($view->status as $key => $status) {
                                        if ($key == $admin['status']) {
                                            echo $status;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?php echo $admin['log_date']; ?></td>
                                <td><a class="ancrul descView" href="#" id="<?php echo $admin['ticket_action_log_id']; ?>"><?php echo _l('Text_Description', 'tickets'); ?></a></td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalViewDesc" tabindex="-1" role="dialog" aria-labelledby="myModalViewDescLabel" aria-hidden="true">
    <div class="modal-dialog col-md-8 padding-left-0 padding-right-0">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo _l('Label_Close', 'common'); ?></span></button>
                <h4 class="modal-title" id="myModalViewDescLabel"><?php echo _l('Text_Description', 'tickets'); ?></h4>
            </div>
            <div class="view-desc-modal-body" height="350" width="100%">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btns-bg white" data-dismiss="modal"><?php echo _l('Button_Close', 'common'); ?></button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.descView').click(function() {
            var ticket_action_log_id = this.id;
            $('#myModalViewDesc').on('show.bs.modal', function() {
                $('#myModalViewDescLabel').html('<?php echo _l('Text_Description', 'tickets'); ?>');
                $('.view-desc-modal-body').html('<iframe style="border: 1px solid #ccc;" id="iframe_container" src="<?php echo $module_url; ?>/ticket-reports/get-description/ticket_action_log_id/'+ticket_action_log_id+'"  height="350" width="100%" frameborder="1"></iframe>');
            });
            $('#myModalViewDesc').modal();
        });
    });
</script>