<script>
    $(document).ready(function() {

        $('#date_from input').datepicker({autoclose: true});
        $('#date_to input').datepicker({autoclose: true});

        $("#ticketassignments").validate({
            errorElement:"div",
            rules: {
                reassigned_from:{required:true,from_to_admin:true},
                reassigned_to:{required:true,from_to_admin:true},
                date_from:{required:true},
                date_to:{required:true,greaterThan:".date_from"}
            },
            messages: {
                reassigned_from:{required:"<?php echo _l('Error_Select_From_Admin', 'tickets'); ?>"},
                reassigned_to:{required:"<?php echo _l('Error_Select_To_Admin', 'tickets'); ?>"},
                date_from:{required:"<?php echo _l('Error_Enter_Date_From', 'tickets'); ?>"},
                date_to:{required:"<?php echo _l('Error_Enter_Date_To', 'tickets'); ?>"}
            }
        });
        $.validator.addMethod('from_to_admin', function (value) {
            return $('#reassigned_from').val() != $('#reassigned_to').val();
        }, '<?php echo _l('Error_Admin_Same', 'tickets'); ?>');

        jQuery.validator.addMethod("greaterThan", function(value, element, params) {
            if (!/Invalid|NaN/.test(new Date(value))) {
                return new Date(value) > new Date($(params).val());
            }
            return isNaN(value) && isNaN($(params).val()) || (Number(value) > Number($(params).val()));
        },'<?php echo _l('Error_Date_Greater', 'tickets'); ?>');

        $('#btnassign').click(function() {
            $('#myModalAssignTicket').on('show.bs.modal', function() {});
            $('#myModalAssignTicket').modal()
        });

        $("#btnsave").confirm({
            title:"<?php echo _l('Text_Assignment_Confirmation', 'tickets'); ?>",
            text:"<?php echo _l('Msg_Assign_Ticket', 'tickets'); ?>",
            confirm: function(button) {
                $("#ticketassignments").submit();
            },
            cancel: function(button) {
                return false;
            },
            confirmButton: "<?php echo _l('Text_Confirm_Msg', 'tickets'); ?>",
            cancelButton: "<?php echo _l('Text_Cancel_Msg', 'tickets'); ?>"
        });
        var oTable = $('#tickets').dataTable({"aaSorting": []})
        .columnFilter({aoColumns:[
                null,
                null,
                null,
                null,
                { type:"text", sSelector: "#assignedToFilter" },
<?php if ($view->hideassignedtofield == '0') { ?>
                    null,
<?php } ?>
                null
            ]});
        $('#clearFilter').on('click', function(e) {
            oTable.fnFilter('',4);
            oTable.fnFilter('');
            $(".text_filter").val("");
            oTable.fnDraw(true);
        });

        $('tfoot').removeClass('hidden-f');
        $('tfoot').addClass('hidden');
    });
</script>

<style>
    .reg-form {
        width:50% !important;
        float: none !important;
    }
    .confirmation-modal {
        margin-top: 30px;
    }
</style>


<div class="wrapper">
    <div class="container con-padding-tb">
        <div class="streaming-table" id="flip-scroll">
            <?php include(APPLICATION_PATH . "/application/global/message.php"); ?>
            <table cellspacing="0" cellpadding="0" border="0" class="display margin-bottom-10" ID="Table1" width="100%">
                <tbody>
                    <tr id="filter_global">
                        <td class="padding-top-5 padding-btm-5 padding-left-5 float-left margin-all-5"><?php echo _l('Text_Assigned_To', 'tickets'); ?></td>
                        <td class="padding-top-5 padding-btm-5 padding-left-5 float-left" id="assignedToFilter"></td>
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
                        <th><?php echo _l('Text_Reassigned_To', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Created_Date', 'tickets'); ?></th>
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
                        <th><?php echo _l('Text_Reassigned_To', 'tickets'); ?></th>
                        <th><?php echo _l('Text_Created_Date', 'tickets'); ?></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (isset($view->tickets)) {
                        foreach ($view->tickets as $ticket) { ?>
                            <tr>
                                <td><?php echo $ticket['uname']; ?></td>
                                <td><?php echo $ticket['mobile_number']; ?></td>
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
                                <td>
                                    <?php echo $ticket['raname']; 
                                        if ($ticket['rusertype_id'] == "1") {
                                            echo ' ['._l('Text_Super_Admin', 'common').']';
                                        }
                                    ?>
                                </td>
                                <td><?php echo $ticket['created_date']; ?></td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
            <?php if (isset($view->tickets) and !empty($view->tickets)) { ?>
                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tbody>
                        <tr>
                            <td><button type="button" class="btns  green  sml-btn flat-btn" id="btnassign"><?php echo _l('Button_Assign', 'common'); ?></button></td>
                        </tr>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</div>

<form name="ticketassignments" id="ticketassignments" method="post">
    <div class="modal fade" id="myModalAssignTicket" tabindex="-1" role="dialog" aria-labelledby="myModalAssignTicketLabel" aria-hidden="true" style="margin-top: 30px;">
        <div class="modal-dialog col-md-8 padding-left-0 padding-right-0">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo _l('Label_Close', 'common'); ?></span></button>
                    <h4 class="modal-title" id="myModalAssignTicketLabel"><?php echo _l('Text_Assign_Ticket', 'tickets'); ?></h4>
                </div>
                <div class="assign-ticket-modal-body" height="350" width="100%">
                    <table cellspacing="5" cellpadding="15" border="0" width="95%">
                        <tbody>
                            <tr>
                                <td width="30%"><?php echo _l('Label_Select_From_Admin', 'tickets'); ?> :</td>
                                <td>
                                    <select name="reassigned_from" id="reassigned_from" style="width:90%">
                                        <option value=""><?php echo _l('Label_Select_From_Admin', 'tickets'); ?></option>
                                        <?php
                                        if (isset($view->adminlist) and !empty($view->adminlist)) {
                                            foreach ($view->adminlist as $adminlist) {
                                                ?>
                                                <option value="<?php echo $adminlist['user_id']; ?>"><?php echo $adminlist['first_name'] . " " . $adminlist['last_name']; 
                                                if ($adminlist['usertype_id'] == "1") {
                                                    echo ' ['._l('Text_Super_Admin', 'common').']';
                                                } ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _l('Label_Select_To_Admin', 'tickets'); ?> :</td>
                                <td>
                                    <select name="reassigned_to" id="reassigned_to" style="width:90%">
                                        <option value=""><?php echo _l('Label_Select_To_Admin', 'tickets'); ?></option>
                                        <?php
                                        if (isset($view->adminlist) and !empty($view->adminlist)) {
                                            foreach ($view->adminlist as $adminlist) {
                                                ?>
                                                <option value="<?php echo $adminlist['user_id']; ?>"><?php echo $adminlist['first_name'] . " " . $adminlist['last_name']; 
                                                if ($adminlist['usertype_id'] == "1") {
                                                    echo ' ['._l('Text_Super_Admin', 'common').']';
                                                }
                                                ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _l('Label_Date_From', 'tickets'); ?> : </td>
                                <td><div class="reg-form" id="date_from" style="width: 90% !important;"><input readonly style="width:99%; border:none;" type="text" placeholder="<?php echo _l('Label_Date_From', 'tickets'); ?>" class="date_from" name="date_from" id="date_from" /></div></td>
                            </tr>
                            <tr>
                                <td><?php echo _l('Label_Date_To', 'tickets'); ?> : </td>
                                <td><div class="reg-form" id="date_to" style="width: 90% !important;"><input readonly style="width:99%; border:none;" type="text" placeholder="<?php echo _l('Label_Date_To', 'tickets'); ?>" class="date_to" name="date_to" id="date_to" /></div></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btns-bg white" data-dismiss="modal"><?php echo _l('Button_Close', 'common'); ?></button>
                    <button type="button" class="btn btns-bg white" id="btnsave"><?php echo _l('Button_Assign', 'common'); ?></button>
                </div>
            </div>
        </div>
    </div>

</form>