<?php  


/**
* 
*/
class M_ruangan extends CI_Model
{
	
	private $table_name = "ruangan";
	private $primary = "id";

	function get_all(){
		#Get all data users
		$data=$this->db->get($this->table_name);
		return $data->result();

	}

	function get_by_id($id){
		#Get data user by id
		$this->db->where($this->primary,$id);
		$data=$this->db->get($this->table_name);

		return $data->row();
	}


	function insert($data){
		#Insert data to table tb_users
		$insert=$this->db->insert($this->table_name,$data);
		return $insert;
	}

	function delete($id){
		#Delete data user by id
		$this->db->where($this->primary,$id);
		$delete=$this->db->delete($this->table_name);
		return $delete;
	}

	function update($id,$data){
		#Update data user by id
		$this->db->where($this->primary,$id);
		$update=$this->db->update($this->table_name,$data);
		if ($update) {
			$update=$this->get_by_id($id);
		}
		return $update;
	}

}

?>