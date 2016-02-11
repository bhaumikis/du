<?php
/*
 * Render the messsages
 */
if ((isset($_SESSION[$session_prefix]["error_message"]) and !empty($_SESSION[$session_prefix]["error_message"])) or (isset($_SESSION[$session_prefix]["action_message"]) and !empty($_SESSION[$session_prefix]["action_message"]))) {


    $error_message = "";
    if (isset($_SESSION[$session_prefix]["error_message"]) and !empty($_SESSION[$session_prefix]["error_message"])) {
        $error_message = $_SESSION[$session_prefix]["error_message"];
        $_SESSION[$session_prefix]["error_message"] = "";
        unset($_SESSION[$session_prefix]["error_message"]);
    }
    $action_message = "";
    if (isset($_SESSION[$session_prefix]["action_message"]) and !empty($_SESSION[$session_prefix]["action_message"])) {
        $action_message = $_SESSION[$session_prefix]["action_message"];
        $_SESSION[$session_prefix]["action_message"] = "";
        unset($_SESSION[$session_prefix]["action_message"]);
    }
    ?>


    <?php
    if (isset($error_message) and !empty($error_message)) {

        if (is_array($error_message)) {

            foreach ($error_message as $k => $msg) {
                ?>
                <div id="div_common_error_msg" class="alert alert-danger alert-dismissable">
                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>
                    <?php echo $msg; ?>
                </div>

                <?php
            }
        } else {
            ?>
            <div id="div_common_error_msg" class="alert alert-danger alert-dismissable">
                <button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>
                <?php echo $error_message; ?>
            </div>

        <?php } ?>


    <?php } ?>


    <?php if (isset($action_message) and !empty($action_message)) { ?>
        <div id="div_common_action_msg" class="alert alert-success alert-dismissable">
            <button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>
            <?php echo $action_message; ?>
        </div>


    <?php } ?>

    <?php
}
