<?php  


/**
* 
*/
class M_sewa extends CI_Model
{
	
	private $table_name = "sewa";

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


	function get_by_status($status){		
		#Get data sewa by status
		#0:berlangsung, 1:selesai
		$this->db->or_where('status',$status);
		$data=$this->db->get($this->table_name)->row();
		return $data;
	}

	function get_like_name($name){	
		#Get data sewa where nama time like $name
		$this->db->like('nama', $name);
		$data=$this->db->get($this->table_name);
		return $data->result();
	}

	function get_by_checkout($begin, $end){	
		#Get data sewa by checkout date in range between $begin and $end
		$this->db->where('tgl_checkout BETWEEN "'. $begin. '" and "'.$end.'" and status = 1');
		$data=$this->db->get($this->table_name);
		return $data->result();
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