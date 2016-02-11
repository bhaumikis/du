<?php

namespace model;

/**
 *
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 *         apiHelpDetailsModel Class
 */
class apiHelpDetailsModel extends globalModel {
	
	/**
	 * get all Active Strucuter data
	 *
	 * @return type
	 */
	function getList($id) {
		$rsResult = $this->getDBTable ( "api-help-details" )->fetchAllByFields ( array (
				"*" 
		), array (
				"where" => "status = 1 AND api_help_id = :api_help_id",
				"params" => array (
						":api_help_id" => $id 
				) 
		) );
		$arrFinal = array ();
		foreach ( $rsResult as $arrData ) {
			if ($arrData ["detail_type"] == "0") {
				$arrFinal ["request"] [] = $arrData;
			} else if ($arrData ["detail_type"] == "1") {
				$arrFinal ["response"] [] = $arrData;
			}
		}
		return $arrFinal;
	}
	
	/**
	 * Dump Structure from the sample data 
	 */
	public function dumpStructFromSampleData() {
		$rsResult = $this->getDBTable ( "api-help" )->fetchAllByFields ( array (
				"*" 
		), array (
				"where" => "is_import_details = 0" 
		) );
		foreach ( $rsResult as $k => $row ) {
			$req = json_decode ( $row ["request_data"], true );
			$resp = json_decode ( $row ["response_data"], true );
			$resp2 = json_decode ( $row ["response_data"], true );
			$this->processDumpRow($req, $row, 0);
			$this->processDumpRow($resp, $row, 1);
			$this->processDumpRow($resp2, $row, 1);
		}
	}
	
	/**
	 * prepare array to insert data into the api help details
	 * @param unknown $data
	 * @param unknown $row
	 * @param number $detailType
	 */
	public function processDumpRow($data, $row, $detailType=0)
	{
		if (is_array ( $data )) {
			foreach ( $data as $k1 => $r1 ) {
				$this->addDumpRow ( $row, $k1, $r1, $detailType );
				if (is_array ( $r1 )) {
					foreach ( $r1 as $k2 => $r2 ) {
						if(is_int($k2) && $k2>0) continue;
						$this->addDumpRow ( $row, $k1 . ' > ' . $k2, $r2, $detailType );
						if (is_array ( $r2 )) {
							foreach ( $r2 as $k3 => $r3 ) {
								if(is_int($k3) && $k3>0) continue;
								$this->addDumpRow ( $row, $k1 . ' > ' . $k2 . ' > ' . $k3, $r3, $detailType );
								if (is_array ( $r3 )) {
									foreach ( $r3 as $k4 => $r4 ) {
										if(is_int($k4) && $k4>0) continue;
										$this->addDumpRow ( $row, $k1 . ' > ' . $k2 . ' > ' . $k3 . ' > '. $k4, $r4, $detailType );
										if (is_array ( $r4 )) {
											foreach ( $r4 as $k5 => $r5 ) {
												if(is_int($k5) && $k5>0) continue;
												$this->addDumpRow ( $row, $k1 . ' > ' . $k2 . ' > ' . $k3 . ' > '. $k4 .' > '. $k5, $r5, $detailType );
												if (is_array ( $r5 )) {
													foreach ( $r5 as $k6 => $r6 ) {
														if(is_int($k6) && $k6>0) continue;
														$this->addDumpRow ( $row, $k1 . ' > ' . $k2 . ' > ' . $k3 . ' > '. $k4 .' > '. $k5.' > '. $k6, $r6, $detailType );
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	
	/**
	 * Insert / Update structure into the api-help-details.
	 * @param unknown $row
	 * @param unknown $key
	 * @param unknown $value
	 * @param number $detail_type
	 * @return Ambigous|mixed
	 */
	public function addDumpRow($row, $key, $value, $detail_type = 0) {
		$data = array ();
		$data ["api_help_id"] = $row ["id"];
		$data ["field_name"] = $key;
		$data ["description"] = ucwords ( str_ireplace ( "_", " ", substr($key, strripos($key, " ")) ) );
		$data ["type"] = gettype ( $value );
		$data ["is_required"] = "M";
		$data ["detail_type"] = $detail_type;
		$data ["status"] = 1;
		$data ["is_import"] = 1;
		$id = NULL;
		$result = $this->getDBTable ( "api-help-details" )->fetchRow ( "api_help_id = '" . $data ["api_help_id"] . "' 
				AND field_name = '" . $data ["field_name"] . "' AND detail_type = '" . $data ["detail_type"] . "'" );
		
		if (isset ( $result ["id"] )) {
			$id = $result ["id"];
			$detailId = $this->getDBTable ( "api-help-details" )->update ( $data, "id = $id" );
		} else {
			$detailId = $this->getDBTable ( "api-help-details" )->insert ( $data );
		}
		
		if ($detailId) {
			//$helpId = $this->getDBTable ( "api-help" )->update ( array ("is_import_details" => 1), " id = '" . $row ["id"] . "'" );
		}
		return $detailId;
	}
}
