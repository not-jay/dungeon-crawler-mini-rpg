<?php

class Town extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model("unit_model", "unit");
	}

	public function index() {
		if(!$this->session->userdata("logged_in")):
			redirect("/");
		else:
			$id = $this->session->userdata("id");
			$data["open_dungeon"] = !$this->unit->isEmpty($id);

			$this->load->view("town/town_view", $data);
		endif;
	}

}

/* End of File town.php */
/* Location: ./application/controllers/town.php */