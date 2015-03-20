</html>
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
		<div class="center-content">
			<div class="two-column pull-left units ff7" style="overflow: scroll">
				<div class="center-text title"><h1>Users</h1></div>
				<?php for($i = 0; $i < count($users); $i++): ?>
				<div id="unit-<?= $i ?>" class="media unit-slot" onClick="selectUser(<?= $i; ?>);">
					<div class="media-left media-middle">
						<div class="media-object avatar">
						</div>
					</div>
					<div class="media-body media-middle">
						<div id="name-<?= $i ?>"><?= $users[$i]["username"]; ?></div>
						<div id="status-<?= $i ?>">Able to battle: <?= $users[$i]["can_battle"]; ?></div>
					</div>
				</div>
				<?php endfor; ?>
			</div>
			<div id="column-2" class="two-column pull-left stats ff7">
				<div class="center-text title"><h1 id="unit-name"></h1></div>
				<div class="sprite center-block"></div>
				<div class="center-block center-content unit-stats">
					<div class="spring">
						<input id="id" type="hidden">
						Level: <span id="level"></span><br>
						Exp: <span id="exp"></span>&nbsp;<span id="next-lvl"></span><br>
						HP: <span id="hp"></span> / <span id="max_hp"></span><br>
						Attack: <span id="atk"></span><br>
						Defense: <span id="def"></span><br>
						Evasion: <span id="evade"></span><br>
						Speed: <span id="spd"></span><br>
					</div>
					<div class="spring">
						<button id="battle" class="btn btn-default center-block">Battle</button>
					</div>
				</div>
			</div>
		</div>
	</div></div>
</body>
<script type="text/javascript" src="<?= base_url('js/jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/base.url.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/string.extended.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/entities.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
	var i, length, ave, unit;
	initBaseURL("429/final_project/");
	
	$("#battle").click(function() {
		// controllerPost("unit/get_party", );
	});

});
function selectUser(id) {
	var party = new Party();

	controllerPost("unit/get_party/"+id, {	
	}, function(data) {
		console.log(data);
	}, true);
	// party.addAll(Units.createAll());
}
</script> 
</html>