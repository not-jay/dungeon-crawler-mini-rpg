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
		$this->db->select("character_stats.char_id AS id");
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

	public function isFull($user_id) {
		$this->db->where("user_id", $user_id);

		return $this->db->get("characters")->num_rows() == 4;
	}

	public function add($unit) {
		$this->db->trans_start();

		$this->db->insert("characters", $unit["characters"]);
		$unit["character_stats"]["char_id"] = $this->db->insert_id();
		$this->db->insert("character_stats", $unit["character_stats"]);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function update($units) {
		$this->db->trans_start();
		foreach($units as $unit):
			if($unit["character_stats"]["hp"] <= 0):
				$this->db->where("char_id", $unit["character_stats"]["char_id"]);
				$this->db->delete("character_stats");
				$this->db->where("id", $unit["character_stats"]["char_id"]);
				$this->db->delete("characters");
			else:
				$this->db->where("char_id", $unit["character_stats"]["char_id"]);
				$this->db->update("character_stats", $unit["character_stats"]);

				$this->db->where("id", $unit["character_stats"]["char_id"]);
				$this->db->update("characters", $unit["characters"]);
			endif;
		endforeach;
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

}

/* End of File unit_model.php */
/* Location: ./application/models/unit_model.php */