<?php 
$temp_path = $module_url . "/images/temp_user_images/";
?>
<script type="text/javascript">
    $(document).ready(function() {      
        $('#user_image').change(function() {

            if ($('#user_image').val() != "") {
                $("form").validate({
                    onclick: false,
                    rules: {
                        user_image: {
                            accept: "jpg|jpeg|png|gif|bmp"
                        }
                    },
                    messages: {
                        user_image: "Please Upload valid file and file size must be less then 1MB"
                    }
                    ,
                    errorPlacement: function(error, element) {
                        return false;
                    }
                });
                if ($('form').valid()) {
                    $('form').submit();
                }
            }
        });
        $('input[type=file]').bootstrapFileInput();
        $('.file-inputs').bootstrapFileInput();
    });
</script>
<style>
body{background:#eceded;}
</style>
<form style="margin: 0px !important; background-color: #eceded;" name="frm-upload" id="frm-upload" method="post" enctype="multipart/form-data">
    <div style="border:1px solid #ccc;">
        <input class="pic-upload" style="margin: 0px !important; width:100%;" type="file" placeholder="User Image" name="user_image" id="user_image"/>
        <span class="file-holder" id="fileholder_text" style=" margin-left: 10px; margin-top: 6px;overflow: hidden;position: absolute;text-overflow: ellipsis;white-space: nowrap;width: 200px;"></span>
    </div>
</form>
<?php if ($view->uploadfile["uploadfile"] != "") { ?>
    <script type="text/javascript">
        $('.file-holder').html('<?php echo $view->uploadfile["uploadfileOrig"]; ?>');        
        parent.$('#temp_user_image').attr("src", "<?php echo $temp_path . $view->uploadfile["uploadfile"]; ?>");
        parent.$('#hid_user_image').val('<?php echo $view->uploadfile["uploadfile"]; ?>');
    </script>
<?php } ?>