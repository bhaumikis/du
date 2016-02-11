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
        var base_currency_index = $('#base_currency_id').msDropDown().data("dd").get('selectedIndex');

        $("#country_id").msDropdown();
        $("#base_currency_id").msDropdown();

        $("#editprofile").validate({
            rules: {
                first_name:{required:true},
                last_name:{required:true},
                city: {required: true},
                zip_code: {required: true},
                email:{required:true},
                country_id:{required:true}
            },
            messages: {
                first_name:{required:"<?php echo _l('Enter_Firstname', 'users'); ?>"},
                last_name:{required:"<?php echo _l('Enter_Lastname', 'users'); ?>"},
                city: {required: "<?php echo _l('Enter_City', 'users'); ?>"},
                zip_code: {required: "<?php echo _l('Enter_Zipcode', 'users'); ?>"},
                email:{required:"<?php echo _l('Enter_Email', 'users'); ?>"},
                country_id:{required:"<?php echo _l('Enter_Country', 'users'); ?>"}
            }
        });
        $("#base_currency_id").change(function() {
            alert("<?php echo _l('Help_Currency_Change', 'users'); ?>");
            $.ajax
            ({
                url: '<?php echo $module_url . "/register/check-exchange-rate-availability"; ?>',
                data: "cur_id="+$(this).val(),
                type: 'post',
                success: function(result)
                {
                    if(result == '0') {
                        $.confirm({
                            title:"<?php echo _l('Text_Title', 'users'); ?>",
                            text:"<?php echo _l('Currency_Conversion_Msg', 'users'); ?>",
                            confirm: function(button) {
                            },
                            cancel: function(button) {
                                $('#base_currency_id').msDropDown().data('dd').set('selectedIndex',base_currency_index);
                            },
                            confirmButton: "<?php echo _l('Text_Confirm', 'users'); ?>",
                            cancelButton: "<?php echo _l('Text_Cancel', 'users'); ?>"
                        });
                    }
                }
            });
        });
    });
</script>
<div class="wrapper">
    <div class="container con-padding-tb">
        <form name="editprofile" id="editprofile" method="post" enctype="multipart/form-data">
            <input type="hidden" name="hid_user_country_id" id="hid_user_country_id" value="<?php echo $view->userDetails['country_id']; ?>">
            <?php include($module_path . "/application/global/message.php"); ?>
            <div class="col-sm-12 text-center">
                <div class="col-md-2 grids hidden-sm"></div>
                <div class="col-md-8 grids col-sm-12">
                    <div class="profile-sec-head pink">
                        <div class="col-md-12 col-sm-12">
                            <span class="profile-sec-head profile-head-bg">
                                <?php /* if ($view->userDetails['user_image'] != "") { ?>
                                  <input type="hidden" name="hid_user_image" value="<?php echo $view->userDetails['user_image']; ?>">
                                  <img src="<?php echo $module_url . '/images/user_images/' . $view->userDetails['user_image']; ?>" height="120px" width="120px" alt="" />
                                  <?php } else { ?>
                                  <img src="" height="120px" width="120px" alt="" />
                                  <?php } */ ?>

                                <img src="<?php echo ($view->userDetails['user_image'] != "") ? $module_url . '/images/user_images/' . $view->userDetails['user_image'] : ""; ?>" alt="" id="temp_user_image" width="120px" height="120px">
                                <input type="hidden" name="hid_user_image" id="hid_user_image">
                            </span>
<!--                            <input class="pic-upload" type="file" placeholder="User Image" name="user_image" id="user_image"/>-->
                            <iframe name="file-upload" id="file-upload" src="<?php echo $module_url . "/users/upload-temporary-image"; ?>" class="file_upload pull_left upload-bg" ></iframe>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="inline-form">
                                <label class="c-label">First Name<span class="error">*</span></label>
                                <input class="input-style pull-left" type="text" placeholder="First Name*" name="first_name" id="first_name" value="<?php
                                if ($_POST['first_name'] != "") {
                                    echo $_POST['first_name'];
                                } else if ($view->userDetails['first_name'] != "") {
                                    echo $view->userDetails['first_name'];
                                } else {
                                    "";
                                }
                                ?>"/>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="inline-form">
                                <label class="c-label">Last Name<span class="error">*</span></label>
                                <input class="input-style pull-left" type="text" placeholder="Last Name*" name="last_name" id="last_name" value="<?php
                                       if ($_POST['last_name'] != "") {
                                           echo $_POST['last_name'];
                                       } else if ($view->userDetails['last_name'] != "") {
                                           echo $view->userDetails['last_name'];
                                       } else {
                                           "";
                                       }
                                ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="registration">
                        <div class="custom-form">

                            <div class="col-md-6 col-sm-12">
                                <div class="inline-form">
                                    <label class="c-label">Mobile Number</label>
                                    <input class="input-style pull-left" type="text" placeholder="Mobile Number*" disabled="disabled" name="mobile_number" id="mobile_number" value="<?php echo $view->userDetails['mobile_number']; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="inline-form">
                                    <label class="c-label">Email<span class="error">*</span></label>
                                    <input class="input-style pull-left" type="text" placeholder="Email*" name="email" id="email" value="<?php
                                       if ($_POST['email'] != "") {
                                           echo $_POST['email'];
                                       } else if ($view->userDetails['email'] != "") {
                                           echo $view->userDetails['email'];
                                       } else {
                                           "";
                                       }
                                ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="inline-form">
                                    <label class="c-label">Address Line1</label>
                                    <input class="input-style pull-left" type="text" placeholder="Address Line1" name="address_line1" id="address_line1" value="<?php
                                           if ($_POST['address_line1'] != "") {
                                               echo $_POST['address_line1'];
                                           } else if ($view->userDetails['address_line1'] != "") {
                                               echo $view->userDetails['address_line1'];
                                           } else {
                                               "";
                                           }
                                ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="inline-form">
                                    <label class="c-label">Address Line2</label>
                                    <input class="input-style pull-left" type="text" placeholder="Address Line2" name="address_line2" id="address_line2" value="<?php
                                           if ($_POST['address_line2'] != "") {
                                               echo $_POST['address_line2'];
                                           } else if ($view->userDetails['address_line2'] != "") {
                                               echo $view->userDetails['address_line2'];
                                           } else {
                                               "";
                                           }
                                ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="inline-form">
                                    <label class="c-label">City*</label>
                                    <input class="input-style pull-left" type="text" placeholder="City" name="city" id="city" value="<?php echo (isset($city) and !empty($city)) ? $city : ""; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="inline-form">
                                    <label class="c-label">Post Code/Zip Code*</label>
                                    <input class="input-style pull-left" type="text" placeholder="Post Code/Zip Code" name="zip_code" id="zip_code" value="<?php echo (isset($zip_code) and !empty($zip_code)) ? $zip_code : ""; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="inline-form">
                                    <label class="c-label">Date of Birth</label>
                                    <input class="input-style pull-left" type="text" placeholder="Birthdate*" disabled="disabled" name="birth_date" id="birth_date" value="<?php echo $view->userDetails['birth_date']; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="inline-form">
                                    <label class="c-label">Select Gender</label>
                                    <select class="form-control" name="gender" id="gender" disabled="disabled">
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?php echo ($view->userDetails['gender'] == "Male") ? 'selected="selected"' : ""; ?>>Male</option>
                                        <option value="Female" <?php echo ($view->userDetails['gender'] == "Female") ? 'selected="selected"' : ""; ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 clearfix">
                                <div class="inline-form">
                                    <label class="c-label">Select Base Currency</label>
                                    <select class="form-control" name="base_currency_id" id="base_currency_id"/>
                                    <option value="">Select Base Currency</option>
                                    <?php if (isset($view->currencies['currencies'])) {
                                        foreach ($view->currencies['currencies'] as $currency) { ?>
                                            <option value="<?php echo $currency['currency_id']; ?>"  data-image="<?php echo $module_url ?>/images/msdropdown/icons/blank.gif" data-imagecss="flag <?php echo strtolower($currency['iso_alpha']); ?>" data-title="<?php echo trim($currency['currency_name']); ?>"
                                                    <?php echo ($view->userDetails['base_currency_id'] == $currency['currency_id']) ? 'selected="selected"' : ""; ?>>
                                                        <?php echo $currency['currency_code']; ?> - <?php echo $currency['currency_name']; ?>
                                            </option>
                                                <?php }
                                            } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="inline-form">
                                    <label class="c-label">Select Country<span class="error">*</span></label>
                                    <select class="form-control" name="country_id" id="country_id"/>
                                    <option value="">Select Country</option>
                                    <?php if (isset($view->countries)) {
                                        foreach ($view->countries as $country) { ?>
                                            <option value="<?php echo $country['country_id']; ?>"  data-image="<?php echo $module_url ?>/images/msdropdown/icons/blank.gif" data-imagecss="flag <?php echo strtolower($country['iso_alpha2']); ?>" data-title="<?php echo $country['name']; ?>"
                                                    <?php echo ($view->userDetails['country_id'] == $country['country_id']) ? 'selected="selected"' : ""; ?>><?php echo $country['name']; ?></option>
                                                <?php }
                                            } ?>
                                    </select>
                                </div>
                            </div>

                            <!--                            <div class="clearfix"></div>
                                                        <div class="col-sm-12 text-center">
                                                            <input type="submit" name="submit" value="Update" class="register-btn" style="margin-top:10px;" />
                                                        </div>-->
                            <div class="clearfix"></div>
                            <div class="col-xs-12 pro-btm-fix">
                                <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-left-0 padding-right-0 brd-right">
                                    <button type="button" class="pro-btns col-sm-12 col-xs-12 padding-left-0" onclick="window.location.href = '<?php echo $module_url . "/users/my-profile"; ?>'"><?php echo _l('Button_Cancel', 'common'); ?></button>
                                </div>
                                <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-right-0 padding-left-0">
                                    <input type="submit" name="submit" value="<?php echo _l('Button_Update', 'common'); ?>" class="pro-btns pro-btns col-sm-12 col-xs-12 padding-left-0" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 grids hidden-sm">&nbsp;</div>
            </div>

        </form>
    </div>
</div>