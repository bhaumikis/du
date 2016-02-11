<style>
    .gridtd {
        font-size: 12px;
    }
</style>
<div class="wrapper" id="div_container">
    <div class="container con-padding-tb">
        <table align="center" width="100%" cellpadding="5" cellspacing="5">
            <tr>
                <td colspan="10" align="center" style="font-size: 18px;"><b>My Trips</b></td>
            </tr>
            <?php if (!count($view->usertrips)) { ?>
                <tr>
                    <td colspan="100%" align="center"><div style="text-align: center;">No Record Found.</div></td>
                </tr>
            <?php } ?>
            <?php
            for ($i = 0; $i < count($view->usertrips); $i++) {
                if (($i + 1) % 2 == 0) {
                    $class = "class=\"evnrw\"";
                } else {
                    $class = "class=\"oddrw\"";
                }
                ?>
                <tr>
                    <td style="font-size: 14px;">Trip Title</td>
                    <td style="font-size: 14px;">Trip Description</td>
                    <td style="font-size: 14px;">Trip Destination</td>
                    <td style="font-size: 14px;">Trip Currency Name</td>
                    <td style="font-size: 14px;">Trip Currency Code</td>
                    <td style="font-size: 14px;">Trip Budget</td>
                    <td style="font-size: 14px;">Trip From</td>
                    <td style="font-size: 14px;">Trip To</td>
                    <td style="font-size: 14px;">Base Expense Type</td>
                </tr>
                <tr <?php echo $class; ?>>
                    <td class="gridtd"><?php echo $view->usertrips[$i]["trip_title"]; ?></td>
                    <td class="gridtd"><?php echo $view->usertrips[$i]["trip_description"]; ?></td>
                    <td class="gridtd"><?php echo $view->usertrips[$i]["name"]; ?></td>
                    <td class="gridtd"><?php echo $view->usertrips[$i]["currency_name"]; ?></td>
                    <td class="gridtd"><?php echo $view->usertrips[$i]["currency_code"]; ?></td>
                    <td class="gridtd"><?php echo $view->usertrips[$i]["trip_budget"]; ?></td>
                    <td class="gridtd"><?php echo date(DATE_TIME_FORMAT, strtotime($view->usertrips[$i]["trip_date_from"])); ?></td>
                    <td class="gridtd"><?php echo date(DATE_TIME_FORMAT, strtotime($view->usertrips[$i]["trip_date_to"])); ?></td>
                    <td colspan="3" class="gridtd"><?php echo $view->usertrips[$i]["base_expense_type_name"]; ?></td>
                </tr>
                <?php $expenses = $view->helper('user')->getUserExpenseByTripId($view->usertrips[$i]["user_trip_id"]); ?>
                <?php if (isset($expenses) and !empty($expenses)) { ?>
                    <tr style="border-bottom: 1px solid #ccc; border-top: 1px solid #ccc;"><td colspan="10" align="left" style="font-size: 14px;"><?php echo "Expense - " . $view->usertrips[$i]["trip_title"]; ?></td></tr>
                    <tr>
                        <td style="font-size: 14px;">Expense Date</td>
                        <td style="font-size: 14px;">Expense Summary</td>
                        <td style="font-size: 14px;">Base Category Type</td>
                        <td style="font-size: 14px;">Category Title</td>
                        <td style="font-size: 14px;">Base Currency Name</td>
                        <td style="font-size: 14px;">Base Currency Code</td>
                        <td style="font-size: 14px;">Expense Base Currency Amount</td>
                        <td style="font-size: 14px;">Expense Currency Name</td>
                        <td style="font-size: 14px;">Expense Currency Code</td>
                        <td style="font-size: 14px;">Expense Amount</td>
                    </tr>
                    <?php foreach ($expenses as $expense) { ?>
                        <tr <?php echo $class; ?>>
                            <td class="gridtd"><?php echo date(DATE_TIME_FORMAT, strtotime($expense["expense_date"]." ".$expense["expense_time"])); ?></td>
                            <td class="gridtd"><?php echo $expense["expense_summary"]; ?></td>
                            <td class="gridtd"><?php echo $expense["base_expense_type_name"]; ?></td>
                            <td class="gridtd"><?php echo $expense["category_title"]; ?></td>
                            <td class="gridtd"><?php echo $expense["base_currency_name"]; ?></td>
                            <td class="gridtd"><?php echo $expense["base_currency_code"]; ?></td>
                            <td class="gridtd"><?php echo $expense["expense_base_currency_amount"]; ?></td>
                            <td class="gridtd"><?php echo $expense["expense_currency_name"]; ?></td>
                            <td class="gridtd"><?php echo $expense["expense_currency_code"]; ?></td>
                            <td class="gridtd"><?php echo $expense["expense_amount"]; ?></td>
                        </tr>
                    <?php } ?>
                <?php }else { ?>
                	<tr style="border-bottom: 1px solid #ccc; border-top: 1px solid #ccc;"><td colspan="10" align="left" style="font-size: 14px;"><?php echo "Expense - " . $view->usertrips[$i]["trip_title"]; ?></td></tr>
                	<tr style="border-bottom: 1px solid #ccc; border-top: 1px solid #ccc;"><td colspan="10" align="center" style="font-size: 12px;">There has no expenses</td></tr>
                <?php }?>
            <?php } ?>
        </table>
        <div class="pad4"></div>
    </div>
</div>