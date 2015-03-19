<?php

class Party extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model("unit_model", "unit");
	}

	public function index() {
		$id = $this->session->userdata("id");
		$data["units"] = json_decode($this->unit->get_party($id));

		$this->load->view("party/party_view", $data);
	}

}

/* End of File party.php */
/* Location: ./application/controllers/party.php */