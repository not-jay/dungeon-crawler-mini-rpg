var Battle = function() {
	var player = undefined;
	var enemy = undefined;
	var unit_list = undefined;
	var ui = {
		"player": {
			"field": undefined,
			"stats": undefined
		},
		"enemy": undefined
	};

	this.init = function() {
		var i, random;
		player = new Party();
		enemy = new Party();

		player.addAll(Unit.createAll(controller("unit/get_party", "json", 1))); //TODO: Replace 1 with user_id
		enemy.addAll(Unit.createAll(controller("unit/get_enemy_party", "json")));
		unit_list = Party.merge(player, enemy);

		player.sortByUID();
		enemy.sortByUID();
		unit_list.sortByUID();
	}

	this.set_ui = function(ui_element) {
		ui.player.field = ui_element.fp;
		ui.player.stats = ui_element.sp;
		ui.enemy = ui_element.fe;
	}

	this.player = function() { return player; }
	this.enemy = function() { return enemy; }
	this.unit_list = function() { return unit_list; }
}
