/**
 *	Attach controller function to window
 *	for global calling to the controller
 */
window.controller = function(link, type) {
	var args, shouldConvert, convertTo,
		result, argsLength, cArgs = "",
		i;

	if(!link.endsWith("/")) link += "/";
	type = type || "text";
	args = Array.prototype.slice.call(arguments, 2);

	shouldConvert = (type !== "text" && type !== "json");
	convertTo = type;
	if(shouldConvert) type = "text";

	argsLength = args.length
	for(i = 0; i < argsLength; i++) {
		cArgs += "/"+args[i];
	};
	cArgs = cArgs.slice(1);

	$.ajax(window.link.base_url(String.format("{0}{1}", link, cArgs)), {
		async: false,
		dataType: type,
		success: function(data) {
			result = data;
		}
	});

	if(shouldConvert) {
		switch(convertTo) {
			case "int":
			default:
				result = parseInt(result);
			break;
		}
	}
	return result;
}

/**
 *	Constructor for the unit "class"
 */
var Unit = function(data) {
	var id				= parseInt(data.id || 1);
	var user_id			= parseInt(data.user_id || 1);
	this.name		 	= data.name;
	this.level			= parseInt(data.level || 1);
	this.exp			= parseInt(data.exp || 0);
	this.hp				= parseInt(data.hp || 0);
	this.max_hp			= parseInt(data.max_hp || 0);
	this.attack			= parseInt(data.attack || 0);
	this.defense		= parseInt(data.defense || 0);
	this.evasion		= parseInt(data.evasion || 0);
	this.speed			= parseInt(data.speed || 0);
	this.charge_time	= 0;
	this.readyToAttack	= false;
	this.lastAttacker	= null;
	this.state			= "idle";
	this.avatar			= String.format("img/avatars/{0}.gif", data.sprite_name);
	this.sprite			= {
		"idle"		: String.format("img/sprites/{0}-idle.gif", data.sprite_name),
		"attack"	: String.format("img/sprites/{0}-attack.gif", data.sprite_name),
		"retreat"	: String.format("img/sprites/{0}-retreat.gif", data.sprite_name)
	};

	this.id = function() { return id; }
	this.user_id = function() { return user_id; }
}

Unit.createAll = function(array) {
	var units = [],
		length = array.length,
		i;

	for (var i = 0; i < length; i++) {
		units[i] = new Unit(array[i]);
	};

	return units;
}

Unit.prototype.incrementCT = function() {
	this.charge_time += this.speed;

	if(this.charge_time >= 100) {
		this.readyToAttack = true;
		return true;
	}
	return false;
};

/**
 *	Returns false if attack failed (missed or couldn't attack)
 */
Unit.prototype.attackTarget = function(target) {
	var result = false, random, exp,
		damage;

	if(!this.readyToAttack) {
		console.log(String.format("{0} is not ready to attack {1} :(", this.name, target.name));
		return false;
	}

	result = false;
	console.log(String.format("{0} attacking {1}...", this.name, target.name));

	random = Math.random() * 100;
	if(random > target.evasion) {
		damage = this.attack - target.defense;
		if(damage < 0) {
			damage = 0;
		}
		console.log(String.format("...for {0} damage!", damage));

		target.hp -= damage;
		target.lastAttacker = this;

		exp = target.checkHP();
		this.checkEXP();
		result = true;
	} else {
		console.log(String.format("...but {0} missed!", this.name));
	}

	this.charge_time = 0;
	this.readyToAttack = false;
	return result;
};

/**
 *	Returns false if unit is dead
 */
Unit.prototype.checkHP = function() {
	var exp;

	if(this.isAlive()) {
		console.log(String.format("{0} is still alive. Phew!", this.name));
		return true;
	}

	console.log(String.format("{0} has been slain by {1}!", this.name, this.lastAttacker.name));
	exp = controller("exp/grant_exp", "int", this.level);
	console.log(String.format("{0} gains {1} exp", this.lastAttacker.name, exp));
	this.lastAttacker.exp += exp;
	return false;
};

/**
 *	Returns true if unit leveled up
 */
Unit.prototype.checkEXP = function() {
	//check if totalexp is >= new level's exp
	var exp = controller("exp/get", "int", this.level + 1),
		level_ups = 0;
	if(this.exp >= exp) {
		console.log(String.format("{0}'s exp is currently {1}. {2} exp to next level.", this.name, this.exp, (exp - this.exp)));
	}

	while(this.exp >= exp) {
		level_ups++;
		exp = controller("exp/get", "int", this.level + level_ups);
	}

	if(level_ups > 0) {
		this.levelUp(level_ups);
		return true;
	}
	return false;
};

Unit.prototype.levelUp = function(levels) {
	var new_stats, i;
	console.log(String.format("{0} gains {1} level{2}!", this.name, levels, (levels != 1)?"s":""));

	for(i = 0; i < levels; i++) {
		new_stats = controller("exp/level_up", "json");
		this.level++;
		this.hp += new_stats.hp;
		this.max_hp += new_stats.hp;
		this.attack += new_stats.attack;
		this.defense += new_stats.defense;
		this.evasion += new_stats.evasion;
		this.speed += new_stats.speed;
	};
};

Unit.prototype.isAlive = function() {
	return this.hp > 0;
};

/**
 *	Constructor for the class "parties"
 */
var Party = function() {
	var id		= -1;
	var units	= [];

	this.id = function(new_id) {
		if(new_id !== undefined) {
			id = new_id;
		}
		return id;
	}
	this.add = function(unit) {
		units.push(unit);
		return true;
	}
	this.addAll = function(unit_array) {
		units = units.concat(unit_array);
		return true;
	}
	this.remove = function(index) {
		if(index < 0 || index >= units.length) return false;
		units.splice(index, 1);
		return true;
	}
	this.removeAll = function() {
		units.splice(0, units.length);
		return true;
	}
	this.get = function(index) {
		if(index === undefined) {
			return units;
		}
		if(index < 0 || index >= units.length) return null;
		return units[index];
	}
	this.size = function() {
		return units.length;
	}
	this.sortByUID = function() {
		units.sort(function(a, b) { return a.id()-b.id(); });
		return true;
	}
}

Party.merge = function() {
	var argLen = arguments.length, args,
		party, i;

	if(argLen < 1) return null;
	args = Array.prototype.slice.call(arguments);

	party = new Party();
	argLen = args.length;
	for (i = 0; i < argLen; i++) {
		party.addAll(args[i].get());
	};

	return party;
}
