<?php if(isset($view->error_message) and !empty($view->error_message)){ ?>
<div align="center" class="errorMessage">
  <ul>
    <?php if(is_array($view->error_message)){ 
				foreach($view->error_message as $k => $msg){?>
					<li><span><?php echo $msg;?></span></li>
	<?php		}
	 }else{ ?>
				<li><span><?php echo $view->error_message;?></span></li>
	<?php } ?>
  </ul>
</div>
<?php } ?>
<?php if(isset($error_message) and !empty($error_message)){ ?>
<div align="center" class="errorMessage">
  <ul>
    <?php if(is_array($error_message)){ 
				foreach($error_message as $k => $msg){?>
					<li><span><?php echo $msg;?></span></li>
	<?php		}
	 }else{ ?>
				<li><span><?php echo $error_message;?></span></li>
	<?php } ?>
  </ul>
</div>
<?php } ?>
<?php if(isset($action_message) and !empty($action_message)){ ?>
<div align="center" class="actionMessage">
  <ul>
    <li><span><?php echo $action_message;?></span></li>
  </ul>
</div>
<?php } ?>
