<?php
if (isset($view->resourcedetails) and !empty($view->resourcedetails)) {
    extract($view->resourcedetails);
}
if ($_POST) {
    extract($_POST);
}
?>
<script type="text/javascript">
	
    $(document).ready( function(){
        $("#resourcesForm").validate({
            errorElement:"div",
            rules: {
                title:"required"
            },
            messages: {
                title:"Please enter title."
            }
        });
    });	
</script>
<div align="center">
    <h3><?php echo ($view->resource_id == 0) ? "Add" : "Edit"; ?> Resource</h3>
</div>
<form id="resourcesForm" name="resourcesForm" action="<?php echo $module_url . "/resources/addedit"; ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="resource_id" id="resource_id" value="<?php echo $view->resource_id; ?>" />
    <div class="frm" style="width:700px;margin-left:20%;">
        <?php if ($view->resource_id) { ?>
            <div>
                <label for="resource_id">Resources ID :</label>
                <?php echo $view->resource_id; ?> </div>
        <?php } ?>

        <div>
            <label for="title">Title <span class="required">*</span>:</label>
            <input type="text" class="txtbox" name="title" id="title" value="<?php echo isset($title) ? $title : ""; ?>"/>
        </div>
        <?php if ($view->resource_id) { ?>
            <div>
                <label for="name">Module :</label>
                <?php echo isset($module) ? $module : ""; ?>
            </div>
            <div>
                <label for="name">Controller :</label>
                <?php echo isset($option) ? $option : ""; ?>
            </div>
            <div>
                <label for="name">Action :</label>
                <?php echo isset($action) ? $action : ""; ?>
            </div>
        <?php } ?>
        <div>
            <label>&nbsp;</label>
            <input name="submit" id="submit" value="Submit" type="submit" class="button">
            <input name="cancel" id="cancel" type="button" value="Cancel" onclick="window.location.href = '<?php echo $module_url . "/resources"; ?>'" class="button"/>
        </div>
    </div>
</form>
<div class="pad4"></div>