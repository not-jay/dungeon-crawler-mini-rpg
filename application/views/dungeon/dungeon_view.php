<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Dungeon Crawler Mini-RPG</title>
	<?= link_tag("css/bootstrap.min.css"); ?>
	<?= link_tag("css/bootstrap-theme.min.css"); ?>
	<?= link_tag("css/rpg.css"); ?>
	<?= link_tag("css/battle.css"); ?>
	<?= link_tag("css/misc.css"); ?>
</head>
<body>
	<div class="center-outer"><div class="center-middle">
		<div class="center-content center-text field">
			<div class="two-column pull-left enemies">
				<div id="enemy-field-0" class="field-sprite flip"></div>
				<div id="enemy-field-1" class="field-sprite flip"></div>
				<div id="enemy-field-2" class="field-sprite flip"></div>
				<div id="enemy-field-3" class="field-sprite flip"></div>
			</div>
			<div class="two-column pull-left player">
				<div id="player-field-0" class="field-sprite"></div>
				<div id="player-field-1" class="field-sprite"></div>
				<div id="player-field-2" class="field-sprite"></div>
				<div id="player-field-3" class="field-sprite"></div>
			</div>
		</div>
		<div class="center-content battle-ui">
			<div class="ui-left pull-left">
				<button id="fight" class="btn btn-primary">Fight</button>
				<button id="run" class="btn btn-primary">Run Away</button>
			</div>
			<div class="ui-right pull-left">
				<div id="party-0" class="media party-slot">
					<div class="media-left media-middle">
						<div class="media-object avatar"></div>
					</div>
					<div class="media-body status">
						<div class="name"></div>
						<div class="health"></div>
						<div class="ct progress">
							<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
				</div>
				<div id="party-1" class="media party-slot">
					<div class="media-left media-middle">
						<div class="media-object avatar"></div>
					</div>
					<div class="media-body status">
						<div class="name"></div>
						<div class="health"></div>
						<div class="ct progress">
							<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
				</div>
				<div id="party-2" class="media party-slot">
					<div class="media-left media-middle">
						<div class="media-object avatar"></div>
					</div>
					<div class="media-body status">
						<div class="name"></div>
						<div class="health"></div>
						<div class="ct progress">
							<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
				</div>
				<div id="party-3" class="media party-slot">
					<div class="media-left media-middle">
						<div class="media-object avatar"></div>
					</div>
					<div class="media-body status">
						<div class="name"></div>
						<div class="health"></div>
						<div class="ct progress">
							<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div></div>
</body>
<script type="text/javascript" src="<?= base_url('js/jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/string.extended.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/base.url.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/entities.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/battle.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function () {
	initBaseURL("429/final_project/");
	
	window.battle = new Battle();
	battle.init();
	battle.setUI({
		"fp": "player-field-{0}",
		"sp": "party-{0}",
		"fe": "enemy-field-{0}"
	});

	$("#run").click(function() {
		battle.issue("retreat");
	});
	$("#fight").click(function() {
		battle.issue("attack");
	});
});
</script>
</html>