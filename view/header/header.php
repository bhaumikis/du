<div class="header-orange">
    <h1><?php
        if ($action == 'index') {
            $strPageTitle = $option;
            $strPageName = ucwords(str_replace("-", " ", $option));
        } else {
            $strPageTitle = $action;
            $strPageName = ucwords(str_replace("-", " ", $action));
        }
        echo (isset($view->header_title))?$view->header_title:$strPageName;
        ?></h1>
</div>
<div class="responsive-menu-dropdown">
    <a title="" class="right-bar-btn"><i class="fa fa-bars" ></i></a>
</div>
<div class="header-alert">
    <ul>
        <?php
        $strActionName = $option . "#" . $action;

        switch ($strActionName) {
            case "my-expenses#index":
            case "my-expenses#add-edit":
                //echo '<li><a href="#" title=""><i class="fa fa-filter"></i></a></li>';
                //echo '<li><a title="" class=""><i class="fa fa-search"></i></a></li>';
                echo '<li><a href="' . $module_url . '/my-expenses/add-edit" title="" class=""><i class="fa fa-plus white"></i></a></li>';
                break;

            case "my-travel-plan#index":
            case "my-travel-plan#add-edit":
                //echo '<li><a href="#" title=""><i class="fa fa-filter"></i></a></li>';
                //echo '<li><a title="" class=""><i class="fa fa-search"></i></a></li>';
                echo '<li><a href="' . $module_url . '/my-travel-plan/add-edit" title="" class=""><i class="fa fa-plus white"></i></a></li>';
                break;

            case "dashboard#index":
                echo '<li><a href="' . $module_url . '/index/logout" title="Logout"><i class="fa fa-sign-out"></i></a></li>';
//                        echo '<li><a href="#" title=""><i class="fa fa-group"></i>Team Statistics</a></li>';
//                        echo '<li><a title="" class="message-btn"><i class="fa fa-envelope"></i><span>3</span></a>
//                          <div class="message"> <span>You have 3 New Messages</span> <a href="#" title=""><img src="http://placehold.it/40x40" alt="" />Hey! How are You Diana. I waiting for you.
//                            toe Check.
//                            <p><i class="fa fa-clock-o"></i>3:45pm</p>
//                            </a> <a href="#" title=""><img src="http://placehold.it/40x40" alt="" />Please Can you Submit A file. I am From Korea
//                            toe Check.
//                            <p><i class="fa fa-clock-o"></i>1:40am</p>
//                            </a> <a href="#" title=""><img src="http://placehold.it/40x40" alt="" />Hey Today is Party So you Will Have to Come
//                            <p><i class="fa fa-clock-o"></i>4 Hours ago</p>
//                            </a> <a href="inbox.html" class="view-all">VIEW ALL MESSAGE</a> </div>
//                        </li>';
//                        echo '<li><a title="" class="notification-btn"><i class="fa fa-bell"></i><span>4</span></a>
//                          <div class="notification"> <span>You have 6 New Notification</span> <a href="#" title=""><img src="http://placehold.it/40x40" alt="" />Server 3 is Over Loader Pleas
//                            toe Check.
//                            <p><i class="fa fa-clock-o"></i>3:45pm</p>
//                            </a> <a href="#" title=""><img src="http://placehold.it/40x40" alt="" />Server 10 is Over Loader Pleas
//                            toe Check.
//                            <p><i class="fa fa-clock-o"></i>1:40am</p>
//                            </a> <a href="#" title=""><img src="http://placehold.it/40x40" alt="" />New User Registered Please Check This
//                            <p><i class="fa fa-clock-o"></i>4 Hours ago</p>
//                            </a> <a href="#" class="view-all">VIEW ALL NOTIFICATIONS</a> </div>
//                        </li>';                  
                break;
            case 'my-categories#index':
                //echo '<li><a href="' . $module_url . '/my-categories/add-edit" title="" class=""><i class="fa fa-plus white"></i></a></li>';
                break;
            case 'my-vendors#index':
                echo '<li><a href="' . $module_url . '/my-vendors/add-edit" title="" class=""><i class="fa fa-plus white"></i></a></li>';
                break;
            case 'users#my-profile':
                echo '<li><a href="' . $module_url . '/users/edit-profile" title="" class=""><i class="fa fa-edit white"></i></a></li>';
                break;
            default:
                break;
        }
        ?>        
    </ul>
</div>