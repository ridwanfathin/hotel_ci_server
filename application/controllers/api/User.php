<?php  


/**
* 
*/
require APPPATH . 'libraries/REST_Controller.php';

class User extends Auth
{
	
	function __construct()
	{
		parent::__construct();
		
		#Configure load model api table users
		$this->load->model('M_user');
	}

	function register_post(){

		$user_data = array(	'nama' =>$this->post('nama') ,
							'email' => $this->post('email') , 
							'role' => $this->post('role') ,
							'password' => md5($this->post('password'))
						);
	
		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success insert data' , 'data' => $user_data );

		#Set response API if Fail
		$response['FAIL'] = array('status' => FALSE, 'message' => 'fail insert data' , 'data' => null );
		
		#Set response API if exist data
		$response['EXIST'] = array('status' => FALSE, 'message' => 'exist data' , 'data' => null );

		#Check if insert user_data Success
		if ($this->M_user->insert($user_data)) {
			
			#If success
			$this->response($response['SUCCESS'],REST_Controller::HTTP_CREATED);

		}else{
			#If fail
			$this->response($response['FAIL'],REST_Controller::HTTP_FORBIDDEN);

		}

	}

	function index_get($id=""){	
		
		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success get user' , 'data' => null );
		
		#Set response API if Not Found
		$response['NOT_FOUND']=array('status' => FALSE, 'message' => 'no users were found' , 'data' => null );
        

		if ($id==null) {
			#Call methode get_all from M_user model
			$users=$this->M_user->get_all();
		
		}


		if ($id!=null) {
			
			#Check if id <= 0
			if ($id<=0) {
				$this->response($response['NOT_FOUND'], REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
			}

			#Call methode get_by_id from M_user model
			$users=$this->M_user->get_by_id($id);
		}


        # Check if the users data store contains users
		if ($users) {
			$response['SUCCESS']['data']=$users;

			#if found Users
			$this->response($response['SUCCESS'] , REST_Controller::HTTP_OK);

		}else{

	        #if Not found Users
	        $this->response($response['NOT_FOUND'], REST_Controller::HTTP_NOT_FOUND); # NOT_FOUND (404) being the HTTP response code

		}

	}

	function update_post($id=""){

		$user_data = array(	'nama' => $this->post('nama'),
							'email' => $this->post('email'), 
							'role' => $this->post('role')
						);
		if ($this->post('password')) {
			$user_data['password'] = md5($this->post('password'));
		}

		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success update user' , 'data' => $user_data );

		#Set response API if Fail
		$response['FAIL'] = array('status' => FALSE, 'message' => 'fail update user' , 'data' => $user_data );
		
		#Set response API if user not found
		$response['NOT_FOUND']= array('status' => FALSE, 'message' => 'no users were found id:'.$id , 'data' => $user_data );

		#Set response API if exist data
		$response['EXIST'] = array('status' => FALSE, 'message' => 'exist insert data' , 'data' => $user_data );

		#Check available user
		if (!$this->validate($id))
			$this->response($response['NOT_FOUND'],REST_Controller::HTTP_NOT_FOUND);

		$up=$this->M_user->update($id,$user_data);
		if ($up) {
			
			$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success update user' , 'data' => $up );			
			#If success
			$this->response($response['SUCCESS'],REST_Controller::HTTP_CREATED);
		
		}else{

			#If Fail
			$this->response($response['FAIL'],REST_Controller::HTTP_CREATED);
			
		}

	}

	function index_delete($id=null){

		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'Success delete user'  );

		#Set response API if Fail
		$response['FAIL'] = array('status' => FALSE, 'message' => 'Fail delete user'  );
		
		#Set response API if user not found
		$response['NOT_FOUND']=array('status' => FALSE, 'message' => 'No users were found' );


		#Check available user
		if (!$this->validate($id))
			$this->response($response['NOT_FOUND'],REST_Controller::HTTP_NOT_FOUND);
		

		if (!empty($this->get('id')))
			$id=$this->get('id');
		
		if ($this->M_user->delete($id)) {
			
			#If success
			$this->response($response['SUCCESS'],REST_Controller::HTTP_CREATED);
		
		}else{

			#If Fail
			$this->response($response['FAIL'],REST_Controller::HTTP_CREATED);
			
		}

	}

	function validate($id){
		$users=$this->M_user->get_by_id($id);
		if ($users)
			return TRUE;
		else
			return FALSE;
	}

}

?>