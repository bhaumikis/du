<?php
foreach ($view->appSettings as $group => $details) {

    foreach ($details as $k => $v) {
        if (isset($_POST["configurations"][$v["user_setting_id"]])) {
            $view->appSettings[$group][$k]["value"] = $_POST["configurations"][$v["user_setting_id"]];
        } elseif (strlen($view->appSettingsvalues[$v["user_setting_id"]])) {
            $view->appSettings[$group][$k]["value"] = $view->appSettingsvalues[$v["user_setting_id"]];
        }
    }
}
?>
<form name="appsettings" id="appsettings" method="post" enctype="multipart/form-data">
    <div class="wrapper">
        <div class="con-padding-tb">
            <!--setting screen-->
            <?php include($module_path . "/application/global/message.php"); ?>
            <div class="account2">
                <ul>
                    <?php
                    foreach ($view->appSettings['default'] as $k => $settings) {
                        $inputfieldname = "configurations[" . $settings["user_setting_id"] . "]";

                        switch ($settings['type']) {
                            case 'dropdown':
                                echo '<li>
                                        <div class="inline-form">
                                            <label class="c-label">' . $settings['title'] . '</label>
                                            <select name="' . $inputfieldname . '">
                                                <option>Select ' . $settings['title'] . '</option>';
                                foreach ($view->dropdown[$settings['parameter']] as $k => $v) {
                                    $selected = ($settings['value'] == $k) ? 'selected="selected"' : '';
                                    echo '<option value="' . $k . '" ' . $selected . ' >' . $v . '</option>';
                                }
                                echo '     </select>
                                        </div>
                                    </li>';
                                break;
                            case 'checkbox':
                                $notified = ($settings['value'] == 'on') ? 'checked="checked"' : '';
                                echo '<li> ' . $settings['title'] . '
                                    <div class="switch-account">
                                        <input type="hidden" name="' . $inputfieldname . '" id="hid_slider_' . $settings["user_setting_id"] . '" value="' . $settings['value'] . '"/>
                                        <input type="checkbox" ' . $notified . ' id="slider_' . $settings["user_setting_id"] . '" value="' . $settings["user_setting_id"] . '"/>
                                        <label for="slider_' . $settings["user_setting_id"] . '" class="switch"> <span class="slide-account"></span> </label>
                                    </div>
                                  </li>';
                                break;
                            case 'radio':
                                $arrFormat = json_decode($settings['list']);
                                echo '<li>
                                <p>' . $settings['title'] . '</p>';
                                foreach ($arrFormat as $strFormat) {
                                    $checked = ($settings['value'] == $strFormat) ? 'checked="checked"' : '';
                                    echo '<div class="col-xs-6">
                                <div class="radio">
                                <label>';
                                    echo '<input type="radio" name="' . $inputfieldname . '" id="optionsRadios1" value="' . $strFormat . '" ' . $checked . '/>';
                                    echo $strFormat . '</label>
                                </div>
                                </div>';
                                }
                                echo '</li>';
                                break;
                        }
                    }
                    ?>
                </ul>
            </div>
            <div class="clearfix"></div>
            <div class="col-xs-12 pro-btm-fix">
                <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-left-0 padding-right-0 brd-right">
                    <button type="button" class="pro-btns col-sm-12 col-xs-12 padding-left-0" onclick="window.location.href = '<?php echo $module_url . "/dashboard"; ?>'"><?php echo _l('Button_Cancel', 'common'); ?></button>
                </div>
                <div class="margin-top-15 col-xs-6 pull-left col-md-6 col-sm-12 padding-right-0 padding-left-0">
                    <input type="submit" name="submit" value="<?php echo _l('Button_Save', 'common'); ?>" class="pro-btns pro-btns col-sm-12 col-xs-12 padding-left-0" />
                </div>
            </div>
        </div>
</form>
<script type="text/javascript">

    $( document ).ready(function() {

        $('[id^="slider_"]').each(function() {
            //$("#" + this.id).removeClass("li_active_default");
            $("#" + this.id).click(function () {
                if(!$(this).is(':checked')) {
                    alert("We recommended to turn on push notification. Are you sure you want to turn off push notification.");
                    $("#" + this.id).removeAttr('checked');
                    $("#hid_slider_" + $("#" + this.id).val()).val('off');
                } else {
                    $("#" + this.id).attr('checked', 'checked');
                    $("#hid_slider_" + $("#" + this.id).val()).val('on');
                }
            });
        });


    });



</script>