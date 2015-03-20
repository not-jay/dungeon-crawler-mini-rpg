var Battle = function() {
	var player = undefined;
	var enemy = undefined;
	var unit_list = undefined;
	var ui = {
		"player": {
			"field": undefined,
			"stats": undefined
		},
		"enemy": undefined,
		"modal": undefined
	};
	var self = this;
	var reset = [];
	var shouldEnd = false;
	var hasEnded = false;
	var isDungeon;

	this.init = function(id, enemyID, flag) {
		var i, random, x;
		player = new Party();
		enemy = new Party();
		isDungeon = (flag === undefined)?true:flag;

		player.addAll(Unit.createAll(controller("unit/get_party", "json", id)));
		if(isDungeon) {
			enemy.addAll(Unit.createAll(controller("unit/get_enemy_party", "json")));
			enemy.__massLevelUp(player.averageLevel());
		} else {
			enemy.addAll(Unit.createAll(controller("unit/get_party", "json", enemyID)));
		}
		unit_list = Party.merge(player, enemy);

		player.check();
		enemy.check();
		unit_list.sortByUID();
	}

	this.setUI = function(ui_element) {
		ui.player.field = ui_element.fp;
		ui.player.stats = ui_element.sp;
		ui.enemy = ui_element.fe;
		ui.modal = ui_element.mo;
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
				if(!player_unit.isAlive()) {
					$(progress_bar).removeClass("progress-bar-info progress-bar-striped active");
					$(progress_bar).addClass("progress-bar-danger");
				}
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

	this.retreat = function() {
		var i, unit,
			length = player.size();

			for(i = 0; i < length; i++) {
				unit = player.get(i);

				if(unit.readyToAttack && unit.state === "retreat") {
					console.log(String.format("{0} attempting to retreat", unit.name));
					unit.readyToAttack = false;
					unit.charge_time = 0;
					if((Math.random() * 100) > 50) {
						console.log("Retreat successful!");
						shouldEnd = true;
					}
				}
			}
	}

	this.attack = function(exp_grant) {
		var i, unit, hit, target,
			length = unit_list.size();
			exp_grant = (exp_grant === undefined)?true:exp_grant;

		for(i = 0; i < length; i++) {
			unit = unit_list.get(i);

			if(unit.isAlive()) {
				//do animation
				if(unit.readyToAttack) {
					unit.state = "attack";
					target = (unit.user_id() !== -1) ?
							  enemy.randomUnit() : 
							  player.randomUnit()
					hit = unit.attackTarget(target, exp_grant);
					unit.hasAttacked = true;
				}
				//apply damage effect if hit
				if(hit) {
					target.state = "hit";
					if(target.isRetreating) {
						this.issueUnit("fight", target);
					}
				}
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
			unit.state = (unit.isAlive())?"idle":
						 ((isDungeon)?"dead":"faint");
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

		hasEnded = shouldEnd;

		if(player.dead() === player.size() ||
		   enemy.dead() === enemy.size()) {
			shouldEnd = true;
		}
	}

	this.step = function() {
		if(hasEnded) return;

		self.retreat();
		self.attack(isDungeon);
		self.incrementCT();

		player.check();
		enemy.check();
		unit_list.check();

		self.check();
	}

	this.issueUnit = function(command, target) {
		var i, length = player.size(),
			unit, field_id;
		for(i = 0; i < length; i++) {
			if(target.id() === player.get(i).id()) {
				field_id = i;
				break;
			}
		}
		if(command === "retreat") {
			target.state = command;
			target.isRetreating = true;
			$(String.format("#"+ui.player.field, field_id)).addClass("retreating");
		} else {
			target.state = "idle";
			target.isRetreating = false;
			$(String.format("#"+ui.player.field, field_id)).removeClass("retreating");
		}
	}

	this.issue = function(command) {
		var i, field, unit,
			length = player.size();

		for(i = 0; i < length; i++) {
			field = String.format(ui.player.field, i);
			unit = player.get(i);

			// Retreat should only make alive units retreat;
			if(!unit.isAlive()) continue;
			if(command === "retreat") {
				unit.state = command;
				unit.isRetreating = true;
				$(String.format("#{0}", field)).addClass("retreating");
			} else {
				unit.state = "idle";
				unit.isRetreating = false;
				$(String.format("#{0}", field)).removeClass("retreating");
			}
		}
	}

	this.postmortem = function() {
		var winner, dead = "",
			i, length = player.size();

		if(enemy.dead() === enemy.size())
			winner = "won";
		if(player.dead() === player.size())
			winner = "lost";
		if((player.dead() !== player.size()) &&
		   	(enemy.dead() !== enemy.size())) {
			winner = "have successfully retreated";
		}

		if(player.dead() >= 4) {
			dead = "Your entire party has fallen in battle.<br>May their souls rest in peace.";
		} else if(player.dead() > 0) {
			for(i = 0; i < length; i++) {
				if(!player.get(i).isAlive()) {
					dead += String.format(", {0}", player.get(i).name);
				}
			}
			dead = String.format("Some of your units have been slain. <br>Dead units: {0}", dead.slice(2));
		} else {
			dead = "No one died. Hooray!<br>To the victor, the spoils!";
		}

		$(String.format("#{0} .modal-title", ui.modal)).html(String.format("You {0}!", winner));
		$(String.format("#{0} .modal-body", ui.modal)).html(dead);
		$(String.format("#{0} .modal-footer", ui.modal)).html(
			"<button type='button' class='btn btn-primary' data-dismiss='modal'>Back to Town</button>"
		);
		$("#"+ui.modal).modal('show');
	}

	this.saveChanges = function() {
		if(!isDungeon) return;
		controllerPost("unit/update", {
			"units": player.jsonify()
		});
	}

	this.hasEnded = function() { return hasEnded; }
	this.player = function() { return player; }
	this.enemy = function() { return enemy; }
	this.unit_list = function() { return unit_list; }
}
