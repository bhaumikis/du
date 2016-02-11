<style>
    .tbHeader {
        font-weight: bold;
    }
</style>
<div class="wrapper" id="div_container">
    <div class="container con-padding-tb">
        <table align="center" width="100%" cellpadding="5" cellspacing="5">
            <tr>
                <td colspan="10" align="center" style="font-weight:bold">My Expenses</td>
            </tr>
            <tr>
                <td style="font-weight:bold">Expense Date</td>
                <td style="font-weight:bold">Expense Summary</td>
                <td style="font-weight:bold">Base Category Type</td>
                <td style="font-weight:bold">Category Title</td>
                <td style="font-weight:bold">Base Currency Name</td>
                <td style="font-weight:bold">Base Currency Code</td>
                <td style="font-weight:bold">Expense Base Currency Amount</td>
                <td style="font-weight:bold">Expense Currency Name</td>
                <td style="font-weight:bold">Expense Currency Code</td>
                <td style="font-weight:bold">Expense Amount</td>
            </tr>

            <?php if (!count($view->userexpenses)) { ?>
                <tr>
                    <td colspan="100%" align="center"><div style="text-align: center;">No Record Found.</div></td>
                </tr>
            <?php } ?>
            <?php
            for ($i = 0; $i < count($view->userexpenses); $i++) {
                if (($i + 1) % 2 == 0) {
                    $class = "class=\"evnrw\"";
                } else {
                    $class = "class=\"oddrw\"";
                }
                ?>
                <tr <?php echo $class; ?>>
                    <td class="gridtd"><?php echo date(DATE_TIME_FORMAT, strtotime($view->userexpenses[$i]['expense_date']." ".$view->userexpenses[$i]['expense_time'])); ?></td>
                    <td class="gridtd"><?php echo $view->userexpenses[$i]["expense_summary"]; ?></td>
                    <td class="gridtd"><?php echo $view->userexpenses[$i]["base_expense_type_name"]; ?></td>
                    <td class="gridtd"><?php echo $view->userexpenses[$i]["category_title"]; ?></td>
                    <td class="gridtd"><?php echo $view->userexpenses[$i]["base_currency_name"]; ?></td>
                    <td class="gridtd"><?php echo $view->userexpenses[$i]["base_currency_code"]; ?></td>
                    <td class="gridtd"><?php echo $view->userexpenses[$i]["expense_base_currency_amount"]; ?></td>
                    <td class="gridtd"><?php echo $view->userexpenses[$i]["expense_currency_name"]; ?></td>
                    <td class="gridtd"><?php echo $view->userexpenses[$i]["expense_currency_code"]; ?></td>
                    <td class="gridtd"><?php echo $view->userexpenses[$i]["expense_amount"]; ?></td>
                </tr>
            <?php } ?>
        </table>
        <div class="pad4"></div>
    </div>
</div>