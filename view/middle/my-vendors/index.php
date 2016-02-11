<div class="wrapper" id="div_container">
    <div class="container con-padding-tb">
        <div class="col-xs-12 col-md-12 light-gray-bg clearfix padding-right-0 padding-left-0">
            <div class="col-xs-12 col-sm-6 padding-left-0 padding-right-0 border-right">
                <div id="reportrange" class="col-xs-12 col-sm-12 padding-btm-5 text-left adding-left-0">
                    <div class="col-xs-1 col-sm-12 text-center padding-left-0 padding-right-0">
                        <div class="inline-form">
                            <input class="input-style" type="text" name="search_vendor" id="search_vendor" value="" placeholder="<?php echo _l("Search vendor(s)", $option); ?>" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 text-right padding-top-0 navbar-fixed-bottom">
                <ul class="nav nav-pills nav-justified" role="tablist">
                    <li><a class="cursor-pointer" id="delete_vendor"><i class="fa fa-trash-o white width-100"></i>
                            <span class="white clearfix"> <?php echo _l("Delete", $option); ?></span></a></li>
                    <!--<li><a class="linkExport cursor-pointer"><i class="fa fa-upload export-icon white"></i><span class="white clearfix"> <?php echo _l("Export", $option); ?></span></a></li>-->
                </ul>
            </div>
        </div>
        <?php include($module_path . "/application/global/message.php"); ?>
        <div id="get-html-ajax">
                <div class="clearfix border-tb mid-gray-bg padding-top-5 padding-btm-5"><!--today div-->
                    <div class="col-xs-5 col-sm-6"> My Vendors</div>
                    <div class="col-xs-5 col-sm-5 text-right"></div>
                    <div class="col-xs-1 col-sm-1 pull-right text-center">
                        <input type="checkbox" name="chk_all_vendors" id="chk_all_vendors" value="" />
                    </div>
                </div>            
            <?php
            if (!empty($view->vendors)){
                    ?>
                    <div class="col-xs-12 pull-left padding-left-0 padding-right-0">
                        <ul id="scrollbox7" class="your-message">
                            <?php
                            foreach ($view->vendors as $arrVendors) {
                                ?>
                                <li class="border-btm">
                                    <div class="col-xs-10 padding-top-10 dark-gray-txt div_vendor cursor-pointer" id="div_vendor_<?php echo $arrVendors['expense_vendor_id']; ?>" vendor_id="<?php echo $arrVendors['expense_vendor_id']; ?>">
                                        <span><?php echo $arrVendors['name']; ?> </span> 
                                        <p class="mid-gray-txt clearfix"><?php echo $arrVendors['description']; ?></p>
                                    </div>
                                    <div class="col-xs-1 padding-top-10">
                                    <?php if($arrVendors['have_expense'] == "yes"){?>
                                     <a title="View Expense" href="<?php echo $module_url; ?>/my-expenses/index/v/<?php echo $arrVendors['expense_vendor_id']; ?>"><span class="text-right mytrip-card"></span></a> 
                                    <?php }else{ ?>
                                     <span class="text-right mytrip-card-inactive"></span>
                                     <?php } ?>
                                     </div>
                                    <div class="col-xs-1 padding-top-10 check-to-box text-center">
                                        <input <?php echo ($arrVendors['have_expense'] == "yes")?"disabled":"";?> type="checkbox" name="chk_vendors[]" id="chk_vendor_<?php echo $arrVendors['expense_vendor_id']; ?>" vid="<?php echo $arrVendors['expense_vendor_id']; ?>" value="<?php echo $arrVendors['expense_vendor_id']; ?>" />
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
    </div>
</div>
<script>
    $(document).ready(function() {    
        $('#div_common_error_msg').addClass('div-expense-alert');
        $('#div_common_action_msg').addClass('div-expense-alert');    
        $("#delete_vendor").click(function() {
            var blnChecked = false;
            var arrData = new Array;
            $('[id^="chk_vendor_"]').each(function() {
                if ($(this).is(':checked')) {
                    blnChecked = true;
                    if ($('#' + this.id).attr('vid')) {
                        arrData.push($('#' + this.id).attr('vid'));
                    }
                }

            });
            if (blnChecked) {
                 if(arrData){
                 
                    if (confirm("<?php echo _l("Are you sure to delete?", $option); ?>")) {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo $module_url . "/my-vendors/delete-vendors"; ?>",
                            data: {
                                selected_vendors: arrData
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
                    alert("<?php echo _l("Please select any vendor to delete.", "my-travel-plan"); ?>");
                }
            } else {
                alert("<?php echo _l("Please select any vendor to delete.", "my-travel-plan"); ?>");
            }
        });
        
            $("#chk_all_vendors").click(function() {
                if ($(this).is(':checked')) {
                    $('[id^="chk_vendor_"]').each(function() {
                        if(!$("#" + this.id).attr('disabled')){
                            $("#" + this.id).prop("checked", true);
                        }
                    });
                } else {
                    $('[id^="chk_vendor_"]').each(function() {
                        $("#" + this.id).removeAttr('checked');
                    });
                }
            }); 
            $(".div_vendor").click(function() {
                var vendor_id = $("#"+this.id).attr('vendor_id');
                var URL = '<?php echo $module_url;?>/my-vendors/add-edit/expense_vendor_id/'+vendor_id;
                window.location.href=URL;
            }); 
            
$(function () {
    $("#search_vendor").keyup(function () {
        // Retrieve the input field text and reset the count to zero
        var filter = $(this).val(),
            count = 0;
        // Loop through the menu list
        $("#scrollbox7 li").each(function () {
            // If the list item does not contain the text phrase fade it out
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                $(this).fadeOut();
                // Show the list item if the phrase matches and increase the count by 1
            } else {
                $(this).show();
                count++;
            }
        });
    });
});
            
    });
</script>