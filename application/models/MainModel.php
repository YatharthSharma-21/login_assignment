<?php

class MainModel extends ci_model
{

	public function selectColumnFromTable($tableName = null, $columnName = null)
	{
		$this->db->select($columnName); // ('a,b,c')
		$result = $this->db->get($tableName)->result_array();
		return $this->db->affected_rows() ? $result : FALSE;
	}

	public function selectColumnFromTableWhere($tableName = null, $columnName = null, $condition)
	{
		$this->db->select($columnName); // ('a,b,c')
		$result = $this->db->get_where($tableName, $condition)->result_array();
		return $this->db->affected_rows() ? $result : FALSE;
	}



	public function selectAllFromTable($tableName = null)
	{
		$imageUrl = base_url() . "assets/noticeImage/";
		if ($tableName == "noticeboard") {
			$query = $this->db->query("SELECT *,CONCAT('$imageUrl',noticeImage) as noticeImage FROM $tableName");
		} else {
			$query = $this->db->get($tableName);
		}
		$result = $query->result_array();
		return $this->db->affected_rows() ? $result : FALSE;
	}

	public function deleteAllTableData()
	{
		$query = $this->db->query("SHOW TABLES");
		$name = $this->db->database;
		foreach ($query->result_array() as $row) {
			if ($row['Tables_in_' . $name] == "erp_admin" || $row['Tables_in_' . $name] == "city" || $row['Tables_in_' . $name] == "country_t" || $row['Tables_in_' . $name] == "gender" || $row['Tables_in_' . $name] == "tbl_cast" || $row['Tables_in_' . $name] == "tbl_religion" || $row['Tables_in_' . $name] == "tbl_occupation" || $row['Tables_in_' . $name] == "leave") {
			} else {
				$table = $row['Tables_in_' . $name];
				$this->db->query("TRUNCATE " . $table);
				$this->db->query("ALTER TABLE " . $table . " AUTO_INCREMENT = 1");
			}
		}
		return TRUE;
	}

	public function selectAllFromWhere($tableName = null, $condition = null, $getColumn = null)
	{
		$query = $this->db->get_where($tableName, $condition)->result_array();

		if ($getColumn == null) {
			return $this->db->affected_rows() ? $query : FALSE;
		} else {
			return $this->db->affected_rows() ? $query[0][$getColumn] : FALSE;
		}
	}

	public function insertInto($tableName = null, $data = null)
	{
		$this->db->insert($tableName, $data); 
		return $this->db->affected_rows() ? TRUE : FALSE;
	}	

	public function updateWhere($tableName = null, $data = null, $condition = null)
	{
		$this->db->trans_start();
		$this->db->where($condition);
		$this->db->update($tableName, $data);
		$this->db->trans_complete();

		return $this->db->trans_status();
		//	$this->db->where($condition);
		//	$this->db->update($tableName, $data); 
		// return $this->db->affected_rows() ? FALSE : TRUE;
	}

	public function updateWhere2($tableName = null, $data = null, $condition = null)
	{	$this->db->trans_start();
		$this->db->where($condition);
		$this->db->update($tableName, $data);
		$this->db->trans_complete();

		return $this->db->trans_status();
			$this->db->where($condition);
			$this->db->update($tableName, $data); 
		return $this->db->affected_rows() ? FALSE : TRUE;
	}

	public function selectAllFromTableWhere($tableName = null, $condition = null, $getColumn = null)
	{
		$result = $this->db->get_where($tableName, $condition)->result_array();
		return $this->db->affected_rows() ? $result : FALSE;
	}

	public function selectAllFromTableOrderBy($tableName = null, $columnName = null, $orderBy = null, $condition = null)
	{
		$this->db->order_by($columnName, $orderBy);
		if ($condition != '') {
			$query = $this->db->get_where($tableName, $condition)->result_array();
			return $this->db->affected_rows() ? $query : FALSE;
		} else {
			$query = $this->db->get($tableName)->result_array();
			return $this->db->affected_rows() ? $query : FALSE;
		}
	}

	public function selectAllFromTableLike($tableName = null, $likeCondition = null, $condition = [])
	{
		$count = count($condition);
		if ($count == 1) {
			foreach ($condition as $key => $content) {
				$this->db->like($key, $content, $likeCondition);
			}
			$q = $this->db->get($tableName)->result_array();
		} else {
			$this->db->like($condition);
			$q = $this->db->get($tableName)->result_array();
		}

		if ($this->db->affected_rows()) {
			return $q;
		} else {
			return false;
		}
	}

	public function selectAllFromLikeOR($tableName = null, $likeCondition = null, $condition = [])
	{
		$sql = "SELECT * FROM " . $tableName;
		$i = 0;
		foreach ($condition as $key => $data) {
			if ($likeCondition == 'before') {
				if ($i == 0) {
					$sql .= " WHERE `$key` LIKE '%$data'";
				} else {
					$sql .= " OR `$key` LIKE '%$data'";
				}
				$i++;
			} elseif ($likeCondition == 'after') {
				if ($i == 0) {
					$sql .= " WHERE `$key` LIKE '$data%'";
				} else {
					$sql .= " OR `$key` LIKE '$data%'";
				}
				$i++;
			} elseif ($likeCondition == 'both') {
				if ($i == 0) {
					$sql .= " WHERE `$key` LIKE '%$data%'";
				} else {
					$sql .= " OR `$key` LIKE '%$data%'";
				}
				$i++;
			}
		}
		$q = $this->db->query($sql)->result_array();

		if ($this->db->affected_rows()) {
			return $q;
		} else {
			return false;
		}
	}

	public function deleteFromTable($tableName = null, $condition = null)
	{
		$this->db->delete($tableName, $condition);
		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	}

	public function selectAllFromTableGroupBy($tableName = null, $condition = [], $groupBy = null)
	{
		$this->db->group_by($groupBy);
		$this->db->where($condition);
		$q = $this->db->get($tableName)->result_array();

		if ($this->db->affected_rows()) {
			return $q;
		} else {
			return false;
		}
	}

	public function selectAllFromTableFullJoin($firstTable, $secondTable, $conditionColumn, $conditionValue, $firstTableColumn, $secondTableColumn)
	{
		$this->db->select('*');
		$this->db->from($firstTable);
		$this->db->join($secondTable, $firstTable . "." . $firstTableColumn . "=" . $secondTable . "." . $secondTableColumn);

		$this->db->where($firstTable . "." . $conditionColumn, $conditionValue);

		$result = $this->db->get()->result_array();
		if ($this->db->affected_rows()) {
			return $result;
		} else {
			return false;
		}
	}

	public function deleteFromTableWhere($tableName = null, $condition = null)
	{
		$this->db->delete($tableName, $condition);
		return $this->db->affected_rows() ? true : false;
	}

	public function truncateTable($tableName = null)
	{
		$this->db->query("TRUNCATE " . $tableName);
		$this->db->query("ALTER TABLE " . $tableName . " AUTO_INCREMENT = 1");
		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	}

	public function getNewIDorNo($prefix, $tableName, $pad_length = 3)
	{
		$id = 0;
		$row = $this->db->query("SELECT max(id) as maxid  FROM " . $tableName)->row();

		if ($row) {
			$id = $row->maxid;
		}
		$id++;

		$Id = strtoupper($prefix . date('d') . str_pad($id, $pad_length, '0', STR_PAD_LEFT));
		return $Id; // $maxid==NULL?1:$maxid+1;
	}

	public function getNewUserIDorNo($prefix, $tableName, $pad_length = 3)
	{
		$id = 0;
		$row = $this->db->query("SELECT max(id) as maxid  FROM " . $tableName)->row();

		if ($row) {
			$id = $row->maxid;
		}
		$id++;
		if(strlen($id)<3){
			$id = '0'.$id;
		}
		$Id = strtoupper($prefix  . str_pad($id, $pad_length, '0', STR_PAD_LEFT));

		return $Id; // $maxid==NULL?1:$maxid+1;
	}

	public function getColorAndDetils($table, $division)
	{
		$query = "SELECT * FROM `colors` ";
		$query .= "LEFT JOIN $table ON ";
		$query .= "colors.name=$table.shortName ";
		$query .= "where colors.divisions='$division'";
		$query .= "order by $table.serial ASC";
		$q = $this->db->query($query)->result_array();
		return $this->db->affected_rows() ? $q : false;
	}

	public function gethouseMapsDetails($userid)
	{
		$query = "SELECT * FROM `propertydetails` ";
		$query .= "LEFT JOIN housemaps ON ";
		$query .= "housemaps.propertId=propertydetails.propertyId ";
		$query .= "LEFT JOIN clientdetails ON ";
		$query .= "propertydetails.clientId=clientdetails.cId ";
		$query .= "where propertydetails.userId = '$userid' ";
		$query .= "order by housemaps.id DESC";
		$q = $this->db->query($query)->result_array();
		return $this->db->affected_rows() ? $q : false;
	}

	public function getAllhouseMapsDetails()
	{
		$query = "SELECT housemaps.mapId, housemaps.propertId, propertydetails.userId, housemaps.imageData FROM `housemaps` ";
		$query .= "LEFT JOIN propertydetails ON ";
		$query .= "housemaps.propertId=propertydetails.propertyId ";			
		$query .= "WHERE housemaps.imageData != ''";	
		$query .= "order by housemaps.id ASC LIMIT 100 OFFSET 1600";
		$q = $this->db->query($query)->result_array();
		return $this->db->affected_rows() ? $q : false;
	}

	public function getClientDetails($value,$userId)
	{
		$condition = $value . '%';
		$query = "select * from clientdetails where clientName like '$condition' and userId = '$userId'";
		$q = $this->db->query($query)->result_array();
		return $this->db->affected_rows() ? $q : false;
	}

	public function updateFields($objects = null, $reportData = null, $id = null)
	{
		$query = "UPDATE housemaps
					SET objects = '$objects', reportData= '$reportData'
					WHERE mapId = '$id'";
		$q = $this->db->query($query)->result_array();
		return $this->db->affected_rows() ? $q : false;
	}

	public function selectSixteenZoneData(){
		$query = "SELECT * FROM `sixteenzones` ";
		$query .= "LEFT JOIN colors ON ";
		$query .= "colors.name=sixteenzones.shortName ";		
		$query .= "where colors.divisions = 'SIXTEEN' ";
		$query .= "order by colors.serial ASC";
		$q = $this->db->query($query)->result_array();
		return $this->db->affected_rows() ? $q : false;
	}

	public function getPropertyHousemapDetails($id = ''){
		$query = "SELECT * FROM `propertydetails` ";
		$query .= "LEFT JOIN housemaps ON ";
		$query .= "housemaps.propertId=propertydetails.propertyId ";
		$query .= "LEFT JOIN login ON ";
		$query .= "propertydetails.userId=login.userId ";
		$query .= "LEFT JOIN clientdetails ON ";
		$query .= "propertydetails.clientId=clientdetails.cId ";
		$query .= "where housemaps.mapId = '$id' ";
		$query .= "order by housemaps.id DESC";
		$q = $this->db->query($query)->result_array();
		return $this->db->affected_rows() ? $q : false;
	}

	public function getClientPropertyHousemapDetails($id = '', $userid = ''){
		$query = "SELECT * FROM `propertydetails` ";		
		$query .= "where propertydetails.clientId = '$id' AND propertydetails.userId = '$userid' ";
		
		$q = $this->db->query($query)->result_array();
		return $this->db->affected_rows() ? $q : false;
	}

	public function getUserDetails($userId)
	{
		$query = "SELECT * FROM login LEFT JOIN entity_details ON login.userId = entity_details.userId WHERE login.userId = '$userId'";		
		$q = $this->db->query($query)->result_array();
		return $this->db->affected_rows() ? $q : false;
	}

	public function getResolutions($userId)
	{
		$query = "SELECT * FROM entity_details LEFT JOIN resolution ON entity_details.entity_id = resolution.entity_id WHERE entity_details.userId = '$userId'";		
		$q = $this->db->query($query)->result_array();
		return $this->db->affected_rows() ? $q : false;
	}

	public function getFinalizeResolutions($userId)
	{
		$query = "SELECT * FROM entity_details LEFT JOIN resolution ON entity_details.entity_id = resolution.entity_id  WHERE entity_details.userId = '$userId' AND resolution.finalize = 'yes'";		
		$q = $this->db->query($query)->result_array();
		return $this->db->affected_rows() ? $q : false;
	}
}
