<?php
if (isset($view->arrExpensesDetails) and !empty($view->arrExpensesDetails)) {
    extract($view->arrExpensesDetails);
}
if ($_POST) {
    extract($_POST);
}
?>
<style>
    .select2-arrow {
    bottom: 7px!important;
    right: 8px !important;
    width: 25px!important;
    top: -5px!important;
    height: 37px!important;
    border-left:1px solid #aaa !important;
}
</style>
<div class="wrapper">
    <div class="container con-padding-tb">
        <!--add expense start-->
        <div class="col-md-12">
            <form name="add_expense" id="add_expense" method="post" enctype="multipart/form-data">
                <input type="hidden" name="expense_category_id" id="expense_category_id" value="<?php echo $expense_category_id;?>" />
                <input type="hidden" name="user_expense_id" id="user_expense_id" value="<?php echo $view->userExpensesId; ?>" />
                <input type="hidden" name="base_type_id" id="base_type_id" value="<?php echo $base_type_id;?>" />
                <div class="wizard-form-h">
                    <div id="wizard" class="swMain">
                        <div id="step-1">
                            <h2 class="StepTitle"><?php echo $view->header_title;?></h2>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("ADD_SUMMARY",$option);?>*</label>
                                    <input class="input-style" type="text" name="expense_summary" id="expense_summary" placeholder="<?php echo _l("ADD_SUMMARY",$option);?>*"  value="<?php echo isset($expense_summary) ? $expense_summary : ""; ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("ADD_AMOUNT",$option);?>*</label>
                                    <input id="expense_amount" name="expense_amount" type="text" placeholder="0000.00" value="<?php echo isset($expense_amount) ? $expense_amount : ""; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6 padding-left-0 padding-right-0">
                                <div class="col-md-6">
                                    <div class="inline-form">
                                        <label class="c-label"><?php echo _l("ADD_EXPENSE_DATE", $option); ?>*</label>
                                        <div class="form-group clearfix" id="expense_date">
                                            <div class="input-group" id='datetimepicker1'>
                                                <input type='text' name="expense_date" id="expense_date" class="form-control" value="<?php echo isset($expense_date) ? $expense_date : ""; ?>"/>
                                                <span class="input-group-addon"><span id="span_date" class="fa fa-calendar txt-orange cursor-pointer-default"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="inline-form">
                                        <label class="c-label"><?php echo _l("ADD_EXPENSE_TIME", $option); ?></label>
                                        <div class="form-group clearfix" id="expense_time">
                                            <div class="input-group" id=''>
                                                <input id="timepicker1" name="expense_time" type="text" class="input-small" value="<?php echo isset($expense_time) ? date("h:i A",  strtotime($expense_time)) : ""; ?>">
                                                <span class="input-group-addon"><span id="span_time" class="fa fa-clock-o txt-orange cursor-pointer-default"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("ADD_EXPENSE_CURRENCY",$option);?>*</label>
                                    <select name="expense_currency_id" id="expense_currency_id"/>
                                    <option value=""><?php echo _l("Select Expense Currency",$option);?>*</option>
                                    <?php if (isset($view->currencies['currencies'])) {
                                        foreach ($view->currencies['currencies'] as $currency) {
                                        	$selected = ($expense_currency_id == $currency['currency_id']) ? 'selected="selected"' : "";
                                        	if($selected=="") {
                                        		$selected = ($_SESSION[$session_prefix]["user"]['base_currency_id'] == $currency['currency_id']) ? 'selected="selected"' : "";
                                        	}
                                            ?>
                                            <option <?php echo $selected;?> data-image="<?php echo $module_url?>/images/msdropdown/icons/blank.gif" data-imagecss="flag <?php echo strtolower($currency['iso_alpha']);?>" data-title="<?php echo trim($currency['currency_name']); ?>" value="<?php echo $currency['currency_id']; ?>"><?php echo $currency['currency_code']; ?> - <?php echo $currency['currency_name']; ?></option>
                                        <?php }
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 padding-left-0 padding-right-0">
                            <div class="col-sm-6 clearfix">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("ADD_EXPENSE_CATEGORY",$option);?></label>
                                    <div class="dropdown select2-container" id="div_cat_menu">
                                        <a id="dLabel" role="button" data-toggle="dropdown" class="btn slt-categories width-93 select2-choice" data-target="#" style="float: left;">
                                            <span id="span_dropdown_lable"><?php echo _l("Select Category",$option);?></span><span class="select2-arrow select2-arrow-vendor" role="presentation"><b role="presentation"></b></span>
                                        </a>
                                        <ul class="dropdown-menu multi-level width-93 zero-margin" role="menu" aria-labelledby="dropdownMenu">
                                        	<li class="">
                                            	<a tabindex="-1" id="<?php echo "Uncategorized"; ?>" bcat_id="0" class="cursor-pointer-default a-menu-link"><?php echo "Uncategorized"; ?></a>
                                            </li>
                                            <li class="divider"></li>        
                                            <?php foreach ($view->arrCategoryData as $intBaseCatId => $arrCategoryData) { ?>
                                                <li class="dropdown-submenu">
                                                    <a tabindex="-1" id="<?php echo ucfirst($arrCategoryData['base_name']); ?>" bcat_id="<?php echo $intBaseCatId;?>" class="cursor-pointer-default a-menu-link"><?php echo ucfirst($arrCategoryData['base_name']); ?></a>
                                                    <ul class="dropdown-menu">
                                                    	<li><a tabindex="-1" id="<?php echo ucfirst($arrCategoryData['base_name']) . " > Uncategorized" ; ?>" bcat_id="<?php echo $intBaseCatId;?>" class="cursor-pointer-default a-menu-link" cat_id="0"><?php echo "Uncategorized"; ?></a></li>
                                                        <?php 
                                                        foreach ($arrCategoryData['cat_name'] as $intCatId => $arrCatName) { ?>
                                                            <li><a tabindex="-1" id="<?php echo ucfirst($arrCategoryData['base_name']) . " > " . $arrCatName; ?>" class="cursor-pointer-default a-menu-link" cat_id="<?php echo $intCatId;?>"><?php echo $arrCatName; ?></a></li>
                                                        <?php } 
                                                        if(is_array($arrCategoryData['data'])){
                                                        foreach ($arrCategoryData['data'] as $intParentId => $arrCategory) { ?>
                                                            <li id="li-2nd" class="dropdown-submenu">
                                                                <a id="<?php echo ucfirst($arrCategoryData['base_name']) . " > " . $arrCatName . " > " . $arrCategory['cat_name']; ?>" class="cursor-pointer-default a-menu-link parent-a" cat_id="<?php echo $intParentId;?>"><?php echo $arrCategory['cat_name']; ?></a>
                                                                <ul id="ul-3rd" class="dropdown-menu">
                                                                    <?php foreach ($arrCategory['data'] as $arrCatData) { ?>
                                                                        <li><a id="<?php echo ucfirst($arrCategoryData['base_name']) . " > " . $arrCategory['cat_name'] . " > " . $arrCatData['title']; ?>" cat_id="<?php echo $arrCatData['expense_category_id']; ?>" class="cursor-pointer-default a-menu-link"><?php echo $arrCatData['title']; ?></a></li>
                                                        <?php } ?>
                                                                </ul>
                                                            </li>
                                                        <?php }} ?>
                                                    </ul>
                                                </li>
                                                <li class="divider"></li>
                                            <?php } ?>
                                        </ul>
                                        <a style="float:right" id="add-more-category"><i class="add-vendor fa fa-plus orange cursor-pointer-default"></i></a>
                                    </div>
                                </div>
                                </div>
                            <div class="col-sm-6">
                              <div class="col-sm-11 padding-left-0 padding-right-0 width-86">
                                <div class="inline-form">
                                  <label class="c-label"><?php echo _l("VENDOR",$option);?>*</label>
                                  <select name="expense_vendor_id" id="expense_vendor_id">
                                      <option value=""><?php echo _l("SELECT_VENDOR",$option);?>*</option>
                                    <?php 
                                    if(isset($view->arrVendorData) and !empty($view->arrVendorData)){
                                    foreach ($view->arrVendorData as $intVendorId => $strVendorName) { ?>
                                    <option <?php echo ($expense_vendor_id == $intVendorId) ? 'selected="selected"' : ""; ?> value="<?php echo $intVendorId; ?>"><?php echo $strVendorName; ?></option>
                                    <?php }} ?>
                                    </select>
                                  </div>
                                </div>
                              <div class="col-sm-1 padding-left-0 padding-right-0">
                                <div class="inline-form">
                                  <label class="c-label">&nbsp;</label>
                                  <a id="addVendor" class="clearfix col-md-12 padding-left-0"><i class="add-vendor fa fa-plus orange cursor-pointer-default"></i></a>
                                  </div>
                                </div>
                            </div>
                            </div>
<!--                            <div class="col-md-6 clearfix">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("PAYMENT_MODE",$option);?></label>
                                    <select name="payment_mode" id="payment_mode">
                                        <option value="cash"><?php echo _l("cash",$option); ?></option>
                                        <option value="card"><?php echo _l("card",$option); ?></option>
                                    </select>
                                </div>
                            </div>-->
<!--                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("SELECT_CARD",$option);?></label>
                                    <select name="card_id" id="card_id">
                                        <?php foreach ($view->arrCardData as $intCardId => $strCardName) { ?>
                                            <option value="<?php echo $intCardId; ?>"><?php echo $strCardName; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                </div>-->
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("ASSIGN_TO_TRIP",$option);?></label>
                                    <select name="user_trip_id" id="user_trip_id">
                                        <option value=""><?php echo _l("Select Trip",$option);?></option>
                                        <?php foreach ($view->arrTripData as $intTripId => $strTripName) { ?>
                                            <option <?php echo ($user_trip_id == $intTripId) ? 'selected="selected"' : ""; ?> value="<?php echo $intTripId; ?>"><?php echo $strTripName; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-xs-6 padding-left-0">
                                  <div class="inline-form">
                                    <label class="c-label"><?php echo _l("UPLOAD_FILE",$option);?></label>
                                    <div class="form-group clearfix">
                                      <div class="input-group" id='uploadfile' style="width: 100%">
                                        <input <?php echo $view->arrDisableFile[0];?> name="expense_file_1" id="expense_file1" type='file' class="filestyle txt-orange" data-buttonText="" />
                                        <!--<span class="input-group-addon"></span>-->
                                        </div>
                                      </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 padding-left-0 padding-right-0">
                                  <div class="inline-form">
                                    <label class="c-label"><?php echo _l("UPLOAD_FILE1",$option);?></label>
                                    <div class="form-group clearfix">
                                      <div class="input-group" id='uploadfile' style="width: 100%">
                                        <input <?php echo $view->arrDisableFile[1];?> name="expense_file_2" id="expense_file2" type='file' class="filestyle txt-orange" data-buttonText="" />
                                        <!--<span class="input-group-addon"></span>-->
                                        </div>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"  id="div_upload_file">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("DESCRIPTION",$option);?></label>
                                    <textarea name="expense_description" id="expense_description" style="height:105px;"><?php echo isset($expense_description) ? $expense_description : ""; ?></textarea>
                                </div>
                            </div>
                            <?php if(is_array($view->arrExpensesReferenceDetails) and !empty($view->arrExpensesReferenceDetails)){?>
                            <div class="clearfix"></div>
				<div class="col-md-6">
                                    <div class="inline-form">
                                        <?php foreach($view->arrExpensesReferenceDetails as $intKey => $imagesData){?>
                                        <div class="alert col-md-4 alert-warning alert-dismissible font-13" role="alert">
                                                <button exp-image-name="<?php echo $imagesData['expense_filename']; ?>" id="img_<?php echo $intKey;?>" type="button" class=" del-exp-img close" data-dismiss="alert">
                                                    <span aria-hidden="true">&times;</span>
                                                    <span class="sr-only">Close</span>
                                                </button>
                                                <strong><a class="html5lightbox" href="<?php echo $module_url;?>/images/user_expenses/<?php echo $imagesData['expense_filename'];?>"><?php echo $imagesData['expense_filename'];?></a></strong>
                                        </div>
                                        <?php }?>
                                    </div>
                               </div>
                            <?php }?>
                        </div>
                    </div>
                    <div class="col-xs-12 pro-btm-fix">
                        <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-left-0 padding-right-0 brd-right">
                            <input type="button" onclick="window.location.href = '<?php echo $module_url . "/my-expenses"; ?>'" class="pro-btns col-sm-12 col-xs-12 padding-left-0" value="Cancel" /></div>
                        <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-right-0 padding-left-0">
                            <input id="btn_sbmt" type="submit" class="pro-btns pro-btns col-sm-12 col-xs-12 padding-left-0" value="Save" /></div>
                    </div>
                </div>
            </form>
        </div>
        <!--add expense end-->
    </div>
</div>
<script>
     
    $(document).ready(function() {
    $('#timepicker1').timepicker();  
    $('#span_dropdown_lable').html('<?php echo (isset($view->strExpenseCategoriesLable) and !empty($view->strExpenseCategoriesLable))?$view->strExpenseCategoriesLable:_l("Select Category",$option);?>');
        $('#expense_date input').datepicker(
                {   format:'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight:true
            });

        $(":file").filestyle({iconName: ""});
        
        //$('#expense_amount').mask('000,000,000,000,000', {reverse: true});
        
        $("#expense_currency_id").msDropdown();
        $("#user_trip_id").msDropdown();
        
        $("#span_date").click(function() {
            $('#expense_date input').datepicker('show');
        });
        $("#span_time").click(function() {
            $("#timepicker1").trigger("click");
        });

        $("#expense_vendor_id").select2({
//            placeholder: "Select a State",
//            allowClear: true
        });        
        
        $(".a-menu-link").click(function() {
        	$('#dLabel').html(this.id);
        	if($(this).attr('bcat_id') > 0 && $(this).attr('cat_id') == undefined) $('#dLabel').html(this.id + ' > Uncategorized');
        	else $('#expense_category_id').val($(this).attr('cat_id'));
        	$('#base_type_id').val($(this).attr('bcat_id'));
            
        });     
        
        $("#add_expense").validate({
            rules: {
                expense_summary:{required:true,maxlength: 50},
                expense_amount:{required:true,currency_format:true,maxlength:10},
                expense_date:{required:true},
                expense_vendor_id:{required:true},
                expense_description:{maxlength:200}
            },
            messages: {
                expense_summary:{required:"<?php echo _l('add_summary','my-expenses'); ?>"},
                expense_amount:{required:"<?php echo _l('add_amount','my-expenses'); ?>"},
                expense_date:{required:"<?php echo _l('add_date','my-expenses'); ?>"},
                expense_vendor_id:{required:"<?php echo _l('add_vendor','my-expenses'); ?>"}
            }
        });  
        
        $.validator.addMethod("currency_format", function (value, element) {
        return this.optional(element) || /^\d{0,20}(\.\d{0,5})?$/.test(value);
        }, "<?php echo _l('Please specify a valid amount','my-expenses'); ?>");        
        
        $('.del-exp-img').click(function() {
            var imageName = $('#' + this.id).attr('exp-image-name');
            if (confirm("<?php echo _l('Are you sure to remove file?','my-expenses'); ?>")) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $module_url . "/my-expenses/remove-expense-file"; ?>",
                    data: {
                        filename: imageName,
                        expense_id:$('#user_expense_id').val()
                    },
                    success: function(response) {
                        if (response === "1") {
                            //window.location.href = '<?php echo $obj->getModuleURL() . "/my-expenses"; ?>';
                        } else {
                            alert("<?php echo _l('Something went wrong please try after sometime.','my-expenses'); ?>");
                        }
                    }
                });
            }
        }); 
        $('#addVendor').click(function() {
            $('#myModal').on('show.bs.modal', function() {
                $('#myModalLabel').html('<?php echo _l("Add Vendor",$option);?>');
                $('.modal-body').html('<iframe style="border: 1px solid #ccc;" id="iframe_container" src="<?php echo $module_url; ?>/my-vendors/add-edit/popup/yes"  height="350" width="100%" frameborder="1"></iframe>');
            });
            $('#myModal').modal()
        }); 
        
        $('#add-more-category').click(function() {
            $('#myModal').on('show.bs.modal', function() {
                $('#myModalLabel').html('<?php echo _l("Add Category",$option);?>');
                $('.modal-body').html('<iframe style="border: 1px solid #ccc;" id="iframe_container" src="<?php echo $module_url; ?>/my-categories/add-edit/popup/yes"  height="430" width="100%" frameborder="1"></iframe>');
            });
            $('#myModal').modal()
        }); 
        
        $("#btn_sbmt").click(function() {
            getMSDrowpdownValidate('add_expense','expense_currency_id','<?php echo _l('ADD_EXPENSE_CURRENCY_MSG', $option); ?>');
            //getMSDrowpdownValidate('add_trip','trip_destination','<?php echo _l('ADD_TRIP_DESTINATION_MSG', $option); ?>');
        }); 
        var base_currency_index = $('#expense_currency_id').msDropDown().data("dd").get('selectedIndex');
        $('#expense_currency_id').on('change', function () {
            $.ajax
            ({
                url: '<?php echo $module_url . "/register/check-exchange-rate-availability"; ?>',
                data: "cur_id="+$(this).val(),
                type: 'post',
                success: function(result)
                {
                    if(result == '0') {
                        alert('<?php echo _l("Rate conversion from selected currency to other currencies is currently not available.", $option); ?>');
                        $('#expense_currency_id').msDropDown().data('dd').set('selectedIndex',base_currency_index);
                    } else {
                        $(this).valid();
                    }
                }
            });
        });     
    });
    
function closeModelBox() {
    $('#myModal').modal('hide')
}
function getUpdatedVendors(intVendorId,strVendorName)
{
    $("#expense_vendor_id").append('<option value="'+intVendorId+'">'+strVendorName+'</option>');
}    
</script>