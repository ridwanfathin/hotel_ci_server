<?php  


/**
* 
*/
require APPPATH . 'libraries/REST_Controller.php';

class Sewa extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		
		#Configure limit request methods
		$this->methods['index_get']['limit']=10; #10 requests per hour per sewa/key
		$this->methods['index_post']['limit']=10; #10 requests per hour per sewa/key
		$this->methods['index_delete']['limit']=10; #10 requests per hour per sewa/key
		$this->methods['index_put']['limit']=10; #10 requests per hour per sewa/key
		
		#Configure load model api table sewas
		$this->load->model('M_sewa');
		$this->load->model('M_ruangan');
	}

	function checkin_post(){

		$sewa_data = array(	'nama' =>$this->post('nama'),
							'id_kamar' =>$this->post('id_kamar'),
							'tgl_checkin' => $this->post('tgl_checkin'), //tanggal sekarang
							'gas_awal' => $this->post('gas_awal'),
							'listrik_awal' => $this->post('listrik_awal'),
							'air_awal' => $this->post('air_awal'),
							'status' => 0
						);

		$ruangan = array(	'status' => 0 //set ruangan jadi digunakan
						);
	
		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success insert data' , 'data' => $sewa_data );

		#Set response API if Fail
		$response['FAIL'] = array('status' => FALSE, 'message' => 'fail insert data' , 'data' => null );
		
		#Set response API if exist data
		$response['EXIST'] = array('status' => FALSE, 'message' => 'exist data' , 'data' => null );

		#Check if insert sewa_data Success
		if ($this->M_sewa->insert($sewa_data)) {
			$this->M_sewa->update($this->post('id_kamar'), $sewa_data)
			#If success
			$this->response($response['SUCCESS'],REST_Controller::HTTP_CREATED);

		}else{
			#If fail
			$this->response($response['FAIL'],REST_Controller::HTTP_FORBIDDEN);

		}

	}

	function index_get($id=""){	
		
		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success get sewa' , 'data' => null );
		
		#Set response API if Not Found
		$response['NOT_FOUND']=array('status' => FALSE, 'message' => 'no sewas were found' , 'data' => null );
        
        

		if ($this->get('search')) {
			#Call methode get_all from M_sewa model
			$sewas=$this->M_sewa->get_like_name($this->get('search'));
		
		}else if ($id==null) {
			#Call methode get_all from M_sewa model
			$sewas=$this->M_sewa->get_all();
		
		}else {
			
			#Check if id <= 0
			if ($id<=0) {
				$this->response($response['NOT_FOUND'], REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
			}

			#Call methode get_by_id from M_sewa model
			$sewas=$this->M_sewa->get_by_id($id);
		}


        # Check if the sewas data store contains sewas
		if ($sewas) {
			$response['SUCCESS']['data']=$sewas;

			#if found sewas
			$this->response($response['SUCCESS'] , REST_Controller::HTTP_OK);

		}else{

	        #if Not found sewas
	        $this->response($response['NOT_FOUND'], REST_Controller::HTTP_NOT_FOUND); # NOT_FOUND (404) being the HTTP response code

		}

	}

	function bystatus_get($id=""){	
		
		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success get sewa' , 'data' => null );
		
		#Set response API if Not Found
		$response['NOT_FOUND']=array('status' => FALSE, 'message' => 'no sewas were found' , 'data' => null );
        

		if ($id==null) {
			#Call methode get_all from M_sewa model
			$sewas=$this->M_sewa->get_all();
		
		}else {
			
			#Check if id <= 0
			if ($id<=0) {
				$this->response($response['NOT_FOUND'], REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
			}

			#Call methode get_by_id from M_sewa model
			$sewas=$this->M_sewa->get_by_status($id);
		}


        # Check if the sewas data store contains sewas
		if ($sewas) {
			$response['SUCCESS']['data']=$sewas;

			#if found sewas
			$this->response($response['SUCCESS'] , REST_Controller::HTTP_OK);

		}else{

	        #if Not found sewas
	        $this->response($response['NOT_FOUND'], REST_Controller::HTTP_NOT_FOUND); # NOT_FOUND (404) being the HTTP response code

		}

	}

	function bycheckout_get(){	

		if($this->get('begin'))
			$begin = $this->get('begin');
		else 
			$begin = "2000-01-01";
			
		if($this->get('end'))
			$end = $this->get('end');
		else 
			$end = "2080-01-01";


		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success get sewa' , 'data' => null );
		
		#Set response API if Not Found
		$response['NOT_FOUND']=array('status' => FALSE, 'message' => 'no sewas were found' , 'data' => null );
        
		#Call methode get_by_checkout from M_sewa model
		$sewas=$this->M_sewa->get_by_checkout($begin,$end);

        # Check if the sewas data store contains sewas
		if ($sewas) {
			$response['SUCCESS']['data']=$sewas;

			#if found sewas
			$this->response($response['SUCCESS'] , REST_Controller::HTTP_OK);

		}else{

	        #if Not found sewas
	        $this->response($response['NOT_FOUND'], REST_Controller::HTTP_NOT_FOUND); # NOT_FOUND (404) being the HTTP response code

		}
	}

	function update_post($id=""){

		$sewa_data = array(	'nama' =>$this->post('nama'),
							'id_kamar' =>$this->post('id_kamar'),
							'tgl_checkin' => $this->post('tgl_checkin'), 
							'tgl_checkout' => $this->post('tgl_checkout'),
							'gas_awal' => $this->post('gas_awal'), 
							'gas_akhir' => $this->post('gas_akhir'),
							'listrik_awal' => $this->post('listrik_awal'), 
							'listrik_akhir' => $this->post('listrik_akhir'),
							'air_awal' => $this->post('air_awal'), 
							'air_akhir' => $this->post('air_akhir'),
							'status' => $this->post('status')
						);

		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success update sewa' , 'data' => $sewa_data );

		#Set response API if Fail
		$response['FAIL'] = array('status' => FALSE, 'message' => 'fail update sewa' , 'data' => $sewa_data );
		
		#Set response API if sewa not found
		$response['NOT_FOUND']= array('status' => FALSE, 'message' => 'no sewas were found id:'.$id , 'data' => $sewa_data );

		#Set response API if exist data
		$response['EXIST'] = array('status' => FALSE, 'message' => 'exist insert data' , 'data' => $sewa_data );

		#Check available sewa
		if (!$this->validate($id))
			$this->response($response['NOT_FOUND'],REST_Controller::HTTP_NOT_FOUND);

		$up=$this->M_sewa->update($id,$sewa_data);
		if ($up) {
			
			$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success update sewa' , 'data' => $up );			
			#If success
			$this->response($response['SUCCESS'],REST_Controller::HTTP_CREATED);
		
		}else{

			#If Fail
			$this->response($response['FAIL'],REST_Controller::HTTP_CREATED);
			
		}

	}

	function checkout_post($id=""){

		$sewa_data = array(
							'tgl_checkout' => $this->post('tgl_checkout'), //tanggal sekarang
							'status' => 1
						);

		$ruangan = array(	'status' => 1 //set ruangan jadi perlu dibersihkan
						);
		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success update sewa' , 'data' => $sewa_data );

		#Set response API if Fail
		$response['FAIL'] = array('status' => FALSE, 'message' => 'fail update sewa' , 'data' => $sewa_data );
		
		#Set response API if sewa not found
		$response['NOT_FOUND']= array('status' => FALSE, 'message' => 'no sewas were found id:'.$id , 'data' => $sewa_data );

		#Set response API if exist data
		$response['EXIST'] = array('status' => FALSE, 'message' => 'exist insert data' , 'data' => $sewa_data );

		#Check available sewa
		if (!$this->validate($id))
			$this->response($response['NOT_FOUND'],REST_Controller::HTTP_NOT_FOUND);

		$up=$this->M_sewa->update($id,$sewa_data);
		if ($up) {
			
			$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success update sewa' , 'data' => $up );			
			#If success
			$this->response($response['SUCCESS'],REST_Controller::HTTP_CREATED);
		
		}else{

			#If Fail
			$this->response($response['FAIL'],REST_Controller::HTTP_CREATED);
			
		}

	}

	function index_delete($id=null){

		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'Success delete sewa'  );

		#Set response API if Fail
		$response['FAIL'] = array('status' => FALSE, 'message' => 'Fail delete sewa'  );
		
		#Set response API if sewa not found
		$response['NOT_FOUND']=array('status' => FALSE, 'message' => 'No sewas were found' );


		#Check available sewa
		if (!$this->validate($id))
			$this->response($response['NOT_FOUND'],REST_Controller::HTTP_NOT_FOUND);
		

		if (!empty($this->get('id')))
			$id=$this->get('id');
		
		if ($this->M_sewa->delete($id)) {
			
			#If success
			$this->response($response['SUCCESS'],REST_Controller::HTTP_CREATED);
		
		}else{

			#If Fail
			$this->response($response['FAIL'],REST_Controller::HTTP_CREATED);
			
		}

	}

	function validate($id){
		$sewas=$this->M_sewa->get_by_id($id);
		if ($sewas)
			return TRUE;
		else
			return FALSE;
	}

}

?>