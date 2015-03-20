<?php

class Battleroom extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model("user_model", "user");
		$this->load->model("unit_model", "unit");
	}

	public function index() {
		if(!$this->session->userdata("logged_in")):
			redirect("/");
		else:
			$id = $this->session->userdata("id");
			$data["users"] = $this->user->get_all_except($id);
			for($i = 0; $i < count($data["users"]); $i++):
				$data["users"][$i]["can_battle"] = (!$this->unit->isEmpty($data["users"][$i]["id"]))?"Yes":"No";
			endfor;

			$this->load->view("battleroom/battleroom_view", $data);
		endif;
	}

}

/* End of File battleroom.php */
/* Location: ./application/controllers/battleroom.php */