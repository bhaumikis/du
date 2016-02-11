<?php
if (isset($view->userdetails) and !empty($view->userdetails)) {
    extract($view->userdetails);
}
if ($_POST) {
    extract($_POST);
}

if (isset($birth_date) and $birth_date != '0000-00-00' and $birth_date != "") {
    $birth_date = date("m/d/Y", strtotime($birth_date));
} else {
    $birth_date = '';
}
?>

<script type="text/javascript">
    $(document).ready( function() {

        $("#register").validate({
            rules: {
                first_name:{required:true},
                last_name:{required:true},
                email:{required:true,email: true},
                mobile_number:{digits: true},
                country_id:{required:true}
            },
            messages: {
                first_name:{required:"<?php echo _l('Enter_Firstname', 'administrators'); ?>"},
                last_name:{required:"<?php echo _l('Enter_Lastname', 'administrators'); ?>"},
                email:{required:"<?php echo _l('Enter_Email', 'administrators'); ?>",email : "<?php echo _l("Enter_Valid_Email", "administrators"); ?>"},
                mobile_number:{digits:"<?php echo _l("Please enter a valid mobile number.", "users"); ?>"},
                country_id:{required:"<?php echo _l('Enter_Country', 'administrators'); ?>"}
            }
        });
        $(".btnSubmit").click(function() {            
            getMSDrowpdownValidate('register','country_id','<?php echo _l('Enter_Country', 'users'); ?>');
        });
<?php if (empty($view->user_id)) { ?>
            $("#password").rules("add", {
                required: true,
                messages: {
                    required: "<?php echo _l('Enter_Password', 'administrators'); ?>"
                }
            });
            $("#password").rules("add", {
                regex: true
            });
            
            $.validator.addMethod('regex', function (value) {
                return /\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\W])(?=\S*[\d])\S*/.test(value);
            }, '<?php echo _l('Invalid_Password_String', 'change_password'); ?>');
<?php } ?>

        $('#genpas').click(function() {
            $.ajax
            ({
                url: '<?php echo $module_url . "/users/get-random-password"; ?>',
                data: "",
                type: 'post',
                success: function(result)
                {
                    if(result != '') {
                        $('#password').val(result);
                    }
                }
            });
            return false;
        });
    });
</script>

<script type="text/javascript" src="<?php echo APPLICATION_URL; ?>/js/multiselect/jquery.js"></script>
<script type="text/javascript" src="<?php echo APPLICATION_URL; ?>/js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo APPLICATION_URL; ?>/js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo APPLICATION_URL; ?>/js/multiselect/jquery.multiselect.filter.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL; ?>/js/multiselect/css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL; ?>/js/multiselect/css/jquery.multiselect.css">
<link rel="stylesheet" type="text/css" href="<?php echo APPLICATION_URL; ?>/js/multiselect/css/jquery.multiselect.filter.css"></link>

<script type="text/javascript">
    var j = jQuery.noConflict();
    j(document).ready(function() {
        j("#assign_country").multiselect({minWidth:400,noneSelectedText: 'Select Country',selectedText: '# of # Countries Selected',selectedList:400}).multiselectfilter();
        j("#assign_country").on("multiselectclick multiselectcheckall multiselectuncheckall", function(event, ui) {
            // event: the original event object ui.value: value of the checkbox ui.text: text of the checkbox ui.checked: whether or not the input was checked or unchecked (boolean)
            //alert(ui.checked);
            //alert(ui.value);
            var checkedValues = j.map(j(this).multiselect("getChecked"), function( input ) {
                return input.value;
            });

            $.ajax
            ({
                url: '<?php echo $module_url . "/users/get-country-user-count"; ?>',
                data: "country_ids="+checkedValues,
                type: 'post',
                success: function(result)
                {
                    if(result != '') {
                        //alert(result);
                        //$("#target").html(checkedValues.length ? checkedValues.join(', '): 'Please select a checkbox' );
                        $("#target").attr('style', 'display:block;border:1px solid #ddd; background: #eee; padding: 10px;');
                        $("#target").html(result);
                    } else {
                        $("#target").attr('style', 'display:none;');
                        $("#target").html("");
                    }
                }
            });
            if(event.type === "multiselectuncheckall") {
                j("#assign_country").multiselect('close');
                j(".ui-multiselect-menu").hide();
            }
        });
        <?php if(isset($view->assignedcountries) and !empty($view->assignedcountries)) { $countryids = implode(',',$view->assignedcountries); ?>
        $.ajax
            ({
                url: '<?php echo $module_url . "/users/get-country-user-count"; ?>',
                data: "country_ids="+<?php echo $countryids;?>,
                type: 'post',
                success: function(result)
                {
                    if(result != '') {
                        //alert(result);
                        //$("#target").html(checkedValues.length ? checkedValues.join(', '): 'Please select a checkbox' );
                        $("#target").attr('style', 'display:block;border:1px solid #ddd; background: #eee; padding: 10px;');
                        $("#target").html(result);
                    } else {
                        $("#target").attr('style', 'display:none;');
                        $("#target").html("");
                    }
                }
            });
        <?php } ?>
    });
</script>


<script type="text/javascript" language="javascript" src="<?php echo APPLICATION_URL; ?>/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    var d = jQuery.noConflict();
    d(document).ready( function(){
        d('#birth_date input').datepicker({autoclose: true});
        d("#a_date_birth").click(function(){
            d('#birth_date input').datepicker('show');
            return false;
        });
    });
</script>


<div class="wrapper">
    <div class="container con-padding-tb">
        <form name="register" id="register" method="post">
            <div class="col-md-12">
                <div class="wizard-form-h">
                    <?php include(APPLICATION_PATH . "/application/global/message.php"); ?>
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $view->user_id; ?>" />
                    <div id="wizard" class="swMain">
                        <div id="step-1">
                            <h2 class="StepTitle"><?php echo _l('Label_Add_Admin', 'administrators'); ?></h2>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_First_Name', 'administrators'); ?>*</label>
                                    <input class="input-style pull-left" type="text" placeholder="<?php echo _l('Placeholder_First_Name', 'administrators'); ?>" name="first_name" id="first_name" value="<?php echo (isset($first_name) and !empty($first_name)) ? $first_name : ""; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Last_Name', 'administrators'); ?>*</label>
                                    <input class="input-style pull-left" type="text" placeholder="<?php echo _l('Placeholder_Last_Name', 'administrators'); ?>" name="last_name" id="last_name" value="<?php echo (isset($last_name) and !empty($last_name)) ? $last_name : ""; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Add1', 'administrators'); ?></label>
                                    <input class="input-style pull-left" type="text" placeholder="<?php echo _l('Placeholder_Add1', 'administrators'); ?>" name="address_line1" id="address_line1" value="<?php echo (isset($address_line1) and !empty($address_line1)) ? $address_line1 : ""; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Add2', 'administrators'); ?></label>
                                    <input class="input-style pull-left" type="text" placeholder="<?php echo _l('Placeholder_Add2', 'administrators'); ?>" name="address_line2" id="address_line2" value="<?php echo (isset($address_line2) and !empty($address_line2)) ? $address_line2 : ""; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Email', 'administrators'); ?>*</label>
                                    <input class="input-style pull-left" type="text" placeholder="<?php echo _l('Placeholder_Email', 'administrators'); ?>" name="email" id="email" value="<?php echo (isset($email) and !empty($email)) ? $email : ""; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Mobile', 'administrators'); ?></label>
                                    <input class="input-style pull-left" type="text" placeholder="<?php echo _l('Placeholder_Mobile', 'administrators'); ?>" name="mobile_number" id="mobile_number" value="<?php echo (isset($mobile_number) and !empty($mobile_number)) ? $mobile_number : ""; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_DOB', 'administrators'); ?></label>
                                    <div id="birth_date" class="reg-form">
                                        <a style="float:left;" id="a_date_birth" class="" href=""><i class="fa fa-calendar cal-orange"></i></a>
                                        <input readonly style="width:90%; border:none;" class="pull-left" type="text" placeholder="<?php echo _l('Placeholder_DOB', 'administrators'); ?>" name="birth_date" id="birth_date" value="<?php echo (isset($birth_date) and !empty($birth_date)) ? $birth_date : ""; ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Gender', 'administrators'); ?></label>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value=""><?php echo _l('Label_Select_Gender', 'administrators'); ?></option>
                                        <option value="Male" <?php echo ($gender == "Male") ? 'selected="selected"' : ""; ?>>Male</option>
                                        <option value="Female" <?php echo ($gender == "Female") ? 'selected="selected"' : ""; ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 clearfix">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Country', 'administrators'); ?>*</label>
                                    <select class="form-control" name="country_id" id="country_id"/>

                                    <option value=""><?php echo _l('Label_Select_Country', 'administrators'); ?></option>
                                    <?php
                                    if (isset($view->countries)) {
                                        foreach ($view->countries as $country) {
                                            ?>
                                            <option value="<?php echo $country['country_id']; ?>" data-image="<?php echo APPLICATION_URL; ?>/images/msdropdown/icons/blank.gif" data-imagecss="flag <?php echo strtolower($country['iso_alpha2']);?>" data-title="<?php echo $country['name']; ?>"
                                                    <?php echo ($country_id == $country['country_id']) ? 'selected="selected"' : ""; ?>><?php echo $country['name']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_City', 'administrators'); ?></label>
                                    <input class="input-style pull-left" type="text" placeholder="<?php echo _l('Placeholder_City', 'administrators'); ?>" name="city" id="city" value="<?php echo (isset($_POST['city']) and !empty($_POST['city'])) ? $_POST['city'] : ""; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_EID', 'administrators'); ?></label>
                                    <input class="input-style pull-left" type="text" placeholder="<?php echo _l('Placeholder_EID', 'administrators'); ?>" name="employee_id" id="employee_id" value="<?php echo (isset($_POST['employee_id']) and !empty($_POST['employee_id'])) ? $_POST['employee_id'] : ""; ?>"/>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div id="target" style="display:none;"></div>
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Help1', 'administrators'); ?></label>
                                    <select name="assign_country[]" id="assign_country" multiple="multiple" class="col-md-6 select-box-country">
                                        <?php foreach ($view->countries as $country) {
                                            if (array_key_exists($country['country_id'], $view->allassignedcountries)) { ?>
                                                <option value="<?php echo $country['country_id']; ?>" disabled="disabled"><?php echo $country['name'] . " :: Assigned To - " . $view->allassignedcountries[$country['country_id']]['name']; ?></option>
                                            <?php } else {
                                                ?>
                                                <option value="<?php echo $country['country_id']; ?>" <?php echo (in_array($country['country_id'], $view->assignedcountries)) ? 'selected="selected"' : ""; ?>><?php echo $country['name']; ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <?php if (empty($view->user_id)) { ?>
                                <div class="col-md-6 clearfix">
                                    <div class="inline-form">
                                        <button class="btn" id="genpas" ><?php echo _l('Btn_Gen_Pass', 'administrators'); ?></button>
                                        <input class="input-style pull-left" type="text" readonly placeholder="<?php echo _l('Placeholder_Gen_Pass', 'administrators'); ?>" name="password" id="password"/>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-xs-12 pro-btm-fix">
                        <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-left-0 padding-right-0 brd-right">
                            <button type="button" class="pro-btns col-sm-12 col-xs-12 padding-left-0" onclick="window.location.href = '<?php echo $module_url . "/users/admin-users"; ?>'"><?php echo _l('Button_Cancel', 'common'); ?></button>
                        </div>

                        <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-right-0 padding-left-0">
                            <input type="submit" name="submit" class="pro-btns pro-btns col-sm-12 col-xs-12 padding-left-0 btnSubmit" value="<?php echo _l('Button_Save', 'common'); ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#country_id").msDropdown();
        $("#gender").msDropdown();
    })
</script>