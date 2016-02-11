<div align="center">
    <h3>Resources</h3>
</div>
<div align="right" style="width:95%"> <a class="crtnew" href="<?php echo $module_url."/resources/update-resource-list";?>">Crawl</a> | <a class="crtnew" href="<?php echo $module_url."/resources/addedit";?>">Add Resource</a></div>
<div>&nbsp;</div>
<table width="90%" cellpadding="2" cellspacing="2"  align="center" cellspacing="2">
    <?php include($module_path . "/view/middle/miscellaneous/fields.php"); ?>
    <?php if (!count($view->resources)) { ?>
        <tr>
            <td colspan="100%" align="center"><div style="text-align: center;">No Record Found.</div></td>
        </tr>
    <?php } ?>
    <?php
    for ($i = 0; $i < count($view->resources); $i++) {
        if (($i + 1) % 2 == 0) {
            $class = "class=\"evnrw\"";
        } else {
            $class = "class=\"oddrw\"";
        }
        ?>
        <tr <?php echo $class; ?>>
            <td class="gridtd"><?php echo $view->resources[$i]["resource_id"]; ?></td>
            <td class="gridtd"><?php echo $view->resources[$i]["title"]; ?></td>
            <td class="gridtd"><?php echo $view->resources[$i]["module"]; ?></td>
            <td class="gridtd"><?php echo $view->resources[$i]["option"]; ?></td>
            <td class="gridtd"><?php echo $view->resources[$i]["action"]; ?></td>
            <td class="gridtd"><a class="ancrul" href="<?php echo $module_url . "/resources/addedit/resource_id/" . $view->resources[$i]["resource_id"]; ?>" ><img src="<?php echo $module_url . "/images/edit_icon.png"; ?>" title="Edit" /></a></td>
        </tr>
<?php } ?>
</table>
<?php // include($module_path . "/view/middle/miscellaneous/pagenav.php"); ?>
<div class="pad4"></div>