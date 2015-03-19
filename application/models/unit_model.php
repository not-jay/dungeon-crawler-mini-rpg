<?php

class Unit_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	private function jsonify($row) {
		return json_encode($row);
	}

	public function get($id) {
		$this->db->where("characters.id", $id);
		$this->db->select("*");
		$this->db->select("character_stats.id AS stats_id");
		$this->db->join("characters", "characters.id = character_stats.char_id", "right");
		$query = $this->db->get("character_stats");

		$row = $query->first_row();
		$query->free_result();
		return $this->jsonify($row);
	}

	public function get_party($user_id) {
		$this->db->where("characters.user_id", $user_id);
		$this->db->select("*");
		$this->db->select("character_stats.id AS stats_id");
		$this->db->join("character_stats", "character_stats.char_id = characters.id");
		$query = $this->db->get("characters");

		$array = $query->result();
		$query->free_result();
		return $this->jsonify($array);
	}

	public function get_enemy_party() {
		$this->db->where("user_id", -1);
		$query = $this->db->get("characters");

		$array = $query->result();
		$query->free_result();

		shuffle($array);
		$enemy_lineup = array_slice($array, 0, 4);

		return $this->jsonify($enemy_lineup);
	}

}

/* End of File unit_model.php */
/* Location: ./application/models/unit_model.php */