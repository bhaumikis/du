<div class="container-fluid">
	<div class="left-menu col-xs-2">
		<ul class="list-group">
			<li class="list-group-item"><b>Api Help</b></li>
			<?php foreach($view->arrList as $i=>$data):?>
			<li class="list-group-item <?php echo (($_REQUEST["id"]==$data["id"])?"active":""); ?>">
				<a class="" href="<?php echo $module_url . "/wiki/index/id/".$data["id"]; ?>">
					<?php echo $data["name"]?> <span class="medium">(<?php echo $data["source"] ?>)</span>
				</a>
			</li>
			<?php endforeach;?>
		</ul>
	</div>
	<div class="main col-xs-10">
		<?php if(isset($view->arrDetail)):?>
			<?php $detail = $view->arrDetail;?>
			<div class="grid-4">
				<p class="pull-right">
					<button class="json-btn btn btn-info" id="normal_json_btn" name="normal_json">Normal Json</button>
					<button class="json-btn btn btn-info" id="pretty_json_btn" name="pretty_json">Pretty Json</button>
				</p>
			</div>
			<table id="api_detail_tbl" class="table table-responsive table-bordered small table-striped">
				<tr><th>Name:</th><td><span><?php echo $detail["name"]?></span></td></tr>
				<tr><th>Description:</th><td><?php echo $detail["description"]?></td></tr>
				<tr><th>Module:</th><td><?php echo $detail["module_name"]?></td></tr>
				<tr><th>URL:</th><td><a href="#"><?php echo APPLICATION_URL.'/'.$detail["source"]?></a></td></tr>
				<tr><th>Method:</th><td><?php echo $detail["method_type"]?></td></tr>
				<?php if(isset($view->arrStruct["request"])): ?>
					<tr><td colspan=2>
						<table id="api_detail_tbl" class="table table-responsive table-striped table-bordered">
						<tr><th colspan="4">Request Details:</th></tr>
						<tr>
							<th>Field</th>
							<th>Description</th>
							<th>Type</th>
							<th>Is Required? <BR/>[C=Conditional,<BR/>M=Mandatory,<BR/>O=Optional]</th>
						</tr>
						<?php foreach($view->arrStruct["request"] as $k=>$reqData):?>
							<tr>
								<td><?php echo $reqData["field_name"]?></td>
								<td><?php echo $reqData["description"]?>
									<?php if($reqData["min"]!=""): ?>
										<?php echo " <b>(Min: ".$reqData["min"].")</b>";?>
									<?php endif;?>
									<?php if($reqData["max"]!=""): ?>
										<?php echo " <b>(Max: ".$reqData["max"].")</b>";?>
									<?php endif;?>
								</td>
								<td><?php echo $reqData["type"]?></td>
								<td><?php echo $reqData["is_required"]?></td>
							</tr>	
						<?php endforeach;?>
						</table>
					</td></tr>
				<?php endif;?>
				
				<tr><td colspan=2>
					<?php if(isset($view->arrStruct["response"])): ?>
						<table id="api_detail_tbl" class="table table-responsive table-striped table-bordered">
						<tr><th colspan="3">Response Details:</th></tr>
						<tr>
							<th>Field</th>
							<th>Description</th>
							<th>Type</th>
						</tr>
						<?php foreach($view->arrStruct["response"] as $k=>$reqData):?>
							<tr>
								<td><?php echo $reqData["field_name"]?></td>
								<td><?php echo $reqData["description"]?></td>
								<td><?php echo $reqData["type"]?></td>
							</tr>	
						<?php endforeach;?>
						</table>
					</td></tr>
					<?php endif;?>
				<tr><th>Sample Request Data 1:</th><td><div id="request_data"></div></td></tr>
				<tr><th>Sample Response Data 1:</th><td><div id="response_data"></div></td></tr>
				<tr><th>Sample Request Data 2:</th><td><div id="request_data_other"></div></td></tr>
				<tr><th>Sample Response Data 2:</th><td><div id="response_data_other"></div></td></tr>
				<tr><th>Notes:</td><td><?php echo $detail["notes"]?></td></tr>
			</table>
			<script>
				var jsonSeq = ['request_data','response_data','request_data_other','response_data_other'];
				var jsonData = ['<?php echo $detail["request_data"]?>','<?php echo $detail["response_data"]?>','<?php echo $detail["request_data_other"]?>','<?php echo $detail["response_data_other"]?>'];
				function prettyJson() {
					for(i=0; i<jsonData.length; i++) {
						if(jsonData[i]!='') { 
							jQuery('#'+jsonSeq[i]).jJsonViewer(jQuery.parseJSON(jQuery.trim(jsonData[i])));
						}
					}
				}
				function normalJson() {
					for(i=0; i<jsonData.length; i++) {
						if(jsonData[i]!='') { 
							jQuery('#'+jsonSeq[i]).html(jQuery.trim(jsonData[i]));
						}
					}
				}
				jQuery(".json-btn").click(function(ele) {
					jQuery(".json-btn").removeClass('active');
					if(jQuery(this).attr('id') == 'normal_json_btn'){
						normalJson();
						jQuery(this).addClass('active');
					}else {
						prettyJson();
						jQuery(this).addClass('active');
					}
				});
				jQuery("#pretty_json_btn").click();
			</script>
		
		<?php endif;?>		
	</div>
</div>