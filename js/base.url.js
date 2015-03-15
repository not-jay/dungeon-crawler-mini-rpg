var initBaseURL = function(rel) {
	window.link = new Link(rel);
}

var Link = function (rel) {
	var baseUrl = rel;
	this.baseUrl = function () { return baseUrl; }
}

Link.prototype.base_url = function(location) {
	location = location || "";
	return window.location.origin+"/"+this.baseUrl()+location;
};
