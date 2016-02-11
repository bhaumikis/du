<div align="center">
  <h3>Skill  (<?php echo $view->user["firstname"]." ".$view->user["lastname"];?>)</h3>
</div>
<div>&nbsp;</div>
<table align="center" width="90%" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="100%" class="gridtd"><table  align="center" cellpadding="2" cellspacing="2" border="0">
        <tr>
          <td>Firstname : </td>
          <td><?php echo $view->user["firstname"];?></td>
        </tr>
        <tr>
          <td>Lastname : </td>
          <td><?php echo $view->user["lastname"];?></td>
        </tr>
        <tr>
          <td>Email : </td>
          <td><?php echo $view->user["email"];?></td>
        </tr>
        <tr>
          <td>Employee code : </td>
          <td>ISABD<?php echo sprintf("%03d",$view->user["employee_code"]);?></td>
        </tr>
		<tr>
          <td>Total Experience : </td>
          <td><?php echo $view->user["exp_years"]." Years, ".$view->user["exp_months"]." Months";?></td>
        </tr>
		<tr>
          <td>Education : </td>
          <td><?php echo $view->education;?></td>
        </tr>
        <tr>
          <td>Status : </td>
          <td><?php echo ($view->user["status"] == 1) ? "Active" : "Inactive";?></td>
        </tr>
        <tr>
          <td>Created date : </td>
          <td><?php echo date(DATETIME_FORMAT,strtotime($view->user["created_date"]));?></td>
        </tr>
        <tr>
          <td>Last updated date : </td>
          <td><?php echo date(DATETIME_FORMAT,strtotime($view->user["updated_date"]));?></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td colspan="100%"><hr /></td>
  </tr>
  <tr>
    <th>Category</th>
    <th>Technology</th>
    <th>Experience</th>
  </tr>
  <?php for($i=0;$i<count($view->skills);$i++){ 
  	if(($i+1)%2 == 0){
		$class = "class=\"evnrw\"";
	}else{
		$class = "class=\"oddrw\"";
	}
  ?>
  <tr <?php echo $class;?>>
    <td class="gridtd"><?php echo $view->skills[$i]["catgory"];?></td>
    <td class="gridtd"><?php echo $view->skills[$i]["technology"];?>
      <?php if($view->skills[$i]["technology"] == "Other") { echo " (".$view->skills[$i]["other"].")";}?></td>
    <td class="gridtd"><?php echo $view->skills[$i]["experience"];?></td>
  </tr>
  <?php } ?>
  <tr>
    <td align="center" colspan="100%"><input name="cancel" id="cancel" type="button" value="Back" onclick="window.location.href = '<?php echo $module_url."/users";?>'" class="button"/></td>
  </tr>
</table>
<div class="pad4"></div>
