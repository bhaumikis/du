<thead>
    <tr>
        <?php
        foreach ($view->fields as $k => $field) {

            if (isset($field["hide_column"]) and $field["hide_column"] == true) {
                continue;
            }

            if ($k == 1) {
                $class = "gridth top-left";
            } elseif (count($view->fields) == $k) {
                $class = "gridth top-right";
            } else {
                $class = "gridth";
            }
            ?>
            <?php if ($field["checkbox"]) { ?>
                <td align="center"><input type="checkbox" name="chkall" id="chkall" /></td>
                <?php continue;
            } ?>

            <td
            <?php if ($field["params"] and is_array($field["params"])) { ?>
                    <?php foreach ($field["params"] as $attribute => $attributeValue) { ?>
                        <?php echo $attribute; ?>="<?php echo $attributeValue; ?>"
                    <?php } ?>
                <?php } ?>>
                <?php
                if (isset($field["enable_sort"]) and ($enable_sort == false)) {
                    echo $field["title"];
                } else {
                    $q_str = preg_replace("/(\&)?sortby\=[0-9]{1,}[A|D]{1}/", "", $_SERVER['QUERY_STRING']);
                    ?>
                    <a class="null" href="<?php
                    $sort_field_url = preg_replace("/\?(.*)/", "", htmlspecialchars($_SERVER['REQUEST_URI']));
                    
                    echo ($view->sortby == $k . "A") ?
                            $sort_field_url . "?" . $q_str . "&sortby=" . $k . "D" :
                            $sort_field_url . "?" . $q_str . "&sortby=" . $k . "A";
                    ?>"><?php echo $field["title"]; ?> </a>
                    <?php if ($view->sortby == $k . "A") { ?>
                        <img src="<?php echo $module_url . "/images/asc.png"; ?>" border="0" title="Ascending"/>
                    <?php } elseif ($view->sortby == $k . "D") { ?>
                        <img src="<?php echo $module_url . "/images/desc.png"; ?>" border="0" title="Descending"/>
                    <?php } ?>
            <?php } ?>
            </td>
<?php } ?>
    </tr>
</thead>
