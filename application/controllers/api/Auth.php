<?php  

/**
* 
*/
require APPPATH . 'libraries/REST_Controller.php';

class Auth extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		
		#Configure limit request methods
		$this->methods['index_get']['limit']=10; #10 requests per hour per user/key
		$this->methods['index_post']['limit']=10; #10 requests per hour per user/key
		$this->methods['index_delete']['limit']=10; #10 requests per hour per user/key
		$this->methods['index_put']['limit']=10; #10 requests per hour per user/key
		
		#Configure load model api table users
		$this->load->model('M_auth');
	}

	function login_post(){
		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'login success' );

		#Set response API if Fail
		$response['FAIL'] = array('status' => FALSE, 'message' => 'login fail' , 'data' => null );
		
		$user_data=$this->M_auth->login($this->post('email'),md5($this->post('password')));
		// $user_data=$this->m_auth->login('admin@gmail.com',md5('admin'));
		// var_dump($this->post('EMAIL'),$this->post('PASSWORD')); die();
		if ($user_data) {
			// var_dump($user_data);
			$response['SUCCESS']['data']=$user_data;
			 // $this->session->set_userdata((array)$user_data);
			 // var_dump($this->session->userdata('role'));			
			$this->response($response['SUCCESS'] , REST_Controller::HTTP_OK);
		}else{
			$this->response($response['FAIL'] , REST_Controller::HTTP_NOT_FOUND);
		}

	}

 	function index_get($id=""){
		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'get data success' );

		#Set response API if Fail
		$response['FAIL'] = array('status' => FALSE, 'message' => 'get data fail' , 'data' => null );
		
		$res=$this->M_auth->get_role($id);
		// $user_data=$this->m_auth->login('admin@gmail.com',md5('admin'));
		// var_dump($this->post('EMAIL'),$this->post('PASSWORD')); die();
		if ($res) {
			// var_dump($user_data);
			$response['SUCCESS']['data']=$res;
			 // $this->session->set_userdata((array)$user_data);
			 // var_dump($this->session->userdata('role'));			
			$this->response($response['SUCCESS'] , REST_Controller::HTTP_OK);
		}else{
			$this->response($response['FAIL'] , REST_Controller::HTTP_NOT_FOUND);
		}

	}


}

?>