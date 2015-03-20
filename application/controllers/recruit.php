<?php

class Recruit extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model("unit_model", "unit");
	}

	public function index() {
		if(!$this->session->userdata("logged_in")):
			redirect("/");
		else:
			$data["units"] = json_decode($this->unit->get_enemy_party());

			$this->load->view("recruit/recruit_view", $data);
		endif;
	}

}

/* End of File recruit.php */
/* Location: ./application/controllers/recruit.php */