<div class="wrapper" id="div_container">
    <div class="container con-padding-tb">
        <?php include($module_path . "/application/global/message.php"); ?>
        <div id="tabmenu">
            <ul id="nav1">
                <li><a id="a_business" base_id="1" class="active cursor-pointer-default"><i class="fa fa-briefcase txt-orange"></i></a></li>
                <li><a id="a_personal" base_id="2" class="cursor-pointer-default"><i class="fa fa-user"></i></a></li>
                <li><a id="a_uncategorize" base_id="0" class="cursor-pointer-default"><i class="fa fa-question-circle"></i></a></li>
            </ul>
            <div id="divcatsubcat" class="col-xs-12 col-sm-12 col-md-12 padding-left-0 padding-right-0">
                <a id="href_cat" class="col-xs-6 col-sm-6 padding-top-10 padding-btm-10" style="border-right:1px solid #adadad;">Category<i class="fa fa-plus-circle pull-right cursor-pointer i_add_cat txt-orange"></i></a>
                <a id="href_subcat" class="col-xs-6 col-sm-6 padding-top-10 padding-btm-10">Subcategory<i class="fa fa-plus-circle pull-right cursor-pointer i_add_sub_cat"></i></a>
            </div>
            <div id="tab-content">
                <?php
                if (is_array($view->arrCategoryData)) {
                    foreach ($view->arrCategoryData as $intBaseTypeId => $arrCategoryData) {
                        ?>
                        <div id="tab<?php echo $intBaseTypeId; ?>">
                            <span id="span_<?php echo $intBaseTypeId; ?>" class="col-sm-6 padding-right-0 padding-left-0">
                                <span id="ispan_add_cat<?php echo $intBaseTypeId; ?>" class="checkbox-1 cursor-pointer-default" style="display: none">
                                    <input type="text" class="input-style col-sm-12" name="cat_title<?php echo $intBaseTypeId; ?>" id="cat_title<?php echo $intBaseTypeId; ?>" />
                                    <span class="pro-btns col-sm-6 padding-right-0 padding-left-0 brd-right i_add_category">
                                        <i class="fa fa-check cursor-pointer"></i>
                                    </span>
                                    <span class="pro-btns col-sm-6 padding-right-0 padding-left-0 i_cancle_category">
                                        <i class="fa fa-times cursor-pointer "></i>
                                    </span>
                                </span>
                                <?php
                                if (is_array($arrCategoryData['cat_name'])) {
                                    foreach ($arrCategoryData['cat_name'] as $intCatId => $strCategoryName) {
                                        ?>
                                        <span cat_id="<?php echo $intCatId; ?>" id="cat_name_<?php echo $intCatId; ?>" class="checkbox-1 cursor-pointer-default border-right-mycat">
                                            <label id="lbl_cat_<?php echo $intCatId; ?>" lbl-cat-id="<?php echo $intCatId; ?>" style="margin: 13px 0;" class="">
                                                <?php echo $strCategoryName; ?>
                                            </label>
                                            <?php if (!in_array($intCatId, $view->arrDefaultCategories)) { ?>
                                                <span class="cat-acton-icons">
                                                    <span id="spn_edit_<?php echo $intCatId; ?>" cid="<?php echo $intCatId; ?>">
                                                        <i id="inline_edit_cat<?php echo $intCatId; ?>" cid="<?php echo $intCatId; ?>" class="fa fa-pencil i_edit_cat"></i>
                                                    </span>&nbsp;&nbsp;&nbsp;<span  cid="<?php echo $intCatId; ?>" is_parent="yes" id="spn_delete_<?php echo $intCatId; ?>"><i class="fa fa-times"></i></span>
                                                </span>
                                            <?php } ?>
                                        </span>
                                        <?php
                                    }
                                }
                                ?>
                            </span>
                            <span id="ispan_add_subcat" class="checkbox-1 cursor-pointer-default ispan_add_subcat col-sm-6 padding-right-0 padding-left-0" style="display: none">
                                <input type="text" class="input-style col-sm-12" name="subcat_title" id="subcat_title" />
                                <span class="pro-btns col-sm-6 padding-right-0 padding-left-0 brd-right i_add_subcategory">
                                    <i class="fa fa-check cursor-pointer"></i>
                                </span>
                                <span class="pro-btns col-sm-6 padding-right-0 padding-left-0 i_cancle_subcategory">
                                    <i class="fa fa-times cursor-pointer"></i>
                                </span>
                            </span>                            
                            <?php
                            if (is_array($arrCategoryData['data'])) {
                                foreach ($arrCategoryData['data'] as $intChildCatId => $arrChildData) {
                                    ?>
                                    <span id="span_<?php echo $intChildCatId; ?>" class="col-sm-6 span_subcate padding-right-0 padding-left-0" style="display: none">
                                        <?php foreach ($arrChildData['data'] as $arrCatChildData) { ?>
                                            <span id="subcat_name_<?php echo $arrCatChildData['expense_category_id']; ?>" class="checkbox-1 cursor-pointer-default">
                                                <label id="lbl_subcat_<?php echo $arrCatChildData['expense_category_id']; ?>" style="margin: 13px 0;" class="col-xs-9 padding-left-0 padding-right-0">
                                                    <?php echo $arrCatChildData['title']; ?>
                                                </label>
                                                <span class="cat-acton-icons">
                                                    <span cid="<?php echo $arrCatChildData['expense_category_id']; ?>" id="spn_edit_<?php echo $arrCatChildData['expense_category_id']; ?>">
                                                        <i id="inline_edit_subcat<?php echo $arrCatChildData['expense_category_id']; ?>" scid="<?php echo $arrCatChildData['expense_category_id']; ?>" class="fa fa-pencil i_subcat_edit"></i>
                                                    </span>&nbsp;&nbsp;&nbsp;<span cid="<?php echo $arrCatChildData['expense_category_id']; ?>" is_parent="no" id="spn_delete_<?php echo $arrCatChildData['expense_category_id']; ?>"><i class="fa fa-times"></i></span>
                                                </span>
                                            </span>
                                        <?php } ?>
                                    </span>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <!-- Container -->
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#div_common_error_msg').addClass('div-category-alert');
        $('#div_common_action_msg').addClass('div-category-alert');
        callLoderJsAfterAJAX();
        //	getSpanActionReload();
		$('#tabmenu #nav1 li a').click(function () {
        	if($(this).attr("base_id")==0) {
                $('#divcatsubcat').hide();	
        	}else {
        		$('#divcatsubcat').show();
        	}    
        });
        $(".i_add_category").click(function() {
            var baseId = $('#nav1 li a.active').attr('base_id');
            var catName = $("#cat_title" + baseId).val();

            if (catName === '' || catName === null) {
                alert("Please enter category name.");
                return false;
            }
            else {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $module_url . "/my-categories/add-quick-category"; ?>",
                    data: {
                        title: catName,
                        base_id: baseId
                    },
                    success: function(response) {
                        var objResponse = jQuery.parseJSON(response);
                        if (objResponse.message === 'failure')
                        {

                        } else {
                            var strHTML = "<span class='checkbox-1 cursor-pointer-default' id='cat_name_" + objResponse.expense_category_id + "' cat_id='" + objResponse.expense_category_id + "'><label id='lbl_cat_" + objResponse.expense_category_id + "' style='margin: 13px 0;'>" + objResponse.title + "</label><span class='cat-acton-icons'><span cid='" + objResponse.expense_category_id + "' id='spn_edit_" + objResponse.expense_category_id + "'><i cid='" + objResponse.expense_category_id + "' id='inline_edit_cat" + objResponse.expense_category_id + "'  class='fa fa-pencil i_edit_cat'></i></span>&nbsp;&nbsp;&nbsp;<span id='spn_delete_" + objResponse.expense_category_id + "' cid='" + objResponse.expense_category_id + "'><i class='fa fa-times'></i></span>";
                            $('#span_' + baseId).append(strHTML);
                            $("#cat_title").val('');
                            $("#ispan_add_cat" + baseId).hide();
                            callLoderJsAfterAJAX();
                            //getSpanActionReload();
                            editableCat(objResponse.expense_category_id,"");
                            deleteCatRegisterEvent(objResponse.expense_category_id,'yes');
                        }
                    }
                });
                return false;
            }

        });

        $(".i_add_subcategory").click(function() {
            var baseId = $('#nav1 li a.active').attr('base_id');
            var parentId = $('.cat-active').attr('cat_id');
            var catName = this.parentElement.children.subcat_title.value; //$('.ispan_add_subcat input').val();

            if (parentId === '' || parentId === undefined) {
                alert("Please select a category first.");
                return false;
            } else if (catName === '' || catName === null) {
                alert("Please enter sub category name.");
                return false;
            }
            else {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $module_url . "/my-categories/add-quick-category"; ?>",
                    data: {
                        title: catName,
                        base_id: baseId,
                        parent_id: parentId
                    },
                    success: function(response) {
                        var objResponse = jQuery.parseJSON(response);
                        if (objResponse.message === 'failure')
                        {

                        } else {
                            var blnID = $("selector").is('#span_' + parentId);
                            if (blnID === false) {
                                var strDivHTML = '<span style="" class="col-sm-6 span_subcate" id="span_' + parentId + '"></span>';
                                $('#tab' + baseId).append(strDivHTML);
                            }
                            var strHTML = '<span id="subcat_name_'+objResponse.expense_category_id+'" class="checkbox-1 cursor-pointer-default"><label id="lbl_subcat_' + objResponse.expense_category_id + '" class="col-xs-9 padding-left-0 padding-right-0 editable" style="margin: 13px 0;">' + catName + '</label><span class="cat-acton-icons"><span id="spn_edit_' + objResponse.expense_category_id + '" cid="' + objResponse.expense_category_id + '"><i id="inline_edit_subcat' + objResponse.expense_category_id + '" scid="' + objResponse.expense_category_id + '" class="fa fa-pencil i_subcat_edit"></i></span>&nbsp;&nbsp;&nbsp;<span id="spn_delete_' + objResponse.expense_category_id + '" cid="' + objResponse.expense_category_id + '"><i class="fa fa-times"></i></span></span></span>';
                            $('#span_' + parentId).append(strHTML);
                            $(".ispan_add_subcat input").val('');
                            $(".ispan_add_subcat").hide();
                            //getSpanActionReload();
                            editableCat(objResponse.expense_category_id, 'sub');
                            deleteCatRegisterEvent(objResponse.expense_category_id,'no');
                        }
                    }
                });
                return false;
            }

        });
    });

    function callLoderJsAfterAJAX() {
        $('[id^="cat_name_"]').each(function() {
            $("#" + this.id).on("click", function() {
                $('[id^="cat_name_"]').each(function() {
                    $("#" + this.id).removeClass('cat-active');
                    $("#" + this.id + " span").css('color', "#b4b4b4");
                });
                $("#" + this.id).addClass('cat-active');
                $("#" + this.id + " span").css('color', "#fff");
                var categoryId = $("#" + this.id).attr('cat_id');
                $('[id^="span_"]').each(function() {
                    $(".span_subcate").hide();
                });
                $("#span_" + categoryId).show();
                $(".i_add_sub_cat").addClass("txt-orange");
            });
        });
        
        $(".i_cancle_category").click(function() {
            $("#cat_title").val('');
            var baseId = $('#nav1 li a.active').attr('base_id');
            $("#ispan_add_cat" + baseId).hide();
        });

        $(".i_cancle_subcategory").click(function() {
            $(".ispan_add_subcat input").val('');
            $(".ispan_add_subcat").hide();
        });

        $(".i_add_cat").click(function() {
            var baseId = $('#nav1 li a.active').attr('base_id');
            $("#ispan_add_cat" + baseId).show();
        });

        $(".i_add_sub_cat").click(function() {
            $(".ispan_add_subcat").show();
        });

        $('#nav1 li a').click(function() {
            var EleId = this.id;
            $('[id^="a_"]').each(function() {
                $("#" + this.id).removeClass("active");
                $("#" + this.id + " i").removeClass("txt-orange");
            });
            $("#" + EleId).addClass("active");
            $("#" + this.id + " i").addClass("txt-orange");
            $(".i_add_sub_cat").removeClass("txt-orange");
        });

        $('#divcatsubcat a').click(function() {
            var hrefId = this.id;
            $('[id^="href_"]').each(function() {
                //$("#" + this.id + " i").removeClass("txt-orange");
            });
            $("#" + hrefId + " i").addClass("txt-orange");
        });
    }
    
	// Parent Category CRUD by AJAX
	function deleteCatRegisterEvent(catId, isParent) {
		$("#spn_delete_" + catId + " i").click(function() {
            if (confirm("<?php echo _l("Are you sure to delete?", "my-categories"); ?>")) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $module_url . "/my-categories/delete-category"; ?>",
                    data: {
                        expense_category_id: catId
                    },
                    success: function(response) {
                        if (response === 'SUCCESS') {            
                            if(isParent == 'yes'){
                                $("#cat_name_"+catId).remove("");
                            }else{
                                $("#subcat_name_"+catId).remove("");
                            }                  
                        } else if (response === 'EXISTS') {
                            alert('<?php echo _l("category assigned to expense.", "my-categories"); ?>');
                        }
                    }
                });
            }
        });
	}
	$('[id^="spn_delete_"]').each(function() {
		 var catId = $("#" + this.id).attr("cid");
         var isParent = $("#" + this.id).attr("is_parent");
         deleteCatRegisterEvent(catId,isParent);
    });

	$.fn.editable.defaults.mode = "inline";
	String.prototype.capitalize = function() {
	    return this.charAt(0).toUpperCase() + this.slice(1);
	}
	function editableCat(catId, level) {
		var lblIdStr = "lbl_"+level+"cat_"+catId;
		  
		$("#"+lblIdStr).editable({
 	    	toggle:'manual',
 	    	inputclass: 'inline-catbox',
 	    	type: 'text',
 	        url: "<?php echo $module_url . "/my-categories/save-inline-category"; ?>",    
 	        pk: catId,    
 	        title: 'Enter Category Name',
 	       	params: function(params) {
               var data = {};
               data['expense_category_id'] = params.pk;
               data['field'] = params.name;
               data['title'] = params.value;
               return data;
           	},
 	       	ajaxOptions: { dataType: 'json'},
 	       	success: function(response, newValue) {
 	        	if(!response) {
 	                return "Unknown error!";
 	            }          
 	            if(response.success == 1) {
 	                return true;
 	            }else {
 	            	return false;
 	            }
 	        }        
 	 	});
 	 	
		$("#inline_edit_"+level+"cat"+catId).click(function(e) {
		  var catIdStr = level+"cat_name_"+catId;
	      $("#"+lblIdStr).editable('toggle');

	      $("#"+lblIdStr).on('hidden', function(e, reason) {
	    	  $("#"+catIdStr+" .cat-acton-icons").show();
      	   });
	      
	      $("#"+catIdStr+" .editable-submit i").removeClass("glyphicon glyphicon-ok");
	      $("#"+catIdStr+" .editable-submit i").addClass("fa fa-check i_save_cat");
	      
	      $("#"+catIdStr+" .editable-cancel i").removeClass("glyphicon glyphicon-remove");
	      $("#"+catIdStr+" .editable-cancel i").addClass("fa fa-times i_cancle_edit_cat");
	      $(this).parent().parent('.cat-acton-icons').hide();
 	      e.stopPropagation();
        });
	}
	$('[id^="inline_edit_cat"]').each(function () {
        var catId = this.getAttribute("cid");
        editableCat(catId, '');
    });
    
	$('[id^="inline_edit_subcat"]').each(function () {
        var catId = this.getAttribute("scid");
        editableCat(catId, 'sub');
    });
</script>