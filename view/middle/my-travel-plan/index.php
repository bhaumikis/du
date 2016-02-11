<div class="wrapper" id="div_container">
    <div class="container con-padding-tb">
        <div class="col-xs-12 col-md-12 light-gray-bg clearfix padding-right-0 padding-left-0">
            <div class="col-xs-12 col-sm-6 padding-left-0 padding-right-0 border-right">
                <div id="reportrange" class="col-xs-12 col-sm-12 padding-btm-5 text-left adding-left-0">
                    <span style="color:#000; line-height:23px;"> <?php echo $view->fromDate; ?> - <?php echo $view->toDate; ?> </span> 
                    <b class="caret"></b>
                    <div class="col-xs-1 col-sm-1 text-center padding-left-0 padding-right-0"> 
                        <a onclick="" title="Date" id="dat_calender" class="cursor-pointer-default">
                            <i class="fa fa-calendar cal-orange"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 text-right padding-top-0 navbar-fixed-bottom">
                <ul class="nav nav-pills nav-justified" role="tablist">
                    <li><a class="cursor-pointer" id="delete_trips"><i class="fa fa-trash-o white width-100"></i>
                            <span class="white clearfix"> <?php echo _l("Delete", $option); ?></span></a></li>
                            <li><a class="linkExport cursor-pointer"><i class="fa fa-upload export-icon white"></i><span class="white clearfix"> <?php echo _l("Export", $option); ?></span></a></li>                            
                </ul>
            </div>
        </div>
        <?php include($module_path . "/application/global/message.php"); ?>
        <!--AJAXPAGESTART-->
        <div id="get-html-ajax">        
        <?php
        if (!empty($view->arrUserTrips))
            foreach ($view->arrUserTrips as $strTripStatus => $arrTripDateWise) {
                ?>
                <div class="clearfix border-tb mid-gray-bg padding-top-5 padding-btm-5"><!--today div-->
                    <div class="col-xs-5 col-sm-6">
                        <?php echo $strTripStatus; ?>
                    </div>
                    <div class="col-xs-5 col-sm-5 text-right">
                        Total: <?php echo ($view->arrTripTotal[$strTripStatus]['expense_currency_symbol']) ? $view->arrTripTotal[$strTripStatus]['expense_currency_symbol'] : $view->userBaseCurrency; ?><?php echo ($view->arrTripTotal[$strTripStatus]['total_expense']) ? $view->arrTripTotal[$strTripStatus]['total_expense'] : "0.00"; ?>
                        <?php if(strtolower($strTripStatus) == 'ongoing'){?>(<?php echo ($view->arrOngoingData['trip_currency']) ? $view->arrOngoingData['trip_currency'] : $view->userBaseCurrency; ?><?php echo ($view->arrOngoingData['trip_budget']) ? $view->arrOngoingData['trip_budget'] : "0.00";?>)<?php }?>
                    </div>
                    <div class="col-xs-1 col-sm-1 pull-right text-center">
                        <input type="checkbox" name="chk_all_<?php echo $strTripStatus; ?>" id="chk_all_<?php echo $strTripStatus; ?>" value="<?php echo $strTripStatus; ?>" />
                    </div>
                </div>
                <div class="col-xs-12 pull-left padding-left-0 padding-right-0">
                    <ul id="scrollbox7" class="your-message">
                        <?php
                        foreach ($arrTripDateWise as $arrTripDetails) {

                            if ($arrTripDetails['base_expense_type_id'] == 1) {
                                $baseCategoryClass = 'fa-briefcase';
                            } elseif ($arrTripDetails['base_expense_type_id'] == 2) {
                                $baseCategoryClass = 'fa-user';
                            } else {
                                $baseCategoryClass = 'fa-question';
                            }

                            if ($arrTripDetails['processor_type'] == 'master') {
                                $cardbaseCategoryClass = 'cards-master';
                            } elseif ($arrTripDetails['processor_type'] == 'visa') {
                                $cardbaseCategoryClass = 'cards';
                            } else {
                                $cardbaseCategoryClass = 'cards-other';
                            }

                            if ($arrTripDetails['payment_mode'] == 2) {
                                $cardbaseCategoryClass = 'cards-cash';
                            }
                            ?>
                            <li class="border-btm">
                                <div class="col-xs-1 padding-top-10 brifcase-ic text-center"> <a href="#" title="" class=""> <i class='fa <?php echo $baseCategoryClass; ?> txt-orange'></i> </a> </div>
                                <div class="col-xs-9 padding-top-10 dark-gray-txt div_travel cursor-pointer" id="div_travel_<?php echo $arrTripDetails['user_trip_id']; ?>" travel_id="<?php echo $arrTripDetails['user_trip_id']; ?>"> 
                                    <span><?php echo $arrTripDetails['trip_title']; ?> | </span> <span class="txt-orange"><?php echo ($arrTripDetails['trip_expense']['expense_currency_symbol'])?$arrTripDetails['trip_expense']['expense_currency_symbol']:$arrTripDetails["trip_currency"] . " ";
                                        echo ($arrTripDetails['trip_expense']['total_expense']) ? $arrTripDetails['trip_expense']['total_expense'] : '0.00'; ?></span>
                                    <p class="mid-gray-txt clearfix"><?php echo $arrTripDetails['trip_description']; ?></p>
                                    <span class="padding-top-10"> <a href=""><?php echo date(DATE_FORMAT, strtotime($arrTripDetails['trip_date_from']))." ".date('h:i A', strtotime($arrTripDetails['trip_date_from'])) . " to " . date(DATE_FORMAT, strtotime($arrTripDetails['trip_date_to']))." ".date('h:i A', strtotime($arrTripDetails['trip_date_to'])); ?> </a> </span>
                                </div>
                                <div class="col-xs-1 padding-top-10"> 
                                    <?php if($arrTripDetails['have_expense'] == "yes"){?>
                                    <a title="View Expense" href="<?php echo $module_url; ?>/my-expenses/index/t/<?php echo $arrTripDetails['user_trip_id']; ?>"><span class="text-right mytrip-card"></span></a> 
                                     <?php }else{ ?>
                                     <span class="text-right mytrip-card-inactive"></span>
                                     <?php } ?>
                                </div>
                                <div class="col-xs-1 padding-top-10 brifcase-ic text-center">                                   
                                    <input type="checkbox" name="chk_trips[]" id="chk_<?php echo $strTripStatus; ?>_<?php echo $arrTripDetails['user_trip_id']; ?>" tid="<?php echo $arrTripDetails['user_trip_id']; ?>" value="<?php echo $arrTripDetails['user_trip_id']; ?>" />
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
        <!--AJAXPAGEEND-->
        <!-- Container --> 
    </div>
</div>
<input type="hidden" name="selected_trip" id="selected_trip" value="" />
<?php if (isset($_GET["donotshowjs"])) { ?>
<?php } else { ?>
<?php } ?>
<script>
    $(document).ready(function() {
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

        $('.div_travel').on("click", function() {
            var trip_id = $('#' + this.id).attr("travel_id");
            window.location.href = '<?php echo $module_url; ?>/my-travel-plan/view-detail/id/' + trip_id;
        });

        $('#openBtn').click(function() {
            var blnChecked = false;
            $('[id^="chk_"]').each(function() {
                if ($(this).is(':checked')) {
                    blnChecked = true;
                }

            });
            if (blnChecked) {
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModalLabel').html('<?php echo _l("My Categories", "my-travel-plan"); ?>');
                    $('.modal-body').html('<iframe style="border: 1px solid #ccc;" id="iframe_container" src="<?php echo $module_url; ?>/my-categories/index/popup/yes"  height="350" width="100%" frameborder="1"></iframe>');
                });
                $('#myModal').modal();
            } else {
                alert("<?php echo _l("Please select any expense to move.", "my-travel-plan"); ?>");
            }
        });

        $('#openTrip').click(function() {
            var blnChecked = false;
            $('[id^="chk_"]').each(function() {
                if ($(this).is(':checked')) {
                    blnChecked = true;
                }

            });
            if (blnChecked) {
                $('#myModal').on('show.bs.modal', function() {
                    $('#myModalLabel').html('<?php echo _l("My Travel Plans", "my-travel-plan"); ?>');
                    $('.modal-body').html('<iframe style="border: 1px solid #ccc;" id="iframe_container" src="<?php echo $module_url; ?>/my-travel-plan/index/popup/yes"  height="350" width="100%" frameborder="1"></iframe>');
                });
                $('#myModal').modal();
            } else {
                alert("<?php echo _l("Please select any expense to move.", "my-travel-plan"); ?>");
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
                'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
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
                url: "<?php echo $module_url . "/my-travel-plan/get-filter-data"; ?>",
                data: {
                	from_date: start.format('YYYY-MM-DD 00:00:00'),
                    to_date: end.format('YYYY-MM-DD 23:59:59')
                },
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
        
        $("#delete_trips").click(function() {
            var blnChecked = false;
            var arrData = new Array;
            $('[id^="chk_"]').each(function() {
                if ($(this).is(':checked')) {
                    blnChecked = true;
                    if ($('#' + this.id).attr('tid')) {
                        arrData.push($('#' + this.id).attr('tid'));
                    }
                }

            });
            if (blnChecked) {
                $('#selected_trip').val('');
                $('#selected_trip').val(arrData);
                
                if($('#selected_trip').val()){
                 
                    if (confirm("<?php echo _l("Are you sure to delete?", "my-travel-plan"); ?>")) {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo $module_url . "/my-travel-plan/delete-trips"; ?>",
                            data: {
                                selected_trip: arrData
                            },
                            success: function(response) {
                                if(response === 'SUCCESS'){
                                    location.reload();;
                                }
                            }
                        });  
                    } 
                }
                else
                {
                    alert("<?php echo _l("Please select any trip to delete.", "my-travel-plan"); ?>");
                }
            } else {
                alert("<?php echo _l("Please select any trip to delete.", "my-travel-plan"); ?>");
            }
        });
        $(".linkExport").click(function() {
            var arrId = new Array;
            $('[id^="chk_"]').each(function() {
                if ($(this).is(':checked')) {
                    if ($('#' + this.id).attr('tid')) {
                        arrId.push($('#' + this.id).attr('tid'));
                    }
                }
            });            
            window.location.href='<?php echo $module_url; ?>/my-travel-plan/export-user-trips/tripid/'+arrId;
        });        
    });
    function closeModelBox() {
        $('#myModal').modal('hide')
    }
</script> 
