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

}

/* End of File unit.php */
/* Location: ./application/controllers/unit.php */