<?php
if(isset($view->emailtemplatedetails) and !empty($view->emailtemplatedetails)){
		extract($view->emailtemplatedetails);
}
if($_POST){ extract($_POST); }
?>
<script type="text/javascript">
	
$(document).ready( function(){
	$("#emailtemplatesForm").validate({
		errorElement:"div",
		rules: {
		   title:"required",
		   to_email:{email:true},
		   from_email:{email:true}
		},
		messages: {
			title:"Please enter title.",
		   	to_email:{email:"Please enter valid email."},
			from_email:{email:"Please enter valid email."}
		}
	});
});	
</script>
<script type="text/javascript">
$(document).ready( function(){
	$('#date').datepick({
		showOn: 'both',
		closeAtTop: false,
		mandatory: false,
		yearRange:'-100y:+100y',
		maxDate: new Date(),
		buttonImageOnly: true,
		buttonImage:'<?php echo IMAGE_URL."/calendar-blue.gif";?>'
	});
});	
</script>
<?php
        include(APPLICATION_PATH."/lib/ckeditor/ckeditor.php");
	$CKEditor = new CKEditor();
	$CKEditor->returnOutput = true;
	$CKEditor->basePath = $module_path."/lib/ckeditor/";
	$CKEditor->config['width'] = 450;
	$CKEditor->config['height'] = 150;
	$CKEditor->textareaAttributes = array("cols" => 55, "rows" => 10);
	$config['toolbar'] = array(
		array( 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike' ),
		array( 'Image', 'Link', 'Unlink', 'Anchor' ),
		array('NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv','Table'),
		array('Styles','Format','Font','FontSize'),
		array('TextColor','BGColor')
	);
	$config['skin'] = 'v2';
?>
<script type="text/javascript" language="javascript" src="<?php echo $module_url;?>/js/jquery.datepick.pack.js"></script>
<link href="<?php echo $module_url;?>/css/jquery.datepick.css" rel="stylesheet" type="text/css" />
<div align="center">
  <h3><?php echo ($view->email_template_id == 0) ? "Add" : "Edit";?> Email Templates</h3>
</div>
<form id="emailtemplatesForm" name="emailtemplatesForm" action="<?php echo $module_url."/emailtemplates/addedit";?>" method="post" enctype="multipart/form-data">
  <input type="hidden" name="email_template_id" id="email_template_id" value="<?php echo $view->email_template_id;?>" />
  <div class="frm" style="width:700px;margin-left:20%;">
    <?php if($view->email_template_id){ ?>
    <div>
      <label for="email_template_id">Email Templates ID :</label>
      <?php echo $view->email_template_id;?> </div>
    <?php } ?>
    
    <div>
      <label for="title">Title <span class="required">*</span>:</label>
      <input type="text" class="txtbox" name="title" id="title" value="<?php echo isset($title) ? $title : "";?>"/>
    </div>
	<div>
      <label for="name">Name :</label>
      <input type="text" class="txtbox" name="name" id="name" value="<?php echo isset($name) ? $name : "";?>" disabled="disabled"/>
    </div>
	<div>
      <label for="format">Format :</label>
      <select name="format" id="format">
        <option value="text" <?php echo (isset($format) and ($format == "text")) ? "selected=\"selected\"" : "";?>>Text</option>
        <option value="html" <?php echo (isset($format) and ($format == "html")) ? "selected=\"selected\"" : "";?>>HTML</option>
      </select>
    </div>
      <div>
      <label for="htmltext">Variables :</label>
      <?php echo isset($variables) ? $variables : "";?>
     
    </div>
    <div>
      <label for="htmltext">HTML Content :</label>
      <?php echo $CKEditor->editor("htmltext", $htmltext, $config);?>
     
    </div>
	<div>
      <label for="text">Text Content :</label>
      <textarea name="text" id="text" style="width:440px;height:300px;"><?php echo isset($text) ? $text : "";?></textarea>
    </div>
   <div>
      <label for="to_email">To Email :</label>
      <input type="text" class="txtbox" name="to_email" id="to_email" value="<?php echo isset($to_email) ? $to_email : "";?>"/>
    </div>
	<div>
      <label for="to_name">To Name :</label>
      <input type="text" class="txtbox" name="to_name" id="to_name" value="<?php echo isset($to_name) ? $to_name : "";?>"/>
    </div>
	<div>
      <label for="from_email">From Email :</label>
      <input type="text" class="txtbox" name="from_email" id="from_email" value="<?php echo isset($from_email) ? $from_email : "";?>"/>
    </div>
	<div>
      <label for="from_name">From Name :</label>
      <input type="text" class="txtbox" name="from_name" id="from_name" value="<?php echo isset($from_name) ? $from_name : "";?>"/>
    </div>
	<div>
      <label for="subject">Email Subject :</label>
      <input type="text" class="txtbox" name="subject" id="subject" value="<?php echo isset($subject) ? $subject : "";?>"/>
    </div>
    <div>
      <label>&nbsp;</label>
      <input name="submit" id="submit" value="Submit" type="submit" class="button">
      <input name="cancel" id="cancel" type="button" value="Cancel" onclick="window.location.href = '<?php echo $module_url."/emailtemplates";?>'" class="button"/>
    </div>
  </div>
</form>
<div class="pad4"></div>