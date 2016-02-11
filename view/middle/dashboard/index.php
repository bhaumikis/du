<div class="bg-transprant">
    <div class="container con-padding-tb">
        <div class="col-xs-6 col-md-6 light-gray-bg margin-top-15 brd-right-gray height-250 gray-transperant">
            <section id="user-registered" class="margin-top-15 col-md-12 padding-left-0 padding-right-0">
                <div class="margin-btm-5 col-md-12 padding-left-0 padding-right-0">
                    <div class="col-xs-10 padding-left-0">
                        <h2 class="fancy-heading txt-orange margin-top-5 padding-left-0"><a class="txt-orange" href="<?php echo $module_url; ?>/my-expenses"><?php echo _l('My Expenses', $option); ?></a></h2>
                    </div>
                    <div class="col-xs-2 text-right padding-right-0"> <a href="<?php echo $module_url; ?>/my-expenses/add-edit" class="plus-btn-o"><i class="fa fa-plus txt-orange"></i></a> </div>
                </div>
                <div class="padding-top-10 clearfix">
                    <div class="col-xs-3 padding-left-0">
                        <div class="user-confirm">
                            <a class="txt-orange" href="<?php echo $module_url; ?>/my-expenses/index/range/today">
                                <span class="money" style="color: #333">
                                    <strong class="money-sign">
                                        <?php echo (isset($view->arrExpense['today']['expense_currency_symbol'])) ? $view->arrExpense['today']['expense_currency_symbol'] : $view->baseCurrencySymbol; ?>
                                    </strong>
                                    <span><?php echo (isset($view->arrExpense['today']['total_expense'])) ? $view->arrExpense['today']['total_expense'] : "0.00"; ?></span>
                                </span>
                            </a>
                            <h3><a class="txt-orange" href="<?php echo $module_url; ?>/my-expenses/index/range/today"><?php echo _l('Today', $option); ?></a></h3>
                        </div>
                    </div>
                    <Div class="col-xs-3 padding-left-5">
                        <div class="user-confirm">
                            <a class="txt-orange" href="<?php echo $module_url; ?>/my-expenses/index/range/yesterday">
                                <span class="money" style="color: #333">
                                    <strong class="money-sign"><?php
                                        echo (isset($view->arrExpense['yesterday']['expense_currency_symbol'])) ? $view->arrExpense['yesterday']['expense_currency_symbol'] : $view->baseCurrencySymbol;
                                        ?></strong>
                                    <span><?php echo (isset($view->arrExpense['yesterday']['total_expense'])) ? $view->arrExpense['yesterday']['total_expense'] : "0.00"; ?></span>
                                </span>
                            </a>
                            <h3><a class="txt-orange" href="<?php echo $module_url; ?>/my-expenses/index/range/yesterday"><?php echo _l('Yesterday', $option); ?></a></h3>
                        </div>
                    </div>
                    <Div class="col-xs-3 padding-left-5">
                        <div class="user-confirm">
                            <a class="txt-orange" href="<?php echo $module_url; ?>/my-expenses/index/range/week">
                                <span class="money" style="color: #333">
                                    <strong class="money-sign"><?php echo (isset($view->arrExpense['last_week']['expense_currency_symbol'])) ? $view->arrExpense['last_week']['expense_currency_symbol'] : $view->baseCurrencySymbol; ?></strong>
                                    <span><?php echo (isset($view->arrExpense['last_week']['total_expense'])) ? $view->arrExpense['last_week']['total_expense'] : "0.00"; ?></span>
                                </span>
                            </a>
                            <h3><a class="txt-orange" href="<?php echo $module_url; ?>/my-expenses/index/range/week"><?php echo _l('Last 7 days', $option); ?></a></h3>
                        </div>
                    </div>
                    <Div class="col-xs-3 padding-right-0">
                        <div class="user-confirm">
                            <a class="txt-orange" href="<?php echo $module_url; ?>/my-expenses/index/range/month">
                                <span class="money" style="color: #333">
                                    <strong class="money-sign"><?php echo (isset($view->arrExpense['last_month']['expense_currency_symbol'])) ? $view->arrExpense['last_month']['expense_currency_symbol'] : $view->baseCurrencySymbol; ?></strong>
                                    <span><?php echo (isset($view->arrExpense['last_month']['total_expense'])) ? $view->arrExpense['last_month']['total_expense'] : "0.00"; ?></span>
                                </span>
                            </a>
                            <h3><a class="txt-orange" href="<?php echo $module_url; ?>/my-expenses/index/range/month"><?php echo _l('Last 30 days', $option); ?></a></h3>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-xs-6 col-md-6 margin-top-15 light-gray-bg padding-btm-5 height-250 gray-transperant">
            <div class="col-md-12 padding-left-0 padding-right-0">
                <div class="col-xs-10 margin-top-15 padding-left-0">
                    <h2 class="fancy-heading txt-orange margin-top-5"><a class="txt-orange" href="<?php echo $module_url; ?>/my-travel-plan"><?php echo _l('My Travel Plan', $option); ?></a></h2>
                </div>
                <div class="col-xs-2 margin-top-15 padding-right-0"> <a href="<?php echo $module_url; ?>/my-travel-plan/add-edit" class="plus-btn-o"><i class="fa fa-plus txt-orange"></i></a> </div>
            </div>
            <div class="col-xs-12 margin-right-0 ongt-brd-btm">
                <h4><strong><?php echo _l("Ongoing Trip", "dashboard"); ?></strong></h4>
                <p>
                    <?php if ($view->arrTripData['Ongoing']) { ?>
                        <a href="<?php echo $module_url; ?>/my-travel-plan/view-detail/id/<?php echo $view->arrTripData['Ongoing']['user_trip_id']; ?>">
                            <span class="txt-orange ">
                                <?php echo $view->arrTripData['Ongoing']['trip_title']; ?>
                            </span>&nbsp;
                            <span class="right-margin">
                                <?php echo date(DATE_FORMAT, strtotime($view->arrTripData['Ongoing']['trip_date_from'])); ?> to <?php echo date(DATE_FORMAT, strtotime($view->arrTripData['Ongoing']['trip_date_to'])); ?>
                            </span>
                            <span >
                                <?php echo $view->arrTripData['Ongoing']['trip_description']; ?>
                            </span>

                            <span class="txt-orange pull-right">
                                <?php echo (isset($view->arrTripData['Ongoing']['trip_expense']['expense_currency_symbol']) and !empty($view->arrTripData['Ongoing']['trip_expense']['expense_currency_symbol'])) ? $view->arrTripData['Ongoing']['trip_expense']['expense_currency_symbol'] : $view->userBaseCurrency;
                                echo (isset($view->arrTripData['Ongoing']['trip_expense']['total_expense']) and !empty($view->arrTripData['Ongoing']['trip_expense']['total_expense'])) ? $view->arrTripData['Ongoing']['trip_expense']['total_expense'] : "0.00";
                                ?>
                            </span></a>
                        <?php
                    } else {
                        echo _l("No Ongoing trip", "dashboard");
                    }
                    ?>
                </p>
            </div>
            <div class="col-xs-6 ongt-brd-r">
                <div class="col-xs-7 padding-left-0 padding-right-0">
                    <h4><strong><?php echo _l("Upcoming Trip", "dashboard"); ?></strong></h4>
                </div>
                <div class="col-xs-5 padding-right-0 padding-left-0 padding-top-10">
                    <span class="txt-orange text-right pull-right">
                        <?php
                        if ($view->arrTripData['Upcoming']) {
                            echo (isset($view->arrTripData['Upcoming']['trip_expense']['expense_currency_symbol']) and !empty($view->arrTripData['Upcoming']['trip_expense']['expense_currency_symbol'])) ? $view->arrTripData['Upcoming']['trip_expense']['expense_currency_symbol'] : $view->userBaseCurrency;
                            echo (isset($view->arrTripData['Upcoming']['trip_expense']['total_expense']) and !empty($view->arrTripData['Upcoming']['trip_expense']['total_expense'])) ? $view->arrTripData['Upcoming']['trip_expense']['total_expense'] : "0.00";
                        }
                        ?>
                    </span>
                </div>
<?php if ($view->arrTripData['Upcoming']) { ?>
                    <a href="<?php echo $module_url; ?>/my-travel-plan/view-detail/id/<?php echo $view->arrTripData['Upcoming']['user_trip_id']; ?>">
                        <span class="clearfix"></span>
                        <span class="txt-orange"><?php echo $view->arrTripData['Upcoming']['trip_title']; ?></span>
                        <span class="clearfix"></span>
                        <span> <?php echo date(DATE_FORMAT, strtotime($view->arrTripData['Upcoming']['trip_date_from'])); ?> to <?php echo date(DATE_FORMAT, strtotime($view->arrTripData['Upcoming']['trip_date_to'])); ?></span>
                        <span class="clearfix"></span>
                        <span><?php echo $view->arrTripData['Upcoming']['trip_description']; ?></span>
                    </a>
                    <?php
                } else {
                    echo "<span class='clearfix'></span>" . _l("No upcoming trips", "dashboard");
                    ;
                }
                ?>
            </div>
            <div class="col-xs-6">
                <div class="col-xs-7 padding-left-0 padding-right-0">
                    <h4><strong><?php echo _l("Last Trip", "dashboard"); ?></strong></h4>
                </div>
                            <?php if ($view->arrTripData['Previous']) { ?>
                    <a href="<?php echo $module_url; ?>/my-travel-plan/view-detail/id/<?php echo $view->arrTripData['Previous']['user_trip_id']; ?>">
                        <div class="col-xs-5 padding-right-0 padding-left-0 padding-top-10">
                            <span class="txt-orange text-right pull-right"><?php echo (isset($view->arrTripData['Previous']['trip_expense']['expense_currency_symbol']) and !empty($view->arrTripData['Previous']['trip_expense']['expense_currency_symbol'])) ? $view->arrTripData['Previous']['trip_expense']['expense_currency_symbol'] : $view->userBaseCurrency;
                            echo (isset($view->arrTripData['Previous']['trip_expense']['total_expense']) and !empty($view->arrTripData['Previous']['trip_expense']['total_expense'])) ? $view->arrTripData['Previous']['trip_expense']['total_expense'] : "0.00";
                            ?></span>

                        </div>
                        <span class="clearfix"></span>
                        <span class="txt-orange"><?php echo $view->arrTripData['Previous']['trip_title']; ?></span>
                        <span class="clearfix"></span>
                        <span> <?php echo date(DATE_FORMAT, strtotime($view->arrTripData['Previous']['trip_date_from'])); ?> to <?php echo date(DATE_FORMAT, strtotime($view->arrTripData['Previous']['trip_date_to'])); ?></span>
                        <p><span><?php echo $view->arrTripData['Previous']['trip_description']; ?></span>&nbsp;
                        </p>
                    </a>
    <?php
} else {
    echo "<span class='clearfix'></span>" . _l("No previous trips", "dashboard");
    ;
}
?>
            </div>
        </div>
        <div class="col-xs-12 col-md-12 clearfix light-gray-bg margin-top-15 brd-right-gray padding-btm-5 gray-transperant recent-expense-div">
            <h2 class="fancy-heading txt-orange margin-top-15"><a class="txt-orange" href="<?php echo $module_url; ?>/my-expenses"><?php echo _l('Recent Expenses', $option); ?></a></h2>
            
            
            	<?php $baseCategoryClass= 'personal';?>
            	<?php  if (!empty($view->arrExpenses)): ?>
            	<ul class="exp-list list-inline">
            	<?php 
            		foreach ($view->arrExpenses as $i => $arrExpenseDetails):
                            if ($arrExpenseDetails['base_type_id'] == 1) {
                                $baseCategoryClass = 'fa-briefcase';
                            } elseif ($arrExpenseDetails['base_type_id'] == 2) {
                                $baseCategoryClass = 'fa-user';
                            } else {
                                $baseCategoryClass = 'fa-question';
                            }

                            if ($arrExpenseDetails['processor_type'] == 'master') {
                                $cardbaseCategoryClass = 'cards-master';
                            } elseif ($arrExpenseDetails['processor_type'] == 'visa') {
                                $cardbaseCategoryClass = 'cards';
                            } else {
                                $cardbaseCategoryClass = 'cards-other';
                            }

                            if ($arrExpenseDetails['payment_mode'] == 2) {
                                $cardbaseCategoryClass = 'cards-cash';
                            }
                            $cat = $arrExpenseDetails['category_title'];
                            if(isset($arrExpenseDetails['parent_category'])) {
                            	$cat = $arrExpenseDetails['parent_category']." > ".$cat;
                            }
                      ?>
            	<li class="col-xs-3"> 
                    <div class="div_expense <?php echo ($i<3)?"":"borderless-exp"?> dark-gray-txt div_expense cursor-pointer" id="div_expense_<?php echo $arrExpenseDetails['user_expense_id']; ?>" expense_id="<?php echo $arrExpenseDetails['user_expense_id']; ?>">
                        <span class="txt-orange padding-right-5 pull-left"><?php echo $arrExpenseDetails['expense_currency_symbol'] . $arrExpenseDetails['expense_amount']; ?></span>
                        <span class="pull-left padding-right-5">(<?php echo $arrExpenseDetails['base_currency_symbol'] . $arrExpenseDetails['expense_base_currency_amount']; ?>)</span>
                        <?php /* 
                        <span><?php echo $arrExpenseDetails['name']; ?></span>
                        */?>
                        <p class="mid-gray-txt clearfix"><?php echo $arrExpenseDetails['expense_summary']; ?></p>
                    	<a href="#" title="" class="">
	                    	<i class='exp-icon fa <?php echo $baseCategoryClass; ?> txt-orange'></i>
	                        <span><?php echo $cat;?></span>
	                    </a>
					</div>
            	</li>
            	<?php endforeach; ?>
            </ul>
        <?php endif; ?>
                  
            
        </div>
        <div class="col-xs-6 col-md-6 clearfix light-gray-bg margin-top-15 brd-right-gray padding-btm-5 height-160 gray-transperant">
            <h2 class="fancy-heading txt-orange margin-top-15">My Cards</h2>
            <ul class="my-cards">
                <li class="col-sm-3 text-center cursor-pointer">
                    <span class="text-center">9518</span>
                    <div class="card-mastro text-center"></div>
                    <div class="text-center"><span>$</span>1250.50</div>
                </li>
                <li class="col-sm-3 text-center cursor-pointer">
                    <span class="text-center">9518</span>
                    <div class="card-american"></div>
                    <div class="text-center clearfix"><span>$</span>1250.45</div>
                </li>
                <li class="col-sm-3 text-center cursor-pointer">
                    <span class="text-center">9518</span>
                    <div class="card-visa"></div>
                    <div class="text-center clearfix"><span>$</span>1250.65</div>
                </li>
                <li class="col-sm-3 text-center cursor-pointer">
                    <span class="text-center">9518</span>
                    <div class="card-maestro"></div>
                    <div class="text-center clearfix"><span>$</span>1250.44</div>
                </li>
            </ul>
        </div>
        <div class="col-xs-6 col-md-6 light-gray-bg margin-top-15 padding-top-10 padding-btm-10 height-160 gray-transperant">
            <div class="col-xs-3 col-sm-6 padding-left-0">
                <div class="stat-boxes widget-body cursor-pointer" onclick="redirectPage('<?php echo $module_url; ?>/users/my-profile')"> <span class="fa fa-user black"></span>
                    <h3 class="ticker--one"><?php echo _l("My Profile", "dashboard"); ?></h3>
                </div>
            </div>
            <div class="col-xs-3 col-sm-6 padding-left-0">
                <div class="stat-boxes widget-body cursor-pointer" onclick="redirectPage('<?php echo $module_url; ?>/users/app-settings')"> <span class="fa fa-gears black"></span>
                    <h3 class="ticker--two"><?php echo _l("App Setting", "dashboard"); ?></h3>
                </div>
            </div>
        </div>
        <!-- Container -->
    </div>
</div>
<script>
$('.div_expense').on("click", function() {
    var expense_id = $('#' + this.id).attr("expense_id");
    window.location.href = '<?php echo $module_url; ?>/my-expenses/view-detail/id/' + expense_id;
});
function redirectPage(location)
{
    window.location.href = location;
}
</script>