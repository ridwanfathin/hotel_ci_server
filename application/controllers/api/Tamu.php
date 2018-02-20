<?php  


/**
* 
*/
require APPPATH . 'libraries/REST_Controller.php';

class Tamu extends REST_Controller
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
		$this->load->model('M_tamu');
	}

	function register_post(){

		$user_data = array(	'nama' =>$this->post('nama'),
							'email' => $this->post('email'), 
							'telp' => $this->post('telp'), 
							'no_identitas' => $this->post('no_identitas'),
							'alamat' => $this->post('alamat')
						);

		#Initialize image name
		$image_name=round(microtime(true)).date("Ymdhis").".jpg";

		#Upload avatar
		if ($this->Upload_Images($image_name))
			$user_data['foto']=$image_name;
	
		#Set response API if Success
		$response['SUCCESS'] = array('status' => TRUE, 'message' => 'success insert data' , 'data' => $user_data );

		#Set response API if Fail
		$response['FAIL'] = array('status' => FALSE, 'message' => 'fail insert data' , 'data' => null );
		
		#Set response API if exist data
		$response['EXIST'] = array('status' => FALSE, 'message' => 'exist data' , 'data' => null );

		#Check if insert user_data Success
		if ($this->M_tamu->insert($user_data)) {
			
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
			#Call methode get_all from M_tamu model
			$users=$this->M_tamu->get_all();
		
		}


		if ($id!=null) {
			
			#Check if id <= 0
			if ($id<=0) {
				$this->response($response['NOT_FOUND'], REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
			}

			#Call methode get_by_id from M_tamu model
			$users=$this->M_tamu->get_by_id($id);
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
							'telp' => $this->post('telp'), 
							'no_identitas' => $this->post('no_identitas'),
							'alamat' => $this->post('alamat')
						);
		
		#Initialize image name
		$image_name=round(microtime(true)).date("Ymdhis").".jpg";

		#Upload avatar
		if ($this->Upload_Images($image_name))
			$user_data['foto']=$image_name;

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

		$up=$this->M_tamu->update($id,$user_data);
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
		
		if ($this->M_tamu->delete($id)) {
			
			#If success
			$this->response($response['SUCCESS'],REST_Controller::HTTP_CREATED);
		
		}else{

			#If Fail
			$this->response($response['FAIL'],REST_Controller::HTTP_CREATED);
			
		}

	}

	function validate($id){
		$users=$this->M_tamu->get_by_id($id);
		if ($users)
			return TRUE;
		else
			return FALSE;
	}

	function Upload_Images($name) 
    {		
    		$strImage= $_FILES['foto']['tmp_name'];
    		if (!empty($strImage)) {
    			$img = imagecreatefromjpeg($strImage);
    			// $img = imagecreatefromstring(base64_decode($strImage));
							
				if($img != false)
				{
				   if (imagejpeg($img, './upload/avatars/'.$name)) {
				   	return true;
				   }else{
				   	return false;
				   }
				}
			}
	}


	function remove_image($name){
		$path='./upload/avatars/'.$name;
		unlink($path);
	}

}

?>