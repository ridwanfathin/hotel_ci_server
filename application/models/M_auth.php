<?php  

/**
* 
*/
class M_auth extends CI_Model
{
	
	private $table_name = "user";

	function login($email,$password){
		$this->db->where('email',$email);
		$this->db->where('password',$password);

		return $this->db->get($this->table_name)->row();
	}

	function get_role($id){
		$this->db->where('id',$id);
		$this->db->select('role');

		return $this->db->get($this->table_name)->row();
	}
}

?>