<table class="table table-striped table-hover" border="0">
    <thead>
    <th><?php echo _l('Text_Name', 'email_templates'); ?></th>
    <th><?php echo _l('Text_Email', 'email_templates'); ?></th>
    <th><?php echo _l('Text_Template', 'email_templates'); ?></th>
    <th><?php echo _l('Text_Date_Sent', 'email_templates'); ?></th>
</thead>
<tbody>
    <?php if (isset($view->emaillog) and !empty($view->emaillog)) {
        foreach ($view->emaillog as $user) { ?>
            <tr>
                <td><?php echo $user['uname']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['title']; ?></td>
                <td><?php echo $user['created_date']; ?></td>
            </tr>
        <?php }
    } else { ?>
        <tr>
            <td colspan="4" align="center"><?php echo _l('Text_No_Record', 'email_templates'); ?></td>
        </tr>
    <?php } ?>
</tbody>
</table>