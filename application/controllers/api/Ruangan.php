<?php  


/**
* 
*/
require APPPATH . 'libraries/REST_Controller.php';

class Ruangan extends REST_Controller
{
	
		#0:digunakan, 1:perlu dibersihkan, 2: tersedia
	
	function __construct()
	{
		parent::__construct();
		
		#Configure limit request methods
		$this->methods['index_get']['limit']=10; #10 requests per hour per ruangan/key
		$this->methods['index_post']['limit']=10; #10 requests per hour per ruangan/key
		$this->methods['index_delete']['limit']=10; #10 requests per hour per ruangan/key
		$this->methods['index_put']['limit']=10; #10 requests per hour per ruangan/key
		
		#Configure load model api table ruangans
		$this->load->model('M_ruangan');
	}

	function create_post(){

		$ruangan_data = array(	
							'tipe' =>$this->post('tipe') ,
							'nomor_kamar' => $this->post('nomor_kamar') , 
							'lantai' => $this->post('lantai'), 
							'status' => $this->post('status')
						);
	
		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success insert data' , 'data' => $ruangan_data );

		#Set response API if Fail
		$response['FAIL'] = array('status' => FALSE, 'message' => 'fail insert data' , 'data' => null );
		
		#Set response API if exist data
		$response['EXIST'] = array('status' => FALSE, 'message' => 'exist data' , 'data' => null );

		#Check if insert ruangan_data Success
		if ($this->M_ruangan->insert($ruangan_data)) {
			
			#If success
			$this->response($response['SUCCESS'],REST_Controller::HTTP_CREATED);

		}else{
			#If fail
			$this->response($response['FAIL'],REST_Controller::HTTP_FORBIDDEN);

		}

	}

	function index_get($id=""){	
		
		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success get ruangan' , 'data' => null );
		
		#Set response API if Not Found
		$response['NOT_FOUND']=array('status' => FALSE, 'message' => 'no ruangans were found' , 'data' => null );
        

		if ($id==null) {
			#Call methode get_all from M_ruangan model
			$ruangans=$this->M_ruangan->get_all();
		
		}


		if ($id!=null) {
			
			#Check if id <= 0
			if ($id<=0) {
				$this->response($response['NOT_FOUND'], REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
			}

			#Call methode get_by_id from M_ruangan model
			$ruangans=$this->M_ruangan->get_by_id($id);
		}


        # Check if the ruangans data store contains ruangans
		if ($ruangans) {
			$response['SUCCESS']['data']=$ruangans;

			#if found ruangans
			$this->response($response['SUCCESS'] , REST_Controller::HTTP_OK);

		}else{

	        #if Not found ruangans
	        $this->response($response['NOT_FOUND'], REST_Controller::HTTP_NOT_FOUND); # NOT_FOUND (404) being the HTTP response code

		}

	}

	function update_post($id=""){

		$ruangan_data = array(	
							'tipe' =>$this->post('tipe') ,
							'nomor_kamar' => $this->post('nomor_kamar') , 
							'lantai' => $this->post('lantai'), 
							'status' => $this->post('status')
						);

		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success update ruangan' , 'data' => $ruangan_data );

		#Set response API if Fail
		$response['FAIL'] = array('status' => FALSE, 'message' => 'fail update ruangan' , 'data' => $ruangan_data );
		
		#Set response API if ruangan not found
		$response['NOT_FOUND']= array('status' => FALSE, 'message' => 'no ruangans were found id:'.$id , 'data' => $ruangan_data );

		#Set response API if exist data
		$response['EXIST'] = array('status' => FALSE, 'message' => 'exist insert data' , 'data' => $ruangan_data );

		#Check available ruangan
		if (!$this->validate($id))
			$this->response($response['NOT_FOUND'],REST_Controller::HTTP_NOT_FOUND);

		$up=$this->M_ruangan->update($id,$ruangan_data);
		if ($up) {
			
			$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success update ruangan' , 'data' => $up );			
			#If success
			$this->response($response['SUCCESS'],REST_Controller::HTTP_CREATED);
		
		}else{

			#If Fail
			$this->response($response['FAIL'],REST_Controller::HTTP_CREATED);
			
		}

	}

	function index_delete($id=null){

		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'Success delete ruangan'  );

		#Set response API if Fail
		$response['FAIL'] = array('status' => FALSE, 'message' => 'Fail delete ruangan'  );
		
		#Set response API if ruangan not found
		$response['NOT_FOUND']=array('status' => FALSE, 'message' => 'No ruangans were found' );


		#Check available ruangan
		if (!$this->validate($id))
			$this->response($response['NOT_FOUND'],REST_Controller::HTTP_NOT_FOUND);
		

		if (!empty($this->get('id')))
			$id=$this->get('id');
		
		if ($this->M_ruangan->delete($id)) {
			
			#If success
			$this->response($response['SUCCESS'],REST_Controller::HTTP_CREATED);
		
		}else{

			#If Fail
			$this->response($response['FAIL'],REST_Controller::HTTP_CREATED);
			
		}

	}

	function validate($id){
		$ruangans=$this->M_ruangan->get_by_id($id);
		if ($ruangans)
			return TRUE;
		else
			return FALSE;
	}

}

?>