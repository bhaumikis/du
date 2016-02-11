<style>
    .gridtd {
        font-size: 12px;
    }
</style>
<div class="wrapper" id="div_container">
    <div class="container con-padding-tb">
        <div class="col-xs-12 pull-left padding-left-0 padding-right-0">
        <table align="center" width="100%" cellpadding="5" cellspacing="5">
           <tr><td colspan="10">
            <ul id="scrollbox7" class="your-message">
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <div class="col-xs-6 padding-left-0 padding-right-0 font-bold">Title</div>
                        <div class="col-xs-6 padding-left-0 padding-right-0 text-right"><?php echo $view->arrTripData['trip_title']; ?></div>
                    </div>
                </li>
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <div class="col-xs-6 padding-left-0 padding-right-0 font-bold">Destination</div>
                        <div class="col-xs-6 padding-left-0 padding-right-0 text-right"><?php echo $view->arrTripData['destination']; ?></div>
                    </div>
                </li>
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <div class="col-xs-6 padding-left-0 padding-right-0 font-bold">Travel Type</div>
                        <div class="col-xs-6 padding-left-0 padding-right-0 text-right"><?php echo $view->arrTripData['base_expense_type_name']; ?></div>
                    </div>
                </li>
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <div class="col-xs-6 padding-left-0 padding-right-0 font-bold">Budget</div>
                        <div class="col-xs-6 padding-left-0 padding-right-0 text-right"><?php echo $view->arrTripData['trip_currency']; ?> <?php echo $view->arrTripData['trip_budget']; ?></div>
                    </div>
                </li>
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <div class="col-xs-6 padding-left-0 padding-right-0 font-bold">Date</div>
                        <div class="col-xs-6 padding-left-0 padding-right-0 text-right"><?php echo date(DATE_TIME_FORMAT, strtotime($view->arrTripData['trip_date_from']))." - ".date(DATE_TIME_FORMAT, strtotime($view->arrTripData['trip_date_to']));?></div>
                    </div>
                </li>
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <div class="col-xs-6 padding-left-0 padding-right-0 font-bold">Trip Expense</div>
                        <div class="col-xs-6 padding-left-0 padding-right-0 text-right"><?php echo $view->arrTripExpense['expense_currency_symbol'] . " " . $view->arrTripExpense['total_expense']; ?></div>
                    </div>
                </li>
                <?php if (isset($view->arrTripData['trip_description']) and !empty($view->arrTripData['trip_description'])) { ?>
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                    	<div class="col-xs-3 padding-left-0 padding-right-0 font-bold">Trip Description</div>
                    	<div class="col-xs-9"><?php echo $view->arrTripData['trip_description'];?></div>
                    </div>
                </li>
                <?php } ?>
                <?php if (is_array($view->arrTripReference) and !empty($view->arrTripReference)) { ?>
                    <li class="border-btm">
                        <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                            <?php foreach ($view->arrTripReference as $arrImages) { ?>
                                <div class="col-xs-4 padding-left-10 padding-right-0"><img src="<?php echo APPLICATION_URL; ?>/images/user_trips/<?php echo $arrImages['trip_filename']; ?>" class="col-xs-12 no-round-brd"/></div>
                            <?php } ?>
                        </div>
                    </li>
                <?php } ?>
            </ul>
            </td></tr>
            <?php $expenses = $view->helper('user')->getUserExpenseByTripId($view->arrTripData["user_trip_id"]); ?>
            <?php if (isset($expenses) and !empty($expenses)) { ?>
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
            <?php } ?>
                </table>
        </div>
    </div>
</div>