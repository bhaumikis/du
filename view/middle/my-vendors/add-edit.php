<?php 
if(isset($view->vendorDetails) and !empty($view->vendorDetails)) {
    extract($view->vendorDetails);
}
if(isset($_POST)) {
    extract($_POST);
}
?>
<div class="wrapper">
    <div class="container">
        <!--add vendor start-->
        <div class="col-md-12">
            <form name="add_vendor" id="add_vendor" method="post" enctype="multipart/form-data">
                <input type="hidden" name="hid_chk" id="hid_chk" value="<?php echo $view->chk; ?>" />
                <input type="hidden" name="expense_vendor_id" id="expense_vendor_id" value="<?php echo $view->expense_vendor_id; ?>" />
                <div class="wizard-form-h">
                    <div id="wizard" class="swMain">
                        <div id="step-1">
                            <h2 class="StepTitle"><?php echo $view->header_title;?></h2>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("ADD_NAME", $option); ?></label>
                                    <input class="input-style" type="text" name="name" id="name" placeholder="<?php echo _l("ADD_NAME", $option); ?>" value="<?php echo (isset($name) and !empty($name)) ? $name : "";?>"/>
                                </div>
                            </div>
                            <div class="col-md-6"  id="div_upload_file">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("DESCRIPTION", $option); ?></label>
                                    <textarea name="description" id="description" style="height:105px;"><?php echo (isset($description) and !empty($description)) ? $description : "";?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 pro-btm-fix">
                        <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-left-0 padding-right-0 brd-right">
                            <input type="button" class="pro-btns col-sm-12 col-xs-12 padding-left-0 cancelBtn" value="<?php echo _l("Cancel","common");?>" /></div>
                        <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-right-0 padding-left-0">
                            <input type="submit" class="pro-btns pro-btns col-sm-12 col-xs-12 padding-left-0" value="<?php echo _l("Save","common");?>" /></div>
                    </div>
                </div>
            </form>
        </div>
        <!--add vendor end-->
    </div>
</div>
<script>
    $("#add_vendor").validate({
        rules: {
            name:{required:true,maxlength: 50},
            description:{maxlength: 200}
        },
        messages: {
            name:{required:"<?php echo _l('ADD_SUMMARY_MSG', $option); ?>"}
        }
    });
    $(document).ready(function() {
        $('.cancelBtn').click(function() {
            <?php if ($view->chk == "yes") { ?>                
                window.parent.closeModelBox();
            <?php } else { ?>
                window.location.href = '<?php echo $module_url; ?>/my-vendors';
            <?php } ?>
        });
    });
</script>