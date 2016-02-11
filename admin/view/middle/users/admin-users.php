<script>
    $(document).ready( function () {

        $('#date_from input').datepicker({autoclose: true});
        $('#date_to input').datepicker({autoclose: true});

        $("#adminusers").validate({
            errorElement:"div",
            rules: {
                date_from:{required:true},
                date_to:{required:true,greaterThan:".date_from"}
            },
            messages: {
                date_from:{required:"<?php echo _l('Msg_Enter_Date_From', 'users'); ?>"},
                date_to:{required:"<?php echo _l('Msg_Enter_Date_To', 'users'); ?>"}
            }
        });
        jQuery.validator.addMethod("greaterThan", function(value, element, params) {
            if (!/Invalid|NaN/.test(new Date(value))) {
                return new Date(value) > new Date($(params).val());
            }
            return isNaN(value) && isNaN($(params).val()) || (Number(value) > Number($(params).val())); 
        },'<?php echo _l('Msg_Date_Greater', 'users'); ?>');

        /*if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            var sc = {"aaSorting": []}; //{"scrollX": false};
        } else {
            var sc = {"aaSorting": [],"scrollX": true};
        }*/
        var oTable = $('#adminlist').dataTable()
        .columnFilter({aoColumns:[
                null,
                null,
                null,
                { type:"text", sSelector: "#countryFilter" },
                null,
                null,
                null,
                null,
                null
            ]});
        $('#clearFilter').on('click', function(e) {
            oTable.fnFilter('',3);
            oTable.fnFilter('');
            $(".text_filter").val("");
            oTable.fnDraw();
        });
        $('tfoot').removeClass('hidden-f');
        $('tfoot').addClass('hidden');
    });
</script>

<div class="wrapper">
    <div class="container con-padding-tb">
        <div class="streaming-table" id="flip-scroll">
            <?php include(APPLICATION_PATH . "/application/global/message.php"); ?>
            <table cellspacing="0" cellpadding="0" border="0" class="display margin-bottom-10" ID="Table1" width="99.8%">
                <tbody>
                    <tr id="filter_global">
                        <td align="left" class="padding-top-5 padding-btm-5 padding-left-5"><?php echo _l('Label_Country', 'users'); ?></td>
                        <td align="left" class="padding-top-5 padding-btm-5 padding-left-5" id="countryFilter"></td>
                        <td align="left" class="padding-top-5 padding-btm-5 padding-left-5 text-right" id=""><button class="green btn" id="clearFilter"><?php echo _l('Text_Clear_Filter', 'common'); ?></button></td>
                    </tr>
                </tbody>
            </table>
            <table id="adminlist" class="display table-bordered table-hover" width="99.8%" cellspacing="0">
                <thead>
                    <tr class="table_header">
                        <th><?php echo _l('Label_Name', 'users'); ?></th>
                        <th><?php echo _l('Label_Mobile_No', 'users'); ?></th>
                        <th><?php echo _l('Label_Email', 'users'); ?></th>
                        <th><?php echo _l('Label_Country', 'users'); ?></th>
                        <th><?php echo _l('Label_Gender', 'users'); ?></th>
                        <th><?php echo _l('Label_Rights', 'users'); ?></th>
                        <th><?php echo _l('Label_Status', 'users'); ?></th>
                        <th><?php echo _l('Label_Created_Date', 'users'); ?></th>
                        <th><?php echo _l('Label_Action', 'users'); ?></th>
                    </tr>
                </thead>
                <tfoot class="hidden-f">
                    <tr>
                        <th><?php echo _l('Label_Name', 'users'); ?></th>
                        <th><?php echo _l('Label_Mobile_No', 'users'); ?></th>
                        <th><?php echo _l('Label_Email', 'users'); ?></th>
                        <th><?php echo _l('Label_Country', 'users'); ?></th>
                        <th><?php echo _l('Label_Gender', 'users'); ?></th>
                        <th><?php echo _l('Label_Rights', 'users'); ?></th>
                        <th><?php echo _l('Label_Status', 'users'); ?></th>
                        <th><?php echo _l('Label_Created_Date', 'users'); ?></th>
                        <th><?php echo _l('Label_Action', 'users'); ?></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (isset($view->administrators)) {
                        foreach ($view->administrators as $user) { ?>
                            <tr>
                                <td><?php echo $user['uname']; ?></td>
                                <td><?php echo $user['mobile_number']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><?php echo $user['name']; ?></td>
                                <td><?php echo $user['gender']; ?></td>
                                <td><?php
                    if ($details = $view->helper('ticket-assignments')->getTicketAssignmentDateForAdmin($user['user_id'])) {
                        echo date('d/m/Y', strtotime($details['date_from'])) . ' To ' . date('d/m/Y', strtotime($details['date_to']));
                                ?>
                                        <a onclick="prompttounsetticketadmin('<?php echo $module_url . '/users/unset-ticket-assignment/user_id/' . $user['user_id']; ?>')" href="javascript:void(0);"> [<?php echo _l('Label_Remove', 'users'); ?>]</a>
                                    <?php } else { ?>
                                        <a title="<?php echo _l('Title_Rights', 'users'); ?>" id="<?php echo $user["user_id"]; ?>" data-toggle="modal" class="ancrul openLogBtn" href="#" ><?php echo _l('Label_Assign', 'users'); ?></a></td>
                                <?php } ?>
                                <td align="center"><?php echo ($user['status'] == '1') ?  '<i id="i_'.$user['user_id'].'" u-status="1" onclick="updateUserStatus('.$user['user_id'].')" class="fa fa-check text-active-color cursor-pointer"></i>' : '<i u-status="0" id="i_'.$user['user_id'].'" class="fa fa-times text-inactive-color cursor-pointer"  onclick="updateUserStatus('.$user['user_id'].')"></i>'; ?></td>
                                <td><?php echo $user['created_date']; ?></td>
                                <td><a class="ancrul fa fa-edit margin-right-5 font-ic-20" href="<?php echo $module_url . "/users/addedit/user_id/" . $user['user_id']; ?>" ></a>
                                    <a class="ancrul fa fa-trash-o margin-right-5 font-ic-20" onclick="prompttodelete('<?php echo $module_url . "/users/delete-admin/user_id/" . $user['user_id']; ?>')" href="javascript:void(0);"></a>
                                </td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<form name="adminusers" id="adminusers" method="post">
    <div class="modal fade" id="myModalTicketAdmin" tabindex="-1" role="dialog" aria-labelledby="myModalTicketAdminLabel" aria-hidden="true" style="margin-top: 25px;">
        <div class="modal-dialog col-md-8 padding-left-0 padding-right-0">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo _l('Label_Close', 'common'); ?></span></button>
                    <h4 class="modal-title" id="myModalTicketAdminLabel"><?php echo _l('Label_Rights', 'users'); ?></h4>
                </div>
                <div class="ticket-admin-modal-body">
                    <table cellspacing="5" cellpadding="10" border="0" width="100%">
                        <tbody>
                            <tr><td><input type="hidden" name="hid_user_id" id="hid_user_id" /></td></tr>
                            <tr>
                                <td width="20%"><?php echo _l('Label_Date_From', 'users'); ?> : </td>
                                <td><div class="reg-form" id="date_from"><input readonly style="width:90%; border:none;" type="text" placeholder="<?php echo _l('Label_Date_From', 'users'); ?>" class="date_from" name="date_from" id="date_from" /></div></td>
                            </tr>
                            <tr>
                                <td><?php echo _l('Label_Date_To', 'users'); ?> : </td>
                                <td><div class="reg-form" id="date_to"><input readonly style="width:90%; border:none;" type="text" placeholder="<?php echo _l('Label_Date_To', 'users'); ?>" class="date_to" name="date_to" id="date_to" /></div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btns-bg white" data-dismiss="modal"><?php echo _l('Button_Close', 'common'); ?></button>
                    <button type="button" class="btn btns-bg white" id="btnsave"><?php echo _l('Button_Save', 'common'); ?></button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('.openLogBtn').click(function() {
            $("#hid_user_id").val(this.id);
            $('#myModalTicketAdmin').on('show.bs.modal', function() {});
            $('#myModalTicketAdmin').modal();
        });
        $("#btnsave").confirm({
            title:"<?php echo _l('Title_Assign', 'users'); ?>",
            text:"<?php echo _l('Msg_Confirm_Rights', 'users'); ?>",
            confirm: function(button) {
                $("#adminusers").submit();
            },
            cancel: function(button) {
                return false;
            },
            confirmButton: "<?php echo _l('Text_Btn_Confirm', 'users'); ?>",
            cancelButton: "<?php echo _l('Text_Btn_Cancel', 'users'); ?>"
        });
    });
    
    function updateUserStatus(userId){
        if(confirm("Are you sure to change the status!")){
            var  strAddClass;
            var  newStatus = 0;
            var usrStatus = $('#i_'+userId).attr('u-status');

            if(usrStatus == 1){
                strAddClass = 'fa fa-times text-inactive-color cursor-pointer';
                newStatus = 0;
            }else{
                strAddClass = 'fa fa-check text-active-color cursor-pointer';
                newStatus = 1;
            }

            $.ajax({
                type: "POST",
                url: "<?php echo $module_url . "/users/change-user-status"; ?>",
                data: {
                    user_id: userId,
                    status:usrStatus
                },
                success: function(response) {
                    if(response === 'Success'){    
                        $('#i_'+userId).removeAttr('class').attr('class', '');
                        $('#i_'+userId).addClass(strAddClass);
                        $('#i_'+userId).attr('u-status',newStatus);
                    }
                }
            }); 
        }
    }
</script>