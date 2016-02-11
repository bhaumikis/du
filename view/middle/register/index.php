<?php
if ($_POST) {
    extract($_POST);
}
?>
<div class="reg-head black">
    <h1 class="text-center">Register</h1>
    <a href="<?php echo $module_url; ?>" >
        <?php include($module_path . "/view/header/header_html.php"); ?>
    </a>
</div>
<div class="col-sm-12 text-center">
    <div class="col-md-2 grids hidden-sm"></div>
    <div class="col-md-8 grids col-sm-12">
        <form name="register" id="register" method="post">
            <?php include($module_path . "/application/global/message.php"); ?>
            <div class="profile-sec-head pink">
                <div class="col-md-12 col-sm-12">
                    <span class="profile-sec-head profile-head-bg">
                        <img src="" alt="" id="temp_user_image" width="120px" height="120px">
                        <input type="hidden" name="hid_user_image" id="hid_user_image" value="<?php echo (isset($hid_user_image) and !empty($hid_user_image)) ? $hid_user_image : ""; ?>">
                    </span>
<!--                    <input class="pic-upload" type="file" placeholder="User Image" name="user_image" id="user_image"/>-->
                    <iframe name="file-upload" id="file-upload" src="<?php echo $module_url . "/register/upload-temporary-image/"; ?>" class="file_upload pull_left upload-bg" ></iframe>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="inline-form">
                        <label class="c-label">First Name<span class="error">*</span></label>
                        <input class="input-style pull-left" type="text" placeholder="First Name*" name="first_name" id="first_name" value="<?php echo (isset($first_name) and !empty($first_name)) ? $first_name : ""; ?>"/>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="inline-form">
                        <label class="c-label">Last Name<span class="error">*</span></label>
                        <input class="input-style pull-left" type="text" placeholder="Last Name*" name="last_name" id="last_name" value="<?php echo (isset($last_name) and !empty($last_name)) ? $last_name : ""; ?>"/>
                    </div>
                </div>
            </div>
            <!--form start-->
            <div class="registration">
                <div class="custom-form">

                    <div class="col-md-6 col-sm-12">
                        <div class="inline-form">
                            <label class="c-label">Mobile Number<span class="error">*</span></label>
                            <input class="input-style pull-left" type="text" placeholder="Mobile Number*" name="mobile_number" id="mobile_number" value="<?php echo (isset($mobile_number) and !empty($mobile_number)) ? $mobile_number : ""; ?>"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="inline-form">
                            <label class="c-label">Email<span class="error">*</span></label>
                            <input class="input-style pull-left" type="text" placeholder="Email*" name="email" id="email" value="<?php echo (isset($email) and !empty($email)) ? $email : ""; ?>"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="inline-form">
                            <label class="c-label">Password<span class="error">*</span></label>
                            <input class="input-style pull-left" type="password" placeholder="Password*" name="password" id="password"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="inline-form">
                            <label class="c-label">Confirm Password<span class="error">*</span></label>
                            <input class="input-style pull-left" type="password" placeholder="Confirm Password*" name="cpassword" id="cpassword"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="inline-form">
                            <label class="c-label">Select Security Question<span class="error">*</span></label>
                            <select class="form-control" name="security_question_id" id="security_question_id">
                                <option value="">Select Security Question*</option>
                                <?php
                                if (isset($view->securityquestions)) {
                                    foreach ($view->securityquestions as $questions) {
                                        ?>
                                        <option value="<?php echo $questions['security_question_id']; ?>" data-title="<?php echo trim($questions['question']); ?>" 
                                                <?php echo ($_POST['security_question_id'] == $questions['security_question_id']) ? 'selected="selected"' : ""; ?>><?php echo $questions['question']; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="inline-form">
                            <label class="c-label">Security Answer<span class="error">*</span></label>
                            <input class="input-style pull-left" type="password" placeholder="Security Answer*" name="security_answer" id="security_answer"/>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-6 col-sm-12">
                        <div class="inline-form">
                            <label class="c-label">Address Line1</label>
                            <input class="input-style pull-left" type="text" placeholder="Address Line1" name="address_line1" id="address_line1" value="<?php echo (isset($address_line1) and !empty($address_line1)) ? $address_line1 : ""; ?>"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="inline-form">
                            <label class="c-label">Address Line2</label>
                            <input class="input-style pull-left" type="text" placeholder="Address Line2" name="address_line2" id="address_line2" value="<?php echo (isset($address_line2) and !empty($address_line2)) ? $address_line2 : ""; ?>"/>
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
                            <input class="input-style pull-left" maxlength="10" type="text" placeholder="Post Code/Zip Code" name="zip_code" id="zip_code" value="<?php echo (isset($zip_code) and !empty($zip_code)) ? $zip_code : ""; ?>"/>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="inline-form">
                            <label class="c-label">Date of Birth<span class="error">*</span></label>
                            <div class="reg-form" id="birth_date">
                                <a class="cursor-pointer-default" id="a_date_birth" style="float:left;"><i class="fa fa-calendar cal-orange"></i></a>
                                <input style="width:90%; border:none;" class="pull-left" type="text" placeholder="Birthdate*" name="birth_date" id="birth_date" value="<?php echo ($_POST['birth_date'] != "") ? $_POST['birth_date'] : ""; ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="inline-form">
                            <label class="c-label">Base Currency<span class="error">*</span></label>
                            <select class="form-control" name="base_currency_id" id="base_currency_id"/>
                            <option value="">Select Base Currency*</option>
                            <?php
                            if (isset($view->currencies['currencies'])) {
                                foreach ($view->currencies['currencies'] as $currency) {
                                    ?>
                                    <option value="<?php echo $currency['currency_id']; ?>" data-image="<?php echo $module_url ?>/images/msdropdown/icons/blank.gif" data-imagecss="flag <?php echo strtolower($currency['iso_alpha']); ?>" data-title="<?php echo trim($currency['currency_name']); ?>"
                                    <?php echo ($_POST['base_currency_id'] == $currency['currency_id']) ? 'selected="selected"' : ""; ?>>
                                        <?php echo $currency['currency_code']; ?> - <?php echo $currency['currency_name']; ?>
                                    </option>
                                <?php
                                }
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 clearfix">
                        <div class="inline-form">
                            <label class="c-label">Select Gender<span class="error">*</span></label>
                            <select class="form-control" name="gender" id="gender">
                                <option value="">Select Gender*</option>
                                <option value="Male" <?php echo ($_POST['gender'] == "Male") ? 'selected="selected"' : ""; ?>>Male</option>
                                <option value="Female" <?php echo ($_POST['gender'] == "Female") ? 'selected="selected"' : ""; ?>>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="inline-form">
                            <label class="c-label">Select Country<span class="error">*</span></label>
                            <select class="form-control" name="country_id" id="country_id"/>

                            <option value="">Select Country*</option>
                            <?php
                            if (isset($view->countries)) {
                                foreach ($view->countries as $country) {
                                    ?>
                                    <option value="<?php echo $country['country_id']; ?>" data-image="<?php echo $module_url ?>/images/msdropdown/icons/blank.gif" data-imagecss="flag <?php echo strtolower($country['iso_alpha2']); ?>" data-title="<?php echo $country['name']; ?>"
                                            <?php echo ($_POST['country_id'] == $country['country_id']) ? 'selected="selected"' : ""; ?>><?php echo $country['name']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                            </select>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-12 text-center">
                        <input id="" type="submit" name="submit" value="Register" class="register-btn btnSubmit" style="margin-top:10px;" />
                    </div>
                    </form>
                </div>
            </div>
            <!--form end-->
    </div>
    <div class="col-md-2 grids hidden-sm">&nbsp;</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        $("#country_id").msDropdown();
        $("#base_currency_id").msDropdown();
        $("#security_question_id").msDropdown();
        $("#gender").msDropdown();

        $("#register").validate({
            rules: {
                first_name: {required: true},
                last_name: {required: true},
                mobile_number: {required: true, digits: true},
                email: {required: true, email: true},
                password: {required: true, regex: true},
                cpassword: {required: true, equalTo: "#password"},
                security_question_id: {required: true},
                security_answer: {required: true},
                city: {required: true},
                zip_code: {required: true,validateAlphaNum:true},
                birth_date: {required: true,minAge: 15},
                base_currency_id: {required: true},
                gender: {required: true},
                country_id: {required: true}
            },
            messages: {
                first_name: {required: "<?php echo _l('Enter_Firstname', 'users'); ?>"},
                last_name: {required: "<?php echo _l('Enter_Lastname', 'users'); ?>"},
                mobile_number: {required: "<?php echo _l('Enter_Mobile', 'users'); ?>", digits: "<?php echo _l("Error_Mobile_No", "users"); ?>"},
                email: {required: "<?php echo _l('Enter_Email', 'users'); ?>", email: "<?php echo _l("Error_Invalid_Email", "users"); ?>"},
                password: {required: "<?php echo _l('Enter_Password', 'users'); ?>"},
                cpassword: {required: "<?php echo _l('Confirm_Password', 'users'); ?>", equalTo: "<?php echo _l('Password_Confirm_Password_Same', 'users'); ?>"},
                security_question_id: {required: "<?php echo _l('Select_Question', 'users'); ?>"},
                security_answer: {required: "<?php echo _l('Enter_Answer', 'users'); ?>"},
                city: {required: "<?php echo _l('Enter_City', 'users'); ?>"},
                zip_code: {required: "<?php echo _l('Enter_Zipcode', 'users'); ?>"},
                birth_date: {required: "<?php echo _l('Enter_Birthdate', 'users'); ?>",minAge: "<?php echo _l('User_Age', 'users'); ?>"},
                base_currency_id: {required: "<?php echo _l('Enter_Currency', 'users'); ?>"},
                gender: {required: "<?php echo _l('Select_Gender', 'users'); ?>"},
                country_id: {required: "<?php echo _l('Enter_Country', 'users'); ?>"}
            }
        });
        $.validator.addMethod('regex', function(value) {
            return /\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\W])(?=\S*[\d])\S*/.test(value);
        }, '<?php echo _l('Invalid_Password_String', 'users'); ?>');

        var strDateToSet = new Date();// today!
        strDateToSet.setFullYear( strDateToSet.getFullYear() - 15 );
        
        $('#birth_date input').datepicker('setDate', strDateToSet);
        $('#birth_date input').datepicker('update');
        $('#birth_date input').val('');


        $("#a_date_birth").click(function() {
            $('#birth_date input').datepicker('show');
        });

        $('#birth_date input').on('change', function() {
            $('.datepicker').hide();
        });
        
        $(".btnSubmit").click(function() {
            getMSDrowpdownValidate('register','gender','<?php echo _l('Select_Gender', 'users'); ?>');
            getMSDrowpdownValidate('register','country_id','<?php echo _l('Enter_Country', 'users'); ?>');
            getMSDrowpdownValidate('register','base_currency_id','<?php echo _l('Enter_Currency', 'users'); ?>');
            getMSDrowpdownValidate('register','security_question_id','<?php echo _l('Select_Question', 'users'); ?>');
        });
        
        $('#base_currency_id').on('change', function() {            
            $.ajax
            ({
                url: '<?php echo $module_url . "/register/check-exchange-rate-availability"; ?>',
                data: "cur_code="+$(this).val(),
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
                                $('#base_currency_id').msDropDown().data('dd').set('selectedIndex',0);
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
<script>
    $(document).ready(function() {
        $.validator.addMethod("minAge", function(value, element, min) {
            var today = new Date();
            var birthDate = new Date(value);
            var age = today.getFullYear() - birthDate.getFullYear();

            if (age > min+1) {
                return true;
            }

            var m = today.getMonth() - birthDate.getMonth();

            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            return age >= min;
        }, "<?php echo _l('User_Age', 'users'); ?>");
    })
    
</script>