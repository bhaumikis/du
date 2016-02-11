<script>
    $(document).ready(function() {
        var oTable = $('#tickets').dataTable({"aaSorting": []})
        .columnFilter({aoColumns:[
                null,
                null,
                null,
                { type:"text", sSelector: "#statusFilter" },
                null,
<?php if ($view->hideassignedtofield == '0') { ?>
                null,
<?php } ?>
                null
            ]});
        $('#clearFilter').on('click', function(e) {
            oTable.fnFilter('',3);
            oTable.fnFilter('');
            $(".text_filter").val("");
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
                    <tr id="filter_global">
                        <td class="padding-top-5 padding-btm-5 padding-left-5 float-left margin-all-5"><?php echo _l('Text_Status', 'tickets'); ?></td>
                        <td class="padding-top-5 padding-btm-5 padding-left-5 float-left" id="statusFilter"></td>
                        <td class="padding-top-5 padding-btm-5 padding-left-5 float-right" id=""><button class="green btn" id="clearFilter"><?php echo _l('Text_Clear_Filter', 'common'); ?></button></td>
                    </tr>
                </tbody>
            </table>
            <table id="tickets" class="display table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr class="table_header">
                        <th><?php echo _l('Text_Name', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Mobile_No', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Subject', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Status', 'tickets'); ?></th>
                        <?php if ($view->hideassignedtofield == '0') { ?>
                            <th><?php echo _l('Text_Assigned_To', 'tickets'); ?></th>
                        <?php } ?>
                        <th><?php echo _l('Text_Created_Date', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Is_Read', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Action', 'tickets'); ?></th>
                    </tr>
                </thead>
                <tfoot class="hidden-f">
                    <tr class="table_header">
                        <th><?php echo _l('Text_Name', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Mobile_No', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Subject', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Status', 'tickets'); ?></th>
                        <?php if ($view->hideassignedtofield == '0') { ?>
                            <th><?php echo _l('Text_Assigned_To', 'tickets'); ?></th>
                        <?php } ?>
                        <th><?php echo _l('Text_Created_Date', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Is_Read', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Action', 'tickets'); ?></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (isset($view->tickets)) {
                        foreach ($view->tickets as $ticket) { ?>
                            <tr>
                                <td><?php echo $ticket['uname']; ?></td>
                                <td><?php echo $ticket['user_mobno']; ?></td>
                                <td><?php echo $ticket['subject']; ?></td>
                                <td><?php echo array_key_exists($ticket['stat'], $view->status) ? $view->status[$ticket['stat']] : '-'; ?></td>
                                <?php
                                if ($view->hideassignedtofield == '0') {
                                    $label = "";
                                    if ($ticket['usertype_id'] == "1") {
                                        $label = ' ['._l('Text_Super_Admin', 'common').']';
                                    }
                                    ?>
                                    <td><?php echo (isset($ticket['aname']) and !empty($ticket['aname'])) ? $ticket['aname'] . $label : '-'; ?></td>
                                <?php } ?>
                                <td><?php echo $ticket['created_date']; ?></td>
                                <td><?php echo ($ticket['is_read'] == '1') ? _l('Text_Yes', 'common') : _l('Text_No', 'common'); ?></td>
                                <td><a class="ancrul fa fa-eye margin-right-15 font-ic-20" href="<?php echo $module_url . "/tickets/view/ticket_id/".generalFunctions::encryptURL($ticket['ticket_id']); ?>" ></a></td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>