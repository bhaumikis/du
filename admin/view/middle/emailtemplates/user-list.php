<table class="table table-striped table-hover" border="0">
    <thead>
    <th></th>
    <th><?php echo _l('Text_Name', 'email_templates'); ?></th>
    <th><?php echo _l('Text_Email', 'email_templates'); ?></th>
</thead>
<tbody>
    <?php if (isset($view->userlist)) { ?>
        <tr>
            <td colspan="3">
                <a href="javascript:setchecked('chk[]',1)" class="navigationtext"><?php echo _l('Text_Check_All', 'email_templates'); ?></a>&nbsp; |&nbsp; <a href="javascript:setchecked('chk[]',0)" class="navigationtext"><?php echo _l('Text_Clear_All', 'email_templates'); ?></a>
            </td>
        </tr>
        <?php foreach ($view->userlist as $user) { ?>
            <tr>
                <td><input type="checkbox" id='chk' class="uemails" name="chk[]" value="<?php echo $user['email'] . '~~~' . $user['user_id']; ?>" /></td>
                <td><?php echo $user['uname']; ?></td>
                <td><?php echo $user['email']; ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="3">
                <a href="javascript:setchecked('chk[]',1)" class="navigationtext"><?php echo _l('Text_Check_All', 'email_templates'); ?></a>&nbsp; |&nbsp; <a href="javascript:setchecked('chk[]',0)" class="navigationtext"><?php echo _l('Text_Clear_All', 'email_templates'); ?></a>
            </td>
        </tr>
    <?php } else { ?>
        <tr>
            <td colspan="3"><?php echo _l('Text_No_Record', 'email_templates'); ?></td>
        </tr>
    <?php } ?>
</tbody>
</table>
<script>
    function setchecked(elemName,status){
        elem = document.getElementsByName(elemName);
        for(i=0;i<elem.length;i++){
            elem[i].checked=status;
        }
    }
</script>
