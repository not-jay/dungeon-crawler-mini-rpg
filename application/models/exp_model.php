<?php

class EXP_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get($level) {
		$this->db->where("level", $level);
		$this->db->select("exp");
		$query = $this->db->get("exp_table");

		$exp = $query->row()->exp;
		$query->free_result();

		return $exp;
	}

}

/* End of File exp_model.php */
/* Location: ./application/models/exp_model.php */