<?php

class User_model extends CI_Model {

	function __construct() {
		parent::__construct();

		$this->timestamp = "%Y-%m-%d %H:%i:%s";
	}

	function validate($user, $pass) {
		$this->db->where("username", $user);
		$this->db->where("password", md5($pass));

		return $this->db->get("users")->num_rows() == 1;
	}

	function taken($user) {
		$this->db->where("username", $user);

		return $this->db->get("users")->num_rows() == 1;
	}

	function login($user) {
		date_default_timezone_set("Asia/Manila");
		$data = array("last_login" => mdate($this->timestamp));
		$this->db->where("username", $user);
		$this->db->update("users", $data);
	}

	function get_data($user) {
		$this->db->where("username", $user);
		$this->db->select("id, last_login");

		return $this->db->get("users")->first_row('array');
	}

	function register($user, $pass) {
		date_default_timezone_set("Asia/Manila");
		$data = array(
			"username"	=> $user,
			"password"	=> md5($pass),
			"created_on"=> mdate($this->timestamp)
		);
		
		$this->db->insert("users", $data);
	}

}

/* End of File user_model.php */
/* Location: ./application/models/user_model.php */