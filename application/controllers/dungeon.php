<?php

class Dungeon extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->load->view("dungeon/dungeon_view");
	}

}

/* End of File dungeon.php */
/* Location: ./application/controllers/dungeon.php */