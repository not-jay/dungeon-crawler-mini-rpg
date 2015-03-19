<?php

class Unit extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model("unit_model", "unit");
	}

	public function get($id) {
		echo $this->unit->get($id);
		return;
	}

	public function get_party($user_id) {
		echo $this->unit->get_party($user_id);
		return;
	}

	public function get_enemy_party() {
		echo $this->unit->get_enemy_party();
		return;
	}

	public function update() {
		$units = $this->input->post("units");
		$this->unit->update($units);
	}

	public function recruit() {
		$unit = $this->input->post("unit");
		$result = array();

		if(!$this->unit->isFull($unit["characters"]["user_id"])):
			$this->unit->add($unit);
			$result["status"] = "okay";
			$result["unit_name"] = $unit["characters"]["name"];
		else:
			$result["status"] = "error";
			$result["error"] = "Party full!";
		endif;

		echo json_encode($result);
	}

}

/* End of File unit.php */
/* Location: ./application/controllers/unit.php */