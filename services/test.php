<?php
require("../application/config/configurations.php");


$methods = array();

$methods["login"] = array("mobile_number", "password","device_id","app_version","installation_date");
$methods['logout'] = array('token');
$methods["register"] = array("first_name", "last_name", "mobile_number", "password", "cpassword", "security_question_id" => array("type" => "dropdown", "value" => array(1 => "What was your childhood nickname?", 2 => "What is the name of your favourite childhood friend?", 3 => "In what city or town was your first job?", 4 => "What is your mother's maiden name?", 5 => "What is the name of the first school you attended?")), "security_answer", "email", "birth_date", "gender" => array("type" => "dropdown", "value" => array('Male' => 'Male', 'Female' => 'Female')), "address_line1", "address_line2", "city", "zip_code", "state", "country" => array("type" => "dropdown", "value" => array('India' => 'India', 'United States' => 'United States')), "base_currency_id" => array("type" => "dropdown", "value" => array('1' => 'US Dollar', '2' => 'Great Britain Pound')), "user_image_name", "user_image" => array("type" => "textarea", "name" => "user_image"));
$methods['get-currencies-list'] = array();
$methods['get-country-list'] = array();
$methods['get-security-question'] = array();
$methods['get-default-data'] = array();
$methods["change-password"] = array("token", "current_password", "new_password", "confirm_password");
$methods['get-user-data'] = array("token");
$methods["forgotpassword"] = array("mobile_number", "email", "security_question_id" => array("type" => "dropdown", "value" => array(1 => "What was your childhood nickname?", 2 => "What is the name of your favourite childhood friend?", 3 => "In what city or town was your first job?", 4 => "What is your mother's maiden name?", 5 => "What is the name of the first school you attended?")), "security_answer");
$methods["change-base-currency"] = array("token", "base_currency_id");
$methods["change-security-question"] = array("token", "old_security_question_id" => array("type" => "dropdown", "value" => array(1 => "What was your childhood nickname?", 2 => "What is the name of your favourite childhood friend?", 3 => "In what city or town was your first job?", 4 => "What is your mother's maiden name?", 5 => "What is the name of the first school you attended?")), "old_security_answer", "security_question_id" => array("type" => "dropdown", "value" => array(1 => "What was your childhood nickname?", 2 => "What is the name of your favourite childhood friend?", 3 => "In what city or town was your first job?", 4 => "What is your mother's maiden name?", 5 => "What is the name of the first school you attended?")), "security_answer");
$methods['save-user-image'] = array("token", "binary_content" => array("type" => "textarea", "name" => "binary_content"), "filename");
$methods['delete-user-image'] = array("token");
$methods['save-expense-image'] = array("token", "expense_id", "binary_content" => array("type" => "textarea", "name" => "binary_content"), "extension", "LUID");
$methods['save-trip-image'] = array("token", "user_trip_id", "binary_content" => array("type" => "textarea", "name" => "binary_content"), "extension", "LUID");
$methods['save-trip-images'] = array("token", "request_data" => array("type" => "textarea", "name" => "request_data"));
$methods['save-expense-images'] = array("token", "request_data" => array("type" => "textarea", "name" => "request_data"));
$methods["update-profile"] = array("token", "first_name", "last_name", "address_line1", "address_line2","city", "zip_code", "state", "email", "country" => array("type" => "dropdown", "value" => array('India' => 'India', 'United States' => 'United States')), "user_image_name", "user_image_raw" => array("type" => "textarea", "name" => "user_image_raw"));
$methods['exchange-currency-rate'] = array("token", "amount", "from_currency", "to_currency");
$methods['add-ticket'] = array("mobile_number", "subject", "comment");
$methods["add-expense"] = array("token", "expense_category_id" => array("type" => "dropdown", "value" => array(1 => "Hotels", 2 => "food", 3 => "breakfast")), "base_type_id" => array("type" => "dropdown", "value" => array(1 => "business", 2 => "personal")), "card_id" => array("type" => "dropdown", "value" => array(1 => "card1", 2 => "card 2", 3 => "card 3")), "expense_summary", "vendor_id", "expense_currency_id", "expense_base_currency_amount", "base_currency_id", "expense_date", "time", "expense_tags", "expense_amount", "expense_description", "LUID");
$methods["add-expenses"] = array("token", "request_data" => array("type" => "textarea", "name" => "request_data"));
$methods["delete-expenses"] = array("token", "request_data" => array("type" => "textarea", "name" => "request_data"));
$methods["update-expenses"] = array("token", "request_data" => array("type" => "textarea", "name" => "request_data"));
$methods["add-vendor"] = array("token", "name", "description", "address_line1", "address_line2", "city", "state", "country_id" => array("type" => "dropdown", "value" => array('98' => 'India', '159' => 'Norway')), "zip_code", "email", "phone", "LUID");
$methods["add-vendors"] = array("token", "request_data" => array("type" => "textarea", "name" => "request_data"));
$methods["update-vendors"] = array("token", "request_data" => array("type" => "textarea", "name" => "request_data"));
$methods["delete-vendors"] = array("token", "request_data" => array("type" => "textarea", "name" => "request_data"));
$methods["add-category"] = array("token", 'title', 'description', 'status' => array("type" => "dropdown", "value" => array(0 => "Inactive", 1 => "Active")), 'is_default' => array("type" => "dropdown", "value" => array(0 => "No", 1 => "Yes")), 'parent_expense_category_id', 'base_expense_type_id' => array("type" => "dropdown", "value" => array(1 => "business", 2 => "personal")), 'LUID');
$methods["add-categories"] = array("token", "request_data" => array("type" => "textarea", "name" => "request_data"));
$methods["update-categories"] = array("token", "request_data" => array("type" => "textarea", "name" => "request_data"));
$methods["delete-categories"] = array("token", "request_data" => array("type" => "textarea", "name" => "request_data"));
$methods["add-trip"] = array("token", 'trip_title', 'base_expense_type_id' => array("type" => "dropdown", "value" => array(1 => "business", 2 => "personal")), 'trip_budget', 'trip_date_from', 'trip_date_to', 'trip_description', 'trip_destination', 'trip_status' => array("type" => "dropdown", "value" => array(1 => "upcoming", 2 => "ongoing", 3 => "previous")), 'LUID');
$methods["add-trips"] = array("token", "request_data" => array("type" => "textarea", "name" => "request_data"));
$methods["update-trips"] = array("token", "request_data" => array("type" => "textarea", "name" => "request_data"));
$methods["delete-trips"] = array("token", "request_data" => array("type" => "textarea", "name" => "request_data"));
$methods['add-images'] = array("token", "binary_content" => array("type" => "textarea", "name" => "binary_content"), "extension");
$methods['delete-expense-images'] = array("token", "image_path", "LUID");
$methods["get-user-vendor"] = array("token", "timestamp");
$methods["get-user-trips"] = array("token", "timestamp");
$methods["get-user-categories"] = array("token", "timestamp");
$methods["get-user-expenses"] = array("token", "timestamp");
$methods["get-user-profile"] = array("token");
$methods["get-user-security-question"] = array("token", "mobile_number");
$methods["get-all-images"] = array("token", "timestamp");
$methods["check-currency-rate-status"] = array("currency_code");
$methods["export-expenses"] = array("token", "user_expense_id" => array("type" => "textarea", "name" => "user_expense_id"), "type" => array("type" => "dropdown", "value" => array('single' => 'single', 'multiple' => 'multiple')));
$methods["export-trips"] = array("token", "user_trip_id" => array("type" => "textarea", "name" => "user_trip_id"), "type" => array("type" => "dropdown", "value" => array('single' => 'single', 'multiple' => 'multiple')));
$methods["update-luids"] = array("token", "table_name" => array("type" => "dropdown", "value" => array('user_expenses' => 'user_expenses', 'user_trips' => 'user_trips', "expense_vendors" => "expense_vendors", "expense_categories" => "expense_categories", "user_expense_reference" => "user_expense_reference", "user_trip_reference" => "user_trip_reference")), "request_data" => array("type" => "textarea", "name" => "request_data"));
$methods["reset-sync-mapping"] = array("token");
ksort($methods);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Dailyuse</title>
    </head>
    <body>
        <table align="center" width="600" cellpadding="2" cellspacing="2" border="0">
            <tr>
                <td>method</td>
                <td><select name="method" onchange="window.location.href = 'test.php?method=' + this.value">
                        <option value="">Select Method</option>
                        <?php foreach ($methods as $method => $fields) { ?>
                            <option value="<?php echo $method; ?>" <?php echo (isset($_GET["method"]) and $_GET["method"] == $method) ? "selected=\"selected\"" : ""; ?>><?php echo $method; ?></option>
                        <?php } ?>
                    </select></td>
            </tr>
        </table>
        <?php if (isset($_GET["method"]) and $_GET["method"]) { ?>
            <form action="<?php echo APPLICATION_URL . "/services/" . ((isset($_GET["method"])) ? $_GET["method"] : ""); ?>" method="post" name="frm" enctype="multipart/form-data">
                <table align="center" width="600" cellpadding="2" cellspacing="2" border="0">
                    <tr>
                        <td colspan="100%">Service URL: <?php echo APPLICATION_URL . "/services/" . $_GET["method"]; ?> </td>
                    </tr>
                    <?php foreach ($methods[$_GET["method"]] as $k => $v) { ?>
                        <tr>
                            <td><?php echo (is_array($v)) ? $k : $v; ?></td>
                            <td>
                                <?php if (!is_array($v)) { ?>
                                    <input type="text" name="<?php echo $v; ?>" />
                                <?php } else { ?>
                                    <?php
                                    switch ($v["type"]) {
                                        case "dropdown":
                                            ?>
                                            <select name="<?php echo $k; ?>">
                                                <option value=""></option>
                                                <?php foreach ($v["value"] as $key => $value) { ?>
                                                    <option value="<?php echo $key; ?>"><?php echo $key; ?> - <?php echo $value; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php
                                            break;
                                        case "file":
                                            ?>
                                            <input type="file" name="<?php echo $v["name"]; ?>" />
                                            <?php
                                            break;
                                        case "checkbox":
                                            ?>
                                            <input type="checkbox" name="<?php echo $k; ?>" value="<?php echo $v["value"]; ?>" />
                                            <?php
                                            break;
                                        case "textarea":
                                            ?>
                                            <textarea name="<?php echo $v["name"]; ?>"></textarea>
                                            <?php
                                            break;
                                    }
                                    ?>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php /* <tr>
                      <td>response_content_type</td>
                      <td><select name="response_content_type">
                      <option value=""></option>
                      <option value="application/json">application/json - Default</option>
                      <option value="text/xml">text/xml</option>
                      </select></td>
                      </tr> */ ?>
                    <tr>
                        <td></td>
                        <td><input type="submit" name="submit" value="Submit" /></td>
                    </tr>

                </table>
            </form>
        <?php } ?>
    </body>
</html>
<?php
?>