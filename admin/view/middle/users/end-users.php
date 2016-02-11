<script>
    $(document).ready( function () {
        $.datepicker.regional[""].dateFormat = 'yy-mm-dd';
        $.datepicker.regional[""].changeMonth = true;
        $.datepicker.regional[""].changeYear = true;
        $.datepicker.setDefaults($.datepicker.regional['']);
        
        var oTable = $('#userlist').dataTable({"aaSorting": []})
        .columnFilter({aoColumns:[
                null,
                null,
                null,
                { type:"text", sSelector: "#countryFilter" },
                null,
                null,
                null,
<?php if ($view->hideassignedtofield == '0') { ?>
                null,
<?php } ?>
                { type:"date-range", sSelector: "#createddateFilter" },
<?php /*if ($view->hideassignedtofield == '0') { ?>
                null
<?php }*/ ?>
            ]});
        $('#clearFilter').on('click', function(e) {
            oTable.fnFilter('',3);
            oTable.fnFilter('',8);
            oTable.fnFilter('');
            $(".text_filter").val("");
            $(".date_range_filter").val("");
            oTable.fnDraw();
        });
        $('tfoot').removeClass('hidden-f');
        $('tfoot').addClass('hidden');
    });
</script>
<style>
    .form-control { display:inline !important; width:30%;}
    .filter_column.filter_text input.text_filter.form-control.search_init, .filter_column.filter_text input.text_filter.form-control:focus{width:80.0%;}
</style>
<div class="wrapper">
    <div class="container con-padding-tb">
        <div class="streaming-table" id="flip-scroll">
            <table cellspacing="0" cellpadding="0" border="0" class="display margin-bottom-10" ID="Table1" width="100%">
                <tbody>
                    <tr id="filter_global">
                        <td align="left" class="padding-top-5 padding-btm-5 padding-left-5"><?php echo _l('Label_Country', 'users'); ?></td>
                        <td align="left" class="padding-top-5 padding-btm-5 padding-left-5" id="countryFilter"></td>
                        <td align="left" class="padding-top-5 padding-btm-5 padding-left-5"><?php echo _l('Label_Created_Date', 'users'); ?></td>
                        <td align="left" class="padding-top-5 padding-btm-5 padding-left-5" id="createddateFilter"></td>
                        <td align="left" class="padding-top-5 padding-btm-5 padding-left-5 text-right" id=""><button class="green btn" id="clearFilter"><?php echo _l('Text_Clear_Filter', 'common'); ?></button></td>
                    </tr>
                </tbody>
            </table>

            <table id="userlist" class="display table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr class="table_header">
                        <th><?php echo _l('Label_Name', 'users'); ?></th>
                        <th><?php echo _l('Label_Mobile_No', 'users'); ?></th>
                        <th><?php echo _l('Label_Email', 'users'); ?></th>
                        <th><?php echo _l('Label_Country', 'users'); ?></th>
                        <th><?php echo _l('Label_Gender', 'users'); ?></th>
                        <th><?php echo _l('Label_Base_Currency', 'users'); ?></th>
                        <th><?php echo _l('Label_Status', 'users'); ?></th>
                        <?php if ($view->hideassignedtofield == '0') { ?>
                            <th><?php echo _l('Label_Assigned_To', 'users'); ?></th>
                        <?php } ?>
                        <th><?php echo _l('Label_Created_Date', 'users'); ?></th>
                        <?php /* if ($view->hideassignedtofield == '0') { ?>
                            <th>Action</th>
                        <?php } */?>
                    </tr>
                </thead>
                <tfoot class="hidden-f">
                    <tr>
                        <th><?php echo _l('Label_Name', 'users'); ?></th>
                        <th><?php echo _l('Label_Mobile_No', 'users'); ?></th>
                        <th><?php echo _l('Label_Email', 'users'); ?></th>
                        <th><?php echo _l('Label_Country', 'users'); ?></th>
                        <th><?php echo _l('Label_Gender', 'users'); ?></th>
                        <th><?php echo _l('Label_Base_Currency', 'users'); ?></th>
                        <th><?php echo _l('Label_Status', 'users'); ?></th>
                        <?php if ($view->hideassignedtofield == '0') { ?>
                            <th><?php echo _l('Label_Assigned_To', 'users'); ?></th>
                        <?php } ?>
                        <th><?php echo _l('Label_Created_Date', 'users'); ?></th>
                        <?php /* if ($view->hideassignedtofield == '0') { ?>
                            <th>Action</th>
                        <?php } */?>
                    </tr>
                </tfoot>

                <tbody>
                    <?php if (isset($view->users)) {
                        foreach ($view->users as $user) { ?>
                            <tr>
                                <td><?php echo $user['uname']; ?></td>
                                <td><?php echo $user['mobile_number']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><?php echo $user['country']; ?></td>
                                <td><?php echo $user['gender']; ?></td>
                                <td><?php echo $user['base_currency']; ?></td>
                                <td align="center"><?php echo ($user['status'] == '1') ?'<i id="i_'.$user['user_id'].'" u-status="1" onclick="updateUserStatus('.$user['user_id'].')" class="fa fa-check text-active-color cursor-pointer"></i>' : '<i u-status="0" id="i_'.$user['user_id'].'" class="fa fa-times text-inactive-color cursor-pointer"  onclick="updateUserStatus('.$user['user_id'].')"></i>'; ?></td>
                                <?php if ($view->hideassignedtofield == '0') { ?>
                                    <td><?php echo (isset($user['aname']) and !empty($user['aname'])) ? $user['aname'] : '-'; ?></td>
                                <?php } ?>
                                <td><?php echo $user['created_date']; ?></td>
                                <?php /*if ($view->hideassignedtofield == '0') { ?>
                                    <td><a class="ancrul cursor-pointer" onclick="assignAdmin('<?php echo $user['user_id']; ?>');">Assign Admin</a></td>
                                <?php }*/ ?>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function assignAdmin(userid)
    {
        var iFrameURL = "";
        iFrameURL = '<?php echo $module_url; ?>/users/assign-admin/user_id/' + userid ;

        $("#contentedit").attr("src", iFrameURL);
        $("#dialog-form").dialog({
            width: 450,
            height: 450,
            title: "Assign Admin",
            closeOnEscape: true,
            modal: true
        });
    }
 
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