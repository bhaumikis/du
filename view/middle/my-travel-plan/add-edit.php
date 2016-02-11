<?php
if (isset($view->arrTripDetails) and !empty($view->arrTripDetails)) {
	extract($view->arrTripDetails);
	if(!isset($trip_time_from))	$trip_time_from = $trip_date_from;
	if(!isset($trip_time_to)) $trip_time_to = $trip_date_to;
}
if ($_POST) {
    extract($_POST);
}
?>
<div class="wrapper">
    <div class="container con-padding-tb">
        <?php include($module_path . "/application/global/message.php"); ?>
        <!--add expense start-->
        <div class="col-md-12">
            <form name="add_trip" id="add_trip" method="post" enctype="multipart/form-data">
                <input type="hidden" name="user_trip_id" id="user_trip_id" value="<?php echo $view->userTripId; ?>" />
                <div class="wizard-form-h">
                    <div id="wizard" class="swMain">
                        <div id="step-1">
                            <h2 class="StepTitle"><?php echo $view->header_title;?></h2>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("TRIP_NAME", $option); ?>*</label>
                                    <input class="input-style" type="text" name="trip_title" id="trip_title" value="<?php echo isset($trip_title) ? $trip_title : ""; ?>" placeholder="<?php echo _l("TRIP_NAME", $option); ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("BUDGET", $option); ?>*</label>
                                    <input id="trip_budget" name="trip_budget" type="text" placeholder="0000.00" value="<?php echo isset($trip_budget) ? $trip_budget : ""; ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("ADD_TRIP_CURRENCY", $option); ?></label>
                                    <select name="trip_currency" id="trip_currency">
                                    <option value=""><?php echo _l("Select Trip Currency", $option); ?>*</option>
                                    <?php
                                    if (isset($view->currencies['currencies'])) {
                                        foreach ($view->currencies['currencies'] as $currency) {
                                            ?>
                                            <option data-image="<?php echo $module_url ?>/images/msdropdown/icons/blank.gif" data-imagecss="flag <?php echo strtolower($currency['iso_alpha']); ?>" data-title="<?php echo trim($currency['currency_name']); ?>" value="<?php echo $currency['currency_id']; ?>"
                                                <?php echo ($_SESSION[$session_prefix]["user"]['base_currency_id'] == $currency['currency_id']) ? 'selected="selected"' : ""; ?>><?php echo $currency['currency_code']; ?> - <?php echo $currency['currency_name']; ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("DESTINATION", $option); ?>*</label>
                                    <select name="trip_destination" id="trip_destination">
                                        <option value="">Select Destination*</option>
                                        <?php
                                        if (isset($view->countries)) {
                                            foreach ($view->countries as $country) {
                                                ?>
                                                <option value="<?php echo $country['country_id']; ?>" data-image="<?php echo $module_url ?>/images/msdropdown/icons/blank.gif" data-imagecss="flag <?php echo strtolower($country['iso_alpha2']); ?>" data-title="<?php echo $country['name']; ?>"
                                                        <?php echo ($trip_destination == $country['country_id']) ? 'selected="selected"' : ""; ?>><?php echo $country['name']; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                    </select>
                                </div>
                            </div>    
                            <div class="col-md-6 padding-left-0 padding-right-0">
                                <div class="col-md-6">
                                    <div class="inline-form">
                                        <label class="c-label"><?php echo _l("FROM_DATE", $option); ?>*</label>
                                        <div class="form-group clearfix" id="trip_date_from">
                                            <div class="input-group" id='datetimepicker1'>
                                                <input type='text' name="trip_date_from" id="trip_date_from" class="form-control trip_date_from" value="<?php echo isset($trip_date_from) ? date(getFormat("MYSQL_DATE"), strtotime($trip_date_from)) : ""; ?>"/>
                                                <span class="input-group-addon"><span id="span_date_from" class="fa fa-calendar txt-orange cursor-pointer-default"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="inline-form">
                                        <label class="c-label"><?php echo _l("FROM_TIME", $option); ?></label>
                                        <div class="form-group clearfix" id="trip_time_from">
                                            <div class="input-group" id=''>
                                                <input id="timepicker1" name="trip_time_from" type="text" class="input-small" value="<?php echo isset($trip_time_from) ? date("h:i A",strtotime($trip_time_from)) : ""; ?>">
                                                <span class="input-group-addon"><span id="span_time_from" class="fa fa-clock-o txt-orange cursor-pointer-default"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
<!--                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("FROM_DATE", $option); ?>*</label>
                                    <div class="form-group clearfix" id="trip_date_from">
                                        <div class="input-group" id='datetimepicker1'>
                                            <input type='text' name="trip_date_from" id="trip_date_from" class="form-control trip_date_from" value="<?php echo isset($trip_date_from) ? $trip_date_from : ""; ?>"/>
                                            <span class="input-group-addon"><span id="span_date_from" class="fa fa-calendar txt-orange cursor-pointer-default"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                            <div class="col-md-6 padding-left-0 padding-right-0">
                                <div class="col-md-6">
                                    <div class="inline-form">
                                        <label class="c-label"><?php echo _l("TO_DATE", $option); ?>*</label>
                                        <div class="form-group clearfix" id="trip_date_to">
                                            <div class="input-group" id='datetimepicker1'>
                                                <input type='text' name="trip_date_to" id="trip_date_to" class="form-control trip_date_to" value="<?php echo isset($trip_date_to) ? date(getFormat("MYSQL_DATE"), strtotime($trip_date_to)) : ""; ?>"/>
                                                <span class="input-group-addon"><span id="span_date_to" class="fa fa-calendar txt-orange cursor-pointer-default"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="inline-form">
                                        <label class="c-label"><?php echo _l("TO_TIME", $option); ?></label>
                                        <div class="form-group clearfix" id="trip_time_to">
                                            <div class="input-group" id=''>
                                                <input id="timepicker2" name="trip_time_to" type="text" class="input-small" value="<?php echo isset($trip_time_to) ? date("h:i A",strtotime($trip_time_to)) : ""; ?>">
                                                <span class="input-group-addon"><span id="span_time_to" class="fa fa-clock-o txt-orange cursor-pointer-default"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  
<!--                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("TO_DATE", $option); ?>*</label>
                                    <div class="form-group clearfix" id="trip_date_to">
                                        <div class="input-group" id='datetimepicker1'>
                                            <input type='text' name="trip_date_to" id="trip_date_to" class="form-control trip_date_to" value="<?php echo isset($trip_date_to) ? $trip_date_to : ""; ?>"/>
                                            <span class="input-group-addon"><span id="span_date_to" class="fa fa-calendar txt-orange cursor-pointer-default"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>                            -->
                            <div class="col-md-6">
                                <div class="inline-form" id="div_trip">
                                    <label class="c-label"><?php echo _l("TRIP_TYPE", $option); ?>*</label>
                                    <label for="base_expense_type_id" style="color:#FF0000;"></label>
                                    <select name="base_expense_type_id" id="base_expense_type_id">
                                        <option value=""><?php echo _l("SELECT_TYPE", $option); ?>*</option>
                                        <?php foreach ($view->arrBaseCategory as $intBaseCategoryId => $strBaseCategory) { ?>
                                            <option <?php echo ($intBaseCategoryId == $base_expense_type_id)?"selected=selected":"";?> value="<?php echo $intBaseCategoryId; ?>"><?php echo $strBaseCategory; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>                                
                                <div class="col-xs-6 padding-left-0">
                                    <div class="inline-form">
                                        <label class="c-label"><?php echo _l("UPLOAD_FILE", $option); ?></label>
                                        <div class="form-group clearfix">
                                            <div class="input-group" id='uploadfile' style="width: 100%">
                                                <input <?php echo $view->arrDisableFile[0];?> name="trip_file_1" id="trip_file_1" type='file' class="filestyle txt-orange" data-buttonText="" />
                                                <!--<span class="input-group-addon"></span>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 padding-left-0 padding-right-0">
                                    <div class="inline-form">
                                        <label class="c-label"><?php echo _l("UPLOAD_FILE1", $option); ?></label>
                                        <div class="form-group clearfix">
                                            <div class="input-group" id='uploadfile' style="width: 100%">
                                                <input <?php echo $view->arrDisableFile[1];?> name="trip_file_2" id="trip_file_2" type='file' class="filestyle txt-orange" data-buttonText="" />
                                                <!--<span class="input-group-addon"></span>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"  id="div_upload_file">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l("DESCRIPTION", $option); ?></label>
                                    <textarea name="trip_description" id="trip_description" style="height:105px;"><?php echo isset($trip_description) ? $trip_description : "";?></textarea>
                                </div>
                            </div>
                            <?php if(is_array($view->arrTripReferenceDetails) and !empty($view->arrTripReferenceDetails)){?>
                            <div class="clearfix"></div>
				<div class="col-md-6">
                                    <div class="inline-form">
                                        <?php foreach($view->arrTripReferenceDetails as $intKey => $imagesData){?>
                                        <div class="alert col-md-4 alert-warning alert-dismissible font-13" role="alert">
                                                <button exp-image-name="<?php echo $imagesData['trip_filename']; ?>" id="img_<?php echo $intKey;?>" type="button" class=" del-exp-img close" data-dismiss="alert">
                                                    <span aria-hidden="true">&times;</span>
                                                    <span class="sr-only">Close</span>
                                                </button>
                                                <strong><a class="html5lightbox" href="<?php echo $module_url;?>/images/user_trips/<?php echo $imagesData['trip_filename'];?>"><?php echo $imagesData['trip_filename'];?></a></strong>
                                        </div>
                                        <?php }?>
                                    </div>
                               </div>
                            <?php }?>                            
                        </div>
                    </div>
                    <div class="col-xs-12 pro-btm-fix">
                        <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-left-0 padding-right-0 brd-right">
                            <input onclick="window.location.href = '<?php echo $module_url . "/my-travel-plan"; ?>'" type="button" class="pro-btns col-sm-12 col-xs-12 padding-left-0" value="Cancel" /></div>
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
        $('#timepicker2').timepicker();  
        $("#trip_currency").msDropdown();
        $('#trip_currency').data('dd').set('disabled', true);
        $("#trip_destination").msDropdown();
        $("#base_expense_type_id").msDropdown();    
        
        $('#trip_date_from input').datepicker(
                {format: 'yyyy-mm-dd',
                    autoclose: true,todayHighlight:true});
        $('#trip_date_to input').datepicker(
                {format: 'yyyy-mm-dd',
                    autoclose: true,todayHighlight:true});
        $("#span_date_from").click(function() {
            $('#trip_date_from input').datepicker('show');
        });
        $("#span_date_to").click(function() {
            $('#trip_date_to input').datepicker('show');
        });  
        
        $("#span_time_from").click(function() {
            $("#timepicker1").trigger("click");
        });
        
        $("#span_time_to").click(function() {
            $("#timepicker2").trigger("click");
        });        

        $("#add_trip").validate({
            rules: {
                trip_title: {required: true,maxlength: 50},
                trip_budget: {required: true,currency_format:true},
                trip_destination: {required: true},
                trip_date_from: {required: true},
                trip_date_to: {
                    "required": true,
                    "greaterDate": ".trip_date_from"
                },
                base_expense_type_id: {required: true},
                trip_description: {maxlength: 200}
            },
            messages: {
                trip_title: {required: "<?php echo _l('ADD_TRIP_MSG', $option); ?>"},
                trip_budget: {required: "<?php echo _l('ADD_BUDGET_MSG', $option); ?>"},
                trip_date_from: {required: "<?php echo _l('ADD_TRIP_FROM', $option); ?>"},
                trip_date_to: {
                    required: "<?php echo _l('ADD_TRIP_TO', $option); ?>",
                    greaterDate: "<?php echo _l("To date must be greater than equal to from date.", $option); ?>"
                }
            }
        });
        
        $.validator.addMethod("currency_format", function (value, element) {
        return this.optional(element) || /^\d{0,20}(\.\d{0,5})?$/.test(value);
        }, "<?php echo _l('TRIP_BUDGET_VALID',$option); ?>");         
         

        $("#btn_sbmt").click(function() {
            getMSDrowpdownValidate('add_trip','base_expense_type_id','<?php echo _l('ADD_EXPENSE_TYPE_MSG', $option); ?>');
            getMSDrowpdownValidate('add_trip','trip_destination','<?php echo _l('ADD_TRIP_DESTINATION_MSG', $option); ?>');
        });         
        
        $('#base_expense_type_id').on('change', function () {
            $(this).valid();
        });      
    });
    
</script>