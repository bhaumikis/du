<div class="wrapper" id="div_container">
    <div class="container con-padding-tb">
        <div class="col-xs-12 col-md-12 light-gray-bg clearfix padding-right-0 padding-left-0">
            <div style="float: left;width: 30%;"><h4 class="padding-left-10"><?php echo _l("On Going Trip", "my-travel-plan"); ?></h4></div>
            <div class="col-sm-7 navbar-fixed-bottom padding-left-0 padding-right-0 pull-right">
                <ul class="nav nav-pills nav-justified" role="tablist">
                    <li><a href="<?php echo $module_url;?>/my-travel-plan/add-edit/id/<?php echo $view->arrTripData['user_trip_id'];?>"><i class="fa fa-edit export-icon white"></i><span class="white clearfix"> <?php echo _l("Edit", $option); ?></span></a></li>
                    <li><a class="cursor-pointer delete_travel" id="<?php echo $view->arrTripData['user_trip_id']; ?>" ><i class="fa fa-trash-o white"></i><span class="white clearfix"> <?php echo _l("Delete", $option); ?></span></a></li>
                    <li><a id="<?php echo $view->arrTripData['user_trip_id'];?>" href="#" class="linkPdf"><i class="fa fa-upload export-icon white width-100"></i><span class="white clearfix"> <?php echo _l("Export", $option); ?></span></a></li>
<!--                    <li><a href="#"><i class="fa fa-arrows white"></i><span class="white clearfix">Move</span></a></li>
                    <li><a href="#"><i class="fa fa-plane white"></i><span class="white clearfix"> Remove from Trip</span></a></li>-->
                </ul>
            </div>
        </div>
        <div class="col-xs-12 pull-left padding-left-0 padding-right-0">
            <ul id="scrollbox7" class="your-message">
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <div class="col-xs-6 padding-left-0 padding-right-0 font-bold"><?php echo _l("TRIP_NAME", "my-travel-plan"); ?></div>
                        <div class="col-xs-6 padding-left-0 padding-right-0 text-right"><?php echo $view->arrTripData['trip_title'];?></div>
                    </div>
                </li>
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <div class="col-xs-6 padding-left-0 padding-right-0 font-bold"><?php echo _l("DESTINATION", "my-travel-plan"); ?></div>
                        <div class="col-xs-6 padding-left-0 padding-right-0 text-right"><?php echo $view->arrTripData['destination'];?></div>
                    </div>
                </li>
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <div class="col-xs-6 padding-left-0 padding-right-0 font-bold"><?php echo _l("TRIP_TYPE", "my-travel-plan"); ?></div>
                        <div class="col-xs-6 padding-left-0 padding-right-0 text-right"><?php echo $view->arrTripData['base_expense_type_name'];?></div>
                    </div>
                </li>
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <div class="col-xs-6 padding-left-0 padding-right-0 font-bold"><?php echo _l("BUDGET", "my-travel-plan"); ?></div>
                        <div class="col-xs-6 padding-left-0 padding-right-0 text-right"><?php echo $view->arrTripData['trip_currency'];?> <?php echo $view->arrTripData['trip_budget'];?></div>
                    </div>
                </li>
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <div class="col-xs-6 padding-left-0 padding-right-0 font-bold"><?php echo _l("DATE", "my-travel-plan"); ?></div>
                        <div class="col-xs-6 padding-left-0 padding-right-0 text-right"><?php echo date(DATE_TIME_FORMAT, strtotime($view->arrTripData['trip_date_from']))." - ".date(DATE_TIME_FORMAT, strtotime($view->arrTripData['trip_date_to']));?></div>
                    </div>
                </li>
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <div class="col-xs-6 padding-left-0 padding-right-0 font-bold"><?php echo _l("TRIP_EXPENSE", "my-travel-plan"); ?></div>
                        <div class="col-xs-6 padding-left-0 padding-right-0 text-right"><?php echo $view->arrTripExpense['expense_currency_symbol']." ".$view->arrTripExpense['total_expense'];?></div>
                    </div>
                </li>
                <?php if(isset($view->arrTripData['trip_description']) and !empty($view->arrTripData['trip_description'])){?>
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                    	<div class="col-xs-3 padding-left-0 padding-right-0 font-bold">Trip Description</div>
                    	<div class="col-xs-9"><?php echo $view->arrTripData['trip_description'];?></div>
                    </div>
                </li>
                
                <?php }?>
                 <?php if(is_array($view->arrTripReference) and !empty($view->arrTripReference)){?>
                <li class="border-btm">
                    <div class="col-xs-12 padding-top-10 padding-btm-10 dark-gray-txt">
                        <?php foreach($view->arrTripReference as $intKey => $arrImages){?>
                            <div class="col-xs-3 padding-left-10 padding-right-0 remove-img">
                                <a class="html5lightbox" href="<?php echo $module_url;?>/images/user_trips/<?php echo $arrImages['trip_filename'];?>">
                                    <img src="<?php echo $module_url;?>/images/user_trips/<?php echo $arrImages['trip_filename'];?>" class="col-xs-12 no-round-brd"/>
                                </a>
                                <a id="<?php echo $intKey;?>" trip-image-name="<?php echo $arrImages['trip_filename']; ?>" class="cursor-pointer div_img_remove"><i class="fa fa-times-circle-o txt-orange"></i></a>
                            </div>
                        <?php }?>
                    </div>      
                </li>
                 <?php }?>
            </ul>
        </div>
        <!-- Container --> 
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $(".delete_travel").click(function() {
        var travel_id = this.id;
        if (confirm("<?php echo _l("Are you sure to delete? This will unlink all your travel related expenses for this travel.", "my-travel-plan"); ?>")) {
            $.ajax({
                type: "POST",
                url: "<?php echo $module_url . "/my-travel-plan/delete-travel-by-id"; ?>",
                data: {
                    id: travel_id
                },
                success: function(response) {
                    if(response == "SUCCESS") {
                        window.location.href = '<?php echo $obj->getModuleURL() . "/my-travel-plan"; ?>';
                    } else {
                        alert("<?php echo _l("Something went wrong please try after sometime.", "my-travel-plan"); ?>");
                    }
                }
            });  
        }
    });
    
        $(".div_img_remove").click(function() {
            var imageName = $('#' + this.id).attr('trip-image-name');
            if (confirm("<?php echo _l("Are you sure to remove file?", "my-travel-plan"); ?>")) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $module_url . "/my-travel-plan/remove-trip-file"; ?>",
                    data: {
                        filename: imageName,
                        trip_id:<?php echo $view->arrTripData['user_trip_id'];?>
                    },
                    success: function(response) {
                        if (response === "1") {
                            window.location.href = '<?php echo $obj->getModuleURL() . "/my-travel-plan"; ?>';
                        } else {
                            alert("<?php echo _l("Something went wrong please try after sometime.", "my-travel-plan"); ?>");
                        }
                    }
                });
            }
        });

    $(".linkPdf").click(function() {
            var trip_id = this.id;
            
            $.ajax({
                type: "POST",
                url: "<?php echo $module_url . "/my-travel-plan/export-pdf-user-trip"; ?>",
                data: {
                    id: trip_id
                },
                success: function(response) {
                    window.location.href = '<?php echo $obj->getModuleURL() . "/my-travel-plan/download-trip-pdf"; ?>';
                }
            });
            
        });
});   
</script>