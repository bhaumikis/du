<?php
if (isset($view->userDetails)) {
    extract($view->userDetails);
}
if ($_POST) {
    extract($_POST);
}
?>
<script type="text/javascript">
    $(document).ready( function(){
        $("#managemyaccount").validate({
            rules: {
                first_name:{required:true},
                last_name:{required:true},
                email:{required:true,email: true},
                gender:{required:true},
                country_id:{required:true}
            },
            messages: {
                first_name:{required:"<?php echo _l('Enter_Firstname', 'administrators'); ?>"},
                last_name:{required:"<?php echo _l('Enter_Lastname', 'administrators'); ?>"},
                email:{required:"<?php echo _l('Enter_Email', 'administrators'); ?>",email : "<?php echo _l("Enter_Valid_Email", "administrators"); ?>"},
                gender:{required:"<?php echo _l('Select_Gender', 'administrators'); ?>"},
                country_id:{required:"<?php echo _l('Enter_Country', 'administrators'); ?>"}
            }
        });
        $(".btnSubmit").click(function() {            
            getMSDrowpdownValidate('managemyaccount','gender','<?php echo _l('Select_Gender', 'administrators'); ?>');
            getMSDrowpdownValidate('managemyaccount','country_id','<?php echo _l('Enter_Country', 'administrators'); ?>');
        });
    });
</script>
<form name="managemyaccount" id="managemyaccount" method="post" enctype="multipart/form-data">
    <div class="wrapper">
        <div class="container con-padding-tb">
            <div class="col-md-12">
                <div class="wizard-form-h">
                    <?php include(APPLICATION_PATH . "/application/global/message.php"); ?>
                    <div id="wizard" class="swMain">
                        <div id="step-1">
                            <h2 class="StepTitle">Account Information</h2>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_First_Name', 'administrators'); ?>*</label>
                                    <input class="input-style" type="text" placeholder="<?php echo _l('Placeholder_First_Name', 'administrators'); ?>" name="first_name" id="first_name" value="<?php
                    echo (isset($first_name) and !empty($first_name)) ? $first_name : "";
                    ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Last_Name', 'administrators'); ?>*</label>
                                    <input class="input-style" type="text" placeholder="<?php echo _l('Placeholder_Last_Name', 'administrators'); ?>" name="last_name" id="last_name" value="<?php
                                           echo (isset($last_name) and !empty($last_name)) ? $last_name : "";
                    ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Email', 'administrators'); ?>*</label>
                                    <input class="input-style" type="text" placeholder="<?php echo _l('Placeholder_Email', 'administrators'); ?>" name="email" id="email" value="<?php
                                           echo (isset($email) and !empty($email)) ? $email : "";
                    ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Mobile', 'administrators'); ?></label>
                                    <input class="input-style" type="text" placeholder="<?php echo _l('Placeholder_Mobile', 'administrators'); ?>" disabled="disabled" name="mobile_number" id="mobile_number" value="<?php echo $view->userDetails['mobile_number']; ?>"/>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Add1', 'administrators'); ?></label>
                                    <input class="input-style" type="text" placeholder="<?php echo _l('Placeholder_Add1', 'administrators'); ?>" name="address_line1" id="address_line1" value="<?php
                                           echo (isset($address_line1) and !empty($address_line1)) ? $address_line1 : "";
                    ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Add2', 'administrators'); ?></label>
                                    <input class="input-style" type="text" placeholder="<?php echo _l('Placeholder_Add2', 'administrators'); ?>" name="address_line2" id="address_line2" value="<?php
                                           echo (isset($address_line2) and !empty($address_line2)) ? $address_line2 : "";
                    ?>"/>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Country', 'administrators'); ?>*</label>
                                    <select class="form-control" name="country_id" id="country_id"/>
                                    <option value=""><?php echo _l('Label_Select_Country', 'administrators'); ?></option>
                                    <?php if (isset($view->countries)) {
                                        foreach ($view->countries as $country) { ?>
                                            <option value="<?php echo $country['country_id']; ?>" data-image="<?php echo APPLICATION_URL; ?>/images/msdropdown/icons/blank.gif" data-imagecss="flag <?php echo strtolower($country['iso_alpha2']);?>" data-title="<?php echo $country['name']; ?>"
                                                    <?php echo ($view->userDetails['country_id'] == $country['country_id']) ? 'selected="selected"' : ""; ?>><?php echo $country['name']; ?></option>
                                                <?php }
                                            } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 pull-left">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_Gender', 'administrators'); ?>*</label>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value=""><?php echo _l('Label_Select_Gender', 'administrators'); ?></option>
                                        <option value="Male" <?php echo ($view->userDetails['gender'] == "Male") ? 'selected="selected"' : ""; ?>>Male</option>
                                        <option value="Female" <?php echo ($view->userDetails['gender'] == "Female") ? 'selected="selected"' : ""; ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inline-form">
                                    <label class="c-label"><?php echo _l('Label_DOB', 'administrators'); ?></label>
                                    <input class="input-style" type="text" placeholder="<?php echo _l('Placeholder_DOB', 'administrators'); ?>" disabled="disabled" name="birth_date" id="birth_date" value="<?php echo $view->userDetails['birth_date']; ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 pro-btm-fix">
                        <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-left-0 padding-right-0 brd-right">
                            <input type="button" class="pro-btns col-sm-12 col-xs-12 padding-left-0" value="<?php echo _l('Button_Cancel', 'common'); ?>" onclick="window.location.href = '<?php echo $module_url . "/dashboard"; ?>'"/>
                        </div>
                        <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-right-0 padding-left-0">
                            <input type="submit" class="pro-btns pro-btns col-sm-12 col-xs-12 padding-left-0 btnSubmit" value="<?php echo _l('Button_Save', 'common'); ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#country_id").msDropdown();
        $("#gender").msDropdown();
    })
</script>