<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Dungeon Crawler Mini-RPG</title>
	<?= link_tag("css/misc.css"); ?>
	<?= link_tag("css/rpg-ui.css"); ?>
	<?= link_tag("css/bootstrap.min.css"); ?>
	<?= link_tag("css/bootstrap-theme.min.css"); ?>
</head>
<body>
	<div class="center-outer"><div class="center-middle">
		<div class="center-content center-text center-block ff7">
			<div class="title"><h1>Welcome, <?= $this->session->userdata("username"); ?>!</h1></div>
			<div class="sub-title"><h4>Your last login was <?= $this->session->userdata("last_login"); ?></h4></div>
			<div class="town-stuff pad-me">
				<button type="button" class="btn btn-lg btn-primary" id="party">Party</button>
				<button type="button" class="btn btn-lg btn-primary" id="recruit">Recruit</button>
				<button type="button" class="btn btn-lg btn-primary" id="dungeon">Dungeon</button>
				<button type="button" class="btn btn-lg btn-primary" id="battleroom">Battle Room</button>
			</div>
		</div>
	</div></div>
</body>
<script type="text/javascript" src="<?= base_url('js/jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/base.url.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/string.extended.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#party").click(function() {
		$(location).attr("href", "party");
	});
	$("#recruit").click(function() {
		$(location).attr("href", "recruit");
	});
	$("#dungeon").click(function() {
		$(location).attr("href", "dungeon");
	});
	$("#battleroom").click(function() {
		$(location).attr("href", "battleroom");
	});
});
</script>
</html>