<?php  


/**
* 
*/
class M_ruangan extends CI_Model
{
	
	private $table_name = "ruangan";
	private $primary = "id";

	function get_all(){
		#Get all data ruangans
		$data=$this->db->get($this->table_name);
		return $data->result();

	}

	function get_by_id($id){
		#Get data ruangan by id
		$this->db->where($this->primary,$id);
		$data=$this->db->get($this->table_name);

		return $data->row();
	}

	function get_by_status($status){
		#Get ruangan by  status
		#0:digunakan, 1:perlu dibersihkan, 2: tersedia
		$this->db->where("status",$status);
		$data=$this->db->get($this->table_name);

		return $data->row();
	}


	function insert($data){
		#Insert data to table tb_ruangans
		$insert=$this->db->insert($this->table_name,$data);
		return $insert;
	}

	function delete($id){
		#Delete data ruangan by id
		$this->db->where($this->primary,$id);
		$delete=$this->db->delete($this->table_name);
		return $delete;
	}

	function update($id,$data){
		#Update data ruangan by id
		$this->db->where($this->primary,$id);
		$update=$this->db->update($this->table_name,$data);
		if ($update) {
			$update=$this->get_by_id($id);
		}
		return $update;
	}

}

?>