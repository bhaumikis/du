<script type="text/javascript">
    $(document).ready(function() {
        $("#addticket").validate({
            errorElement: "div",
            rules: {
                query_template_id: "required"
            },
            messages: {
                query_template_id: "<?php echo _l('Select_Query_Template', 'tickets'); ?>"
            }
        });
        $(".btnSubmit").click(function() {            
            getMSDrowpdownValidate('addticket','query_template_id','<?php echo _l('Select_Query_Template', 'tickets'); ?>');
        });
        $('#query_template_id').on('change', function() {
            $(this).valid();
        });
        $('.summernote').summernote({
            height: 200
        });
    });
</script>

<div class="wrapper">
    <div class="container con-padding-tb">
        <div class="col-xs-12">
            <div class="wizard-form-h">
                <div id="wizard" class="swMain">
                    <div id="step-1">
                        <div class="col-xs-12">
                            <div class="inline-form">
                                <label class="c-label"><?php echo _l('Text_Mobile_Number', 'tickets'); ?></label>
                                <div class="form-group clearfix">
                                    <div class="input-group" id=''>
                                        <input type='text' name="mobile_number" id="mobile_number" placeholder="<?php echo _l('Placeholder_Mobile_Number', 'tickets'); ?>" class="form-control" />
                                        <span class="input-group-addon cursor-pointer-default" id="search_mobile"><span class="fa fa-search font-ic-20 txt-orange"></span> </span> 
                                    </div>
                                </div>
                                <div id="mobile_error" class="form-group clearfix" style="display: none; color: red;"></div>
                            </div>
                        </div>
                        <form name="addticket" id="addticket" method="post">
                            <div class="col-xs-12" id="userdetails">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <tr>
                                            <th width="20%"><?php echo _l('Label_First_Name', 'tickets'); ?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo _l('Label_Last_Name', 'tickets'); ?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo _l('Label_Add1', 'tickets'); ?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo _l('Label_Add2', 'tickets'); ?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo _l('Label_DOB', 'tickets'); ?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo _l('Label_Gender', 'tickets'); ?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo _l('Label_Mobile', 'tickets'); ?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo _l('Label_Email', 'tickets'); ?></th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo _l('Label_Country', 'tickets'); ?></th>
                                            <td></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-xs-12 margin-top-15" style="display: none;" id="querydiv">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <tr>
                                            <th width="20%"><?php echo _l('Label_Select_Query_Template', 'tickets'); ?></th>
                                            <td>
                                                <select name="query_template_id" id="query_template_id" style="width:100%;">
                                                    <option value=""><?php echo _l('Label_Select_Query_Template', 'tickets'); ?></option>
                                                    <?php
                                                    if (isset($view->queryTemplates) and !empty($view->queryTemplates)) {
                                                        foreach ($view->queryTemplates as $template) {
                                                            ?>
                                                            <option value="<?php echo $template['email_template_id']; ?>"><?php echo $template['title']; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php echo _l('Label_Comment', 'tickets'); ?></th>
                                            <td><textarea class="" name="comments" id="comments" style="width:100%;" placeholder="<?php echo _l('Placeholder_Comment', 'tickets'); ?>"></textarea></td>
                                        </tr>
                                        <tr>
                                            <th width="20%"><?php echo _l('Label_Subject', 'tickets'); ?></th>
                                            <td><input type='text' name="subject" id="subject" placeholder="<?php echo _l('Placeholder_Subject', 'tickets'); ?>" class="form-control" /></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo _l('Label_Final_Solution', 'tickets'); ?></th>
                                            <td><textarea class="summernote" name="final_solution" id="final_solution" style="width:100%;" placeholder="<?php echo _l('Placeholder_Final_Solution', 'tickets'); ?>"></textarea></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo _l('Text_Status', 'tickets'); ?></th>
                                            <td>
                                                <select name="status" id="status" style="width:40%;">
                                                    <?php foreach ($view->status as $key => $status) { ?>
                                                        <option value="<?php echo $key; ?>"><?php echo $status; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-xs-12">
                                    <input type="hidden" name="hid_submit" id="hid_submit" />
                                    <div class="margin-top-15 padding-left-0 margin-right-10 pull-left">
                                        <input type="submit" class="btns  green  sml-btn flat-btn btnSubmit" value="<?php echo _l('Button_Add_Ticket', 'common'); ?>" name="addsubmit" id="addsubmit">
                                    </div>
                                    <div class="margin-top-15 padding-left-0 margin-right-10 pull-left">
                                        <input type="submit" class="btns  green  sml-btn flat-btn btnSubmit" value="<?php echo _l('Button_Add_Send_Ticket', 'common'); ?>" name="sendsubmit" id="sendsubmit">
                                    </div>
                                    <div class="margin-top-15 padding-left-0 margin-right-10 pull-left">
                                        <button type="button" class="btns  green  sml-btn flat-btn" onclick="window.location.href = '<?php echo $module_url . "/tickets"; ?>'"><?php echo _l('Button_Cancel', 'common'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#addsubmit").click(function() {
            $("#hid_submit").val('0');
            $("#addticket").submit();
        });

        $("#sendsubmit").click(function() {
            $("#hid_submit").val('1');
            $("#addticket").submit();
        });

        $( "#mobile_number" ).keyup(function(event) {
            if ( event.which == 13 ) {
                $( "#search_mobile" ).trigger( "click" );
            }
        });

        $("#search_mobile").click(function() {
            if ($("#mobile_number").val() === "") {
                $('#mobile_error').css("display", "block");
                $('#mobile_error').html("<?php echo _l('Empty_Mobile_No_Error_Msg', 'tickets'); ?>");
            } else {
                $('#mobile_error').css("display", "none");
                $.ajax
                ({
                    url: '<?php echo $module_url . "/tickets/get-user-details"; ?>',
                    data: "mobile_no=" + $("#mobile_number").val(),
                    type: 'post',
                    success: function(response)
                    {
                        var objResponse = jQuery.parseJSON(response);
                        $("#userdetails").html(objResponse.data);
                        if (objResponse.records == "no") {
                            $("#querydiv").hide();
                            $('#mobile_error').css("display", "block");
                            $('#mobile_error').html("<?php echo _l('No_Data', 'tickets'); ?>");
                        } else {
                            $("#querydiv").show();
                            $("#query_template_id").msDropdown();
                            $("#status").msDropdown();
                        }
                    }
                });
            }
        });
        $("#query_template_id").change(function() {
            $.ajax
            ({
                url: '<?php echo $module_url . "/tickets/get-template-details"; ?>',
                data: "template_id=" + $(this).val(),
                type: 'post',
                success: function(response)
                {
                    var objResponse = jQuery.parseJSON(response);
                    $('.note-editable').html(objResponse.content);
                    $('#subject').val(objResponse.subject);
                }
            });
        });
    });
</script>