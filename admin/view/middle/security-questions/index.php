<script>
    $(document).ready(function() {

        var oTable = $('#security_questions').dataTable({"aaSorting": []})
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
            <table id="security_questions" class="display table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr class="table_header">
                        <th><?php echo _l('Text_Question_Id', 'change_security_question'); ?></th>
                        <th><?php echo _l('Text_Question', 'change_security_question'); ?></th>
                        <th><?php echo _l('Text_Status', 'change_security_question'); ?></th>
                        <th><?php echo _l('Text_Created_Date', 'change_security_question'); ?></th>
                    </tr>
                </thead>
                <tfoot class="hidden-f">
                    <tr class="table_header">
                        <th><?php echo _l('Text_Question_Id', 'change_security_question'); ?></th>
                        <th><?php echo _l('Text_Question', 'change_security_question'); ?></th>
                        <th><?php echo _l('Text_Status', 'change_security_question'); ?></th>
                        <th><?php echo _l('Text_Created_Date', 'change_security_question'); ?></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (isset($view->securityquestions)) {
                        foreach ($view->securityquestions as $questions) { ?>
                            <tr>
                                <td><?php echo $questions['security_question_id']; ?></td>
                                <td><?php echo $questions['question']; ?></td>
                                <td><?php echo ($questions['status'] == '1') ? _l('Text_Active', 'common') : _l('Text_Inactive', 'common'); ?></td>
                                <td><?php echo $questions['created_date']; ?></td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>