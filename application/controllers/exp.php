<?php

class EXP extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model("exp_model", "exp");
	}

	public function get($level) {
		if($level <= 0) return;

		echo $this->exp->get($level);
		return;
	}

	public function grant_exp($defeated_unit_level) {
		if($defeated_unit_level - 1 <= 1):
			echo 1;
			return;
		endif;

		$prev_level_exp = $this->exp->get($defeated_unit_level - 1);
		$level_exp = $this->exp->get($defeated_unit_level);

		echo $level_exp - $prev_level_exp;
		return;
	}

	public function level_up() {
		$stats = 10;

		$hp = rand(1, $stats-3);
		$stats -= $hp;

		$attack = rand(1, $stats-3);
		$stats -= $attack;

		$defense = rand(1, $stats-3);
		$stats -= $defense;

		$evasion = rand(1, $stats-3);
		$stats -= $evasion;

		if($stats <= 0) $stats = 1;
		$speed = $stats;

		echo json_encode(array(
			"hp"		=> $hp,
			"attack"	=> $attack,
			"defense"	=> $defense,
			"evasion"	=> $evasion,
			"speed"		=> $speed
		));
		return;
	}

}

/* End of File exp.php */
/* Location: ./application/controllers/exp.php */