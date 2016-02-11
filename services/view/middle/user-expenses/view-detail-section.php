<?php
if ($view->arrUserExpense['payment_mode'] == 2) {
    $cardbaseCategoryClass = 'fa-money';
} else {
    $cardbaseCategoryClass = 'fa-credit-card';
}
if ($view->arrUserExpense['base_type_id'] == 1) {
    $baseCategoryClass = 'fa-briefcase';
} elseif ($view->arrUserExpense['base_type_id'] == 2) {
    $baseCategoryClass = 'fa-user';
} else {
    $baseCategoryClass = 'fa-question';
}
?>
<div class="wrapper" id="div_container">
    <div class="container con-padding-tb">        
        <div class="col-xs-12 pull-left padding-left-0 padding-right-0">
            <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt text-center border-btm">
                <h3><?php echo $view->arrUserExpense['vendor']; ?></h3>
                <h4><?php echo $view->arrUserExpense['expense_summary']; ?></h4>
                <h5 class="txt-orange"><i class="fa <?php echo $cardbaseCategoryClass; ?> margin-right-15 txt-orange"></i><?php echo $view->arrUserExpense['expense_currency_symbol'] . $view->arrUserExpense['expense_amount']; ?>
                    (<?php echo $view->arrUserExpense['base_currency_symbol'] . $view->arrUserExpense['expense_base_currency_amount']; ?>)</h5>
            </div>
            <ul id="scrollbox7" class="your-message">
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <div class="col-xs-1 padding-left-0 padding-right-0"><i class="fa <?php echo $baseCategoryClass; ?> txt-orange"></i></div>
                        <div class="col-xs-11 padding-left-0 padding-right-0">
                            <div class="padding-left-5"><!--12:32PM--></div><div class="padding-right-5">
                                <?php
                                if (!empty($view->arrExpenseCategory['parent_cat_name']) and !empty($view->arrExpenseCategory['cat_name'])) {
                                    echo ($view->arrExpenseCategory['parent_cat_name']) ? $view->arrExpenseCategory['parent_cat_name'] : "";
                                    ?> > <?php
                                    echo ($view->arrExpenseCategory['cat_name']) ? $view->arrExpenseCategory['cat_name'] : "";
                                } elseif (!empty($view->arrExpenseCategory['parent_cat_name']) and empty($view->arrExpenseCategory['cat_name'])) {
                                    echo $view->arrExpenseCategory['parent_cat_name'];
                                } elseif (empty($view->arrExpenseCategory['parent_cat_name']) and !empty($view->arrExpenseCategory['cat_name'])) {
                                    echo $view->arrExpenseCategory['cat_name'];
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <div class="col-xs-1 padding-left-0 padding-right-0"><i class="fa fa-calendar txt-orange"></i></div>
                        <div class="col-xs-11 padding-left-0 padding-right-0">
                            <div class="padding-left-5"><!--12:32PM--></div><div class="padding-right-5"><?php echo date(DATE_TIME_FORMAT, strtotime($view->arrUserExpense['expense_date']." ".$view->arrUserExpense['expense_time'])); ?></div>
                        </div>
                    </div>
                </li>
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <div class="col-xs-1 padding-left-0 padding-right-0"><i class="fa fa-plane txt-orange"></i></div>
                        <div class="col-xs-11 padding-left-0 padding-right-0"><?php echo $view->arrUserExpense['trip_title']; ?></div>
                    </div>
                </li>
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <div class="col-xs-1 padding-left-0 padding-right-0"><i class="fa fa-file-text-o txt-orange"></i></div>
                        <div class="col-xs-11 padding-left-0 padding-right-0"><p class="font-16"><?php echo $view->arrUserExpense['expense_description']; ?></p></div>
                    </div>
                </li>
                <?php if (is_array($view->arrExpenseReference) and !empty($view->arrExpenseReference)) { ?>
                    <li class="border-btm">
                        <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                            <div class="col-xs-1 padding-left-0 padding-right-0"><i class="fa fa-paperclip txt-orange"></i></div>
                            <?php foreach ($view->arrExpenseReference as $arrImages) { ?>
                                <div class="col-xs-4 padding-left-10 padding-right-0"><img src="<?php echo APPLICATION_URL; ?>/images/user_expenses/<?php echo $arrImages['expense_filename']; ?>" class="col-xs-12 no-round-brd"/></div>
                                <?php } ?>
                        </div>     
                    </li>
                <?php } ?>
            </ul>
        </div>

        <!-- Container --> 
    </div>
</div>