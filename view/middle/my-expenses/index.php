<div class="wrapper" id="div_container">
    <div class="container con-padding-tb">
        <div class="col-xs-12 col-md-12 light-gray-bg clearfix padding-right-0 padding-left-0">
            <div class="col-xs-11 col-sm-4 padding-left-0 padding-right-0 border-right">
                <div id="reportrange" class="col-xs-12 col-sm-12 padding-btm-5 text-center">
                    <span style="color:#000">
                        <?php echo $view->fromDate; ?> - <?php echo $view->toDate; ?>
                    </span>
                    <b class="caret"></b>
                </div>
                <div class="col-xs-12 col-sm-12 text-left border-top text-center padding-top-0">
                    <p class="margin-btm-0" id="grand_total" style="line-height: 25px;"><strong>Total: </strong> <?php echo $view->arrTotal['expense_currency_symbol'] . $view->arrTotal['total_expense']; ?></p>
                </div>
            </div>
            <div class="col-xs-1 col-sm-1 text-center padding-left-0 padding-right-0 padding-top-15">
                <a onclick="" title="Date" id="dat_calender" class="cursor-pointer-default"><i class="fa fa-calendar cal-orange"></i></a>
            </div>
            <div class="col-sm-7 navbar-fixed-bottom padding-left-0 padding-right-0">
                <ul class="nav nav-pills nav-justified" role="tablist">
                    <li><a class="cursor-pointer"  data-toggle="modal" id="openBtn"><i class="fa fa-arrows white width-100"></i><span class="white clearfix"><?php echo _l("Move", $option); ?></span></a></li>
                    <li><a class="cursor-pointer" id="delete_expenses"><i class="fa fa-trash-o white width-100"></i><span class="white clearfix"> <?php echo _l("Delete", $option); ?></span></a></li>
                    <li><a class="linkExport cursor-pointer"><i class="fa fa-upload export-icon white width-100"></i><span class="white clearfix"> <?php echo _l("Export", $option); ?></span></a></li>
                    <li><a class="cursor-pointer" data-toggle="model" id="openTrip"><i class="fa fa-plane white width-100"></i><span class="white clearfix"> <?php echo _l("Assign to Trip", $option); ?></span></a></li>
                </ul>
            </div>
        </div>
        <?php include($module_path . "/application/global/message.php"); ?>
        <!--AJAXPAGESTART-->
        <div id="get-html-ajax">
        <span class="hidden-total" style="display:none"><strong>Total: </strong> <?php echo $view->arrTotal['expense_currency_symbol'] . $view->arrTotal['total_expense']; ?></span>
        <?php
        if (!empty($view->arrExpenses))
            foreach ($view->arrExpenses as $strKeyDate => $arrExpenseDateWise) {
                ?>
                <div class="clearfix border-tb mid-gray-bg padding-top-5 padding-btm-5"><!--today div-->
                    <div class="col-xs-5 col-sm-6"><?php
                        if (date('m-d-y', strtotime($strKeyDate)) == date('m-d-y')) {
                            echo "Today";
                        } elseif (date('m-d-y', strtotime($strKeyDate)) == date('m-d-y', strtotime("-1 day"))) {
                            echo "Yesterday";
                        } else {
                            echo date(DATE_FORMAT, strtotime($strKeyDate));
                        }
                        ?></div>
                    <div class="col-xs-5 col-sm-5 text-right">Total: <?php echo $arrExpenseDateWise['total_amount_symbol'] . array_sum($arrExpenseDateWise['total_amount']); ?></div>
                    <div class="col-xs-1 col-sm-1 pull-right text-center">
                        <input type="checkbox" name="chk_all_<?php echo $strKeyDate; ?>" id="chk_all_<?php echo $strKeyDate; ?>" value="<?php echo $strKeyDate; ?>" />
                    </div>
                </div>
                <div class="col-xs-12 pull-left padding-left-0 padding-right-0">
                    <ul id="scrollbox7" class="your-message">
                        <?php
                        foreach ($arrExpenseDateWise['data'] as $arrExpenseDetails) {

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
                            ?>
                            <li class="border-btm">
                                <div class="col-xs-1 padding-top-10 brifcase-ic text-center">
                                    <a href="#" title="" class="">
                                        <i class='fa <?php echo $baseCategoryClass; ?> txt-orange'></i>
                                    </a>
                                </div>
                                <div class="col-xs-8 padding-top-10 dark-gray-txt div_expense_row cursor-pointer" id="div_expense_<?php echo $arrExpenseDetails['user_expense_id']; ?>" expense_id="<?php echo $arrExpenseDetails['user_expense_id']; ?>">
                                    <span class="txt-orange"><?php echo $arrExpenseDetails['expense_currency_symbol'] . $arrExpenseDetails['expense_amount']; ?></span>
                                    <span>(<?php echo $arrExpenseDetails['base_currency_symbol'] . $arrExpenseDetails['expense_base_currency_amount']; ?>) | <?php echo $arrExpenseDetails['name']; ?> | </span>
                                    <span class="mid-gray-txt"><?php echo date(DATE_TIME_FORMAT, strtotime($arrExpenseDetails['expense_date']." ".$arrExpenseDetails['expense_time'])); ?></span>
                                    <p class="mid-gray-txt clearfix"><?php echo $arrExpenseDetails['expense_summary']; ?></p>
                                    <span class="padding-top-10">
                                        <a href="">
                                            <?php
                                            if (!empty($arrExpenseDetails['parent_category']) and !empty($arrExpenseDetails['category_title'])) {
                                                echo ($arrExpenseDetails['parent_category']) ? $arrExpenseDetails['parent_category'] : "";
                                                ?> > <?php echo ($arrExpenseDetails['category_title']) ? $arrExpenseDetails['category_title'] : "";
                                            } elseif (!empty($arrExpenseDetails['parent_category']) and empty($arrExpenseDetails['category_title'])) {
                                                echo $arrExpenseDetails['parent_category'];
                                            } elseif (empty($arrExpenseDetails['parent_category']) and !empty($arrExpenseDetails['category_title'])) {
                                                echo $arrExpenseDetails['category_title'];
                                            }
                                            ?>
                                        </a>
                                    </span>
                                </div>
                                <div class="col-xs-1 padding-top-10">
                                    <span class="text-right <?php echo $cardbaseCategoryClass; ?>"><?php echo substr($arrExpenseDetails['card_number'], -4, 4); ?></span>
                                </div>
                                <div class="col-xs-1 text-center">
                                    <span class="triangle-bottomright">
                                        <?php if (!empty($arrExpenseDetails['trip_title'])) { ?><span title="<?php echo $arrExpenseDetails['trip_title']?>"><i class="fa fa-plane dark-gray-tx font-ic-22"></i></span><?php } ?>
                                    </span>
                                </div>
                                <div class="col-xs-1 padding-top-15 brifcase-ic text-center">
                                    <?php if ($arrExpenseDetails['payment_mode'] == 2) { ?>
                                        <input type="checkbox" name="chk_expense[]" id="chk_<?php echo $strKeyDate . "_" . $arrExpenseDetails['user_expense_id']; ?>" eid="<?php echo $arrExpenseDetails['user_expense_id']; ?>" value="<?php echo $arrExpenseDetails['user_expense_id']; ?>" />
                                    <?php } ?>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } else {
            ?>
            <div class="col-xs-12 pull-left padding-left-0 padding-right-0">
                <ul id="scrollbox7">
                    <li class="alert alert-danger alert-dismissable margin-top-15"><span><?php echo _l("No records found.", $option); ?></span></li>
                </ul>
            </div>
        <?php } ?>
            </div>
            
<script>
    $(document).ready(function() {
    	$('#grand_total').html($('.hidden-total').html());
        $('#div_common_error_msg').addClass('div-expense-alert');
        $('#div_common_action_msg').addClass('div-expense-alert');
        $('[id^="chk_all_"]').each(function() {
            $("#" + this.id).click(function() {
                var checkId = $("#" + this.id).val();
                var elementId = "chk_" + checkId + "_";

                if ($(this).is(':checked')) {
                    $('[id^="' + elementId + '"]').each(function() {
                        $("#" + this.id).prop("checked", true);
                    });
                } else {
                    $('[id^="' + elementId + '"]').each(function() {
                        $("#" + this.id).removeAttr('checked');
                    });
                }
            });
        });

        $("#dat_calender").click(function() {
            $("#reportrange").trigger("click");
        });

        $('#openBtn').click(function() {
            var blnChecked = false;
            var arrData = new Array;
            $('[id^="chk_"]').each(function() {
                if ($(this).is(':checked')) {
                    blnChecked = true;
                    if ($('#' + this.id).attr('eid')) {
                        arrData.push($('#' + this.id).attr('eid'));
                    }
                }

            });
            if (blnChecked) {
                $('#selected_expense').val('');
                $('#selected_expense').val(arrData);
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModalLabel').html('My Categories');
                    $('.modal-body').html('<iframe style="border: 1px solid #ccc;" id="iframe_container" src="<?php echo $module_url; ?>/my-categories/list/popup/yes"  height="350" width="100%" frameborder="1"></iframe>');
                });
                $('#myModal').modal();
            } else {
                alert("<?php echo _l('Please select any expense to move.','my-expenses'); ?>");
            }
        });

        $('#openTrip').click(function() {
            var blnChecked = false;
            var arrData = new Array;
            $('[id^="chk_"]').each(function() {
                if ($(this).is(':checked')) {
                    blnChecked = true;
                    if ($('#' + this.id).attr('eid')) {
                        arrData.push($('#' + this.id).attr('eid'));
                    }
                }
            });
            if (blnChecked) {
                $('#selected_expense').val('');
                $('#selected_expense').val(arrData);
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModalLabel').html('My Travel Plans');
                    $('.modal-body').html('<iframe style="border: 1px solid #ccc;" id="iframe_container" src="<?php echo $module_url; ?>/my-travel-plan/get-trip-list/popup/yes"  height="350" width="100%" frameborder="1"></iframe>');
                });
                $('#myModal').modal();
            } else {
                alert("<?php echo _l('Please select any expense to assign to trip.','my-expenses'); ?>");
            }
        });


        $('#reportrange').daterangepicker({
            startDate: moment().subtract('days', 29),
            endDate: moment(),
            minDate: '01/01/2012',
            dateLimit: {
                days: 1000
            },
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                'Last 7 Days': [moment().subtract('days', 6), moment()],
                'Last 30 Days': [moment().subtract('days', 29), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
            },
            opens: 'left',
            buttonClasses: ['btn btn-default'],
            applyClass: 'btn-small btn-primary',
            cancelClass: 'btn-small',
            format: 'MM/DD/YYYY',
            separator: ' to ',
            locale: {
                applyLabel: 'Submit',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom Range',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            }
        }, function(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $.ajax({
                type: "POST",
                url: "<?php echo $module_url . "/my-expenses/get-filter-data"; ?>",
                data: {
                    from_date: start.format('YYYY-MM-DD 00:00:00'),
                    to_date: end.format('YYYY-MM-DD 23:59:59')
                },
                success: function(response) {
                    var objResponse = jQuery.parseJSON(response);
                    $('#get-html-ajax').html('');
                    $('#get-html-ajax').html(objResponse.HTML);
                    //applyUtc2local();
                }
            });
        });

       
        $('.daterangepicker .ranges .cancelBtn').click(function() {
            //do something, like clearing an input
            $.ajax({
                type: "POST",
                url: "<?php echo $module_url . "/my-expenses/get-filter-data"; ?>",
                success: function(response) {
                    var objResponse = jQuery.parseJSON(response);
                    $('#get-html-ajax').html('');
                    $('#get-html-ajax').html(objResponse.HTML);
                }
            });
        });
        
        $('.li_custom').on("click", function() {
            $('html body div.daterangepicker.dropdown-menu.opensleft').css("width", "55%");
        });

        $('.div_expense_row').on("click", function() {
            var expense_id = $('#' + this.id).attr("expense_id");
            window.location.href = '<?php echo $module_url; ?>/my-expenses/view-detail/id/' + expense_id;
        });

        $("#delete_expenses").click(function() {
            var blnChecked = false;
            var arrData = new Array;
            $('[id^="chk_"]').each(function() {
                if ($(this).is(':checked')) {
                    blnChecked = true;
                    if ($('#' + this.id).attr('eid')) {
                        arrData.push($('#' + this.id).attr('eid'));
                    }
                }

            });
            if (blnChecked) {
                $('#selected_expense').val('');
                $('#selected_expense').val(arrData);

                if ($('#selected_expense').val()) {

                    if (confirm("Are you sure to delete?")) {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo $module_url . "/my-expenses/delete-expense"; ?>",
                            data: {
                                selected_expense: arrData
                            },
                            success: function(response) {
                                if (response === 'SUCCESS') {
                                    location.reload();
                                    ;
                                }
                            }
                        });
                    }
                }
                else
                {
                    alert("<?php echo _l('Please select any expense to delete.','my-expenses'); ?>");
                }
            } else {
                alert("<?php echo _l('Please select any expense to delete.','my-expenses'); ?>");
            }
        });
    });
    function closeModelBox() {
        $('#myModal').modal('hide')
    }
    $(".linkExport").click(function() {
        var arrId = new Array;
        $('[id^="chk_"]').each(function() {
            if ($(this).is(':checked')) {
                if ($('#' + this.id).attr('eid')) {
                    arrId.push($('#' + this.id).attr('eid'));
                }
            }
        });
        window.location.href = '<?php echo $module_url; ?>/my-expenses/export-user-expenses/expid/' + arrId;
    });


</script>
            
        <!--AJAXPAGEEND-->
        <!-- Container -->
    </div>
    <input type="hidden" name="selected_expense" id="selected_expense" value="" />
</div>

<?php if (isset($_GET["donotshowjs"])) { ?>

<?php } else { ?>

<?php } ?>
