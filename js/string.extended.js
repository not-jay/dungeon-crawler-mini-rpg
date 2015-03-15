// This basically extends the native String
// adding a format and endsWith functionality

/**
 *	fearphage's String.format()
 *	http://stackoverflow.com/a/4673436/1135805
 */
if(!String.format) {
	String.format = function(format) {
		var args = Array.prototype.slice.call(arguments, 1);
		return format.replace(/{(\d+)}/g, function(match, number) { 
			return typeof args[number] != 'undefined' ? args[number] : match;
		});
	};
}

/**
 *	chakrit's String.prototype.endsWith()
 *	http://stackoverflow.com/a/2548133/1135805
 */
if(!String.prototype.endsWith) {
	String.prototype.endsWith = function(suffix) {
		return this.indexOf(suffix, this.length - suffix.length) !== -1;
	};
}
