<div class="wrapper" id="div_container">
    <?php if(isset($view->arrTripData) and !empty($view->arrTripData)) { ?>
    <form name="assign_trip" id="assign_trip" method="post" enctype="multipart/form-data">
        <input type="hidden" name="user_expense_id" id="user_expense_id" value="" />
        <div class="container con-padding-tb">
            
            <div id="tabmenu">
                <ul id="nav1">
                    <li><a href="#" class="active"><i class="fa fa-briefcase txt-orange"></i></a></li>
                    <li><a href="#" class=""><i class="fa fa-user"></i></a></li>
                    <li><a href="#" class=""><i class="fa fa-question-circle"></i></a></li>
                </ul>
                <div id="tab-content">
                    <?php foreach ($view->arrTripData as $intBaseTypeId => $arrTrip) { ?>
                        <div id="tab<?php echo $intBaseTypeId; ?>">
                            <span class="col-sm-6">
                                <?php foreach ($arrTrip as $intTripId => $strTripName) { ?>
                                    <span class="checkbox-1"><label><input name="user_trip_id" id="user_trip_id<?php echo $intTripId; ?>" type="radio" value="<?php echo $intTripId; ?>" class="width-15"><?php echo $strTripName; ?></label></span>
                                <?php } ?>
                            </span>
                        </div>
                    <?php }
                    ?>
                </div>
                <!-- Container -->
            </div>
        </div>
        <div class="col-xs-12 pro-btm-fix">
            <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-left-0 padding-right-0 brd-right">
                <input type="button" onclick="closeModelBox()" class="pro-btns col-sm-12 col-xs-12 padding-left-0" value="<?php echo _l("Cancel", "common"); ?>" />
            </div>
            <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-right-0 padding-left-0">
                <input type="submit" id="btn_sbmt" class="pro-btns pro-btns col-sm-12 col-xs-12 padding-left-0" value="<?php echo _l("Save", "common"); ?>" />
            </div>
        </div>
    </form>
    <?php } else { ?>
    <div class="container con-padding-tb" style="text-align:center;"><div id="tabmenu"><div id="tab-content"><span class="col-sm-6"><?php echo _l("No records found.", $option); ?></div></div></div></span>
    <?php } ?>
</div>
<script>
    $(document).ready(function () {
         $("#btn_sbmt").click(function() {
            var blnChecked = false;
            $('[id^="user_trip_id"]').each(function() {
                if ($(this).is(':checked')) {
                    blnChecked = true;
                }

            });
            
            if (blnChecked) {
                $('#user_expense_id').val(window.parent.$("#selected_expense").val());
            } else {
                alert("<?php echo _l("Please select any trip.", "my-travel-plan"); ?>");
                return false;
            }
        });
    });
function closeModelBox() {
    parent.closeModelBox();
}    
</script>