<?php

class Home extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model("user_model", "users");
		$this->load->helper("form");
	}

	public function index() {
		if($this->session->userdata("logged_in")):
			redirect("town");
		else:
			$this->load->view("home/login_view");
		endif;
	}

	public function login() {
		$user = $this->input->post("username");
		$pass = $this->input->post("password");
		$result = array();

		if($this->users->validate($user, $pass)):
			//login user
			$data = $this->users->get_data($user);
			$this->users->login($user);
			$session_data = array(
				"id"		=> $data["id"],
				"last_login"=> $data["last_login"],
				"username"	=> $user,
				"logged_in"	=> true
			);
			$this->session->set_userdata($session_data);
			$result["status"] = "okay";
		else:
			$result["status"] = "error";
			$result["error"] = "Invalid username or password";
		endif;

		echo json_encode($result);
	}

	public function register() {
		$user = $this->input->post("username");
		$pass = $this->input->post("password");
		$result = array();

		if(!$this->users->taken($user)):
			//register
			$this->users->register($user, $pass);
			$result["status"] = "okay";
			$result["info"] = "You may now log-in using your credentials";
		else:
			$result["status"] = "error";
			$result["error"] = "Username is already taken";
		endif;

		echo json_encode($result);
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect("/");
	}

}

/* End of File home.php */
/* Location: ./application/controllers/home.php */