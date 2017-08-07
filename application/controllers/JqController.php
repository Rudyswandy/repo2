<?php
	class JqController extends CI_Controller{
		function __construct(){
			parent::__construct();
			$this->load->model("JqModel");
		}
		
		public function import(){
		    $this->load->view("Import");
		}
		
		public function loginView(){
		    $this->load->view("LoginView");
		}
		
		public function grid(){
			$this->load->view("JqView");
		}
		
		public function index(){
		    if(isset($_POST['submit'])){
		        $username = $_POST['username'];
		        $password = $_POST['password'];
		        
		        if(!empty($username) && !empty($password)){
		            $query = $this->JqModel->checkLogin($username, $password);
		            
		            if($query){
		                $this->load->view("JqView");
		            }else{
		                $this->load->view("LoginView");
		                echo "<b>Incorrect Username or Password!</b>";
		            }
		            
		        }else{
		            $this->load->view("LoginView");
		            echo "<b>Please fill in all fields!</b>";
		        }
		    }
		}
		
		public function showData(){
			$page = $_POST['page'];
			$limit = $_POST['rows'];
			$sidx = $_POST['sidx'];
			$sord = $_POST['sord'];
			
			$start = $limit * $page - $limit;
			$start = ($start < 0) ? 0 : $start;
			
			$where = "";
	 
			if ($_POST['_search'] == 'true') {
			    
			    $searchField = $_POST['searchField'];
			    $searchOper = $_POST['searchOper'];
			    $searchString = $_POST['searchString'];
			    
				$ops = array(
				'eq'=>'=',
				'ne'=>'<>',
				'lt'=>'<',
				'le'=>'<=',
				'gt'=>'>',
				'ge'=>'>=',
				'bw'=>'LIKE',
				'bn'=>'NOT LIKE',
				'in'=>'LIKE',
				'ni'=>'NOT LIKE',
				'ew'=>'LIKE',
				'en'=>'NOT LIKE',
				'cn'=>'LIKE',
				'nc'=>'NOT LIKE'
				);
				
				foreach($ops as $key=>$value){
					if($searchOper == $key) {
						$ops = $value;
					}
				}
				
				if($searchOper == 'eq' ) 
					$searchString = $searchString;
				if($searchOper == 'bw' || $searchOper == 'bn') 
					$searchString .= '%';
				if($searchOper == 'ew' || $searchOper == 'en' ) 
					$searchString = '%'.$searchString;
				if($searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') 
					$searchString = '%'.$searchString.'%';
	 
				$where = "$searchField $ops '$searchString' ";
			}
			
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
			
			$query = $this->JqModel->getData($start, $limit, $where, $sidx, $sord);
			
			$data = new stdClass();
			
			$data->page = $page;
			$data->total = $pageTotal;
			$data->records = $count;
			
			$i = 0;
			
			foreach($query as $row){
				$data->rows[$i]['cell'] = array($row->id, $row->name, $row->age, $row->phone_number, $row->email);
				$i++;
			}
			echo json_encode($data);
		}

		public function showDataSub(){
			$id = $_GET['employee_id'];
			$page = $_GET['page'];
			$limit = $_GET['rows'];
			$sidx = $_GET['sidx'];
			$sord = $_GET['sord'];
			
			$start = $limit * $page - $limit;
			$start = ($start < 0) ? 0 : $start;

			$where = "";
	 
			if ($_GET['_search'] == 'true') {
			    
			    $searchField = $_GET['searchField'];
			    $searchOper = $_GET['searchOper'];
			    $searchString = $_GET['searchString'];
			    
				$ops = array(
				'eq'=>'=',
				'ne'=>'<>',
				'lt'=>'<',
				'le'=>'<=',
				'gt'=>'>',
				'ge'=>'>=',
				'bw'=>'LIKE',
				'bn'=>'NOT LIKE',
				'in'=>'LIKE',
				'ni'=>'NOT LIKE',
				'ew'=>'LIKE',
				'en'=>'NOT LIKE',
				'cn'=>'LIKE',
				'nc'=>'NOT LIKE'
				);
				
				foreach($ops as $key=>$value){
					if($searchOper == $key) {
						$ops = $value;
					}
				}
				
				if($searchOper == 'eq' ) 
					$searchString = $searchString;
				if($searchOper == 'bw' || $searchOper == 'bn') 
					$searchString .= '%';
				if($searchOper == 'ew' || $searchOper == 'en' ) 
					$searchString = '%'.$searchString;
				if($searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') 
					$searchString = '%'.$searchString.'%';
	 
				$where = "$searchField $ops '$searchString' ";
			}
			
			if(!$sidx) $sidx = 1;
			
			$query2 = $this->JqModel->getCountSub($id);
			
			$count = ($query2);
			
			if($count > 0){
				$pageTotal = ceil($count / $limit);
			}
			else{
				$pageTotal = 0;
			}
			
			if($page > $pageTotal) $page = $pageTotal;
			
			$query = $this->JqModel->getDataSub($start, $limit, $id, $sidx, $sord, $where);
			
			$data = new stdClass();
			
			$data->page = $page;
			$data->total = $pageTotal;
			$data->records = $count;
			
			$i = 0;
			
			foreach($query as $row){
				$data->rows[$i]['cell'] = array($row->order_id, $row->item_name, $row->order_date);
				$i++;
			}
			echo json_encode($data);
		}
		
		public function subGrid(){
			$id = $_POST['id'];

			$query = $this->JqModel->getSubGrid($id);
			
			$data = new stdClass();
			
			$i = 0;
			
			foreach($query as $row){
				$data->rows[$i]['cell'] = array($row->order_id, $row->item_name, $row->order_date);
				$i++;
			}
			echo json_encode($data);
		}

		public function crudData() {
			$oper = $this->input->post('oper');
			$id = $this->input->post('id');
			$name = $this->input->post('name');
			$age = $this->input->post('age');
			$phone_number = $this->input->post('phone_number');
			$email = $this->input->post('email');

			switch($oper){
				case 'add':
				    
				    $data = array('name' => $name, 'age' => $age, 'phone_number' => $phone_number, 'email' => $email);
					$this->JqModel->insertData($data);
					
					break;
					
				case 'edit':

				    $data = array('name' => $name, 'age' => $age, 'phone_number' => $phone_number, 'email' => $email);
					$this->JqModel->editData($id, $data);
					
					break;
					
				case 'del':

					$this->JqModel->deleteData($id);
					$this->JqModel->deleteDataChild($id);
					
					break;
			}
		}

		public function crudDataSub() {
			$oper = $this->input->get_post('oper');
			$order_id = $this->input->get_post('id');
			$employee_id = $this->input->get_post('employee_id');
			$item_name = $this->input->get_post('item_name');
			$order_date = $this->input->get_post('order_date');

			switch($oper){
				case 'add':
				    
				    $data = array('employee_id' => $employee_id, 'item_name' => $item_name, 'order_date' => $order_date);
					$this->JqModel->insertDataSub($data);
					
					break;
					
				case 'edit':

				    $data = array('item_name' => $item_name, 'order_date' => $order_date);
					$this->JqModel->editDataSub($order_id, $data);
					
					break;
					
				case 'del':

					$this->JqModel->deleteDataSub($order_id);
					
					break;
			}
		}
		
		public function importCsv(){    
		    $fname = $_FILES["csvFile"]["name"];
		    echo "upload file name: " . $fname;
		    $check = explode(".", $fname);
		    
		    if(strtolower(end($check)) == "csv"){
		        $filename = $_FILES["csvFile"]["tmp_name"];
		        $handle = @fopen($filename, "r");
		    }
		    
		    $row = 0;
		    $col = 0;
		    
		    if ($handle)
		    {
		        while (($row = fgetcsv($handle, 4096)) !== false)
		        {
		            if (empty($fields))
		            {
		                $fields = $row;
		                continue;
		            }
		            
		            foreach ($row as $k=>$value)
		            {
		                $results[$col][$fields[$k]] = $value;
		            }
		            $col++;
		            unset($row);
		        }

		        $num_of_rows = count($results);
		        
		        $flag = 0;
		        
		        for($i=-1; $i<$num_of_rows; $i++){
		            if($flag == 0){
		                $flag++;
		            }else{
		                
		            $data_tmp = array("name"=>$results[$i]["name"], "age"=>$results[$i]["age"], "phone_number"=>$results[$i]["phone_number"], "email"=>$results[$i]["email"]);
		            $this->JqModel->import_csv($data_tmp);
		            }
		        }
		        
		        if (!feof($handle))
		        {
		            echo "Error: unexpected fgets() failn";
		        }
		        fclose($handle);
		    }
		}
		
		public function exportCsv(){
		    $id = $_GET["id"];
		    $query = $this->JqModel->export_csv($id);
		    
		    $i = 0;
		    foreach($query[0] as $q){
		        $data[$i] = $q;
		        $i++;
		    }
		    
		    $filename = 'csvFile' . $id;
		    
		    $filepath = $_SERVER["DOCUMENT_ROOT"] . $filename.'.csv';
		    $handle = fopen($filepath, 'w+');
		    
		    fputcsv($handle, array("id", "name", "age", "phone_number", "email"));
		    fputcsv($handle, $data);
		    
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
		    header('Content-Length: ' . filesize($filepath));
		    readfile($filepath);
		    
		    fclose($handle);
		}

		public function exportExcel(){

			
			$id = $this->input->get("id");

			$query = $this->JqModel->export_excel($id);

			$objPHPExcel = new PHPExcel();

			$objPHPExcel->getActiveSheet()->getProtection()->setPassword('asd');
			$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setFormatColumns(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setFormatRows(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setInsertColumns(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setInsertHyperlinks(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setDeleteColumns(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setDeleteRows(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setAutoFilter(true);
			$objPHPExcel->getActiveSheet()->getStyle('E:E')->getProtection()
			->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

	        $objPHPExcel->getActiveSheet()->setTitle('Employee');

	        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
	 
	        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
	        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Name');
	        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Age');
	        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Phone Number');
	        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Email');


			foreach($query as $row){
		        $objPHPExcel->getActiveSheet()->setCellValue('A2', $row->id);
		        $objPHPExcel->getActiveSheet()->setCellValue('B2', $row->name);
		        $objPHPExcel->getActiveSheet()->setCellValue('C2', $row->age);
		        $objPHPExcel->getActiveSheet()->setCellValue('D2', $row->phone_number);
		        $objPHPExcel->getActiveSheet()->setCellValue('E2', $row->email);
			}

	        $filename='Employee' . $id . '.xlsx';
	 
	        header('Content-Type: application/vnd.ms-excel');
	 
	        header('Content-Disposition: attachment;filename="'.$filename.'"');
	 
	        header('Cache-Control: max-age=0');
	 
	        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
	 
	        $objWriter->save('php://output');
		}

		public function upload(){  
		    foreach($_FILES["attachments"]["error"] as $key=>$error){
		        if($error == UPLOAD_ERR_OK){
		            $name = $_FILES["attachments"]["name"][$key];
		            move_uploaded_file($_FILES["attachments"]["tmp_name"][$key], "E:\uploads" . $_FILES["attachments"]["name"][$key]);
		        }
		    }
		}
	}
?>