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
			<div class="two-column pull-left units ff7">
				<div class="center-text title"><h1>New Units</h1></div>
				<?php for($i = 0; $i < 4; $i++): ?>
				<div class="media unit-slot" onClick="selectUnit(<?= $i; ?>);">
					<div class="media-left media-middle">
						<div class="media-object avatar">
							<img src="<?= base_url('img/avatars/'.$units[$i]->sprite_name.'.gif'); ?>" />
						</div>
					</div>
					<div class="media-body media-middle">
						<div class="name">Name: <?= $units[$i]->name; ?></div>
					</div>
				</div>
				<?php endfor; ?>
			</div>
			<div id="column-2" class="two-column pull-left stats ff7">
				<div class="center-text title"><h1>Review Unit</h1></div>
				<div class="sprite center-block"></div>
				<div class="center-block center-content unit-stats">
					Name: <input type="text" id="unit-name" placeholder="Enter a name">
					<div class="spring">
						HP: <span id="hp"></span><br>
						Level: <span id="level"></span><br>
						Attack: <span id="atk"></span><br>
						Defense: <span id="def"></span><br>
						Evasion: <span id="evade"></span><br>
						Speed: <span id="spd"></span><br>
					</div>
					<div class="spring">
						<button class="btn btn-default center-block">Recruit</button>
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
var units;
$(document).ready(function() {
	var playerParty = new Party(),
		i, length, ave, unit;
	units = new Party();
	initBaseURL("429/final_project/");

	playerParty.addAll(Unit.createAll(controller("unit/get_party", "json", 1))); //TODO: replace 1
	units.addAll(Unit.createAll(JSON.parse('<?= json_encode($units); ?>')));
	ave = playerParty.averageLevel() || 1;
	length = units.size();
	for(i = 0; i < length; i++) {
		var low = ((ave-2) <= 0)?1:(ave-2);
		unit = units.get(i);
		unit.levelUp(Math.floor(Math.random() * ave) + low);
	}
});
function selectUnit(id) {
	var unit = units.get(id);

	$("#column-2").show();
	$("#unit-name").val(unit.name);
	$("#column-2 .sprite").css("background-image", String.format("url({0})", unit.sprite("retreat")));
	$("#hp").html(unit.max_hp);
	$("#level").html(unit.level);
	$("#atk").html(unit.attack);
	$("#def").html(unit.defense);
	$("#evade").html(unit.evasion);
	$("#spd").html(unit.speed);
}
</script> 
</html>