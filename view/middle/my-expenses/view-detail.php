<?php
//echo localToUtc("2015-10-29 23:59:59", "MYSQL_DATETIME"); die;
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
        <div class="col-xs-12 col-md-12 light-gray-bg clearfix padding-right-0 padding-left-0">
            <input type="hidden" name="selected_expense" id="selected_expense" value="<?php echo $view->expense_id; ?>" />
            <div class="col-sm-7 navbar-fixed-bottom padding-left-0 padding-right-0 pull-right">
                <ul class="nav nav-pills nav-justified" role="tablist">
                    <li><a href="<?php echo $module_url; ?>/my-expenses/add-edit/id/<?php echo $view->arrUserExpense['user_expense_id']; ?>"><i class="fa fa-edit export-icon white"></i><span class="white clearfix"> Edit</span></a></li>
                    <li><a id="<?php echo $view->expense_id; ?>" class="delete_expenses cursor-pointer"><i class="fa fa-trash-o white"></i><span class="white clearfix"> Delete</span></a></li>
                    <li><a id="<?php echo $view->expense_id; ?>" class="move_expenses cursor-pointer"><i class="fa fa-arrows white"></i><span class="white clearfix">Move</span></a></li>
                    <li><a id="<?php echo $view->expense_id; ?>" href="#" class="linkPdf"><i class="fa fa-upload export-icon white width-100"></i><span class="white clearfix"> <?php echo _l("Export", $option); ?></span></a></li>
                    <?php if(isset($view->arrUserExpense['trip_title']) and !empty($view->arrUserExpense['trip_title'])){ ?>
                    <li><a id="<?php echo $view->expense_id; ?>" class="remove_trip cursor-pointer"><i class="fa fa-plane white"></i><span class="white clearfix"> Remove from Trip</span></a></li>
                    <?php }else{?>
                    <li><a href="#" data-toggle="model" id="openTrip"><i class="fa fa-plane white width-100"></i><span class="white clearfix"> <?php echo _l("Assign to Trip", $option); ?></span></a></li>
                    <?php }?>
                </ul>
            </div>
        </div>

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
                        <div class="col-xs-1 padding-left-0 padding-right-0"><i class="fa <?php echo $baseCategoryClass;?> txt-orange"></i></div>
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
                            <?php foreach ($view->arrExpenseReference as $intKey => $arrImages) { ?>
                                <div class="col-xs-3 padding-left-10 padding-right-0 remove-img">
                                    <a class="html5lightbox" href="<?php echo $module_url;?>/images/user_expenses/<?php echo $arrImages['expense_filename'];?>">
                                        <img src="<?php echo $module_url; ?>/images/user_expenses/<?php echo $arrImages['expense_filename']; ?>" class="col-xs-12 no-round-brd"/>
                                    </a>
                                    <a id="<?php echo $intKey;?>" exp-image-name="<?php echo $arrImages['expense_filename']; ?>" class="cursor-pointer div_img_remove"><i class="fa fa-times-circle-o txt-orange"></i></a>
                                </div>
                                <?php } ?>
                        </div>     
                    </li>
                <?php } ?>
            </ul>
        </div>

        <!-- Container --> 
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(".delete_expenses").click(function() {
            var expense_id = this.id;
            if (confirm("<?php echo _l('Are you sure to delete?','my-expenses'); ?>")) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $module_url . "/my-expenses/delete-expense-by-id"; ?>",
                    data: {
                        id: expense_id
                    },
                    success: function(response) {
                        if (response == "SUCCESS") {
                            window.location.href = '<?php echo $obj->getModuleURL() . "/my-expenses"; ?>';
                        } else {
                            alert("<?php echo _l('Something went wrong please try after sometime.','my-expenses'); ?>");
                        }
                    }
                });
            }
        });
        
        $(".remove_trip").click(function() {
            var expense_id = this.id;
            if (confirm("<?php echo _l('Are you sure to remove trip?','my-expenses'); ?>")) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $module_url . "/my-expenses/remove-expense-trip"; ?>",
                    data: {
                        id: expense_id
                    },
                    success: function(response) {
                        if (response === "1") {
                           window.location.reload(true);
                        } else {
                            alert("<?php echo _l('Something went wrong please try after sometime.','my-expenses'); ?>");
                        }
                    }
                });
            }
        });
        
        $(".move_expenses").click(function() {
               $('#myModal').on('show.bs.modal', function() {
                    $('#myModalLabel').html('<?php echo _l('My Categories','my-expenses'); ?>');
                    $('.modal-body').html('<iframe style="border: 1px solid #ccc;" id="iframe_container" src="<?php echo $module_url; ?>/my-categories/list/popup/yes"  height="350" width="100%" frameborder="1"></iframe>');
                });
                $('#myModal').modal();          
        });
        
        $(".div_img_remove").click(function() {
            var imageName = $('#' + this.id).attr('exp-image-name');
            if (confirm("<?php echo _l('Are you sure to remove file?','my-expenses'); ?>")) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $module_url . "/my-expenses/remove-expense-file"; ?>",
                    data: {
                        filename: imageName,
                        expense_id:$('#selected_expense').val()
                    },
                    success: function(response) {
                        if (response === "1") {
                            window.location.reload(true);
                        } else {
                            alert("<?php echo _l('Something went wrong please try after sometime.','my-expenses'); ?>");
                        }
                    }
                });
            }
        });
        
        $(".linkPdf").click(function() {
            var expense_id = this.id;
            
            $.ajax({
                type: "POST",
                url: "<?php echo $module_url . "/my-expenses/export-pdf-user-expense"; ?>",
                data: {
                    id: expense_id
                },
                success: function(response) {
                    window.location.href = '<?php echo $obj->getModuleURL() . "/my-expenses/download-expense-pdf"; ?>';
                }
            });
            
        });
        
        $('#openTrip').click(function() {
            $('#myModal').on('show.bs.modal', function() {
                $('#myModalLabel').html('<?php echo _l('My Travel Plans','my-expenses'); ?>');
                $('.modal-body').html('<iframe style="border: 1px solid #ccc;" id="iframe_container" src="<?php echo $module_url; ?>/my-travel-plan/get-trip-list/popup/yes"  height="350" width="100%" frameborder="1"></iframe>');
            });
            $('#myModal').modal();
        });        
        
    });
    function closeModelBox() {
        $('#myModal').modal('hide')
    }    
</script>