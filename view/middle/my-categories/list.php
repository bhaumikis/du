<div class="wrapper" id="div_container">
    <form name="assign_category" id="assign_category" method="post" enctype="multipart/form-data">
        <input type="hidden" name="user_expense_id" id="user_expense_id" value="" />
        <div class="container con-padding-tb">
            <div id="tabmenu">
                <ul id="nav1">
                    <li><a href="#" class="active"><i class="fa fa-briefcase txt-orange"></i></a></li>
                    <li><a href="#" class=""><i class="fa fa-user"></i></a></li>
                    <li><a href="#" class=""><i class="fa fa-question-circle"></i></a></li>
                </ul>
                <div id="tab-content">
                    <?php foreach ($view->arrCategoryData as $intBaseTypeId => $arrCategoryData) { ?>
                        <div id="tab<?php echo $intBaseTypeId; ?>">
                            <span class="col-sm-6">
                                <?php foreach ($arrCategoryData['cat_name'] as $intCatId => $strCategoryName) { ?>
                                    <span class="checkbox-1"><label><input name="category_id" id="cat_name_<?php echo $intCatId; ?>" type="radio" value="<?php echo $intCatId; ?>" class="width-15"><?php echo $strCategoryName; ?></label></span>
                                <?php } ?>
                            </span>
                            <?php if (is_array($arrCategoryData['data'])) {
                                foreach ($arrCategoryData['data'] as $intChildCatId => $arrChildData) { ?>
                                    <span id="span_<?php echo $intChildCatId; ?>" class="col-sm-6" style="display: none">
                                        <?php foreach ($arrChildData['data'] as $arrCatChildData) { ?>
                                            <span class="checkbox-1"><label><input name="sub_category_id" id="cat_name_<?php echo $arrCatChildData['expense_category_id']; ?>" type="radio" value="<?php echo $arrCatChildData['expense_category_id']; ?>" class="width-15"><?php echo $arrCatChildData['title']; ?></label></span>
                                        <?php } ?>
                                    </span>
                                <?php }
                            } ?>
                        </div>
                    <?php }
                    ?>
                </div>
                <!-- Container -->
            </div>
        </div>
        <div class="col-xs-12 pro-btm-fix">
            <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-left-0 padding-right-0 brd-right">
                <input type="button" onclick="closeModelBox()" class="pro-btns col-sm-12 col-xs-12 padding-left-0" value="<?php echo _l("Cancel", "my-categories"); ?>" />
            </div>
            <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-right-0 padding-left-0">
                <input type="submit" id="btn_sbmt" class="pro-btns pro-btns col-sm-12 col-xs-12 padding-left-0" value="<?php echo _l("Save", "my-categories"); ?>" />
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function () {
         $("#btn_sbmt").click(function() {
            var blnChecked = false;
            $('[id^="cat_name_"]').each(function() {
                if ($(this).is(':checked')) {
                    blnChecked = true;
                }

            });
            
            if (blnChecked) {
                $('#user_expense_id').val(window.parent.$("#selected_expense").val());
            } else {
                alert("<?php echo _l("Please select any category.", "my-categories"); ?>");
                return false;
            }
        });         
    });
function closeModelBox() {
    parent.closeModelBox();
}    
</script>