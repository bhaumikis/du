<script>
    $(document).ready(function() {
        var oTable = $('#ticketreports').dataTable({"aaSorting": []})
        .columnFilter({aoColumns:[
                { type:"text", sSelector: "#adminFilter" },
                { type:"text", sSelector: "#assignedbyFilter" },
                null,
                null,
                null,
                null,
                null,
                null
            ]});
        $('#clearFilter').on('click', function(e) {
            oTable.fnFilter('',0);
            oTable.fnFilter('',1);
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
                        <td colspan="5" class="admin-ticket-header"><?php echo _l('Text_Ticket_Rights', 'tickets'); ?></td>
                    </tr>
                    <tr id="filter_global">
                        <td class="padding-top-5 padding-btm-5 padding-left-5 float-left margin-all-5"><?php echo _l('Text_Admin_Name', 'tickets'); ?></td>
                        <td class="padding-top-5 padding-btm-5 padding-left-5 float-left" id="adminFilter"></td>
                        <td class="padding-top-5 padding-btm-5 padding-left-5 float-left margin-all-5"><?php echo _l('Text_Assigned_By', 'tickets'); ?></td>
                        <td class="padding-top-5 padding-btm-5 padding-left-5 float-left" id="assignedbyFilter"></td>
                        <td class="padding-top-5 padding-btm-5 padding-left-5 float-right" id=""><button class="green btn" id="clearFilter"><?php echo _l('Text_Clear_Filter', 'common'); ?></button></td>
                    </tr>
                </tbody>
            </table>
            <table id="ticketreports" class="display table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr class="table_header">
                        <th><?php echo _l('Text_Admin_Name', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Assigned_By', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Date_From', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Date_To', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Status', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Created_Date', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Updated_Date', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Action', 'tickets'); ?></th>
                    </tr>
                </thead>
                <tfoot class="hidden-f">
                    <tr class="table_header">
                        <th><?php echo _l('Text_Admin_Name', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Assigned_By', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Date_From', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Date_To', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Status', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Created_Date', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Updated_Date', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Action', 'tickets'); ?></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (isset($view->assignedAdmin)) {
                        foreach ($view->assignedAdmin as $admin) { ?>
                            <tr>
                                <td><?php echo $admin['adminname']; ?></td>
                                <td><?php echo ($admin['usertype_id'] == '1') ? $admin['assignedbyname'] . ' ['._l('Text_Super_Admin', 'common').']' : $admin['assignedbyname']; ?></td>
                                <td><?php echo $admin['date_from']; ?></td>
                                <td><?php echo $admin['date_to']; ?></td>
                                <td><?php echo ($admin['is_deleted'] == '0') ? _l('Text_Active', 'common') : _l('Text_Inactive', 'common'); ?></td>
                                <td><?php echo $admin['created_date']; ?></td>
                                <td><?php echo $admin['updated_date']; ?></td>
                                <td><a class="ancrul fa fa-eye margin-right-15 font-ic-20" href="<?php echo $module_url . "/ticket-reports/view-ticket-assignments/tdi/" . $admin['ticket_assignment_detail_id']; ?>" ></a></td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>