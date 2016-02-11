<script>
    $(document).ready(function() {
        var oTable = $('#ticketreports').dataTable({"aaSorting": []})
        .columnFilter({aoColumns:[
                null,
                { type:"text", sSelector: "#assignedbyFilter" },
                { type:"text", sSelector: "#assignedtoFilter" },
                null,
                null,
                null,
                null,
                null,
                null
            ]});
        $('#clearFilter').on('click', function(e) {
            oTable.fnFilter('',1);
            oTable.fnFilter('',2);
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
                    <tr>
                        <td colspan="5" class="admin-ticket-header"><?php echo _l('Text_Ticket_Assignments', 'tickets'); ?></td>
                    </tr>
                    <tr id="filter_global">
                        <td class="padding-top-5 padding-btm-5 padding-left-5 float-left margin-all-5"><?php echo _l('Text_Assigned_By', 'tickets'); ?></td>
                        <td class="padding-top-5 padding-btm-5 padding-left-5 float-left" id="assignedbyFilter"></td>
                        <td class="padding-top-5 padding-btm-5 padding-left-5 float-left margin-all-5"><?php echo _l('Text_Assigned_To', 'tickets'); ?></td>
                        <td class="padding-top-5 padding-btm-5 padding-left-5 float-left" id="assignedtoFilter"></td>
                        <td class="padding-top-5 padding-btm-5 padding-left-5 float-right" id=""><button class="green btn" id="clearFilter"><?php echo _l('Text_Clear_Filter', 'common'); ?></button></td>
                    </tr>
                </tbody>
            </table>
            <table id="ticketreports" class="display table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr class="table_header">
                        <th><?php echo _l('Text_Ticket_Id', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Assigned_By', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Assigned_To', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Belongs_To', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Date_From', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Date_To', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Status', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Assigned_Date', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Action', 'tickets'); ?></th>
                    </tr>
                </thead>
                <tfoot class="hidden-f">
                    <tr class="table_header">
                        <th><?php echo _l('Text_Ticket_Id', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Assigned_By', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Assigned_To', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Belongs_To', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Date_From', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Date_To', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Status', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Assigned_Date', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Action', 'tickets'); ?></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (isset($view->ticketAssignmentDetails)) {
                        foreach ($view->ticketAssignmentDetails as $admin) { ?>
                            <tr>
                                <td><?php echo $admin['ticket_id']; ?></td>
                                <td><?php echo $admin['assignedbyname']; ?></td>
                                <td><?php echo $admin['reassignedtoname']; ?></td>
                                <td><?php echo $admin['belongstoname']; ?></td>
                                <td><?php echo $admin['date_from']; ?></td>
                                <td><?php echo $admin['date_to']; ?></td>
                                <td><?php echo ($admin['is_deleted'] == '0') ? _l('Text_Active', 'common') : _l('Text_Inactive', 'common'); ?></td>
                                <td><?php echo $admin['assigned_date']; ?></td>
                                <td><a class="ancrul fa fa-eye margin-right-15 font-ic-20" href="<?php echo $module_url . "/ticket-reports/view-ticket-logs/tdi/".$view->ticket_detail_id."/ti/" . $admin['ticket_id']; ?>" ></a></td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>