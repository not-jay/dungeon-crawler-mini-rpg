<?php

class Dungeon extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		if(!$this->session->userdata("logged_in")):
			redirect("/");
		else:
			$this->load->view("dungeon/dungeon_view");
		endif;
	}

}

/* End of File dungeon.php */
/* Location: ./application/controllers/dungeon.php */