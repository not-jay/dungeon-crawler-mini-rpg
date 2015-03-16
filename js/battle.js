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
	var self = this;
	var reset = [];
	var hasEnded = false;

	this.init = function() {
		var i, random;
		player = new Party();
		enemy = new Party();

		player.addAll(Unit.createAll(controller("unit/get_party", "json", 1))); //TODO: Replace 1 with user_id
		enemy.addAll(Unit.createAll(controller("unit/get_enemy_party", "json")));
		enemy.__massLevelUp(player.averageLevel());
		unit_list = Party.merge(player, enemy);

		player.check();
		player.sortByUID();
		enemy.check();
		enemy.sortByUID();
		unit_list.sortByUID();
	}

	this.setUI = function(ui_element) {
		ui.player.field = ui_element.fp;
		ui.player.stats = ui_element.sp;
		ui.enemy = ui_element.fe;
		self.updateUI();
	}

	this.updateUI = function() {
		var player_unit, enemy_unit, i,
			player_field, player_avatar,
			progress_bar;

		for (i = 0; i < 4; i++) {
			player_unit = player.get(i);
			enemy_unit = enemy.get(i);

			if(player_unit !== null) {
				player_field = String.format(ui.player.field, i);
				player_avatar = String.format(ui.player.stats, i);
				progress_bar = String.format("#{0} .ct .progress-bar", player_avatar);

				$(String.format("#{0}", player_field)).css(
					"background-image",
					String.format("url({0})", player_unit.sprite(player_unit.state))
				);
				$(String.format("#{0} .avatar", player_avatar)).css(
					"background-image", String.format("url({0})", player_unit.sprite("avatar"))
				);
				$(String.format("#{0} .name", player_avatar)).html(
					player_unit.name
				);
				$(String.format("#{0} .health", player_avatar)).html(
					player_unit.hp+" / "+player_unit.max_hp
				);
				$(progress_bar).attr("aria-valuenow", player_unit.charge_time)
							   .width(String.format("{0}%", player_unit.charge_time));
				if(player_unit.readyToAttack) {
					$(progress_bar).addClass("progress-bar-info progress-bar-striped active");
				} else {
					$(progress_bar).removeClass("progress-bar-info progress-bar-striped active");
				}
			} else {
				player_field = String.format(ui.player.field, i);
				player_avatar = String.format(ui.player.stats, i);

				$(String.format("#{0}", player_field)).hide();
				$(String.format("#{0}", player_avatar)).hide();
			}

			if(enemy_unit !== null) {
				$(String.format("#"+ui.enemy, i)).css(
					"background-image", String.format("url({0})", enemy_unit.sprite(enemy_unit.state))
				);
			} else {
				$(String.format("#"+ui.enemy, i)).hide();
			}
		};
	}

	this.incrementCT = function() {
		var i, unit,
			length = unit_list.size();

		for(i = 0; i < length; i++) {
			unit = unit_list.get(i);

			if(!unit.isAlive()) continue;
			unit.incrementCT();
		}
	}

	this.attack = function() {
		var i, unit, hit, target,
			length = unit_list.size();

		for(i = 0; i < length; i++) {
			unit = unit_list.get(i);

			if(unit.isAlive()) {
				//do animation
				if(unit.readyToAttack) {
					unit.state = "attack";
					target = (unit.user_id() !== -1) ?
							  enemy.randomUnit() : 
							  player.randomUnit()
					hit = unit.attackTarget(target);
					unit.hasAttacked = true;
				}
				//apply damage effect if hit
				if(hit) target.state = "hit";
			}
		}
	}

	this.check = function() {
		var i, unit,
			length = unit_list.size(),
			resetLength = reset.length;

		// reset states
		for(i = 0; i < resetLength; i++) {
			unit = reset[i];
			unit.state = (unit.isAlive())?"idle":"dead";
		}
		reset.splice(0, reset.length);

		// prep for reset
		for(i = 0; i < length; i++) {
			unit = unit_list.get(i);

			if(unit.hasAttacked || unit.state === "hit") {
				if(unit.hasAttacked) unit.hasAttacked = false;
				reset.push(unit);
			}
		}

		if(enemy.dead() === enemy.size()) {
			console.log("You win!");
			$(location).attr("href", link.base_url());
		}
		if(player.dead() === player.size()) {
			console.log("You suck!");
			$(location).attr("href", link.base_url());
		}
	}

	this.step = function() {
		self.attack();
		self.incrementCT();

		player.check();
		// player.sortByUID();
		enemy.check();
		// enemy.sortByUID();
		unit_list.check();
		// unit_list.sortByUID();

		self.check();
	}

	this.issue = function(command) {
		var i, field, unit,
			length = player.size();

		for(i = 0; i < length; i++) {
			field = String.format(ui.player.field, i);
			unit = player.get(i);

			if(command === "retreat") {
				unit.state = command;
				$(String.format("#{0}", field)).addClass("retreating");
			} else {
				unit.state = "idle";
				$(String.format("#{0}", field)).removeClass("retreating");
			}
		}
	}

	this.player = function() { return player; }
	this.enemy = function() { return enemy; }
	this.unit_list = function() { return unit_list; }
}
