document.oncontextmenu = function() {
	return false
};
document.onselectstart = function() {
	if (event.srcElement.type != "text" && event.srcElement.type != "textarea" && event.srcElement.type != "password") return false;
	else return true;
};
if (window.sidebar) {
	document.onmousedown = function(e) {
		var obj = e.target;
		if (obj.tagName.toUpperCase() == "INPUT" || obj.tagName.toUpperCase() == "TEXTAREA" || obj.tagName.toUpperCase() == "PASSWORD") return true;
		else return false;
	}
};
if (parent.frames.length > 0) top.location.replace(document.location);