<?php
	class JqModel extends CI_Model{
		public function getData($start, $limit, $where, $sidx, $sord){
			$this->db->select("*");
			$this->db->limit($limit);
			if($where != "") $this->db->where($where);
			$this->db->order_by($sidx, $sord);
			
			$query = $this->db->get("employee", $limit, $start);
			
			return $query->result();
		}

		public function getDataSub($start, $limit, $id, $sidx, $sord, $where){
			$this->db->select("*");
			$this->db->limit($limit);
			$this->db->where("employee_id", $id);
			if($where != "") $this->db->where($where);
			$this->db->order_by($sidx, $sord);

			$query = $this->db->get("employee_order", $limit, $start);
			
			return $query->result();
		}

		public function getSubGrid($id){
			$this->db->select("*");
			$this->db->from("employee_order");
			$this->db->where("employee_id", $id);
			
			$query = $this->db->get();
			
			return $query->result();
		}
		
		public function checkLogin($username, $password){
		    $this->db->select("email, password");
		    $this->db->from("admin");
		    $this->db->where("email", $username);
		    $this->db->where("password", $password);
		    
		    $query = $this->db->get();
		    
		    if($query->num_rows() > 0){
		        return true;
		    }
		    else{
		        return false;
		    }
		    
		    return $query->result();
		}
		
		public function getCount(){
			$this->db->select("*");
			$this->db->from("employee");
			
			$query = $this->db->get();
			$rowcount = $query->num_rows();
			
			return $rowcount;
		}

		public function getCountSub($id){
			$this->db->select("*");
			$this->db->from("employee_order");
			$this->db->where("employee_id", $id);
			
			$query = $this->db->get();
			$rowcount = $query->num_rows();
			
			return $rowcount;
		}
		
		public function insertData($data){
			return $this->db->insert("employee", $data);
		}
		
		public function editData($id, $data){
			$this->db->where("id", $id);
			return $this->db->update("employee", $data);
		}
		
		public function deleteData($id){
			$this->db->where("id", $id);
			return $this->db->delete("employee");

			$this->db->where("employee_id", $id);
			return $this->db->delete("employee_order");
		}

		public function insertDataSub($data){
			return $this->db->insert("employee_order", $data);
		}
		
		public function editDataSub($id, $data){
			$this->db->where("order_id", $id);
			return $this->db->update("employee_order", $data);
		}
		
		public function deleteDataSub($id){
			$this->db->where("order_id", $id);
			return $this->db->delete("employee_order");
		}

		public function deleteDataChild($id){
			$this->db->where("employee_id", $id);
			return $this->db->delete("employee_order");
		}

		public function getAll(){
			$this->db->select("*");
		    $this->db->from("employee");
		    
		    $query = $this->db->get();
		    
		    return $query->result();
		}
		
		public function import_csv($data){		    
		    $this->db->insert("employee", $data);
		}

		public function export_csv($id){
		    $this->db->select("*");
		    $this->db->from("employee");
		    $this->db->where("id", $id);
		    
		    $query = $this->db->get();
		    
		    return $query->result();
		}

		public function export_excel($id){
		    $this->db->select("*");
		    $this->db->from("employee");
		    $this->db->where("id", $id);
		    
		    $query = $this->db->get();
		    
		    return $query->result();
		}
	}
?>