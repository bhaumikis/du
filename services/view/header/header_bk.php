<script type="text/javascript">

</script>
<div id="head">
    <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td style="padding-left:0px;background:url(../images/header_BG.png) repeat-x;" class="mainbanner" height="93">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td width="15">&nbsp;</td>
                        <td align="left"><a href="<?php echo $module_url; ?>/index"><div class="logo"></div></a></td>
                        <td align="right" valign="bottom" class="gridtd">
                            <?php if (isset($_SESSION[$session_prefix]["user"]["first_name"])) { ?>
                                <b>Welcome, <?php echo $_SESSION[$session_prefix]["user"]["first_name"]; ?></b>&nbsp;
                                <br/>
                                <a class="ancrul" href="<?php echo $module_url . "/index/logout"; ?>">Logout</a>&nbsp;
                            <?php } ?>
                        </td>
                    </tr>
                </table></td>
        </tr>
    </table>
</div>
<?php if ($obj->checkUserIsLoggedIn()) { ?>
    <div class="wrapper1">
        <div class="wrapper">
            <div class="nav-wrapper">
                <div class="nav-left"></div>
                <div class="nav">
                    <ul id="navigation"> 
                        <li class="<?php echo (in_array($option, array("users", "usertypes", "resources"))) ? "active" : ""; ?>"> <a href="#"> <span class="menu-left"></span> <span class="menu-mid">Users</span> <span class="menu-right"></span> </a>
                            <div class="sub">
                                <ul>
                                    <?php if ($obj->checkPermissions(array("option" => "users", "action" => "index"))) { ?><li> <a href="<?php echo $module_url . "/users"; ?>">Users</a> </li><?php } ?>
                                    <?php if ($obj->checkPermissions(array("option" => "usertypes", "action" => "index"))) { ?><li> <a href="<?php echo $module_url . "/usertypes"; ?>">User Types</a> </li><?php } ?>
                                    <?php if ($obj->checkPermissions(array("option" => "resources", "action" => "index"))) { ?><li> <a href="<?php echo $module_url . "/resources"; ?>">Resources</a> </li><?php } ?>

                                </ul>
                                <div class="btm-bg"></div>
                            </div>
                        </li>
                        <li class="<?php echo (in_array($option, array("configurations", "emailtemplates", "changepassword"))) ? "active" : ""; ?>"> <a href="#"> <span class="menu-left"></span> <span class="menu-mid">Tools</span> <span class="menu-right"></span> </a>
                            <div class="sub">
                                <ul>
                                    <?php if ($obj->checkPermissions(array("option" => "configurations", "action" => "index"))) { ?><li> <a href="<?php echo $module_url . "/configurations"; ?>">Configurations</a> </li><?php } ?>
                                    <?php if ($obj->checkPermissions(array("option" => "emailtemplates", "action" => "index"))) { ?><li> <a href="<?php echo $module_url . "/emailtemplates"; ?>">Email Templates</a> </li><?php } ?>
                                    <?php if ($obj->checkPermissions(array("option" => "changepassword", "action" => "index"))) { ?><li> <a href="<?php echo $module_url . "/changepassword"; ?>">Change Password</a> </li><?php } ?>        
                                </ul>
                                <div class="btm-bg"></div>
                            </div>
                        </li>

                    </ul>
                </div>
                <div class="nav-right"></div>
            </div>
        </div>
    </div>
<?php } ?>