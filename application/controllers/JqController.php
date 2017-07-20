<?php
	class JqController extends CI_Controller{
		function __construct(){
			parent::__construct();
			$this->load->model("JqModel");
		}
		
		public function index(){
			$this->load->view("JqView");
		}
		
		public function showData(){
			$page = $_POST['page'];
			$limit = $_POST['rows'];
			$sidx = $_POST['sidx'];
			$sord = $_POST['sord'];
			
			$start = $limit * $page - $limit;
			$start = ($start < 0) ? 0 : $start;
			
			if(!$sidx) $sidx = 1;
			
			$query2 = $this->JqModel->getCount();
			
			$count = ($query2);
			
			if($count > 0){
				$pageTotal = ceil($count / $limit);
			}
			else{
				$pageTotal = 0;
			}
			
			if($page > $pageTotal) $page = $pageTotal;
			
			$query = $this->JqModel->getData($start, $limit, $sidx, $sord);
			
			$dataa = new stdClass();
			
			$dataa->page = $page;
			$dataa->total = $pageTotal;
			$dataa->records = $count;
			
			$i = 0;
			
			foreach($query as $row){
				$dataa->rows[$i]['id'] = $row->id;
				$dataa->rows[$i]['cell'] = array($row->id, $row->name, $row->age, $row->phone_number, $row->email);
				$i++;
			}
			echo json_encode($dataa);
		}
		
		public function crudData() {
			$oper = $this->input->post('oper');
			$id = $this->input->post('id');
			$name = $this->input->post('name');
			$age = $this->input->post('age');
			$phone_number = $this->input->post('phone_number');
			$email = $this->input->post('email');

			switch ($oper) {
				case 'add':
					$data = array('name' => $name, 'age' => $age, 'phone_number' => $phone_number, 'email' => $email);
					$this->JqModel->insertData($data);
					break;
				case 'edit':
					$data = array('name' => $name, 'age' => $age, 'phone_number' => $phone_number, 'email' => $email);
					$this->JqModel->editData($name, $data);
					break;
				case 'del':
					$this->JqModel->deleteData($name);
					break;
			}
		}
	}
?>