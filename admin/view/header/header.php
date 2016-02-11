<script>
    $(document).ready(function() {
        setInterval(get_msg_count, 10000);
    });
    function get_msg_count() {
        $.ajax ({
            url: '<?php echo $module_url . "/tickets/get-header-notification-count"; ?>',
            data: "",
            type: 'post',
            success: function(response) {
                if(response != 'na') {
                    $("#notification_sound").html('<audio preload="auto" autoplay="autoplay" hidden=true controls><source src="<?php echo APPLICATION_URL."/images/ding.wav"?>"></audio>');
                    $('#my_notifications').html(response);
                    jQuery('<div/>', {
                        id: 'notif-bot',
                        class : 'notif-bot alert alert-info',
                        text: 'You just got a notification!'
                    }).appendTo('.notif-bot-cnt')
                    .delay(5000)
                    .fadeOut();
                }
            }
        });
    }
</script>
<style>
    .notif-bot-cnt{ position: absolute; right: 8%; top: -25%;}
</style>
<div id="notification_sound" style="display:none;"></div>
<div class="header-orange">
    <h1><?php
$strPageTitle = ucwords(str_replace("-", " ", $option));
echo (isset($view->header_title)) ? $view->header_title : $strPageTitle;
?>
    </h1>    
</div>
<div class="responsive-menu-dropdown">
    <a title="" class="right-bar-btn"><i class="fa fa-bars" ></i></a>
</div>
<div class="notif-bot-cnt"></div>
<div class="header-alert">
    <ul>
        <?php
        $strActionName = $option . "#" . $action;

        $notifications = $view->helper('notification')->getAdminTickets();
        $tickets = "";
        if (isset($notifications) and !empty($notifications)) {
            foreach ($notifications as $notification) {
                if (isset($notification['user_image']) and !empty($notification['user_image'])) {
                    $img = '<img src="' . APPLICATION_URL . '/images/user_images/' . $notification['user_image'] . '" height="40px" width="40px" alt="" />';
                } else {
                    $img = '<img src="http://placehold.it/40x40" alt="" />';
                }

                $tickets .= '<a href="' . $module_url . '/tickets/view/ticket_id/' . $notification['ticket_id'] . '" title="">' . $img . ' ' . $notification['subject'] . '
                                <p><i class="fa fa-clock-o"></i>' . $notification['created_date'] . '</p></a> ';
            }
        }
        $notificationsLI = '<li><a title="" class="notification-btn"><i class="fa fa-bell"></i><span id="my_notifications">' . $view->helper('notification')->getNotificationCount() . '</span></a>
                                <div class="notification"> <span>' . _l('Text_Note1', 'notifications') . ' <b>' . $view->helper('notification')->getNotificationCount() . '</b> ' . _l('Text_Note2', 'notifications') . '</span>
                                ' . $tickets . '
                                <a href="' . $module_url . '/tickets/" class="view-all">' . _l('Text_Note3', 'notifications') . '</a>
                                </div>
                            </li>';

        switch ($strActionName) {
            case "my-expenses#index":
                echo $notificationsLI;
                echo '<li><a href="#" title=""><i class="fa fa-filter"></i></a></li>';
                echo '<li><a title="" class=""><i class="fa fa-search"></i></a></li>';
                echo '<li><a title="" class=""><i class="fa fa-plus white"></i></a></li>';
                break;
            case "users#admin-users":
                echo $notificationsLI;
                echo '<li><a href="' . $module_url . '/users/addedit" title="Add Admin" class=""><i class="fa fa-plus white"></i></a></li>';
                break;

            case "users#end-users":
                echo $notificationsLI;
                break;

            case "users#manage-my-account":
                echo $notificationsLI;
                break;

            case "users#change-password":
                echo $notificationsLI;
                break;

            case "users#change-security-question":
                echo $notificationsLI;
                break;

            case "tickets#index":
                echo $notificationsLI;
                echo '<li><a href="' . $module_url . '/tickets/add-ticket" title="Add Ticket" class=""><i class="fa fa-plus white"></i></a></li>';
                break;

            case "tickets#add-ticket":
                echo $notificationsLI;
                break;

            case "ticket-reports#index":
                echo $notificationsLI;
                break;

            case "ticket-reports#view-ticket-assignments":
                echo $notificationsLI;
                break;

            case "ticket-reports#view-ticket-logs":
                echo $notificationsLI;
                break;

            case "ticket-assignments#index":
                echo $notificationsLI;
                break;

            case "security-questions#index":
                echo $notificationsLI;
                echo '<li><a href="' . $module_url . '/security-questions/add" title="Add Security Question" class=""><i class="fa fa-plus white"></i></a></li>';
                break;

            case "security-questions#add":
                echo $notificationsLI;
                break;

            case "emailtemplates#index":
                echo $notificationsLI;
                echo '<li><a href="' . $module_url . '/emailtemplates/addedit" title="Add Email Template" class=""><i class="fa fa-plus white"></i></a></li>';
                break;

            case "emailtemplates#addedit":
                echo $notificationsLI;
                break;

            case "dashboard#index":
                echo $notificationsLI;
                echo '<li><a href="' . $module_url . '/index/logout" title="Logout"><i class="fa fa-sign-out"></i></a></li>';
                break;

            case 'users#my-profile':
                echo $notificationsLI;
                echo '<li><a href="' . $module_url . '/users/edit-profile" title="" class=""><i class="fa fa-edit white"></i></a></li>';
                break;
            default:
                break;
        }
        ?>
    </ul>
</div>