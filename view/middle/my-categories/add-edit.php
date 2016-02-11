<?php
if (isset($view->arrCategoryDetails) and !empty($view->arrCategoryDetails)) {
    extract($view->arrCategoryDetails);
}
if ($_POST) {
    extract($_POST);
}
?>
<div class="wrapper">
    <div class="container <?php echo ($view->blnPopUp == false)?'con-padding-tb':"";?>">
        <!--add expense start-->
        <div class="col-md-12">
            <form name="add_category" id="add_category" method="post" enctype="multipart/form-data">
                <input type="hidden" name="expense_category_id" id="expense_category_id" value="<?php echo $view->userCategoryId;?>" />
                <div class="wizard-form-h">
                    <div id="wizard" class="swMain">
                        <div id="step-1">
                            <?php if($view->blnPopUp == false){?>
                            <h2 class="StepTitle"><?php echo $view->header_title;?></h2>
                            <?php }?>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("ADD_NAME", $option); ?>*</label>
                                    <input class="input-style" type="text" name="title" id="title" placeholder="<?php echo _l("ADD_NAME", $option); ?>" value="<?php echo $title; ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("BASE_CATEGORY", $option); ?>*</label>
                                    <select name="base_type_id" id="base_type_id" <?php echo (!empty($base_type_id)) ? 'disabled' : ""; ?>>
                                        <option value=""><?php echo _l("SELECT_BASE_CATEGORY", $option); ?>*</option>
                                        <?php
                                        if (isset($view->arrBaseCategoryData)) {
                                            foreach ($view->arrBaseCategoryData as $intBaseCategoryId => $strBaseCategoryName) {
                                                ?>
                                        <option <?php echo ($base_type_id == $intBaseCategoryId) ? 'selected="selected"' : ""; ?> value="<?php echo $intBaseCategoryId; ?>"><?php echo ucfirst($strBaseCategoryName); ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>   
                            <div class="clearfix"></div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("PARENT_CATEGORY", $option); ?></label>
                                    <select name="parent_expense_category_id" id="parent_expense_category_id" <?php echo (!empty($parent_expense_category_id)) ? 'disabled' : ""; ?>>
                                        <option value=""><?php echo _l("SELECT_PARENT_CATEGORY", $option); ?></option>
                                    </select>
                                </div>
                            </div>                            
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("DESCRIPTION", $option); ?></label>
                                    <textarea name="description" id="description" style="height:105px;"><?php echo $description; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 pro-btm-fix">
                        <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-left-0 padding-right-0 brd-right">
                            <?php if($view->blnPopUp == false){?>
                            <input onclick="window.location.href = '<?php echo $module_url . "/my-categories"; ?>'" type="button" class="pro-btns col-sm-12 col-xs-12 padding-left-0" value="Cancel" />
                            <?php }else{?>
                            <input onclick="window.parent.closeModelBox();" type="button" class="pro-btns col-sm-12 col-xs-12 padding-left-0" value="Cancel" />
                            <?php }?>
                        </div>
                        <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-right-0 padding-left-0">
                            <input type="submit" class="pro-btns pro-btns col-sm-12 col-xs-12 padding-left-0 btnSubmit" value="Save" /></div>
                    </div>
                </div>
            </form>
        </div>
        <!--add expense end-->
    </div>
</div>
<script>
    var parentCategory = '<?php echo $parent_expense_category_id;?>';
    getDataRefplected();
$(document).ready(function() {
    $("#add_category").validate({
        rules: {
            title: {required: true,maxlength:50},
            base_type_id: {required: true},
            description: {maxlength: 200}
        },
        messages: {
            title: {required: "<?php echo _l('ADD_CATEGORY_MSG', $option); ?>"},
            base_type_id: {required: "<?php echo _l('ADD_BASE_TYPE_MSG', $option); ?>"}
        }
    });

    $("#base_type_id").msDropdown();

    $('#base_type_id').on('change', function() {
        getDataRefplected();
    });
    
    $(".btnSubmit").click(function() {
        getMSDrowpdownValidate('add_category','base_type_id','<?php echo _l('Please select base category', $option); ?>');
    });      

});
function getDataRefplected(){
            $.ajax({
            type: "POST",
            url: "<?php echo $module_url . "/my-categories/get-category-data"; ?>",
            data: {
                base_type_id: $('#base_type_id').val()
            },
            success: function(response) {
                var objResponse = jQuery.parseJSON(response);
                var options = '<option value=""><?php echo _l("SELECT_PARENT_CATEGORY", $option); ?></option>';
                $("#parent_expense_category_id option").remove();
                for (i = 0; i < objResponse.length; i++) {
                    
                    if(objResponse[i].expense_category_id === parentCategory){
                        var selected = "selected=selected";
                    }
                    else{
                        var selected = "";
                    }
                    
                    options += '<option '+selected+' value="' + objResponse[i].expense_category_id + '">' + objResponse[i].title + '</option>';
                }
                $("#parent_expense_category_id").html(options);
            }
        });
}
</script>